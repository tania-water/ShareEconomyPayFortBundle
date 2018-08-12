<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ibtikar\ShareEconomyPayFortBundle\APIResponse as AppAPIResponses;
use Ibtikar\ShareEconomyToolsBundle\APIResponse as ToolsBundleAPIResponses;

class PayFortController extends Controller
{

    /**
     * request device SDK token
     *
     * @ApiDoc(
     *  section="PayFort",
     *  tags={
     *     "stable"="green"
     *  },
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

    /**
     * pay1
     *
     * @ApiDoc(
     *  section="PayFort",
     *  tags={
     *     "stable"="green"
     *  },
     *  authentication=true,
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=true},
     *      {"name"="tokenName", "dataType"="string", "required"=true},
     *      {"name"="merchantReference", "dataType"="string", "required"=true},
     *  },
     *  statusCodes = {
     *      200="Returned on success",
     *      422="Returned if there is a validation error in the sent data",
     *      500="Returned if there is an internal server error"
     *  },
     *  responseMap = {
     *      422="Ibtikar\ShareEconomyToolsBundle\APIResponse\ValidationErrors",
     *      500="Ibtikar\ShareEconomyToolsBundle\APIResponse\InternalServerError"
     *  }
     * )
     * @author Ahmed Yahia <ahmed.mahmoud@ibtikar.net.sa>
     * @return JsonResponse
     */
    public function initialTransactionAction(Request $request)
    {
        $itr = new \Ibtikar\ShareEconomyPayFortBundle\APIResponse\initialTransactionRequest();
        $itr->email=$request->get('email');
        $itr->merchantReference=$request->get('merchantReference');
        $itr->tokenName=$request->get('tokenName');
        $validationMessages = $this->get('api_operations')->validateObject($itr);
        if (count($validationMessages)) {
            $output = new ToolsBundleAPIResponses\ValidationErrors();
            $output->errors = $validationMessages;
            return new JsonResponse($validationMessages);
        } 
        /* @var $pf \Ibtikar\ShareEconomyPayFortBundle\Service\PayFortIntegration */
        $pf = $this->get('ibtikar.shareeconomy.payfort.integration');
        $paymentAmount= 1;
        $response = $pf->purchase($request->get('tokenName'), $paymentAmount, $request->get('merchantReference'), $request->get('email'));
        return new JsonResponse($response);
    }
    
}