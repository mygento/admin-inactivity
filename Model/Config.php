<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_AdminInactivity
 */

namespace Mygento\AdminInactivity\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const XML_IS_ENABLED = 'admin/security/inactivity_cron';
    private const XML_EXCLUDE = 'admin/security/inactivity_domain';
    private const XML_PERIOD = 'admin/security/inactivity_lifetime';
    private const XML_ADMIN_SECURITY_PASSWORD_LIFETIME = 'admin/security/password_lifetime';

    private ScopeConfigInterface $config;

    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    public function isEnabled(): bool
    {
        return $this->config->isSetFlag(self::XML_IS_ENABLED);
    }

    public function getExcludeDomain(): ?string
    {
        return $this->config->getValue(self::XML_EXCLUDE)
            ? trim($this->config->getValue(self::XML_EXCLUDE)) : null;
    }

    public function getPeriod(): int
    {
        return 86400 * (
            $this->config->getValue(self::XML_PERIOD)
                ? (int) $this->config->getValue(self::XML_PERIOD) :
            (int) $this->config->getValue(self::XML_ADMIN_SECURITY_PASSWORD_LIFETIME)
        );
    }
}
