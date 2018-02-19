<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Eleanorsoft\DesignersPage\Model\Uploader;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class Upload extends Designer
{
    /**
     * @var Uploader
     */
    protected $uploader;

    public function __construct
    (
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,

        Uploader $uploader
    )
    {
        parent::__construct($context, $repository, $resultPageFactory);

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
     * @return string
     */
    protected function getFieldName()
    {
       return $this->_request->getParam('photo');
    }
}