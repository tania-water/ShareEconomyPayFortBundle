<?php

namespace Ibtikar\ShareEconomyPayFortBundle\APIResponse;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Kitchen object in a list
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class PaymentMethodDetailsResponse extends \Ibtikar\ShareEconomyToolsBundle\APIResponse\MainResponse
{
    /**
     * @Assert\Type("integer")
     */
    public $id;

    /**
     * @Assert\Type("string")
     */
    public $fortId;

    /**
     * @Assert\Type("string")
     */
    public $cardNumber;

    /**
     * @Assert\Type("string")
     */
    public $expiryDate;

    /**
     * @Assert\Type("string")
     */
    public $merchantReference;

    /**
     * @Assert\Type("string")
     */
    public $tokenName;

    /**
     * @Assert\Type("string")
     */
    public $paymentOption;

    /**
     * @Assert\Type("boolean")
     */
    public $isDefault;

}