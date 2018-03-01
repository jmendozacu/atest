<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;


use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Magento\Framework\App\ResponseInterface;

class Delete extends Designer
{

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('designer_id');

        if ($id) {
            try {
                // init model and delete
                $model = $this->repository->getById($id);
                $this->repository->delete($model);
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the designer.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['designer_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find the image to delete.'));
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}