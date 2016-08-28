<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

trait PfPaymentMethodHolderTrait
{
    /**
     * @var ArrayCollection
     */
    protected $pfPaymentMethods;

    /**
     * Add PfPaymentMethod
     *
     * @param Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod $pfPaymentMethod
     * @return this
     */
    public function addPfPaymentMethod(Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod $pfPaymentMethod)
    {
        $this->pfPaymentMethods->add($pfPaymentMethod);

        return $this;
    }

    /**
     * Add PfPaymentMethod
     *
     * @param Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod $pfPaymentMethod
     */
    public function removePfPaymentMethod(Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod $pfPaymentMethod)
    {
        $this->pfPaymentMethods->removeElement($pfPaymentMethod);
    }

    /**
     * Get PfPaymentMethods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPfPaymentMethods()
    {
        return $this->pfPaymentMethods;
    }
}