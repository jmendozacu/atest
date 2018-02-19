<?php
namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;

class Add extends Designer
{
    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    public function __construct
    (
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory
    )
    {
        parent::__construct($context, $repository, $resultPageFactory);
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