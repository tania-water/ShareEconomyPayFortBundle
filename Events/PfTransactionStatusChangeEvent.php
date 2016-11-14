<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;

class PfTransactionStatusChangeEvent extends Event
{
    /**
     * @var PfTransaction
     */
    protected $transaction;

    public function __construct(PfTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }
}