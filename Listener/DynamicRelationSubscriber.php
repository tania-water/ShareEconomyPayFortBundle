<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DynamicRelationSubscriber implements EventSubscriberInterface
{
    const INTERFACE_FQNS = 'Ibtikar\ShareEconomyPayFortBundle\PfPaymentMethodHolderInterface';

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();

        if (!in_array(self::INTERFACE_FQNS, class_implements($metadata->getName()))) {
            return;
        }

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy()
        ;

        $metadata->mapOneToMany(array(
            'targetEntity' => PfPaymentMethod::CLASS,
            'mappedBy'     => 'holder',
            'cascade'      => ['persist', 'remove']
        ));
    }
}