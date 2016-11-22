<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Command;

use Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionStatus;
use Ibtikar\ShareEconomyPayFortBundle\PfTransactionsResponseCodes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 *
 * command: $ bin/console ibtikar:share-economy-payfort:update-transactions-status
 */
class UpdateTransactionsStatusCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('ibtikar:share-economy-payfort:update-transactions-status')
            ->setDescription("update pending and uncertain transactions statuses from payfort");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em                     = $this->getContainer()->get('doctrine.orm.entity_manager');
        $candidatesTransactions = $em->getRepository('IbtikarShareEconomyPayFortBundle:PfTransaction')->getUpdateStatusCandidatesTransactions();

        foreach ($candidatesTransactions as $transaction) {
            $responseParams = $this->getContainer()->get('ibtikar.shareeconomy.payfort.integration')->checkTransactionStatus($transaction);

            if ($responseParams['status'] == PfTransactionsResponseCodes::CHECK_STATUS_SUCCESS) {
                $transactionStatus = new PfTransactionStatus();
                $transactionStatus->setAttributesFromResponse($responseParams);

                $transaction->addTransactionStatus($transactionStatus);

                $em->persist($transaction);
                $em->flush();
            }
        }
    }
}