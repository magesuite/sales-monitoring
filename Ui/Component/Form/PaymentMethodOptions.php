<?php

namespace MageSuite\SalesMonitoring\Ui\Component\Form;

class PaymentMethodOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \MageSuite\SalesMonitoring\DataProvider\PaymentMethodDataProvider
     */
    private $paymentMethodDataProvider;

    public function __construct(
        \MageSuite\SalesMonitoring\DataProvider\PaymentMethodDataProvider $paymentMethodDataProvider
    ) {
        $this->paymentMethodDataProvider = $paymentMethodDataProvider;
    }

    public function toOptionArray()
    {
        $methods = [
            ['value' => null, 'label' => 'Any']
        ];

        foreach ($this->paymentMethodDataProvider->getAll() as $code => $label) {
            $methods[] = ['label' => $label, 'value' => $code];
        }

        return $methods;
    }
}