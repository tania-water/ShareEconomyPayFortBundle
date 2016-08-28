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

    public function hostToHostNotificationsAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info($request);
    }

    public function afterPaymentAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info($request);
    }

    public function addCreditCardAction(Request $request)
    {
        $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
        $tokenizationForm   = $payfortIntegration->getTokenizationForm(rand(10000, 99999));

        return $this->render('IbtikarShareEconomyPayFortBundle:Default:addCreditCard.html.twig', ['tokenizationForm' => $tokenizationForm]);
    }

    public function purchaseAction(Request $request, $token)
    {
        $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
        $response           = $payfortIntegration->purchase("kareem.elshendy@ibtikar.net.sa", $token, 200.00, rand(10000, 99999));

        echo "<pre>";
        var_dump($response);
        echo "</pre>";
        die;
    }
}