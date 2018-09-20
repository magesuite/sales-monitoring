<?php

namespace MageSuite\SalesMonitoring\Model\Config\Backend;

class StringArray extends \Magento\Config\Model\Config\Backend\Serialized
{
    public function beforeSave()
    {
        $emails = preg_split('/[\s,]+/', $this->getValue());

        $this->setValue($emails);

        parent::beforeSave();
    }

    public function afterLoad()
    {
        parent::afterLoad();

        if (is_array($this->getValue())) {
            $this->setValue(implode("\n", $this->getValue()));
        }
    }
}