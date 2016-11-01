<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

use Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethodHolderInterface;

/**
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class PaymentMethodsInterface
{
    private $em;

    /**
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     *
     * @param integer $id
     * @param PfPaymentMethodHolderInterface $holder
     * @return PfPaymentMethod|null
     */
    public function getPaymentMethodByHolder($id, $holder)
    {
        return $this->em->getRepository('IbtikarShareEconomyPayFortBundle:PfPaymentMethod')->findOneBy(['id' => $id, 'holder' => $holder]);
    }

    /**
     * @param integer $id
     * @param PfPaymentMethodHolderInterface $holder
     * @return PfPaymentMethod|null
     */
    public function getHolderDefaultPaymentMethod($holder)
    {
        return $this->em->getRepository('IbtikarShareEconomyPayFortBundle:PfPaymentMethod')->findOneBy(['holder' => $holder, 'isDefault' => true]);
    }
}