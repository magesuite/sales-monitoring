<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\Sales\Model\Order\Payment;
// @codingStandardsIgnoreFile


require 'default_rollback.php';
require 'product_simple.php';

$createOrder = function($product, $createdAt, $shippingMethod = 'flatrate_flatrate', $paymentMethod = 'checkmo') {
    /** @var \Magento\Catalog\Model\Product $product */
    static $incrementId = 100000001;
    $incrementId++;

    $addressData = include __DIR__ . '/address_data.php';
    $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

    $billingAddress = $objectManager->create('Magento\Sales\Model\Order\Address', ['data' => $addressData]);
    $billingAddress->setAddressType('billing');

    /** @var Magento\Sales\Model\Order\Address $shippingAddress */
    $shippingAddress = clone $billingAddress;
    $shippingAddress->setId(null)->setAddressType('shipping');

    /** @var Payment $payment */
    $payment = $objectManager->create(Payment::class);
    $payment->setMethod($paymentMethod)
        ->setAdditionalInformation([
            'token_metadata' => [
                'token' => 'f34vjw',
                'customer_id' => 1
            ]
        ]);

    /** @var \Magento\Sales\Model\Order\Item $orderItem */
    $orderItem = $objectManager->create('Magento\Sales\Model\Order\Item');
    $orderItem->setProductId($product->getId())->setQtyOrdered(3);
    $orderItem->setBasePrice($product->getPrice());
    $orderItem->setPrice($product->getPrice());
    $orderItem->setRowTotal($product->getPrice());
    $orderItem->setProductType('simple');
    $orderItem->setSku('simple-export-test');
    $orderItem->setBasePriceInclTax(10);

    /** @var \Magento\Sales\Model\Order $order */
    $order = $objectManager->create('Magento\Sales\Model\Order');
    $order
        ->setIncrementId(
            $incrementId
        )->setCreatedAt(
            $createdAt
        )->setState(
            \Magento\Sales\Model\Order::STATE_PROCESSING
        )->setStatus(
            $order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_PROCESSING)
        )->setSubtotal(
            100
        )->setGrandTotal(
            100
        )->setBaseSubtotal(
            100
        )->setBaseGrandTotal(
            100
        )->setCustomerIsGuest(
            true
        )->setCustomerEmail(
            'customer@null.com'
        )->setBillingAddress(
            $billingAddress
        )->setShippingAddress(
            $shippingAddress
        )->setStoreId(
            $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId()
        )->addItem(
            $orderItem
        )->setPayment(
            $payment
        );

    $order->setShippingMethod($shippingMethod);
    $order->save();
};
