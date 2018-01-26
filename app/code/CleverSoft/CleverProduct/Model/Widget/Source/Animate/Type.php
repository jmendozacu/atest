<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Model\Widget\Source\Parallax\Animate;

class Type  implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        return array(
            array('value' => 'effect-zoomOut', 'label' => __('Zoom Out')),
            array('value' => 'effect-zoomIn', 'label' => __('Zoom In')),
            //array('value' => 'effect-slideBottom', 'label' => __('Slide Bottom')),
            array('value' => 'effect-bounceIn', 'label' => __('Bounce In')),
            array('value' => 'effect-bounceInRight', 'label' => __('Bounce In Right')),
            //array('value' => 'effect-bounceInUp', 'label' => __('Bounce In Up')),
            //array('value' => 'effect-bounceInDown', 'label' => __('Bounce In Down')),
            array('value' => 'effect-pageTop', 'label' => __('Page Top')),
            //array('value' => 'effect-pageTopBack', 'label' => __('Page Top Back')),
            array('value' => 'effect-pageBottom', 'label' => __('Page Bottom')),
            array('value' => 'effect-starwars', 'label' => __('Star Wars')),
            array('value' => 'effect-pageLeft', 'label' => __('Page Left')),
            array('value' => 'effect-pageRight', 'label' => __('Page Right'))
        );
    }
}
