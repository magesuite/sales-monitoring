<?php

namespace MageSuite\SalesMonitoring\Service;


class Notifier implements \Magento\Framework\Event\ObserverInterface
{
    const EMAIL_TEMPLATE = 'sales_monitoring_notification';

    const EMAIL_COLOR_ALARM = '#a90720';
    const EMAIL_COLOR_OK = '#06a950';

    const SLACK_COLOR_ALARM = '#ff0000';
    const SLACK_COLOR_OK = '#00ff00';

    const SLACK_MESSAGE_ALARM = ':warning: Sales monitoring check *{{name}}* entered _ALARM_ state in project *{{project}}*!';
    const SLACK_MESSAGE_OK = ":white_check_mark: Sales monitoring check *{{name}}* is now _OK_ in project *{{project}}*.\n"
        . "Was last triggered at _{{triggered_at}}_, last order count was _{{count}}_.";

    const DATE_FORMAT = 'd.m.Y H:i:s';

    /**
     * @var \MageSuite\SalesMonitoring\Service\SlackWebhook
     */
    private $slackWebhook;

    /**
     * @var \MageSuite\SalesMonitoring\Service\Mailer
     */
    private $mailer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterfaceFactory
     */
    private $storeManagerFactory;

    /**
     * @var \MageSuite\SalesMonitoring\Model\Config
     */
    private $config;


    public function __construct(
        \MageSuite\SalesMonitoring\Model\Config $config,
        \MageSuite\SalesMonitoring\Service\SlackWebhook $slackWebhook,
        \MageSuite\SalesMonitoring\Service\Mailer $mailer,
        \Magento\Store\Model\StoreManagerInterfaceFactory $storeManagerFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->slackWebhook = $slackWebhook;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->storeManagerFactory = $storeManagerFactory;
        $this->config = $config;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \MageSuite\SalesMonitoring\Model\Check $check */
        $check = $observer->getData('check');

        foreach ($this->config->getNotificationSlackHooks() as $hookConfig) {
            try {
                $this->slackWebhook->post(
                    $hookConfig['url'],
                    strtr($check->isInAlarmState() ? self::SLACK_MESSAGE_ALARM : self::SLACK_MESSAGE_OK, $this->createSlackMessageVars($check)),
                    $hookConfig['channel'],
                    $check->isInAlarmState() ? self::SLACK_COLOR_ALARM : self::SLACK_COLOR_OK
                );
            } catch (\Exception $e) {
                $this->logger->warning('Could not send sales monitoring slack notification: ' . $e);
            }
        }

        try {
            $this->mailer->send(
                self::EMAIL_TEMPLATE,
                $this->config->getNotificationEmails(),
                $this->createEmailTemplateVars($check)
            );
        } catch (\Exception $e) {
            $this->logger->warning('Could not send sales monitoring email notification: ' . $e);
        }
    }

    protected function formatDate(\DateTime $date = null): string
    {
        if (!$date) {
            return '';
        }

        return $date->format(self::DATE_FORMAT);
    }

    private function createSlackMessageVars(
        \MageSuite\SalesMonitoring\Model\Check $check
    ): array {
        return [
            '{{project}}' => $this->config->getNotificationProjectName(),
            '{{name}}' => $check->getName(),
            '{{state}}' => $check->getState(),
            '{{count}}' => $check->getLastOrderCount(),
            '{{executed_at}}' => $this->formatDate($check->getExecutedAt()),
            '{{triggered_at}}' => $this->formatDate($check->getTriggeredAt()),
        ];
    }

    protected function createEmailTemplateVars(
        \MageSuite\SalesMonitoring\Model\Check $check
    ): array {
        $storeManager = $this->storeManagerFactory->create();

        return [
            'notification_type' => $check->isInAlarmState() ? __('ALARM') : __('OK'),
            'notification_color' => $check->isInAlarmState() ? self::EMAIL_COLOR_ALARM : self::EMAIL_COLOR_OK,
            'project_name' => $this->config->getNotificationProjectName(),
            'check_name' => $check->getName(),
            'check_state' => $check->getState(),
            'check_order_count' => $check->getLastOrderCount(),
            'check_executed_at' => $this->formatDate($check->getExecutedAt()),
            'check_triggered_at' => $this->formatDate($check->getTriggeredAt()),
            'check_criteria_hours_back' => $check->getCriteria()->getHoursBack(),
            'dashboard_link' => $storeManager->getDefaultStoreView()->getBaseUrl() . 'sales_monitoring/dashboard/index/?token=' . $this->config->getDashboardToken(),
        ];
    }
}
