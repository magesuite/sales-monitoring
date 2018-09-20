<?php

namespace MageSuite\SalesMonitoring\Ui\Component\Form;

class ShippingMethodOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \MageSuite\SalesMonitoring\DataProvider\ShippingMethodDataProvider
     */
    private $shippingMethodDataProvider;

    public function __construct(
        \MageSuite\SalesMonitoring\DataProvider\ShippingMethodDataProvider $shippingMethodDataProvider
    ) {
        $this->shippingMethodDataProvider = $shippingMethodDataProvider;
    }

    public function toOptionArray()
    {
        $methods = [
            ['value' => null, 'label' => 'Any']
        ];

        foreach ($this->shippingMethodDataProvider->getAll() as $code => $label) {
            $methods[] = ['label' => $label, 'value' => $code];
        }

        return $methods;
    }
}