<?php


namespace Mabes\Controllers;

use Mabes\Entity\Deposit;
use Mabes\Service\Command\DepositMarkAsDoneCommand;
use Mabes\Service\Command\DepositMarkAsFailedCommand;

class AdminDepositController extends BaseController
{
    public function getAdminDeposits()
    {
        $data["deposits"] = $this->app->em->getRepository("Mabes\\Entity\\Deposit")
            ->findBy(["status" => Deposit::STATUS_OPEN]);
        $data["base_url"] = $this->app->config["base_url"];
        $this->app->render('Pages/_admin_deposit.twig', $data);
    }

    public function getProcessedDeposit()
    {
        $data["deposits"] = $this->app->em->getRepository("Mabes\\Entity\\Deposit")
            ->findBy(["status" => Deposit::STATUS_PROCESSED], ["deposit_id" => "DESC"]);
        $data["base_url"] = $this->app->config["base_url"];
        $this->app->render('Pages/_complete_admin_deposit.twig', $data);
    }

    public function getDeletedDeposit()
    {
        $data["deposits"] = $this->app->em->getRepository("Mabes\\Entity\\Deposit")
            ->findBy(["status" => Deposit::STATUS_FAILED], ["deposit_id" => "DESC"]);
        $data["base_url"] = $this->app->config["base_url"];
        $this->app->render('Pages/_deleted_admin_deposit.twig', $data);
    }

    public function getAdminDepositMarkAsFailed($deposit_id = 0)
    {
        try {
            $depo_failed_service = $this->app->container->get("DepositMarkAsFailedService");

            $depo_failed_command = new DepositMarkAsFailedCommand();
            $depo_failed_command->massAssignment([
                "deposit_id" => $deposit_id
            ]);

            $depo_failed_service->execute($depo_failed_command);
            $this->app->response->redirect("{$this->app->config["base_url"]}administrator/deposits");
        } catch (\DomainException $e) {
            echo $e->getMessage();
        }
    }

    public function getAdminDepositMarkAsDone($deposit_id = 0)
    {
        try {
            $depo_done_service = $this->app->container->get("DepositMarkAsDoneService");

            $depo_done_command = new DepositMarkAsDoneCommand();
            $depo_done_command->massAssignment([
                "deposit_id" => $deposit_id
            ]);

            $depo_done_service->execute($depo_done_command);
            $this->app->response->redirect("{$this->app->config["base_url"]}administrator/deposits");
        } catch (\DomainException $e) {
            echo $e->getMessage();
        }
    }
}

// EOF
