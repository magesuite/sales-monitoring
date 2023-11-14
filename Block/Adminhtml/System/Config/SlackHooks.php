<?php

namespace MageSuite\SalesMonitoring\Block\Adminhtml\System\Config;

class SlackHooks extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected function _prepareToRender()
    {
        $this->addColumn('url', ['label' => __('Url')]);
        $this->addColumn('channel', ['label' => __('Channel')]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
