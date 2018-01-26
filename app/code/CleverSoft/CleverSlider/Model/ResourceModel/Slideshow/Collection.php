<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Model\ResourceModel\Slideshow;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

	protected function _construct(){
		$this->_init('CleverSoft\CleverSlider\Model\Slideshow','CleverSoft\CleverSlider\Model\ResourceModel\Slideshow');
	}
	
	protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
		
	}
	
	
}
