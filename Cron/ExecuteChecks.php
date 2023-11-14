<?php

namespace MageSuite\SalesMonitoring\Cron;

class ExecuteChecks
{
    /**
     * @var \MageSuite\SalesMonitoring\Service\CheckExecutor
     */
    private $executor;

    public function __construct(
        \MageSuite\SalesMonitoring\Service\CheckExecutor $updater
    ) {
        $this->executor = $updater;
    }

    public function execute()
    {
        $this->executor->executeAll();
    }
}
