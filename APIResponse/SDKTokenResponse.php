<?php

namespace Ibtikar\ShareEconomyPayFortBundle\APIResponse;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Kitchen Profile response object
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class SDKTokenResponse extends \Ibtikar\ShareEconomyToolsBundle\APIResponse\MainResponse
{
    /**
     * @Assert\NotBlank
     */
    public $SDKToken;

}