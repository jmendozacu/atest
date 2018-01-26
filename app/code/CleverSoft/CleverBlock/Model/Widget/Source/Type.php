<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Model\Widget\Source;


class Type implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types = [
                    ['value' => 'list', 'label' => __('List')],
                    ['value' => 'grid', 'label' => __('Grid')],
                    ['value' => 'carousel', 'label' => __('Carousel')]
        ];

        return $types;
    }
}
