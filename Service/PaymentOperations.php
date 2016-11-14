<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionStatus;
use Ibtikar\ShareEconomyPayFortBundle\Service\PayFortIntegration;

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

    /**
     * @param $em
     */
    public function __construct($em, PayFortIntegration $pfPaymentIntegration)
    {
        $this->em                   = $em;
        $this->pfPaymentIntegration = $pfPaymentIntegration;
    }

    public function payInvoice($invoice)
    {
        // make sure that the invoice object implements the PfTransactionInvoiceInterface interface
        if (!in_array(self::PF_TRANSACTION_INVOICE_INTERFACE_FQNS, class_implements($invoice))) {
            throw new \Exception('Invoice object shuold implement PfTransactionInvoiceInterface interface.');
        }

        // make sure that the invoice has an active payment method
        if (!$invoice->getPaymentMethod()) {
            throw new \Exception('Invoice should have payment method to complete the payment process.');
        }

        // make sure that we can create new transaction for this invoice
        if (!$invoice->canCreateNewTransaction()) {
            throw new \Exception('Invoice already has not failed transaction.');
        }

        // create new transaction
        $transaction = new PfTransaction();
        $transaction->setInvoice($invoice)
            ->setAmount($invoice->getAmountDue())
            ->setPaymentMethod($invoice->getPaymentMethod())
            ->setMerchantReference(bin2hex(random_bytes(16)));

        // make purchase in the payfort
        $paymentResponse = $this->pfPaymentIntegration->purchaseTransaction($transaction);

        $transaction->setFortId($paymentResponse['fort_id'])
            ->setCustomerIp($paymentResponse['customer_ip'])
            ->setCurrency($paymentResponse['currency'])
            ->setMerchantReference($paymentResponse['merchant_reference'])
            ->setAuthorizationCode($paymentResponse['authorization_code']);

        // create new transaction status
        $transactionStatus = new PfTransactionStatus();
        $transactionStatus->setAttributesFromResponse($paymentResponse);

        $transaction->addTransactionStatus($transactionStatus);

        $this->em->persist($transaction);
        $this->em->flush();
    }
}