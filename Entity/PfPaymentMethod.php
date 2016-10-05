<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PfPaymentMethod
 *
 * @ORM\Table(
 *      name="pf_payment_method",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="token_name", columns={"token_name"}),
 *          @ORM\UniqueConstraint(name="fort_id", columns={"fort_id"}),
 *          @ORM\UniqueConstraint(name="merchant_reference", columns={"merchant_reference"})
 *      },
 *      indexes={
 *          @ORM\Index(name="holder_id", columns={"holder_id"}),
 *          @ORM\Index(name="is_default", columns={"is_default"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Ibtikar\ShareEconomyPayFortBundle\Repository\PfPaymentMethodRepository")
 * @UniqueEntity(fields={"tokenName"}, message="Token name already exist")
 * @UniqueEntity(fields={"fortId"}, message="Fort ID already exist")
 * @UniqueEntity(fields={"merchantReference"}, message="Merchant reference already exist")
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
     *
     * @Assert\NotBlank(message="fill_mandatory_field")
     * @Assert\Valid
     */
    private $holder;

    /**
     * @var string
     *
     * @ORM\Column(name="fort_id", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank(message="fill_mandatory_field")
     */
    private $fortId;

    /**
     * @var string
     *
     * @ORM\Column(name="card_number", type="string", length=20, nullable=false)
     *
     * @Assert\NotBlank(message="fill_mandatory_field")
     */
    private $cardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="expiry_date", type="string", length=10, nullable=false)
     *
     * @Assert\NotBlank(message="fill_mandatory_field")
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_reference", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(message="fill_mandatory_field")
     */
    private $merchantReference;

    /**
     * @var string
     *
     * @ORM\Column(name="token_name", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="fill_mandatory_field")
     */
    private $tokenName;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_option", type="string", length=50, nullable=true)
     */
    private $paymentOption;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private $isDefault;

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
     * Set fortId
     *
     * @param string $fortId
     *
     * @return PfPaymentMethod
     */
    public function setFortId($fortId)
    {
        $this->fortId = $fortId;

        return $this;
    }

    /**
     * Get fortId
     *
     * @return string
     */
    public function getFortId()
    {
        return $this->fortId;
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
     * @param string $expiryDate
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
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set merchantReference
     *
     * @param string $merchantReference
     *
     * @return PfPaymentMethod
     */
    public function setMerchantReference($merchantReference)
    {
        $this->merchantReference = $merchantReference;

        return $this;
    }

    /**
     * Get merchantReference
     *
     * @return string
     */
    public function getMerchantReference()
    {
        return $this->merchantReference;
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
     * Set paymentOption
     *
     * @param string $paymentOption
     *
     * @return PfPaymentMethod
     */
    public function setPaymentOption($paymentOption)
    {
        $this->paymentOption = $paymentOption;

        return $this;
    }

    /**
     * Get paymentOption
     *
     * @return string
     */
    public function getPaymentOption()
    {
        return $this->paymentOption;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     *
     * @return DishImage
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
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