<?php
namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Designer
{

    /**
     * To pass designer model into blocks
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param DesignerRepositoryInterface $repository
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $collectionFactory
     * @param Registry $registry
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    public function __construct(
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,
        CollectionFactory $collectionFactory,
        Registry $registry
    )
    {
        parent::__construct($context, $repository, $resultPageFactory, $collectionFactory);
        $this->registry = $registry;
    }

    /**
     * @var Designer
     */
    private $model;

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
            $this->registry->register('es_item', $this->model);
        }

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $value = ($id) ? 'Edit Designer' : 'New Designer';
        $this->initPage($resultPage, $value);

        return $resultPage;
    }
}