<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
 
namespace CleverSoft\Base\Model\System\Config\Source\Category;

class Altimagecolumn implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $types = [
            ['value' => 'label', 'label' => __('Label')],
            ['value' => 'position', 'label' => __('Sort Order')],
        ];

        return $types;
    }

}