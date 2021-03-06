<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\Base\Model\System\Config\Source\Mainmenu;

class Menuanimation implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $types = [
            ['value' => 'show', 'label' => __('Show/Hide')],
            ['value' => 'slide', 'label' => __('Slide')],
            ['value' => 'fade', 'label' => __('Fade')]
        ];

        return $types;
    }

}
