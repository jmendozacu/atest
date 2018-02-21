<?php
namespace Eleanorsoft\DesignersPage\Controller\Index;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\IndexBase;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
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

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var DesignerRepositoryInterface
     */
    protected $designerRepository;

    public function __construct
    (
        Context $context,
        PageFactory $pageFactory,
        CollectionFactory $collectionFactory,
        DesignerRepositoryInterface $designerRepository
    )
    {
        parent::__construct($context, $pageFactory);

        $this->collectionFactory = $collectionFactory;
        $this->designerRepository = $designerRepository;
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
        try{
            $designer = $this->designerRepository->getById($id);
        }catch (NoSuchEntityException $e){
            throw new NotFoundException(__('Page not found.'));
        }
        $resultPage = $this->pageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');

        $breadcrumbs->addCrumb('Eleanorsoft_DesignersPage', [
                'label' => __($designer->getFullName()),
                'title' => __($designer->getFullName())
            ]
        );
        return $resultPage;
    }
}