<?php

namespace MageSuite\SalesMonitoring\DataProvider;


class PaymentMethodDataProvider
{
    /**
     * @var \Magento\Payment\Helper\Data
     */
    private $data;

    public function __construct(
        \Magento\Payment\Helper\Data $data
    ) {
        $this->data = $data;
    }

    private function isMethodValid(array $data): bool
    {
        return isset($data['active']) && $data['active'];
    }

    private function getMethodLabel(array $data): string
    {
        return $data['title'];
    }

    public function getAll(): array
    {
        return array_map(
            [$this, 'getMethodLabel'],
            array_filter(
                $this->data->getPaymentMethods(),
                [$this, 'isMethodValid']
            )
        );
    }
}
