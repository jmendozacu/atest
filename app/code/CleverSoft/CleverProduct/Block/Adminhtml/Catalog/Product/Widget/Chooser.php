<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Block\Adminhtml\Catalog\Product\Widget;
class Chooser extends \Magento\Catalog\Block\Adminhtml\Product\Widget\Chooser{
    public function getGridUrl(){
        return $this->getUrl('cleverproduct/widget/products', array(
            'products_grid' => true,
            '_current' => true,
            'uniq_id' => $this->getId(),
            'use_massaction' => $this->getUseMassaction()
        ));
    }

    public function getCheckboxCheckCallback(){
        if ($this->getUseMassaction()) {
            return "function (grid, element) {
                $(grid.containerId).fire('productNode:changed', {element: element});
            }";
        }
    }
}