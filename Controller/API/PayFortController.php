<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ibtikar\ShareEconomyPayFortBundle\APIResponse as AppAPIResponses;

class PayFortController extends Controller
{

    /**
     * request device SDK token
     *
     * @ApiDoc(
     *  section="PayFort",
     *  output="Ibtikar\ShareEconomyPayFortBundle\APIResponse\SDKTokenResponse"
     * )
     * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
     * @return JsonResponse
     */
    public function requestSDKTokenAction(Request $request, $device_id)
    {
        $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
        $apiResponse        = $payfortIntegration->requestSDKToken($device_id);
        $responseObject     = new AppAPIResponses\SDKTokenResponse();

        if ($apiResponse['status'] == 22 && $apiResponse['response_code'] == 22000) {
            $responseObject->SDKToken = $apiResponse['sdk_token'];
            $responseObject->message  = $apiResponse['response_message'];
        } else {
            $responseObject->status  = false;
            $responseObject->code    = $apiResponse['status'];
            $responseObject->message = $apiResponse['response_message'];
        }

        return new JsonResponse($responseObject);
    }
}