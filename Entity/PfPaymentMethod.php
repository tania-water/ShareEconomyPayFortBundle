<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * PfPaymentMethod
 *
 * @ORM\Table(name="pf_payment_method", indexes={@ORM\Index(name="holder_id", columns={"holder_id"})})
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class PfPaymentMethod
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PfPaymentMethodHolderInterface", inversedBy="pfPaymentMethods")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="holder_id", referencedColumnName="id", nullable=false)
     * })
     * @var PfPaymentMethodHolderInterface
     */
    private $holder;

    /**
     * @var string
     *
     * @ORM\Column(name="card_holder_name", type="string", length=255, nullable=true)
     */
    private $cardHolderName;

    /**
     * @var string
     *
     * @ORM\Column(name="card_number", type="string", length=20, nullable=false)
     */
    private $cardNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="date", nullable=false)
     */
    private $expiryDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="card_bin", type="integer", nullable=true)
     */
    private $cardBin;

    /**
     * @var string
     *
     * @ORM\Column(name="token_name", type="string", length=255, nullable=true)
     */
    private $tokenName;

    /**
     * @var integer
     *
     * @ORM\Column(name="pf_status", type="smallint", nullable=true)
     */
    private $pfStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set holder
     *
     * @return PfPaymentMethod
     */
    public function setHolder($holder = null)
    {
        $this->holder = $holder;

        return $this;
    }

    /**
     * Get holder
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * Set cardHolderName
     *
     * @param string $cardHolderName
     *
     * @return PfPaymentMethod
     */
    public function setCardHolderName($cardHolderName)
    {
        $this->cardHolderName = $cardHolderName;

        return $this;
    }

    /**
     * Get cardHolderName
     *
     * @return string
     */
    public function getCardHolderName()
    {
        return $this->cardHolderName;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     *
     * @return PfPaymentMethod
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return PfPaymentMethod
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set cardBin
     *
     * @param integer $cardBin
     *
     * @return PfPaymentMethod
     */
    public function setCardBin($cardBin)
    {
        $this->cardBin = $cardBin;

        return $this;
    }

    /**
     * Get cardBin
     *
     * @return integer
     */
    public function getCardBin()
    {
        return $this->cardBin;
    }

    /**
     * Set tokenName
     *
     * @param string $tokenName
     *
     * @return PfPaymentMethod
     */
    public function setTokenName($tokenName)
    {
        $this->tokenName = $tokenName;

        return $this;
    }

    /**
     * Get tokenName
     *
     * @return string
     */
    public function getTokenName()
    {
        return $this->tokenName;
    }

    /**
     * Set pfStatus
     *
     * @param integer $pfStatus
     *
     * @return PfPaymentMethod
     */
    public function setPfStatus($pfStatus)
    {
        $this->pfStatus = $pfStatus;

        return $this;
    }

    /**
     * Get pfStatus
     *
     * @return integer
     */
    public function getPfStatus()
    {
        return $this->pfStatus;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PfPaymentMethod
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return PfPaymentMethod
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
}
