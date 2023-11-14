<?php

namespace MageSuite\SalesMonitoring\Model;

class Config
{
    const DASHBOARD_TOKEN_PATH = 'sales_monitoring/dashboard/token';
    const NOTIFICATIONS_SLACK_HOOKS_PATH = 'sales_monitoring/notifications/slack_hooks';
    const NOTIFICATIONS_EMAILS_PATH = 'sales_monitoring/notifications/emails';
    const NOTIFICATIONS_PROJECT_NAME_PATH = 'sales_monitoring/notifications/project_name';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Serialize\Serializer\Json $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
    }

    public function getDashboardToken()
    {
        return $this->scopeConfig->getValue(self::DASHBOARD_TOKEN_PATH);
    }

    public function getNotificationSlackHooks(): array
    {
        $data = $this->scopeConfig->getValue(self::NOTIFICATIONS_SLACK_HOOKS_PATH);

        if (empty($data)) {
            return [];
        }

        return $this->serializer->unserialize($data);
    }

    public function getNotificationEmails(): array
    {
        $data = $this->scopeConfig->getValue(self::NOTIFICATIONS_EMAILS_PATH);

        if (empty($data)) {
            return [];
        }

        return $this->serializer->unserialize($data);
    }

    public function getNotificationProjectName(): string
    {
        return $this->scopeConfig->getValue(self::NOTIFICATIONS_PROJECT_NAME_PATH);
    }
}
