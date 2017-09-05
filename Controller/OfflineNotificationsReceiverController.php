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
                $this->get('ibtikar.shareeconomy.payfort.transaction_status_service')->addTransactionStatus($transaction, $requestParams);
            }

            return new \Symfony\Component\HttpFoundation\Response('notification received successfully.');
        } else {
            return new \Symfony\Component\HttpFoundation\Response('Request signature not valid.', 400);
        }
    }
}
