<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PfTransaction
 *
 * @ORM\Table(
 *      name="pf_transaction",
 *      indexes={
 *          @ORM\Index(name="payment_method_id", columns={"payment_method_id"}),
 *          @ORM\Index(name="invoice_id", columns={"invoice_id"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Ibtikar\ShareEconomyPayFortBundle\Repository\PfTransactionRepository")
 */
class PfTransaction
{

    /**
     * not a final state and the transaction maybe updated to be success or fail.
     */
    const STATUS_PENDING = 1;

    /**
     * transaction failed for any reason, final state and it will not be updated again.
     */
    const STATUS_FAIL = 2;

    /**
     * transaction succeed, it is a final state, final state and it will not be updated again.
     */
    const STATUS_SUCCESS = 3;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface
     *
     * @ORM\ManyToOne(targetEntity="Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface", inversedBy="pfTransactions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * })
     *
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_ip", type="string", length=50, nullable=true)
     */
    private $customerIp;

    /**
     * @var string
     *
     * @ORM\Column(name="fort_id", type="string", length=20, nullable=false)
     */
    private $fortId;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3, nullable=false, options={"fixed": true})
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_reference", type="string", length=100, nullable=false)
     */
    private $merchantReference;

    /**
     * @var integer
     *
     * @ORM\Column(name="authorization_code", type="integer", nullable=true)
     */
    private $authorizationCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_status", type="smallint", nullable=true)
     */
    private $currentStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updateAt;

    /**
     * @var PfPaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="PfPaymentMethod", inversedBy="transactions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id", onDelete="RESTRICT", nullable=false)
     * })
     */
    private $paymentMethod;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PfTransactionStatus", mappedBy="transaction", cascade={"persist"})
     */
    private $transactionStatuses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactionStatuses = new ArrayCollection();
    }

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
     * Set invoice
     *
     * @return PfTransaction
     */
    public function setInvoice($invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set customerIp
     *
     * @param string $customerIp
     *
     * @return PfTransaction
     */
    public function setCustomerIp($customerIp)
    {
        $this->customerIp = $customerIp;

        return $this;
    }

    /**
     * Get customerIp
     *
     * @return string
     */
    public function getCustomerIp()
    {
        return $this->customerIp;
    }

    /**
     * Set fortId
     *
     * @param string $fortId
     *
     * @return PfTransaction
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
     * Set currency
     *
     * @param string $currency
     *
     * @return PfTransaction
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return PfTransaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set merchantReference
     *
     * @param string $merchantReference
     *
     * @return PfTransaction
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
     * Set authorizationCode
     *
     * @param integer $authorizationCode
     *
     * @return PfTransaction
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * Get authorizationCode
     *
     * @return integer
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * Set currentStatus
     *
     * @param integer $currentStatus
     *
     * @return PfTransaction
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * Get currentStatus
     *
     * @return integer
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PfTransaction
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
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return PfTransaction
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set paymentMethod
     *
     * @param PfPaymentMethod $paymentMethod
     *
     * @return PfTransaction
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
     * Add transactionStatus
     *
     * @param PfTransactionStatus $transactionStatus
     *
     * @return PfTransaction
     */
    public function addTransactionStatus(PfTransactionStatus $transactionStatus)
    {
        $this->transactionStatuses[] = $transactionStatus;
        $transactionStatus->setTransaction($this);

        return $this;
    }

    /**
     * Remove transactionStatus
     *
     * @param PfTransactionStatus $transactionStatus
     */
    public function removeTransactionStatus(PfTransactionStatus $transactionStatus)
    {
        $this->transactionStatuses->removeElement($transactionStatus);
    }

    /**
     * Get transactionStatuses
     *
     * @return Collection
     */
    public function getTransactionStatuses()
    {
        return $this->transactionStatuses;
    }
}
