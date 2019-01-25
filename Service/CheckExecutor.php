<?php

namespace MageSuite\SalesMonitoring\Service;


class CheckExecutor
{
    const EVENT_CHECK_OK = 'sales_monitoring_check_entered_ok_state';
    const EVENT_CHECK_ALARM = 'sales_monitoring_check_entered_alarm_state';
    const EVENT_CHECK_STATE_CHANGED = 'sales_monitoring_check_state_changed';

    /**
     * @var \MageSuite\SalesMonitoring\Repository\CheckRepository
     */
    private $checkRepository;

    /**
     * @var \MageSuite\SalesMonitoring\Repository\OrderRepository
     */
    private $orderRepository;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \MageSuite\SalesMonitoring\Repository\CheckRepository $checkRepository,
        \MageSuite\SalesMonitoring\Repository\OrderRepository $orderRepository,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->checkRepository = $checkRepository;
        $this->orderRepository = $orderRepository;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    private function shouldCheckBeProcessed(
        \MageSuite\SalesMonitoring\Model\Check $check
    ): bool {
        if ($check->isInAlarmState()) {
            /* Always re-check when in ALARM state */
            return true;
        }

        $criteria = $check->getCriteria();
        $dayOfTheWeek = (intval(date('w')) + 7) % 7;

        if (null !== $criteria->getDaysOfTheWeek() && !in_array($dayOfTheWeek, $criteria->getDaysOfTheWeek())) {
            return false;
        }

        $hour = intval(date('G'));

        if (null !== $criteria->getHourFrom() && $hour < $criteria->getHourFrom()) {
            return false;
        }

        if (null !== $criteria->getHourTo() && $hour > $criteria->getHourTo()) {
            return false;
        }

        return true;
    }

    private function triggerAlarm(\MageSuite\SalesMonitoring\Model\Check $check)
    {
        if ($check->isInAlarmState()) {
            return;
        }

        $this->logger->warning(sprintf('Check %s #%d entered ALARM state', $check->getName(), $check->getId()));

        $check->triggerAlarm();
        $this->checkRepository->save($check);
        $this->eventManager->dispatch(self::EVENT_CHECK_ALARM, ['check' => $check]);
    }


    private function triggerOK(\MageSuite\SalesMonitoring\Model\Check $check)
    {
        if ($check->isInOKState()) {
            return;
        }

        $this->logger->warning(sprintf('Check %s #%d entered OK state', $check->getName(), $check->getId()));

        $check->markOK();
        $this->checkRepository->save($check);
        $this->eventManager->dispatch(self::EVENT_CHECK_OK, ['check' => $check]);
    }

    public function execute(\MageSuite\SalesMonitoring\Model\Check $check)
    {
        $this->logger->debug(sprintf('Executing check %s #%d', $check->getName(), $check->getId()));

        $criteria = $check->getCriteria();

        if (!$this->shouldCheckBeProcessed($check)) {
            $this->logger->info(sprintf('Skipping check %s #%d because of time constraints', $check->getName(), $check->getId()));     
            return;
        }

        $previousState = $check->getState();
        $count = $this->orderRepository->countOrdersForAlarmCritera($criteria);

        $check->setLastOrderCount($count);
        $check->updateExecutedAt();

        if ($count === 0) {
            $this->triggerAlarm($check);
        } else {
            $this->triggerOK($check);
        }

        $this->checkRepository->save($check);

        if ($check->getState() !== $previousState) {
            $this->eventManager->dispatch(self::EVENT_CHECK_STATE_CHANGED, ['check' => $check]);
        }
    }

    public function executeAll()
    {
        /** @var \MageSuite\SalesMonitoring\Model\Check $check */
        foreach ($this->checkRepository->getAll() as $check) {
            $this->execute($check);
        }
    }
}