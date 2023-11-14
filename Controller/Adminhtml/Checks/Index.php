<?php

namespace MageSuite\SalesMonitoring\Controller\Adminhtml\Checks;


class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Magento_Backend::system');
        $resultPage->getConfig()->getTitle()->prepend((__('Sales Monitoring')));
        $resultPage->getConfig()->getTitle()->prepend((__('Checks')));
        $resultPage->addBreadcrumb(__('Sales Monitoring'), __('Checks'));

        return $resultPage;
    }
}
