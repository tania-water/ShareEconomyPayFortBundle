<?php

namespace Ibtikar\ShareEconomyPayFortBundle\APIResponse;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of MainResponse
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class MainResponse
{
    /**
     * @Assert\NotBlank
     */
    public $code = 200;

    /**
     * @Assert\NotBlank
     */
    public $status = true;

    /**
     * @Assert\NotBlank
     */
    public $message = '';

}