<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */


namespace CleverSoft\CleverSlider\Block\Widget;
use Magento\Framework\View\Element\Template;
use CleverSoft\CleverSlider\Model\SlideshowFactory as SlideshowFactory;

class Slideshow extends Template implements \Magento\Widget\Block\BlockInterface
{
	protected $_slideshowFactory;
	protected $_categoriesTree;
	protected $_filterProvider;
	protected $_storeManager;
	protected $_blockFactory;
	protected $_blockFilter;
	protected $_menuObject;
	protected $_menuContentArray;
	
	public function __construct(
		Template\Context $context,
		SlideshowFactory $slideshowFactory,
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory,
		\Magento\Framework\App\Http\Context $httpContext,
        array $data = []
	){
		parent::__construct($context, $data);
		$this->_slideshowFactory = $slideshowFactory;
		$this->httpContext = $httpContext;
		$this->_filterProvider = $filterProvider;
        $this->_storeManager = $context->getStoreManager();
        $this->_blockFactory = $blockFactory;
		$storeId = $this->_storeManager->getStore()->getId();
		$this->_blockFilter = $this->_filterProvider->getBlockFilter()->setStoreId($storeId);
		$this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => ['MEGAMENU']
		]);
	}
	public function getSliderObject(){
		if(!$this->_menuObject){
			$identifier = $this->getSlider();
			$slideshow = $this->_slideshowFactory->create();
			$col = $slideshow->getCollection()
				->addFieldToFilter('is_active',1)
				->addFieldToFilter('identifier',$identifier);
			if($col->count() > 0){
				$this->_menuObject = $col->getFirstItem();
			}else{
				$this->_menuObject = $col;
			}
		}
		return $this->_menuObject;
	}

    protected function _toHtml()
    {
        return $this->filter(parent::_toHtml());
    }


    public function filter($content){
        return $this->_blockFilter->filter($content);
    }


    public function getTemplate()
    {
        return 'slideshow.phtml';
    }

	public function getSliderContentArray(){
		if(!$this->_menuContentArray){
            $slider = $this->getSliderObject();
			$this->_menuContentArray = json_decode($slider->getContent());
		}
		return $this->_menuContentArray;
	}
}