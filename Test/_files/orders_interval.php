<?php

require 'orders_base.php';

$hoursBack = 24;
$date = new \DateTime();
$date->modify("-{$hoursBack}hours");

for ($i = 0; $i < $hoursBack; ++$i) {
    $orderDate = clone $date;

    $orderDate->modify('+5min');
    $createOrder(
        $product,
        $orderDate,
        \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::SHIPPING_METHOD_A,
        \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::PAYMENT_METHOD_A
    );

    $orderDate->modify('+5min');
    $createOrder(
        $product,
        $orderDate,
        \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::SHIPPING_METHOD_B,
        \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::PAYMENT_METHOD_B
    );


    $orderDate->modify('+5min');
    $createOrder(
        $product,
        $orderDate,
        \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::SHIPPING_METHOD_A,
        \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::PAYMENT_METHOD_B
    );


    $date->modify("+ 1 hour");
}