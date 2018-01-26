<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
 
namespace CleverSoft\CleverSlider\Controller\Adminhtml\Slideshow;
use CleverSoft\CleverSlider\Controller\Adminhtml\MassStatus;
class MassDisable extends MassStatus{
	const ID_FIELD = 'id';
	protected $collection = 'CleverSoft\CleverSlider\Model\ResourceModel\Slideshow\Collection';
	protected $model = 'CleverSoft\CleverSlider\Model\Slideshow';
	protected $status = false;
}