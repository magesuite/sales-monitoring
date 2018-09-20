<?php

namespace MageSuite\SalesMonitoring\Test\Integration\Repository;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 * @magentoDataFixture loadOrders
 */
class OrderRepositoryTest extends \PHPUnit\Framework\TestCase
{
    const PAYMENT_METHOD_A = 'pma';
    const PAYMENT_METHOD_B = 'pmb';

    const SHIPPING_METHOD_A = 'cc_sma';
    const SHIPPING_METHOD_B = 'cc_smb';

    /**
     * @var \MageSuite\SalesMonitoring\Repository\OrderRepository
     */
    private $repository;


    public function setUp()
    {
        $this->repository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\MageSuite\SalesMonitoring\Repository\OrderRepository::class);
    }

    public function testCountAllLastHour()
    {
        $count = $this->repository->countOrdersForAlarmCritera(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
            'hours_back' => 1,
        ]));

        $this->assertEquals(3, $count);
    }

    public function testCountAllLastFourHours()
    {
        $count = $this->repository->countOrdersForAlarmCritera(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
            'hours_back' => 4,
        ]));

        $this->assertEquals(12, $count);
    }

    public function testCountShippingMethod()
    {
        $count = $this->repository->countOrdersForAlarmCritera(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
            'hours_back' => 4,
            'shipping_method' => static::SHIPPING_METHOD_A,
        ]));

        $this->assertEquals(8, $count);
    }

    public function testCountPaymentMethod()
    {
        $count = $this->repository->countOrdersForAlarmCritera(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
            'hours_back' => 4,
            'payment_method' => static::PAYMENT_METHOD_A,
        ]));

        $this->assertEquals(4, $count);
    }

    public function testCountShippingPaymentMethod()
    {
        $count = $this->repository->countOrdersForAlarmCritera(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
            'hours_back' => 4,
            'shipping_method' => static::SHIPPING_METHOD_A,
            'payment_method' => static::PAYMENT_METHOD_B,
        ]));

        $this->assertEquals(4, $count);
    }

    public static function loadOrders()
    {
        include __DIR__ . '/../../_files/orders_interval.php';
    }
}