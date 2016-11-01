<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DynamicRelationSubscriber implements EventSubscriberInterface
{
    const PF_PAYMENT_METHOD_HOLDER_INTERFACE_FQNS = 'Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethodHolderInterface';
    const PF_TRANSACTION_INVOICE_INTERFACE_FQNS   = 'Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface';

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

        if (in_array(self::PF_PAYMENT_METHOD_HOLDER_INTERFACE_FQNS, class_implements($metadata->getName()))) {
            $metadata->mapOneToMany(array(
                'targetEntity' => PfPaymentMethod::CLASS,
                'mappedBy'     => 'holder',
                'cascade'      => ['persist', 'remove']
            ));
        } elseif (in_array(self::PF_TRANSACTION_INVOICE_INTERFACE_FQNS, class_implements($metadata->getName()))) {
            $metadata->mapOneToMany(array(
                'targetEntity' => PfTransaction::CLASS,
                'mappedBy'     => 'invoice',
                'cascade'      => ['persist', 'remove']
            ));
        }
    }
}