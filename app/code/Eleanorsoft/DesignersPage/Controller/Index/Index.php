<?php
namespace Eleanorsoft\DesignersPage\Controller\Index;

use Eleanorsoft\DesignersPage\Controller\IndexBase;
use Magento\Framework\App\ResponseInterface;

class Index extends IndexBase
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
        return $this->pageFactory->create();
    }
}