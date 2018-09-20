<?php

namespace MageSuite\SalesMonitoring\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    private $config;

    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $config
    ) {
        $this->config = $config;
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $this->config->saveConfig('sales_monitoring/dashboard/token', sha1(uniqid()), 'default', 0);
        $this->config->saveConfig('sales_monitoring/notifications/slack_hooks', json_encode([
            'creativestyle' => ['url' => 'https://hooks.slack.com/services/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', 'channel' => '#m2c-notifications']
        ]), 'default', 0);

        $setup->endSetup();
    }
}