<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Service;

/**
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
class PayFortIntegration
{
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
    const TEST_URL        = "https://sbcheckout.payfort.com/FortAPI/paymentApi";
    const PRODUCTION_MODE = "production";
    const TEST_MODE       = "test";

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->mode   = $config['environment'];

        if ($config['environment'] == self::PRODUCTION_MODE) {
            $this->baseURL = self::PRODUCTION_URL;
            $this->setEnvironmentAttrs($config['production']);
        } else {
            $this->baseURL = self::TEST_URL;
            $this->setEnvironmentAttrs($config['test']);
        }
    }

    /**
     *
     * @param type $config
     */
    private function setEnvironmentAttrs($config)
    {
        $this->merchantIdentifier = $config['production']['merchantIdentifier'];
        $this->accessCode         = $config['production']['accessCode'];
        $this->shaType            = $config['production']['shaType'];
        $this->shaRequestPhrase   = $config['production']['shaRequestPhrase'];
        $this->shaResponsePhrase  = $config['production']['shaResponsePhrase'];
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
        unset($params['card_security_code'], $params['card_number'], $params['expiry_date ']);

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
        return $this->getSignature($requestParams, $this->shaRequestPhrase);
    }

    /**
     * calculate response Signature Value
     *
     * @param array $requestParams
     * @return string Request signature
     */
    private function calculateResponseSignature($requestParams)
    {
        return $this->getSignature($requestParams, $this->shaResponsePhrase);
    }
}