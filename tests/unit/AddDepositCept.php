<?php

$I = new UnitTester($scenario);
$I->wantTo('add deposit');

$app = \Slim\Slim::getInstance();

$app->container->singleton(
    "CreateDepositService",
    function () use ($app) {
        $member_repo = \Codeception\Util\Stub::make(
            "\\Mabes\\Entity\\MemberRepository",
            [
                "findOneBy" => function () {
                    $member = new \Mabes\Entity\Member();
                    $member->setAccountId(12345);
                    $member->setCreatedAt(new \DateTime());
                    return $member;
                }
            ]
        );

        $depo_repo = \Codeception\Util\Stub::make(
            "\\Mabes\\Entity\\DepositRepository",
            [
                "save" => function () {
                    return true;
                }
            ]
        );

        $event_emittier = \Codeception\Util\Stub::make(
            "\\Evenement\\EventEmitter",
            [
                "emit" => function () {
                    return true;
                }
            ]
        );

        $validator = $app->container->get("Validator");

        return new \Mabes\Service\CreateDepositService($member_repo, $depo_repo, $validator, $event_emittier);
    }
);

$data = [
    "account_id" => 1234,
    "amount_idr" => 12000,
    "amount_usd" => 1.2,
    "to_bank" => "BCA"
];

$command = new \Mabes\Service\Command\CreateDepositCommand();
$command->massAssignment($data);

$service = $app->container->get("CreateDepositService");

$I->assertEquals(0, $service->execute($command));

// EOF
