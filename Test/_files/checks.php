<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();


/** @var \MageSuite\SalesMonitoring\Model\Check $check */
$check = $objectManager->create(\MageSuite\SalesMonitoring\Model\Check::class);
$check->setName('All 3h');
$check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
    'hours_back' => 3,
    'payment_method' => null,
    'shipping_method' => null,
    'hour_from' => null,
    'hour_to' => null,
    'days_of_the_week' => [1, 2, 3, 4, 5, 6, 7],
]));
$check->save();

$check = $objectManager->create(\MageSuite\SalesMonitoring\Model\Check::class);
$check->setName('Shipping Method No Orders');
$check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
    'hours_back' => 1,
    'payment_method' => null,
    'shipping_method' => \MageSuite\SalesMonitoring\Test\Integration\Service\CheckExecutorTest::SHIPPING_METHOD_B,
    'hour_from' => null,
    'hour_to' => null,
    'days_of_the_week' => [1, 2, 3, 4, 5, 6, 7],
]));
$check->save();

$check = $objectManager->create(\MageSuite\SalesMonitoring\Model\Check::class);
$check->setName('Payment Method No Orders');
$check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
    'hours_back' => 1,
    'payment_method' => \MageSuite\SalesMonitoring\Test\Integration\Service\CheckExecutorTest::PAYMENT_METHOD_B,
    'shipping_method' => null,
    'hour_from' => null,
    'hour_to' => null,
    'days_of_the_week' => [1, 2, 3, 4, 5, 6, 7],
]));
$check->save();

$check = $objectManager->create(\MageSuite\SalesMonitoring\Model\Check::class);
$check->setName('Shipping and payment');
$check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
    'hours_back' => 1,
    'payment_method' => \MageSuite\SalesMonitoring\Test\Integration\Service\CheckExecutorTest::PAYMENT_METHOD_A,
    'shipping_method' => \MageSuite\SalesMonitoring\Test\Integration\Service\CheckExecutorTest::SHIPPING_METHOD_A,
    'hour_from' => null,
    'hour_to' => null,
    'days_of_the_week' => [1, 2, 3, 4, 5, 6, 7],
]));
$check->save();

$currentHour = (int)$date->format('G');

$check = $objectManager->create(\MageSuite\SalesMonitoring\Model\Check::class);
$check->setName('Outside hours');
$check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
    'hour_from' => ($currentHour + 1) % 24,
    'hour_to' => (($currentHour - 1) + 24) % 24,
    'days_of_the_week' => [1, 2, 3, 4, 5, 6, 7],
]));
$check->save();


$currentDay = ((int)(date('w')) + 7) % 7;

$check = $objectManager->create(\MageSuite\SalesMonitoring\Model\Check::class);
$check->setName('Outside days');
$check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
    'days_of_the_week' => array_filter(range(1, 7), function(int $x) use ($currentDay) { return $x !== $currentDay; }),
]));
$check->save();
