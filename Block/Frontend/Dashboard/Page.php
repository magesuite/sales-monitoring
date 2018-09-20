<?php

namespace MageSuite\SalesMonitoring\Block\Frontend\Dashboard;

class Page extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MageSuite\SalesMonitoring\Repository\CheckRepository
     */
    private $checkRepository;

    /**
     * @var array
     */
    private $shippingMethods;

    /**
     * @var array
     */
    private $paymentMethods;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageSuite\SalesMonitoring\Repository\CheckRepository $checkRepository,
        \MageSuite\SalesMonitoring\DataProvider\PaymentMethodDataProvider $paymentMethodDataProvider,
        \MageSuite\SalesMonitoring\DataProvider\ShippingMethodDataProvider $shippingMethodDataProvider,
        array $data = []
    ) {
        $this->checkRepository = $checkRepository;

        $this->shippingMethods = $shippingMethodDataProvider->getAll();
        $this->paymentMethods = $paymentMethodDataProvider->getAll();

        parent::__construct($context, $data);
    }

    public function getShippingMethodName(string $code): string
    {
        if (!isset($this->shippingMethods[$code])) {
            return null;
        }

        return $this->shippingMethods[$code];
    }

    public function getPaymentMethodName(string $code): string
    {
        if (!isset($this->paymentMethods[$code])) {
            return null;
        }

        return $this->paymentMethods[$code];
    }

    public function getChecks()
    {
        return $this->checkRepository->getAll();
    }
}