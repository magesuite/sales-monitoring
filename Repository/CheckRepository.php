<?php

namespace MageSuite\SalesMonitoring\Repository;

class CheckRepository
{
    /**
     * @var \MageSuite\SalesMonitoring\Model\CheckFactory
     */
    private $factory;

    /**
     * @var \MageSuite\SalesMonitoring\Model\ResourceModel\Check
     */
    private $resourceModel;

    /**
     * @var \MageSuite\SalesMonitoring\Model\ResourceModel\Check\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        \MageSuite\SalesMonitoring\Model\CheckFactory $factory,
        \MageSuite\SalesMonitoring\Model\ResourceModel\Check $resourceModel,
        \MageSuite\SalesMonitoring\Model\ResourceModel\Check\CollectionFactory $collectionFactory
    ) {
        $this->factory = $factory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
    }

    public function create(): \MageSuite\SalesMonitoring\Model\Check
    {
        return $this->factory->create();
    }

    public function get(int $id): \MageSuite\SalesMonitoring\Model\Check
    {
        $check = $this->create();
        $this->resourceModel->load($check, $id);

        return $check;
    }

    /**
     * @param string $name
     * @return \MageSuite\SalesMonitoring\Model\Check[]
     */
    public function findByName(string $name): array
    {
        /** @var \MageSuite\SalesMonitoring\Model\ResourceModel\Check\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('name', ['eq' => $name]);

        return iterator_to_array($collection);
    }

    public function save(\MageSuite\SalesMonitoring\Model\Check $check)
    {
        $this->resourceModel->save($check);
    }

    public function delete(\MageSuite\SalesMonitoring\Model\Check $check)
    {
        $this->resourceModel->delete($check);
    }

    public function getAll()
    {
        /** @var \MageSuite\SalesMonitoring\Model\ResourceModel\Check\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');

        return $collection;
    }
}
