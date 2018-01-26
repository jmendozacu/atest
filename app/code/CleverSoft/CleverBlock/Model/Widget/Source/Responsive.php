<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Model\Widget\Source;

class Responsive implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        return array(
            array('value'=>'width', 'label'=>__('By Width')),
            array('value'=>'breakpoint', 'label'=>__('By Breakpoints'))
        );
    }
}