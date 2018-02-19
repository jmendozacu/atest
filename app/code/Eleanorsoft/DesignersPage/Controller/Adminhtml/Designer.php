<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml;

use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;


/**
 * Class AbstractAction
 *
 * @package Eleanorsoft\DesignersPage\Controller\Adminhtml
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

abstract class Designer extends Action
{
    /**
     * Key for access control list (ACL)
     */
    const ACL_RESOURCE = 'Eleanorsoft_DesignersPage::el_designers';


    /**
     * @var DesignerRepositoryInterface
     */
    protected $repository;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct
    (
        Context $context,

        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);

        $this->repository = $repository;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Access control
     *
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    protected function _isAllowed()
    {
        $result =  parent::_isAllowed();
        $result = $result && $this->_authorization->isAllowed(self::ACL_RESOURCE);
        return $result;
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage, $value = 'Designers')
    {
        $resultPage->setActiveMenu('Eleanorsoft_DesignersPage::designers');
        $resultPage->getConfig()->getTitle()->prepend(__($value));
        $resultPage->addBreadcrumb(__($value), __($value));
        return $resultPage;
    }
}