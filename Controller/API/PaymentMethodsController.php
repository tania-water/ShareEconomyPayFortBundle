<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ibtikar\ShareEconomyToolsBundle\APIResponse as ToolsBundleAPIResponses;

class PaymentMethodsController extends Controller
{

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
     *  output="Ibtikar\ShareEconomyToolsBundle\APIResponse\Success"
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
            $output         = new ToolsBundleAPIResponses\ValidationErrors();
            $output->errors = $validationMessages;
        } else {
            $em->persist($paymentMethod);

            try {
                $em->flush();

                $output = new ToolsBundleAPIResponses\Success();
            } catch (\Exception $exc) {
                $output = new ToolsBundleAPIResponses\InternalServerError();

                $this->get('logger')->error($exc->getMessage());
            }
        }

        return new JsonResponse($output);
    }

    /**
     * set payment method as default
     *
     * @ApiDoc(
     *  section="PayFort",
     *  output="Ibtikar\ShareEconomyToolsBundle\APIResponse\Success"
     * )
     * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
     * @return JsonResponse
     */
    public function setDefaultPaymentMethodAction(Request $request, $id)
    {
        $em            = $this->getDoctrine()->getManager();
        $user          = $this->getUser();
        $paymentMethod = $em->getRepository('IbtikarShareEconomyPayFortBundle:PfPaymentMethod')->find($id);

        if (!$paymentMethod) {
            return new JsonResponse(new ToolsBundleAPIResponses\EntityNotFoundResponse());
        } elseif ($paymentMethod->getHolder()->getId() !== $user->getId()) {
            $output = new JsonResponse(new ToolsBundleAPIResponses\AccessDenied());
        } else {
            $defaultPaymentMethod = $em->getRepository('IbtikarShareEconomyPayFortBundle:PfPaymentMethod')->findOneBy(['holder' => $user, 'isDefault' => true]);

            if ($defaultPaymentMethod){
                $defaultPaymentMethod->setIsDefault(false);
            }

            $paymentMethod->setIsDefault(true);
            $em->flush();

            $output = new ToolsBundleAPIResponses\Success();
        }

        return new JsonResponse($output);
    }
}