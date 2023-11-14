<?php

namespace MageSuite\SalesMonitoring\Model\ResourceModel;


class Check extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('creativestyle_sales_monitoring_checks', 'id');
    }
}
