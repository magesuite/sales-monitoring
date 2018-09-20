<?php

namespace MageSuite\SalesMonitoring\Block\Adminhtml\Check;

class BackButton extends \Magento\Cms\Block\Adminhtml\Block\Edit\GenericButton
    implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
