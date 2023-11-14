<?php

namespace MageSuite\SalesMonitoring\Model;

class AlarmCriteria implements AlarmCriteriaInterface
{
    const DEFAULTS = [
        /* Filters */
        'hours_back' => 1,
        'payment_method' => null,
        'shipping_method' => null,
        /* Time constraints */
        'hour_from' => null,
        'hour_to' => null,
        'days_of_the_week' => [1, 2, 3, 4, 5, 6, 7],
    ];

    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = array_merge(self::DEFAULTS, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getHoursBack(): int
    {
        return $this->data['hours_back'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethod()
    {
        return $this->data['payment_method'];
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethod()
    {
        return $this->data['shipping_method'];
    }

    /**
     * {@inheritdoc}
     */
    public function getHourFrom()
    {
        return $this->data['hour_from'];
    }

    /**
     * {@inheritdoc}
     */
    public function getHourTo()
    {
        return $this->data['hour_to'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDaysOfTheWeek()
    {
        return $this->data['days_of_the_week'];
    }

    public function getHourFromFormatted(): string
    {
        if (!$this->getHourFrom()) {
            return 'XX:XX';
        }

        return str_pad($this->getHourFrom(), 2, '0', STR_PAD_LEFT) . ':00';
    }

    public function getHourToFormatted(): string
    {
        if (!$this->getHourTo()) {
            return 'XX:XX';
        }

        return str_pad($this->getHourTo(), 2, '0', STR_PAD_LEFT) . ':59';
    }
}
