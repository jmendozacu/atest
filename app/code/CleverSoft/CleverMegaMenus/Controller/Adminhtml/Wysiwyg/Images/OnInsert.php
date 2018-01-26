<?php
/**
 * @category    CleverSoft
 * @package     CleverMegaMenus
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
 
namespace CleverSoft\CleverMegaMenus\Controller\Adminhtml\Wysiwyg\Images;

class OnInsert extends \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images {
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Controller\Result\RawFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Controller\Result\Json $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Fire when select image
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $helper = $this->_objectManager->get('CleverSoft\CleverMegaMenus\Helper\Wysiwyg\Images');
        $storeId = $this->getRequest()->getParam('store');
        $filename = $this->getRequest()->getParam('filename');
        $filename = $helper->idDecode($filename);
        $this->_objectManager->get('Magento\Catalog\Helper\Data')->setStoreId($storeId);
        $helper->setStoreId($storeId);
		$image = '{{media url="'.$helper->getImageRelativeUrl($filename).'"}}';
        /** @var \Magento\Framework\Controller\Result\Raw $resultJson */
        return $this->resultJsonFactory->setJsonData(json_encode($image));
	}
}