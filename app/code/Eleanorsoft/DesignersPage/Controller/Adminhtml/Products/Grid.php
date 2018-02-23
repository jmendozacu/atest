<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Products;

use Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit\Tab\Product;

class Grid extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * To pass designer model into blocks
     *
     * @var Registry
     */
    protected $registry;

    protected $repository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry,
        \Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface $repository
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->registry = $registry;
        $this->repository = $repository;
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('designer_id');
        if ($id) {
            $model = $this->repository->getById($id);
            $this->registry->register('es_item', $model);
        }
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                Product::class,
                'designer.product.grid'
            )->toHtml()
        );
    }
}
