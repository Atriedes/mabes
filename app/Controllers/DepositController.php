<?php

namespace Mabes\Controllers;

use Mabes\Service\Command\CreateDepositCommand;

class DepositController extends BaseController
{
    public function getDeposit()
    {
        $this->app->view()->appendData(
            [
                "captcha" => $this->buildCaptcha()
            ]
        );

        $this->app->render('Pages/_deposit.twig');
    }

    public function postDeposit()
    {
        try {
            if ($this->app->session->phrase != $this->app->request->post("captcha")) {
                throw new \DomainException("Captcha yang anda masukkan salah!");
            }

            $deposit_service = $this->app->container->get("CreateDepositService");

            $deposit_command = new CreateDepositCommand();
            $deposit_command->massAssignment($this->app->request->post());

            $ticket = $deposit_service->execute($deposit_command);

            $this->app->view()->appendData(
                [
                    "isSuccess" => true,
                    "successTitle" => "Berhasil",
                    "successMessage" => "Tiket deposit anda : #{$ticket}"
                ]
            );

        } catch (\DomainException $e) {
            $this->validationMessage(
                [
                    "custom" => $e->getMessage()
                ]
            );
        }

        $this->app->view()->appendData(
            [
                "captcha" => $this->buildCaptcha()
            ]
        );

        $this->app->render('Pages/_deposit.twig');
    }
}