<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_AdminInactivity
 */

namespace Mygento\AdminInactivity\Model;

use Magento\Framework\Stdlib\DateTime;
use Magento\User\Model\ResourceModel\User;

class Action
{
    private DateTime $dateTime;
    private Config $config;
    private User $resource;

    public function __construct(
        Config $config,
        User $resource,
        DateTime $dateTime
    ) {
        $this->resource = $resource;
        $this->config = $config;
        $this->dateTime = $dateTime;
    }

    public function execute(): void
    {
        $exclude = $this->config->getExcludeDomain();
        $period = $this->config->getPeriod();
        $timestamp = (string) ($this->dateTime->gmtTimestamp() - $period);
        $this->disableLogged($timestamp, $exclude);
        $this->disableNotLogged($timestamp, $exclude);
    }

    private function disableLogged(string $timestamp, ?string $exclude)
    {
        $where = ['logdate < ?' => $this->dateTime->formatDate($timestamp)];
        if ($exclude !== null) {
            $where['email not like ?'] = '%' . $exclude;
        }
        $this->resource->getConnection()->update(
            $this->resource->getMainTable(),
            ['is_active' => 0],
            $where
        );
    }

    private function disableNotLogged(string $timestamp, ?string $exclude)
    {
        $where = ['logdate is ' => null, 'created_at < ?' => $this->dateTime->formatDate($timestamp)];
        if ($exclude !== null) {
            $where['email not like ?'] = '%' . $exclude;
        }
        $this->resource->getConnection()->update(
            $this->resource->getMainTable(),
            ['is_active' => 0],
            $where
        );
    }
}
