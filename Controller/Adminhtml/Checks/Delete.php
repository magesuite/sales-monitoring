<?php

namespace MageSuite\SalesMonitoring\Controller\Adminhtml\Checks;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \MageSuite\SalesMonitoring\Repository\CheckRepository
     */
    private $checkRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\SalesMonitoring\Repository\CheckRepository $checkRepository
    ) {
        $this->checkRepository = $checkRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $check = $this->checkRepository->get(
                $this->getRequest()->getParam('id')
            );

            $name = $check->getName();

            $this->checkRepository->delete($check);
            $this->messageManager->addSuccessMessage(__('The check "%1" has been deleted.', $name));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('Could not delete the check: %1', $exception->getMessage()));
        }

        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*');

        return $redirect;
    }
}
