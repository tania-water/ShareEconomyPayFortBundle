<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OfflineNotificationsReceiverController extends Controller
{

    /**
     *
     * @param Request $request
     */
    public function transactionStatusUpdateNotificationAction(Request $request)
    {
        $em            = $this->getDoctrine()->getManager();
        $requestParams = $request->request->all();

        if ($this->get('ibtikar.shareeconomy.payfort.integration')->isValidResponse($requestParams)) {
            $transaction = $em->getRepository('IbtikarShareEconomyPayFortBundle:PfTransaction')->findOneBy(['fortId' => $requestParams['fort_id'], 'merchantReference' => $requestParams['merchant_reference']]);

            if ($transaction) {
                $transactionStatus = new PfTransactionStatus();
                $transactionStatus->setAttributesFromResponse($requestParams);

                $transaction->addTransactionStatus($transactionStatus);

                $em->persist($transaction);
                $em->flush();
            }

            return new \Symfony\Component\HttpFoundation\Response('notification received successfully.');
        } else {
            return new \Symfony\Component\HttpFoundation\Response('Request signature not valid.', 400);
        }
    }
}