<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;


use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Eleanorsoft\DesignersPage\Helper\Data;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Delete extends Designer
{

    /**
     *
     * @var Data
     */
    protected $helper;

    /**
     *
     * @var Action
     */
    protected $action;

    /**
     * Delete constructor.
     * @param Context $context
     * @param DesignerRepositoryInterface $repository
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $collectionFactory
     * @param Data $helper
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    public function __construct(
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,
        CollectionFactory $collectionFactory,
        Data $helper,
        Action $action
    )
    {
        parent::__construct($context, $repository, $resultPageFactory, $collectionFactory);
        $this->helper = $helper;
        $this->action = $action;
    }

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
        $collection = $this->collectionFactory->create();

        if ($id) {
            try {

                $existingIds = $this->getProductIdsForDesigner($id);
                $storeIds = $this->helper->toStoresArray();

                foreach ($storeIds as $store){
                    $this->action->updateAttributes($existingIds, ['el_designer' => NULL], $store);
                }
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