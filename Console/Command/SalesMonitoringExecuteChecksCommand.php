<?php

namespace MageSuite\SalesMonitoring\Console\Command;

class SalesMonitoringExecuteChecksCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\SalesMonitoring\Service\CheckExecutor
     */
    private $executor;

    /**
     * @var \MageSuite\SalesMonitoring\Repository\CheckRepository
     */
    private $repository;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    public function __construct(
        \MageSuite\SalesMonitoring\Service\CheckExecutor $executor,
        \MageSuite\SalesMonitoring\Repository\CheckRepository $repository,
        \Magento\Framework\App\State $state
    ) {
        parent::__construct();

        $this->executor = $executor;
        $this->repository = $repository;
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sales-monitoring:execute')
            ->setDescription('Performs check status update')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $output->writeln('Executing...');
        $this->executor->executeAll();

        $output->write("\n");

        $table = new \Symfony\Component\Console\Helper\Table($output);

        $table->setHeaders(['Name', 'State', 'Triggered At']);

        $table->setRows(array_map(function(\MageSuite\SalesMonitoring\Model\Check $check) {
            return [
                $check->getName(),
                $check->getState(),
                $check->getTriggeredAt()->format('d.m.Y H:i:s'),
            ];
        }, iterator_to_array($this->repository->getAll())));

        $table->render();
    }
}
