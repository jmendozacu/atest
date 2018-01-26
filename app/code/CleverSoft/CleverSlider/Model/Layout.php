<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Model;

class Layout implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Full Width')],
            ['value' => 1, 'label' => __('Full Screen')],
            ['value' => 2, 'label' => __('Auto')]
        ];
    }
}
