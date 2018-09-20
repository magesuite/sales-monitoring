<?php

require 'orders_base.php';

$date = new \DateTime();
$date->modify("-2 hours");


$createOrder(
    $product,
    $date,
    \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::SHIPPING_METHOD_A,
    \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::PAYMENT_METHOD_A
);


$date = new \DateTime();
$date->modify("-30 minutes");

$createOrder(
    $product,
    $date,
    \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::SHIPPING_METHOD_A,
    \MageSuite\SalesMonitoring\Test\Integration\Repository\OrderRepositoryTest::PAYMENT_METHOD_A
);