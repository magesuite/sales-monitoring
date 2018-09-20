<?php

namespace MageSuite\SalesMonitoring\Model;

class CheckDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \MageSuite\SalesMonitoring\Repository\CheckRepository
     */
    private $checkRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\SalesMonitoring\Model\ResourceModel\Check\CollectionFactory $collectionFactory,
        \MageSuite\SalesMonitoring\Repository\CheckRepository $checkRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->request = $request;
        $this->checkRepository = $checkRepository;
    }

    public function getData()
    {
        $id = $this->request->getParam('id');

        if (!$id) {
            return [];
        }

        $check = $this->checkRepository->get($id);

        return [$check->getId() => [
            'id' => $check->getId(),
            'name' => $check->getName(),
            'hours_back' => $check->getCriteria()->getHoursBack(),
            'shipping_method' => $check->getCriteria()->getShippingMethod(),
            'payment_method' => $check->getCriteria()->getPaymentMethod(),
            'hour_from' => $check->getCriteria()->getHourFrom(),
            'hour_to' => $check->getCriteria()->getHourTo(),
            /* Values have to be strings or the items won't be selected. Tried every form XML variation to no avail. */
            'days_of_the_week' => array_map('strval', $check->getCriteria()->getDaysOfTheWeek()),
        ]];
    }
}
