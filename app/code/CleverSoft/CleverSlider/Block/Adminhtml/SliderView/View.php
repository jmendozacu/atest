<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Block\Adminhtml\SliderView;
class View extends \Magento\Backend\Block\Template
{
	protected $_filterProvider;
	protected $_storeManager;
	protected $_blockFactory;
	protected $_menuOject;
	protected $_blockFilter;
	
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
		\Magento\Cms\Model\BlockFactory $blockFactory,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->_filterProvider = $filterProvider;
		$this->_storeManager = $context->getStoreManager();
		$this->_blockFactory = $blockFactory;
		$storeId = $this->_storeManager->getStore()->getId();
		$this->_blockFilter = $this->_filterProvider->getBlockFilter()->setStoreId($storeId);
	}

	public function getSliderObject(){
		if(!$this->_menuOject){
			$this->_menuOject = new \Magento\Framework\DataObject($this->getSlider());
		}
		return $this->_menuOject;
	}
	protected function _toHtml()
	{
		return $this->filter(parent::_toHtml());
	}

	
	public function filter($content){
		return $this->_blockFilter->filter($content);
	}

	public function getSliderContentArray(){
		if(!$this->_menuContentArray){
            $slider = $this->getSliderObject();
			$this->_menuContentArray = json_decode($slider->getContent());
		}
		return $this->_menuContentArray;
	}

}