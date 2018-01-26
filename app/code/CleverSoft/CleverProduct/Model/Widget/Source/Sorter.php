<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Model\Widget\Source;

class Sorter implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Varien_Data_Collection::SORT_ORDER_DESC,
                'label' => __('Newest first'),
            ),
            array(
                'value' => CleverProduct_Helper_Data::BLOG_POST_ORDER_RANDOM,
                'label' => __('Random'),
            ),
        );
    }
}