<?php

namespace MageSuite\SalesMonitoring\Controller\Adminhtml\Checks;


class Edit extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->pageFactory = $pageFactory;
        $this->registry = $registry;

        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $resultPage = $this->pageFactory->create();

        $resultPage->setActiveMenu('MageSuite_SalesMonitoring::checks');
        $resultPage->addBreadcrumb(__('Sales Monitoring'), __('Checks'));
        $resultPage->getConfig()->getTitle()->prepend((__('Sales Monitoring')));

        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend((__('Edit Check')));
        } else {
            $resultPage->getConfig()->getTitle()->prepend((__('Create Check')));
        }

        return $resultPage;
    }
}
