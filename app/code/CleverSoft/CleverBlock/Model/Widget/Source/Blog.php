<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

class CleverProduct_Model_Widget_Source_Blog
{
    public function toOptionArray()
    {
        if (Mage::helper('core')->isModuleEnabled('AW_Blog')) {
            $collection = Mage::getModel('blog/cat')->getCollection();
            $cats = array();
            foreach ($collection as $item) {
                $cats[] = array(
                    'value' => $item->getCatId(),
                    'label' => $item->getTitle()
                );
            }
            return $cats;
        }
    }
}