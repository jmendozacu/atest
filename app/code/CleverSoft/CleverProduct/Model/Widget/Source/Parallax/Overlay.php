<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Model\Widget\Source\Parallax;

class Overlay implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'none', 'label' => __('None'));
        $types[] = array('value' => 'mt/widget/images/gridtile.png', 'label' => __('2 x 2 Black'));
        $types[] = array('value' => 'mt/widget/images/gridtile_white.png', 'label' => __('2 x 2 White'));
        $types[] = array('value' => 'mt/widget/images/gridtile_3x3.png', 'label' => __('3 x 3 Black'));
        $types[] = array('value' => 'mt/widget/images/gridtile_3x3_white.png', 'label' => __('3 x 3 White'));

        return $types;
    }
}
