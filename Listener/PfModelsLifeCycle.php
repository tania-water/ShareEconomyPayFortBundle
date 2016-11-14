<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;
use Ibtikar\ShareEconomyPayFortBundle\Events\PfTransactionStatusChangeEvent;

/**
 * 
 */
class PfModelsLifeCycle implements EventSubscriber
{
    private $transactionEvents;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     * */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        $this->transactionEvents = [
            PfTransaction::STATUS_SUCCESS => 'pf.transaction.success',
            PfTransaction::STATUS_FAIL    => 'pf.transaction.fail'
        ];
    }

    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'postPersist'
        );
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof PfTransaction && $args->hasChangedField('currentStatus') && isset($this->transactionEvents[$args->getNewValue('currentStatus')])) {
            $transactionEvent = new PfTransactionStatusChangeEvent($entity);
            $this->dispatcher->dispatch($this->transactionEvents[$args->getNewValue('currentStatus')], $transactionEvent);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof PfTransaction && isset($this->transactionEvents[$entity->getCurrentStatus()])) {
            $transactionEvent = new PfTransactionStatusChangeEvent($entity);
            $this->dispatcher->dispatch($this->transactionEvents[$entity->getCurrentStatus()], $transactionEvent);
        }
    }
}