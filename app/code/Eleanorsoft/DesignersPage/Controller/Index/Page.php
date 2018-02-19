<?php
namespace Eleanorsoft\DesignersPage\Controller\Index;

use Eleanorsoft\DesignersPage\Controller\IndexBase;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;
use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer\CollectionFactory;

/**
 * Class Page
 *
 * @package Eleanorsoft\DesignersPage\Controller\Index
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Page extends IndexBase
{

    protected $collectionFactory;

    public function __construct
    (
        Context $context,
        PageFactory $pageFactory,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context, $pageFactory);

        $this->collectionFactory = $collectionFactory;
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
        $id = $this->getRequest()->getParam('id');
        $collection = $this->collectionFactory->create();

        $collection->addFieldToFilter('designer_id', array("eq" => $id));

        if (count($collection) == 0) {
            throw new NotFoundException(__('Page not found.'));
        }
        return $this->pageFactory->create();
    }
}