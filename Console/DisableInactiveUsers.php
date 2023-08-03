<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_AdminInactivity
 */

namespace Mygento\AdminInactivity\Console;

use Magento\Framework\Console\Cli;
use Mygento\AdminInactivity\Model\Action;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableInactiveUsers extends Command
{
    private const COMMAND_ADMIN_ACCOUNT_DISABLE = 'admin:user:disable_inactive';
    private const COMMAND_DESCRIPTION = 'Disable all inactive admin users';

    private Action $exec;

    public function __construct(Action $exec)
    {
        parent::__construct();
        $this->exec = $exec;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->exec->execute();
        } catch (\Exception $ex) {
            $output->writeln($ex->getMessage());

            return Cli::RETURN_FAILURE;
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_ADMIN_ACCOUNT_DISABLE);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->setHelp(
            <<<HELP
This command disable all inactive users.
HELP
        );
        parent::configure();
    }
}
