<?php

namespace MageSuite\SalesMonitoring\Ui\Component\Listing\Column;

class CheckActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const EDIT_PATH = 'sales_monitoring/checks/edit';
    const DELETE_PATH = 'sales_monitoring/checks/delete';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');

                if (isset($item['id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(self::EDIT_PATH, ['id' => $item['id']]),
                        'label' => __('Edit')
                    ];

                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::DELETE_PATH, ['id' => $item['id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete check'),
                            'message' => __('Are you sure you want to delete check "${ $.$data.name }"?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
