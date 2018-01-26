<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Model\Widget\Source\Parallax;

class Type implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'image', 'label' => __('Image'));
        $types[] = array('value' => 'video', 'label' => __('Video Service'));
        $types[] = array('value' => 'file', 'label' => __('Video File'));

        return $types;
    }
}
