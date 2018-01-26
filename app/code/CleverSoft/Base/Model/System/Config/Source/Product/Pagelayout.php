<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\Base\Model\System\Config\Source\Product;

class Pagelayout implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'horizontal_thumb', 'label' => __('Horizontal Thumb')],
            ['value' => 'vertical_thumb', 'label' => __('Vertical Thumb')],
            ['value' => 'center_vertical_thumb', 'label' => __('Center Vertical Thumb')],
            ['value' => 'sticky_image', 'label' => __('Sticky Image')],
            ['value' => 'sticky_right_content', 'label' => __('Sticky Right Content')],
            ['value' => 'sticky_image_center', 'label' => __('Sticky Image Center')],
            ['value' => 'sticky_accordion', 'label' => __('Sticky Accordion')],
            ['value' => 'images_carousel', 'label' => __('Images Carousel')]
        ];
    }

    public function toArray()
    {
        return [
            'default' => __('Default'),
            'sticky_2' => __('Sticky 2')
        ];
    }
}
