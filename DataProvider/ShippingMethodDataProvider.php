<?php

namespace MageSuite\SalesMonitoring\DataProvider;


class ShippingMethodDataProvider
{
    /**
     * @var \Magento\Shipping\Model\Config
     */
    private $shippingConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    public function getAll(): array
    {
        $methods = [];

        /** @var \Magento\Shipping\Model\Carrier\CarrierInterface|\Magento\Shipping\Model\Carrier\AbstractCarrier $carrierModel */
        foreach ($this->shippingConfig->getAllCarriers() as $carrierCode => $carrierModel) {
            if (!$carrierModel->isActive()) {
                continue;
            }

            if (!$carrierModel->getAllowedMethods()) {
                continue;
            }

            $carrierName = $this->scopeConfig->getValue(
                "carriers/${carrierCode}/title",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            foreach ($carrierModel->getAllowedMethods() as $methodCode => $methodTitle) {
                $code = $carrierCode . '_' . $methodCode;

                $methods[$code] = sprintf('[%s] %s', $carrierName, $methodTitle);
            }
        }

        return $methods;
    }
}
