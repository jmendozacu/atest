<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Model\Widget\Source\Parallax\Image;

class Repeat  implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'no-repeat',    'label' => 'no-repeat');
        $types[] = array('value' => 'repeat',       'label' => 'repeat');
        $types[] = array('value' => 'repeat-x',     'label' => 'repeat-x');
        $types[] = array('value' => 'repeat-y',     'label' => 'repeat-y');

        return $types;
    }
}