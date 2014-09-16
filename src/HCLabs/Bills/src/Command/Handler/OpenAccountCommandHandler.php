<?php

namespace HCLabs\Bills\Command\Handler;

use Doctrine\Bundle\DoctrineBundle\Registry;
use HCLabs\Bills\Command\OpenAccountCommand;
use HCLabs\Bills\Model\Account;

class OpenAccountCommandHandler implements CommandHandler
{
    /** @var Registry */
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param OpenAccountCommand $command
     */
    public function handle($command)
    {
        $account = Account::open(
            $command->service,
            $command->accountNumber,
            $command->recurringCharge,
            $command->dateOpened,
            $command->billingPeriod
        );

        $manager = $this->registry->getManagerForClass(get_class($account));
        $manager->persist($account);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function supports()
    {
        return 'HCLabs\Bills\Command\OpenAccountCommand';
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 0;
    }
}