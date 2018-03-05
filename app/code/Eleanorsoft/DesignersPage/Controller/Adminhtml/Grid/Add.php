<?php
namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;

class Add extends Designer
{
    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Add constructor.
     * @param Context $context
     * @param DesignerRepositoryInterface $repository
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $collectionFactory
     * @param ForwardFactory $resultForwardFactory
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    public function __construct(
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,
        CollectionFactory $collectionFactory,
        ForwardFactory $resultForwardFactory
    )
    {
        parent::__construct($context, $repository, $resultPageFactory, $collectionFactory);
        $this->resultForwardFactory = $resultForwardFactory;
    }


    /**
     * Redirect to EditController
     *
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}