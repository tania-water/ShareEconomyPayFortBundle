<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

/**
 * An interface that the invoice Subject object should implement.
 * In most circumstances, only a single object should implement
 * this interface as the ResolveTargetEntityListener can only
 * change the target to a single object.
 */
interface PfTransactionInvoiceInterface
{

    public function addPfTransaction(PfTransaction $pfTransaction);

    public function removePfTransaction(PfTransaction $pfTransaction);

    public function getPfTransactions();
}