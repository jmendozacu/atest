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
class Save extends \Magento\Backend\App\Action {

    public function __construct(Action\Context $context){
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Ashsmith\Blog\Model\Post $model */
            $model = $this->_objectManager->create('CleverSoft\CleverSlider\Model\Slideshow');
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                $model->load($id);
            }
            $styles = ['css_class'];
            $data['style'] = [];

            foreach($styles as $style){
                if(isset($data[$style])){
                    $data['style'][$style] = $data[$style];
                }
            }
            $data['style'] = json_encode($data['style']);
            $model->setData($data);
            if($this->getRequest()->getParam('duplicate')){
                $model->setId(null);
                $model->setData('identifier','copy-of-'.$data['identifier'].'-'.rand());
                $model->setData('title','Copy of '.$data['title']);
            }


            $this->_eventManager->dispatch(
                'slideshow_prepare_save',
                ['menu' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                if($this->getRequest()->getParam('duplicate')){
                    $this->messageManager->addSuccess(__('You duplicate this slide successfully.'));
                }else{
                    $this->messageManager->addSuccess(__('You saved this slide.'));
                }
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/new', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/manager');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, $e->getMessage());
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/new', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/manager');
    }
}