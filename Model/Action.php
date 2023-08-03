<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_AdminInactivity
 */

namespace Mygento\AdminInactivity\Model;

use Magento\Framework\Stdlib\DateTime;
use Magento\User\Model\ResourceModel\User;
use Zend_Db_Expr;

class Action
{
    private DateTime $dateTime;
    private DateTime\DateTime $time;
    private Config $config;
    private User $resource;

    public function __construct(
        Config $config,
        User $resource,
        DateTime $dateTime,
        DateTime\DateTime $time
    ) {
        $this->resource = $resource;
        $this->config = $config;
        $this->dateTime = $dateTime;
        $this->time = $time;
    }

    public function execute(): void
    {
        $exclude = $this->config->getExcludeDomain();
        $period = $this->config->getPeriod();
        $timestamp = (string) ($this->time->gmtTimestamp() - $period);
        $this->disableLogged($timestamp, $exclude);
        $this->disableNotLogged($timestamp, $exclude);
    }

    private function disableLogged(string $timestamp, ?string $exclude): void
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

    private function disableNotLogged(string $timestamp, ?string $exclude): void
    {
        $where = [
            'logdate is ?' => new Zend_Db_Expr('null'),
            'created < ?' => $this->dateTime->formatDate($timestamp),
        ];
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
