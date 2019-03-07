<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionStatus;
use Ibtikar\ShareEconomyPayFortBundle\Service\PayFortIntegration;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod;
use Ibtikar\ShareEconomyPayFortBundle\Service\TransactionStatusService;

/**
 * Description of PaymentOperations
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class PaymentOperations
{
    const PF_TRANSACTION_INVOICE_INTERFACE_FQNS = 'Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface';

    private $em;
    private $pfPaymentIntegration;
    private $transactionStatusService;

    /**
     * @param $em
     */
    public function __construct($em, PayFortIntegration $pfPaymentIntegration, TransactionStatusService $transactionStatusService)
    {
        $this->em                       = $em;
        $this->pfPaymentIntegration     = $pfPaymentIntegration;
        $this->transactionStatusService = $transactionStatusService;
    }

    public function payInvoice(PfTransactionInvoiceInterface $invoice)
    {
        // make sure that the invoice object implements the PfTransactionInvoiceInterface interface
        if (!in_array(self::PF_TRANSACTION_INVOICE_INTERFACE_FQNS, class_implements($invoice))) {
            throw new \Exception('Invoice object shuold implement PfTransactionInvoiceInterface interface.');
        }

        // make sure that the invoice has an active payment method
        $paymentMethod = $invoice->getPfPaymentMethod();
        if (!$paymentMethod) {
            throw new \Exception('Invoice should have payment method to complete the payment process.');
        }

        if (!$paymentMethod instanceof PfPaymentMethod) {
            throw new \Exception('getPftPaymentMethod should return object from class \Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod.');
        }

        // make sure that we can create new transaction for this invoice
        if (!$invoice->canCreateNewTransaction()) {
            throw new \Exception('Invoice already has not failed transaction.');
        }

        // create new transaction
        $transaction = new PfTransaction();
        $transaction->setInvoice($invoice)
            ->setAmount($invoice->getAmountDue())
            ->setPaymentMethod($paymentMethod)
            ->setMerchantReference(bin2hex(random_bytes(16)));

        // make purchase in the payfort
        $paymentResponse = $this->pfPaymentIntegration->purchaseTransaction($transaction);

        //Handling invalid token_name
        if(!isset($paymentResponse['fort_id']) || !isset($paymentResponse['currency']) || !isset($paymentResponse['merchant_reference'])){
           throw new \Exception('Transaction Failure | Invalid payment info.');
        }

        $transaction->setFortId($paymentResponse['fort_id'])
            ->setCurrency($paymentResponse['currency'])
            ->setMerchantReference($paymentResponse['merchant_reference']);

        if (isset($paymentResponse['customer_ip'])) {
            $transaction->setCustomerIp($paymentResponse['merchant_reference']);
        }

        if (isset($paymentResponse['authorization_code'])) {
            $transaction->setAuthorizationCode($paymentResponse['authorization_code']);
        }

        $this->em->persist($transaction);
        $this->em->flush();
        $this->em->refresh($transaction);

        // create new transaction status
        $this->transactionStatusService->addTransactionStatus($transaction, $paymentResponse);
    }
}
