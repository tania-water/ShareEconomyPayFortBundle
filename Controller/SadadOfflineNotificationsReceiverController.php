<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ibtikar\ShareEconomyPayFortBundle\Entity\PfSadadTransaction;

class SadadOfflineNotificationsReceiverController extends Controller {

    /**
     *
     * @param Request $request
     */
    public function transactionStatusUpdateNotificationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('ibtikar.shareeconomy.payfort.integration')->isValidResponse($requestParams)) {
            $transaction = $em->getRepository('IbtikarShareEconomyPayFortBundle:PfSadadTransaction')->findOneBy(['fortId' => $requestParams['fort_id'], 'merchantReference' => $requestParams['merchant_reference']]);

            if ($transaction) {
                $this->get('ibtikar.shareeconomy.payfort.transaction_status_service')->addTransactionStatus($transaction, $requestParams);
            }
            else if(isset($requestParams['merchant_extra']) && isset($requestParams['sadad_olp']))
            {
                $invoiceEntity = $em->getRepository($this->getParameter('ibtikar_share_economy_pay_fort.invoice_entity_class'))->find($requestParams['merchant_extra']);
                if($invoiceEntity)
                {
                    $transaction = new PfSadadTransaction();

                    $transaction->setInvoice($invoiceEntity)
                            ->setAmount($requestParams['amount'])
                            ->setMerchantReference($requestParams['merchant_reference'])
                            ->setFortId($requestParams['fort_id'])
                            ->setCurrency($requestParams['currency'])
                            ->setSadadOlp($requestParams['sadad_olp']);

                    if (isset($requestParams['customer_ip']))
                        $transaction->setCustomerIp($requestParams['customer_ip']);

                    if (isset($requestParams['authorization_code']))
                        $transaction->setAuthorizationCode($requestParams['authorization_code']);


                    $em->persist($transaction);
                    $em->flush();
                    $em->refresh($transaction);

                    $this->get('ibtikar.shareeconomy.payfort.transaction_status_service')->addTransactionStatus($transaction, $requestParams);
                }
            }

            return new \Symfony\Component\HttpFoundation\Response('notification received successfully.');
        } else {
            return new \Symfony\Component\HttpFoundation\Response('Request signature not valid.', 400);
        }
    }
}
