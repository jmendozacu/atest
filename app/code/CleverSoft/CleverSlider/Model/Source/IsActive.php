<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */


namespace CleverSoft\CleverSlider\Model\Source;
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
	protected $slideshow;
	public function __construct(\CleverSoft\CleverSlider\Model\Slideshow $slideshow)
    {
        $this->slideshow = $slideshow;
    }
	public function toOptionArray()
	{
		$options[] = ['label' => '', 'value' => ''];
		$availableOptions = $this->slideshow->getAvailableStatuses();
		foreach ($availableOptions as $key => $value) {
			$options[] = [
				'label' => $value,
				'value' => $key,
			];
		}
		return $options;
	}
}