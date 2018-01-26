<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Controller\Adminhtml\Index;;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Manager extends \Magento\Backend\App\Action{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute() {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('CleverSoft_CleverSlider::slideshow');
        $resultPage->addBreadcrumb(__('Slideshow'), __('Slideshow'));
        $resultPage->addBreadcrumb(__('Sliders Manager'), __('Sliders Manager'));
        $resultPage->getConfig()->getTitle()->prepend(__('Slideshow'));

        return $resultPage;
    }
}