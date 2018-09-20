<?php

namespace MageSuite\SalesMonitoring\Model\ResourceModel\Check;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            \MageSuite\SalesMonitoring\Model\Check::class,
            \MageSuite\SalesMonitoring\Model\ResourceModel\Check::class);
    }
}