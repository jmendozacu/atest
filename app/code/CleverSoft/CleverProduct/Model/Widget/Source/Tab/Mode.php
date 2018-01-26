<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
 
namespace CleverSoft\CleverProduct\Model\Widget\Source\Tab;
class Mode implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types = [
            ['value' => 'related', 'label' => __('Related Products')],
            ['value' => 'upsell', 'label' => __('Up-sell Products')],
            ['value' => 'crosssell', 'label' => __('Cross-sell Products')],
            ['value' => 'latest', 'label' => __('Latest Products')],
            ['value' => 'new', 'label' => __('New Products')],
            ['value' => 'bestsell', 'label' => __('Best Sell Products')],
            ['value' => 'mostviewed', 'label' => __('Most Viewed Products')],
            ['value' => 'random', 'label' => __('Random Products')],
            ['value' => 'discount', 'label' => __('Discount Products')],
            ['value' => 'rating', 'label' => __('Top Rated Products')],
        ];
        return $types;
    }
}