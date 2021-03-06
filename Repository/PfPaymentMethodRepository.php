<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Repository;

/**
 * PfPaymentMethodRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PfPaymentMethodRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param  $holder
     * @return integer
     */
    public function countHolderPaymentMethods($holder)
    {
        return $this->createQueryBuilder('pm')
                ->select('count(pm.id)')
                ->where('pm.holder = :holder')
                ->setParameter('holder', $holder)
                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
     * @param string $userId
     * @return boolean
     */
    public function clearUserDefaultPaymentMethod($userId)
    {
        return $this->getEntityManager()->createQuery('UPDATE ' . $this->getEntityName() . ' pfp SET pfp.isDefault = 0 WHERE pfp.holder = :userId')->execute(array('userId' => $userId));
    }
}