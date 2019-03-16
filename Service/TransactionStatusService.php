<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionStatus;
use Ibtikar\ShareEconomyPayFortBundle\PfTransactionsResponseCodes;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ibtikar\ShareEconomyPayFortBundle\Events\PfTransactionStatusChangeEvent;

/**
 * This service is responsible for updating transactions statuses
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class TransactionStatusService
{
    /**
     * @var type 
     */
    private $em;

    /**
     * @var type 
     */
    private $transactionEvents;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     * */
    protected $dispatcher;

    /**
     * @param $em
     */
    public function __construct($em, EventDispatcherInterface $dispatcher)
    {
        $this->em                = $em;
        $this->dispatcher        = $dispatcher;
        $this->transactionEvents = [
            PfTransaction::STATUS_SUCCESS => 'pf.transaction.success',
            PfTransaction::STATUS_FAIL    => 'pf.transaction.fail'
        ];
    }

    /**
     *
     * @param PfTransaction $transaction
     * @param array $payfortResponse
     * @throws \Exception
     */
    public function addTransactionStatus(PfTransaction $transaction, array $payfortResponse)
    {
        if (!isset($payfortResponse['response_code']) || !isset($payfortResponse['status'])) {
            throw new \Exception('Response not valid: ' . json_encode($payfortResponse));
        }

        $oldTransactionStatus = $transaction->getCurrentStatus();
        $newTransactionStatus = $this->getTransctionStatusFromResponse($payfortResponse);

        if ($oldTransactionStatus !== $newTransactionStatus) {
            $transaction->setCurrentStatus($newTransactionStatus);
        }

        // create new transaction status
        $transactionStatus = new PfTransactionStatus();
        $transactionStatus->setResponseCode($payfortResponse['response_code'])
            ->setResponseMessage($payfortResponse['response_message'])
            ->setStatus($payfortResponse['status'])
            ->setResponse($payfortResponse);

        $transactionStatus->setTransaction($transaction);

        $this->em->persist($transactionStatus);
        $this->em->flush();

        // if the transaction status changed and the new status has event to be despatched then despatch that event
        if ($oldTransactionStatus !== $newTransactionStatus && isset($this->transactionEvents[$newTransactionStatus])) {
            $transactionEvent = new PfTransactionStatusChangeEvent($transaction);
            $this->dispatcher->dispatch($this->transactionEvents[$newTransactionStatus], $transactionEvent);
        }
    }

    /**
     * @param array $payfortResponse
     * @return integer
     */
    private function getTransctionStatusFromResponse(array $payfortResponse)
    {
        $status = null;
        $return = null;

        if (isset($payfortResponse['transaction_status'])) {
            $status = $payfortResponse['transaction_status'];
        } else {
            $status = $payfortResponse['status'];
        }

        // transaction success
        if ($status == PfTransactionsResponseCodes::TRANSACTION_SUCCESS) {
            $return = PfTransaction::STATUS_SUCCESS;
        }
        // transaction pending
        elseif (in_array($status, [PfTransactionsResponseCodes::TRANSACTION_PENDING, PfTransactionsResponseCodes::TRANSACTION_IN_REVIEW, PfTransactionsResponseCodes::TRANSACTION_UNCERTAIN])) {
            $return = PfTransaction::STATUS_PENDING;
        }
        // transaction failed
        elseif ($status == PfTransactionsResponseCodes::TRANSACTION_FAILURE) {
            $return = PfTransaction::STATUS_FAIL;
        }
        // everything else will be considered as failed
        else {
            $return = PfTransaction::STATUS_FAIL;
        }

        return $return;
    }
}
