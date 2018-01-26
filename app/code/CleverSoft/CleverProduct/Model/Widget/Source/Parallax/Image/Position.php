<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Model\Widget\Source\Parallax\Image;

class Position  implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'center',       'label' => 'center');
        $types[] = array('value' => 'left top',     'label' => 'left top');
        $types[] = array('value' => 'left center',  'label' => 'left center');
        $types[] = array('value' => 'left bottom',  'label' => 'left bottom');
        $types[] = array('value' => 'right top',    'label' => 'right top');
        $types[] = array('value' => 'right center', 'label' => 'right center');
        $types[] = array('value' => 'right bottom', 'label' => 'right bottom');
        $types[] = array('value' => 'center top',   'label' => 'center top');
        $types[] = array('value' => 'center right', 'label' => 'center right');
        $types[] = array('value' => 'center bottom', 'label' => 'center bottom');

        return $types;
    }
}