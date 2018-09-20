<?php

namespace MageSuite\SalesMonitoring\Block\Adminhtml\System\Config;

class DashboardToken extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'system/config/dashboard_token.phtml';

    /**
     * @var string
     */
    private $htmlElementId;

    /**
     * @var \MageSuite\SalesMonitoring\Model\Config
     */
    private $config;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \MageSuite\SalesMonitoring\Model\Config $config,
        array $data = []
    ) {
        $this->config = $config;

        parent::__construct($context, $data);
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _getElementHtml(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        $this->htmlElementId = $element->getHtmlId();

        return parent::_getElementHtml($element) . $this->toHtml();
    }

    public function getHtmlElementId(): string
    {
        return $this->htmlElementId;
    }

    public function getCurrentValue(): string
    {
        return $this->config->getDashboardToken();
    }

    public function getDashboardUrl(): string
    {
        return $this->_storeManager->getDefaultStoreView()->getBaseUrl() .
            'sales_monitoring/dashboard/index/?token=' . $this->getCurrentValue();
    }
}
