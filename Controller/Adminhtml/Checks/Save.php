<?php

namespace MageSuite\SalesMonitoring\Controller\Adminhtml\Checks;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \MageSuite\SalesMonitoring\Repository\CheckRepository
     */
    private $checkRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \MageSuite\SalesMonitoring\Repository\CheckRepository $checkRepository
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->checkRepository = $checkRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $params = $this->getRequest()->getParams();

            if ($id = $this->getRequest()->getParam('id')) {
                $check = $this->checkRepository->get($id);
            } else {
                $check = $this->checkRepository->create();
            }

            $check->setName($params['name']);
            $check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria([
                'hours_back' => intval($params['hours_back']),
                'shipping_method' => $params['shipping_method'] ? $params['shipping_method'] : null,
                'payment_method' => $params['payment_method'] ? $params['payment_method'] : null,
                'hour_from' => $params['hour_from'] ? intval($params['hour_from']) : null,
                'hour_to' => $params['hour_to'] ? intval($params['hour_to']) : null,
                'days_of_the_week' => array_map('intval', $params['days_of_the_week']),
            ]));

            $this->checkRepository->save($check);
            $this->messageManager->addSuccessMessage(__('The check has been saved'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('Could not save the check: %1', $exception->getMessage()));
        }

        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/edit', ['id' => $check->getId()]);

        return $redirect;
    }
}
