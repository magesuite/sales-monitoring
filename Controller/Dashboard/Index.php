<?php

namespace MageSuite\SalesMonitoring\Controller\Dashboard;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \MageSuite\SalesMonitoring\Model\Config
     */
    private $config;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\SalesMonitoring\Model\Config $config
    ) {
        $this->pageFactory = $pageFactory;
        $this->config = $config;

        return parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->getParam('token') !==
            $this->config->getDashboardToken()) {
            /* Throw 404 on purpose for more obfuscation :) */
            throw new \Magento\Framework\Exception\NotFoundException(__('Invalid or missing dashboard token'));
        }

        return $this->pageFactory->create();
    }
}