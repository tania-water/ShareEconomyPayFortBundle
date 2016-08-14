<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPostJsonBody;

/**
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class PayFortIntegration
{
    private $apiCaller;
    private $config;
    private $merchantIdentifier;
    private $accessCode;
    private $shaType;
    private $shaRequestPhrase;
    private $shaResponsePhrase;
    private $mode;
    private $baseURL;
    private $language = 'en';

    const PRODUCTION_URL  = "https://checkout.payfort.com/FortAPI/paymentApi";
    const SANDBOX_URL     = "https://sbcheckout.payfort.com/FortAPI/paymentApi";
    const PRODUCTION_MODE = "production";
    const SANDBOX_MODE    = "sandbox";

    /**
     * @param array $config
     */
    public function __construct(array $config, LoggingApiCaller $apiCaller)
    {
        $this->apiCaller = $apiCaller;
        $this->config    = $config;
        $this->mode      = $config['environment'];

        if ($config['environment'] == self::PRODUCTION_MODE) {
            $this->baseURL = self::PRODUCTION_URL;
            $this->setEnvironmentAttrs($config['production']);
        } else {
            $this->baseURL = self::SANDBOX_URL;
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
    private function calculateSignature($params, $shaPhrase)
    {
        /**
         * As mentioned in the payfort documentation, the calculations for the Merchant Page 2.0
         * require you to calculate the signature without including the following parameters in the signature
         * even if these parameters included in the request of Merchant Page 2.0: Card_security_code , card_number , expiry_date 
         */
        unset($params['r'], $params['signature'], $params['integration_type ']);

        ksort($params);

        $data = $shaPhrase;

        foreach ($params as $key => $val) {
            $data .= $key.'='.$val;
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
        return $this->calculateSignature($requestParams, $this->shaRequestPhrase);
    }

    /**
     * calculate response Signature Value
     *
     * @param array $requestParams
     * @return string Request signature
     */
    private function calculateResponseSignature($requestParams)
    {
        return $this->calculateSignature($requestParams, $this->shaResponsePhrase);
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
    public function makeRequest($parameters)
    {
        $parameters['language']            = $this->language;
        $parameters['merchant_identifier'] = $this->merchantIdentifier;
        $parameters['access_code']         = $this->accessCode;
        $parameters['signature']           = $this->calculateRequestSignature($parameters);
        $response                          = $this->apiCaller->call(new HttpPostJsonBody($this->baseURL, $parameters, true, [ 'HTTPHEADER' => ['Content-Type:application/json']]));

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
    public function storeCustomerCredit($cardNumber, $cardSecurityCode, $cardExpiryDate)
    {
        $parameters = [
            'service_command'    => 'TOKENIZATION',
            'card_number'        => $cardNumber,
            'card_security_code' => $cardSecurityCode,
            'expiry_date'        => $cardExpiryDate
        ];

        return $this->makeRequest($parameters);
    }
}