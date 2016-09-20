<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

trait PfPaymentMethodHolderTrait
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod", mappedBy="holder")
     */
    protected $pfPaymentMethods;

    /**
     * Add PfPaymentMethod
     *
     * @param PfPaymentMethod $pfPaymentMethod
     * @return this
     */
    public function addPfPaymentMethod(PfPaymentMethod $pfPaymentMethod)
    {
        $this->pfPaymentMethods->add($pfPaymentMethod);

        return $this;
    }

    /**
     * Add PfPaymentMethod
     *
     * @param PfPaymentMethod $pfPaymentMethod
     */
    public function removePfPaymentMethod(PfPaymentMethod $pfPaymentMethod)
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