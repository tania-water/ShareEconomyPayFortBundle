<?php

namespace Ibtikar\ShareEconomyPayFortBundle\APIResponse;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * ValidationErrorsResponse response object
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class ValidationErrorsResponse extends MainResponse
{
    /**
     * @Assert\Type("integer")
     */
    public $code = 422;

    /**
     * @Assert\Type("boolean")
     */
    public $status = false;

    /**
     * @Assert\Type("array")
     */
    public $messages = [];

}