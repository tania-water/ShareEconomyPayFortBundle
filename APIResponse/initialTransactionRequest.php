<?php

namespace Ibtikar\ShareEconomyPayFortBundle\APIResponse;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Kitchen Profile response object
 *
 * @author Ahmed Yahia <ahmed.mahmoud@ibtikar.net.sa>
 */
class initialTransactionRequest 
{
    /**
     * @Assert\NotBlank
     */
    public $email;

    /**
     * @Assert\NotBlank
     */
    public $tokenName;

    /**
     * @Assert\NotBlank
     */
    public $merchantReference;
}
