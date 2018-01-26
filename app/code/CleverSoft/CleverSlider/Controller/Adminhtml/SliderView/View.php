<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Controller\Adminhtml\SliderView;

class View extends \Magento\Backend\App\Action
{
	/**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
	protected $resultLayoutFactory;
	public function __construct(
        \Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
		$this->resultLayoutFactory = $resultLayoutFactory;
		$this->resultJsonFactory = $resultJsonFactory;
		parent::__construct($context);
		
    }
	
    public function execute()
    {	
		$menu = $this->getRequest()->getParams();
		
		$resultLayout = $this->resultLayoutFactory->create();
		$resultLayout->getLayout()->getBlock('slider_view')->setSlider($menu);
		return $resultLayout;
	}
}