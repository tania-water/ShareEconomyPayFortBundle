<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPostJsonBody;

/**
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class PayFortIntegration
{
    private $templating;
    private $apiCaller;
    private $config;
    private $merchantIdentifier;
    private $accessCode;
    private $shaType;
    private $shaRequestPhrase;
    private $shaResponsePhrase;
    private $mode;
    private $baseMerchantPageURL;
    private $baseApiURL;
    private $language = 'en';

    const SANDBOX_MODE                 = "sandbox";
    const PRODUCTION_MODE              = "production";
    const SANDBOX_API_URL              = "https://sbpaymentservices.payfort.com/FortAPI/paymentApi";
    const PRODUCTION_API_URL           = "https://paymentservices.payfort.com/FortAPI/paymentApi";
    const SANDBOX_MERCHANT_PAGE_URL    = "https://sbcheckout.PayFort.com/FortAPI/paymentPage";
    const PRODUCTION_MERCHANT_PAGE_URL = "https://checkout.payfort.com/FortAPI/paymentPage";

    /**
     * @param array $config
     */
    public function __construct(array $config, $templating, LoggingApiCaller $apiCaller)
    {
        $this->templating = $templating;
        $this->apiCaller  = $apiCaller;
        $this->config     = $config;
        $this->mode       = $config['environment'];

        if ($config['environment'] == self::PRODUCTION_MODE) {
            $this->baseMerchantPageURL = self::PRODUCTION_MERCHANT_PAGE_URL;
            $this->baseApiURL          = self::PRODUCTION_API_URL;

            $this->setEnvironmentAttrs($config['production']);
        } else {
            $this->baseMerchantPageURL = self::SANDBOX_MERCHANT_PAGE_URL;
            $this->baseApiURL          = self::SANDBOX_API_URL;

            $this->setEnvironmentAttrs($config['sandbox']);
        }
    }

    /**
     *
     * @param type $config
     */
    private function setEnvironmentAttrs($config)
    {
        $this->merchantIdentifier = $config['merchantIdentifier'];
        $this->accessCode         = $config['accessCode'];
        $this->shaType            = $config['shaType'];
        $this->shaRequestPhrase   = $config['shaRequestPhrase'];
        $this->shaResponsePhrase  = $config['shaResponsePhrase'];
    }

    /**
     * calculate Signature Value
     *
     * @param array $params
     * @return string signature
     */
    private function calculateSignature($params, $shaPhrase, $excludedParams)
    {
        ksort($params);

        $data = $shaPhrase;
        foreach ($params as $key => $val) {
            if (!in_array($key, $excludedParams)) {
                $data .= $key . '=' . $val;
            }
        }
        $data .= $shaPhrase;

        return hash($this->shaType, $data);
    }

    /**
     * calculate request Signature Value
     *
     * @param array $requestParams
     * @return string Request signature
     */
    private function calculateRequestSignature($requestParams)
    {
        return $this->calculateSignature($requestParams, $this->shaRequestPhrase, ["card_number", "expiry_date", "card_security_code", "card_holder_name"]);
    }

    /**
     * calculate response Signature Value
     *
     * @param array $requestParams
     * @return string Request signature
     */
    public function calculateResponseSignature($requestParams)
    {
        return $this->calculateSignature($requestParams, $this->shaResponsePhrase, ["signature"]);
    }

    /**
     *
     * @param type $responseParameters
     * @return type
     */
    private function isValidResponse($responseParameters)
    {
        return isset($responseParameters['signature']) && $this->calculateResponseSignature($responseParameters) == $responseParameters['signature'];
    }

    /**
     *
     * @param array $parameters
     * @return array
     * @throws \Exception
     */
    public function makeAPIRequest($params)
    {
        $parameters = $this->addDefaultParams($params);

        $response   = $this->apiCaller->call(new HttpPostJsonBody($this->baseApiURL, $parameters, true, [ 'HTTPHEADER' => ['Content-Type:application/json']]));

        // verify the request signature
        if (!$this->isValidResponse($response)) {
            throw new \Exception("Response signature not correct.");
        }

        return $response;
    }

    /**
     *
     * @param type $cardNumber
     * @param type $cardSecurityCode
     * @param type $cardExpiryDate
     * @return type
     */
    public function storeCustomerCredit($holderName, $merchantReference, $cardNumber, $cardSecurityCode, $cardExpiryDate)
    {
        $parameters = [
            'card_holder_name'   => $holderName,
            'merchant_reference' => $merchantReference,
            'service_command'    => 'TOKENIZATION',
            'card_number'        => $cardNumber,
            'card_security_code' => $cardSecurityCode,
            'expiry_date'        => $cardExpiryDate
        ];

        return $this->makeAPIRequest($parameters);
    }

    /**
     *
     * @param type $deviceID
     * @return type
     */
    public function requestSDKToken($deviceID)
    {
        $parameters = [
            'service_command'  => 'SDK_TOKEN',
            'device_id' => $deviceID
        ];

        return $this->makeAPIRequest($parameters);
    }

    /**
     * gererates the tokenization form html that should be submitted to payfort
     *
     * @param type $merchantReference
     * @return type
     */
    public function getTokenizationForm($merchantReference)
    {
        $params = [
            'service_command'    => 'TOKENIZATION',
            'merchant_reference' => $merchantReference
        ];

        $formParams = $this->addDefaultParams($params);

        return $this->templating->render('IbtikarShareEconomyPayFortBundle:Default:tokenizationForm.html.twig', ['formParams' => $formParams, 'formAction' => $this->baseMerchantPageURL]);
    }

    public function purchase($email, $token_name, $amount, $reference)
    {
        $parameters = [
            'command'            => 'PURCHASE',
            'customer_email'     => $email,
            'currency'           => 'AED',
            'amount'             => $amount,
            'token_name'         => $token_name,
            'merchant_reference' => $reference
        ];

        return $this->makeAPIRequest($parameters);
    }

    private function addDefaultParams($params)
    {
        $params['language']            = $this->language;
        $params['merchant_identifier'] = $this->merchantIdentifier;
        $params['access_code']         = $this->accessCode;
        $params['signature']           = $this->calculateRequestSignature($params);

        return $params;
    }
}