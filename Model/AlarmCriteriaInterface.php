<?php

namespace MageSuite\SalesMonitoring\Model;


interface AlarmCriteriaInterface
{
    public function getData(): array;

    public function getHoursBack(): int;

    /**
     * @return string|null
     */
    public function getPaymentMethod();

    /**
     * @return string|null
     */
    public function getShippingMethod();

    /**
     * @return int|null
     */
    public function getHourFrom();

    /**
     * @return int|null
     */
    public function getHourTo();

    /**
     * @return int[]|null
     */
    public function getDaysOfTheWeek();
}
