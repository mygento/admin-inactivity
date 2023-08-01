<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_AdminInactivity
 */

namespace Mygento\AdminInactivity\Cron;

use Mygento\AdminInactivity\Model\Action;
use Mygento\AdminInactivity\Model\Config;

class DisableInactiveUsers
{
    private Config $config;
    private Action $exec;

    public function __construct(Action $exec, Config $config)
    {
        $this->exec = $exec;
        $this->config = $config;
    }

    public function execute(): void
    {
        if ($this->config->isEnabled()) {
            $this->exec->execute();
        }
    }
}
