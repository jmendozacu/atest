<?php
namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;

class Edit extends Designer
{

    /**
     * @var Designer
     */
    private $model;

    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('designer_id');

        // 2. Initial checking
        if ($id) {
            $this->model = $this->repository->getById($id);
            if (!$this->model->getId()) {
                $this->messageManager->addErrorMessage(__('This designer no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $value = ($id) ? 'Edit Designer' : 'New Designer';
        $this->initPage($resultPage, $value);

        return $resultPage;
    }
}