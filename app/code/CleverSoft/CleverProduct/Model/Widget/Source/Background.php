<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Model\Widget\Source;

class Background implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        return array(
            array('value' => '', 'label' => 'No'),
            array('value' => 'parallax', 'label' => 'Parallax')
        );
    }
}
