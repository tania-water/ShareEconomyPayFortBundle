<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

trait PfTransactionInvoiceTrait
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction", mappedBy="invoice")
     */
    protected $pfTransactions;

    /**
     * Add PfTransaction
     *
     * @param PfTransaction $pfTransaction
     * @return this
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
}