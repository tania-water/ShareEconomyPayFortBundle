<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pf_transaction")
 * @ORM\Entity()
 */
class PfSadadTransaction extends PfTransaction
{

    /**
     * @var string
     *
     * @ORM\Column(name="sadad_olp", type="string", length=255, nullable=true)
     */
    private $sadadOlp;

    /**
     * Set sadadOlp
     *
     * @param string $sadadOlp
     *
     * @return PfTransaction
     */
    public function setSadadOlp($sadadOlp)
    {
        $this->sadadOlp = $sadadOlp;

        return $this;
    }

    /**
     * Get sadadOlp
     *
     * @return string
     */
    public function getSadadOlp()
    {
        return $this->sadadOlp;
    }
}
