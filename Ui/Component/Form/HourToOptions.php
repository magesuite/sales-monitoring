<?php

namespace MageSuite\SalesMonitoring\Ui\Component\Form;

class HourToOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        $items = [['value' => null, 'label' => 'Any']];

        for ($hour = 0; $hour < 24; ++$hour) {
            $items[] = ['value' => $hour, 'label' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':59'];
        }

        return $items;
    }
}
