<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
class Delete extends \Magento\Backend\App\Action{


	public function execute(){
		$id = $this->getRequest()->getParam('id');
		$resultRedirect = $this->resultRedirectFactory->create();
		if($id){
			try {	
				$model = $this->_objectManager->create('CleverSoft\CleverSlider\Model\Slideshow');
				$model->load($id);
				$model->delete();
				$this->messageManager->addSuccess(__('The menu has been deleted.'));
				return $resultRedirect->setPath('*/*/manager');
			}catch(\Exception $e){
				$this->messageManager->addError($e->getMessage());
				return $resultRedirect->setPath('*/*/new', ['id' => $id]);
			}
		}
		$this->messageManager->addError(__('We can\'t find a menu to delete.'));
		return $resultRedirect->setPath('*/*/manager');
	}
}