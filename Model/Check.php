<?php

namespace MageSuite\SalesMonitoring\Model;

/**
 * A named check configuration.
 *
 * Contains criteria and state information.
 */
class Check extends \Magento\Framework\Model\AbstractModel implements CheckInterface
{
    /* The last check evaluation did not fulfill the alarm criteria. */
    const STATE_OK = 'OK';

    /* The check criteria have been fulfilled, notification was sent and the condition has not changed. */
    const STATE_ALARM = 'ALARM';

    /**
     * @var AlarmCriteria|null
     */
    private $criteria;

    protected function _construct()
    {
        $this->_init(
            \MageSuite\SalesMonitoring\Model\ResourceModel\Check::class
        );
    }

    public function getName(): string
    {
        return $this->getData('name');
    }

    public function setName(string $name)
    {
        $this->setData('name', $name);
    }

    /**
     * @return int|null
     */
    public function getLastOrderCount()
    {
        return $this->getData('last_order_count');
    }

    public function setLastOrderCount(int $count)
    {
        $this->setData('last_order_count', $count);
    }

    /**
     * @return \DateTime|null
     */
    public function getTriggeredAt()
    {
        $data = $this->getData('triggered_at');

        if ($data instanceof \DateTime) {
            return $data;
        }

        if (!$data) {
            return null;
        }

        return \DateTime::createFromFormat('Y-m-d H:i:s', $data);
    }

    /**
     * @return \DateTime|null
     */
    public function getExecutedAt()
    {
        $data = $this->getData('executed_at');

        if ($data instanceof \DateTime) {
            return $data;
        }

        if (!$data) {
            return null;
        }

        return \DateTime::createFromFormat('Y-m-d H:i:s', $data);
    }

    public function triggerAlarm()
    {
        $this->setData('triggered_at', new \DateTime());
        $this->setData('state', self::STATE_ALARM);
    }

    public function getState(): string
    {
        return $this->getData('state');
    }

    public function markOK()
    {
        $this->setData('state', self::STATE_OK);
    }

    public function updateExecutedAt()
    {
        $this->setData('executed_at', new \DateTime());
    }

    public function isInOKState()
    {
        return $this->getState() === self::STATE_OK;
    }

    public function isInAlarmState()
    {
        return $this->getState() === self::STATE_ALARM;
    }

    public function getCriteria(): AlarmCriteria
    {
        if (!$this->criteria) {
            $this->criteria = new AlarmCriteria(json_decode($this->getData('criteria_data'), true));
        }

        return $this->criteria;
    }

    public function setCriteria(AlarmCriteria $criteria)
    {
        $this->setData('criteria_data', json_encode($criteria->getData(), JSON_PRETTY_PRINT));
        $this->criteria = null;
    }

    /**
     * Returns a string that identifies this check by combination of name and status and allows
     * simple string matching to be performed.
     *
     * @return string
     */
    public function getStatusPatternString()
    {
        return \Creativestyle\Utilities\StringHelpers::urlize($this->getName() . '-' . $this->getState());
    }
}
