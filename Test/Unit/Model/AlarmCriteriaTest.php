<?php

namespace MageSuite\SalesMonitoring\Test\Unit\Model;


class AlarmCriteriaTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultsAreSet()
    {
        $criteria = new \MageSuite\SalesMonitoring\Model\AlarmCriteria();

        $this->assertEquals(1, $criteria->getHoursBack());
        $this->assertEquals(null, $criteria->getPaymentMethod());
        $this->assertEquals(null, $criteria->getShippingMethod());
        $this->assertEquals(range(1, 7), $criteria->getDaysOfTheWeek());
        $this->assertEquals(null, $criteria->getHourFrom());
        $this->assertEquals(null, $criteria->getHourTo());
    }

    public function testDefaultsCanBeOverridden()
    {
        $criteria = new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
            'hours_back' => 4,
            'payment_method' => 'rain_check',
        ]);

        $this->assertEquals(4, $criteria->getHoursBack());
        $this->assertEquals('rain_check', $criteria->getPaymentMethod());
    }
}
