<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction;

trait PfTransactionInvoiceTrait
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction", mappedBy="invoice")
     */
    protected $pfTransactions;

    /**
     * @ORM\ManyToOne(targetEntity="Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod")
     * @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id", nullable=true)
     */
    protected $paymentMethod;

    /**
     * Add PfTransaction
     *
     * @param PfTransaction $pfTransaction
     * @return
     */
    public function addPfTransaction(PfTransaction $pfTransaction)
    {
        $this->pfTransactions->add($pfTransaction);

        return $this;
    }

    /**
     * remove PfTransaction
     *
     * @param PfTransaction $pfTransaction
     */
    public function removePfTransaction(PfTransaction $pfTransaction)
    {
        $this->pfTransactions->removeElement($pfTransaction);
    }

    /**
     * Get PfTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPfTransactions()
    {
        return $this->pfTransactions;
    }

    /**
     * Set paymentMethod
     *
     * @param PfPaymentMethod $paymentMethod
     *
     * @return
     */
    public function setPaymentMethod(PfPaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return PfPaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod
     */
    public function getPfPaymentMethod() {
        return $this->getPaymentMethod();
    }

    /**
     * check if it is possible to create new transaction for this invoice or not
     * @return boolean
     */
    public function canCreateNewTransaction()
    {
        $return = true;

        if ($this->getPfTransactions()) {
            foreach ($this->getPfTransactions() as $transaction) {
                if ($transaction->getCurrentStatus() != PfTransaction::STATUS_FAIL) {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }
}