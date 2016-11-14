<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PfTransactionStatus
 *
 * @ORM\Table(name="pf_transaction_status", indexes={@ORM\Index(name="transaction_id", columns={"transaction_id"})})
 * @ORM\Entity
 */
class PfTransactionStatus
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
     * @var integer
     *
     * @ORM\Column(name="response_code", type="smallint", nullable=false)
     */
    private $responseCode;

    /**
     * @var string
     *
     * @ORM\Column(name="response_message", type="string", length=255, nullable=true)
     */
    private $responseMessage;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="string", length=2, nullable=false, options={"fixed": true})
     */
    private $status;

    /**
     * @var array
     *
     * @ORM\Column(name="response", type="text", length=65535, nullable=true)
     */
    protected $response;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction
     *
     * @ORM\ManyToOne(targetEntity="Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transaction_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * })
     */
    private $transaction;

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
     * Set responseCode
     *
     * @param integer $responseCode
     *
     * @return PfTransactionStatus
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * Get responseCode
     *
     * @return integer
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Set responseMessage
     *
     * @param string $responseMessage
     *
     * @return PfTransactionStatus
     */
    public function setResponseMessage($responseMessage)
    {
        $this->responseMessage = $responseMessage;

        return $this;
    }

    /**
     * Get responseMessage
     *
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return PfTransactionStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set response
     *
     * @param array $response
     *
     * @return PfTransactionStatus
     */
    public function setResponse($response)
    {
        $this->response = json_encode($response);

        return $this;
    }

    /**
     * Get response
     *
     * @return array
     */
    public function getResponse()
    {
        return json_decode($this->response, true);
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PfTransactionStatus
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
     * Set transaction
     *
     * @param \Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction $transaction
     *
     * @return PfTransactionStatus
     */
    public function setTransaction(\Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction $transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param array $response
     */
    public function setAttributesFromResponse(array $response)
    {
        $this->setResponseCode($response['response_code'])
            ->setResponseMessage($response['response_message'])
            ->setStatus($response['status'])
            ->setResponse($response);
    }
}