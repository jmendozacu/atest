<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Eleanorsoft\DesignersPage\Model\Uploader;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class Upload extends Designer
{
    /**
     * @var Uploader
     */
    protected $uploader;

    /**
     * Upload constructor.
     * @param Context $context
     * @param DesignerRepositoryInterface $repository
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $collectionFactory
     * @param Uploader $uploader
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    public function __construct(
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,
        CollectionFactory $collectionFactory,
        Uploader $uploader
    )
    {
        parent::__construct($context, $repository, $resultPageFactory, $collectionFactory);
        $this->uploader = $uploader;
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
        try {
            $result = $this->uploader->saveFileToTmpDir($this->getFieldName());

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * Get field with id photo
     *
     * @return mixed
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    protected function getFieldName()
    {
       return $this->_request->getParam('photo');
    }
}