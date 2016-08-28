<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PayFortController extends Controller
{

    public function indexAction(Request $request)
    {
        $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
        $response           = $payfortIntegration->requestSDKToken("ffffffff-a9fa-0b44-7b27-29e70033c587");

        echo "<pre>";
        var_dump($response);
        echo "</pre>";
        die;

        return $this->render('AppBundle:Dashboard:index.html.twig');
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

    public function requestSDKTokenAction(Request $request, $token)
    {
        $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
        $response           = $payfortIntegration->requestSDKToken($token);

        echo "<pre>";
        var_dump($response);
        echo "</pre>";
        die;
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