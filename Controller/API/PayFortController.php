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

    /**
     * add payment method
     *
     * @ApiDoc(
     *  section="PayFort",
     *  parameters={
     *      {"name"="cardNumber", "dataType"="string", "required"=true},
     *      {"name"="expiryDate", "dataType"="string", "required"=true},
     *      {"name"="tokenName", "dataType"="string", "required"=true},
     *      {"name"="paymentOption", "dataType"="string", "required"=false},
     *      {"name"="merchantReference", "dataType"="string", "required"=true},
     *      {"name"="fortId", "dataType"="string", "required"=true}
     *  },
     *  output="Ibtikar\ShareEconomyPayFortBundle\APIResponse\MainResponse"
     * )
     * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
     * @return JsonResponse
     */
    public function addPaymentMethodAction(Request $request)
    {
        $em                      = $this->getDoctrine()->getManager();
        $user                    = $this->getUser();
        $hasDefaultPaymentMethod = $em->getRepository('IbtikarShareEconomyPayFortBundle:PfPaymentMethod')->hasDefaultPaymentMethod($user);

        $paymentMethod = new \Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethod();
        $paymentMethod->setHolder($user);
        $paymentMethod->setCardNumber($request->request->get('cardNumber'));
        $paymentMethod->setExpiryDate($request->request->get('expiryDate'));
        $paymentMethod->setTokenName($request->request->get('tokenName'));
        $paymentMethod->setPaymentOption($request->request->get('paymentOption'));
        $paymentMethod->setMerchantReference($request->request->get('merchantReference'));
        $paymentMethod->setFortId($request->request->get('fortId'));
        $paymentMethod->setIsDefault(!$hasDefaultPaymentMethod);

        $validationMessages = $this->get('api_operations')->validateObject($paymentMethod);

        if (count($validationMessages)) {
            $output           = new AppAPIResponses\ValidationErrorsResponse();
            $output->messages = $validationMessages;
        } else {
            $em->persist($paymentMethod);

            try {
                $em->flush();

                $output = new AppAPIResponses\MainResponse;
            } catch (\Exception $exc) {
                $output          = new AppAPIResponses\MainResponse();
                $output->status  = false;
                $output->code    = 400;
                $output->message = $this->get('translator')->trans("something_went_wrong");

                $this->get('logger')->error($exc->getMessage());
            }
        }

        return new JsonResponse($output);
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

    public function refundAction(Request $request, $fortId, $amount, $currency)
    {
        $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
        $response           = $payfortIntegration->refund($fortId, $amount, $currency);

        echo "<pre>";
        var_dump($response);
        echo "</pre>";
        die;
    }
}