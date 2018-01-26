<?php
/**
 * @category    CleverSoft
 * @package     CleverCategory
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverCategory\Block\Adminhtml\Widget;
use \Magento\Catalog\Model\Product\Attribute\Repository;

class Categories extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_productAttributeRepository;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/categories.phtml');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_productAttributeRepository =  $objectManager->create('Magento\Catalog\Model\Product\Attribute\Repository');
    }

    public function getInforCategories(){
        $categoryIds = explode(',' , $this->getData('category_ids'));
        $options = array();
        foreach($categoryIds as $key=>$value){
            $categoryId = (int)$value;
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $object_manager = $_objectManager->create('Magento\Catalog\Model\Category')->load($categoryId);

            $options[] = array(
                'numberofproduct' => $object_manager->getProductCount() ?  $object_manager->getProductCount() : 0,
                'image' => $object_manager->getImageUrl() ?  $object_manager->getImageUrl() : '',
                'namecategory' => $object_manager->getName() ?  $object_manager->getName() : '',
                'urlcategory' => $object_manager->getUrl() ?  $object_manager->getUrl() : '',
            );
        }

        return $options;
    }
}
