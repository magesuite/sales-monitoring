<?php

namespace MageSuite\SalesMonitoring\Repository;

class OrderRepository
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface
     */
    protected $orderCollectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface $collectionFactory
    ) {
        $this->orderCollectionFactory = $collectionFactory;
    }

    public function countOrdersForAlarmCritera(
        \MageSuite\SalesMonitoring\Model\AlarmCriteriaInterface $criteria
    ) {
        $collection = $this->orderCollectionFactory->create();

        $since = new \DateTime("-{$criteria->getHoursBack()} hours");

        $collection->addFieldToFilter('created_at', ['gteq' => $since]);

        if ($criteria->getShippingMethod()) {
            $collection->addFieldToFilter('shipping_method', ['eq' => $criteria->getShippingMethod()]);
        }

        if ($criteria->getPaymentMethod()) {
            $collection->getSelect()
                ->join(['sop' => 'sales_order_payment'], 'main_table.entity_id = sop.parent_id', array('method'))
                ->where('sop.method = ?', $criteria->getPaymentMethod())
            ;
        }


        return $collection->getSize();
    }
}