<?php
/**
 * @category    CleverSoft
 * @package     CleverBrands
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
 
namespace CleverSoft\CleverBrands\Block\Widget\Adminhtml;
use \Magento\Catalog\Model\Product\Attribute\Repository;

class Brands extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_productAttributeRepository;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/brands.phtml');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_productAttributeRepository =  $objectManager->create('Magento\Catalog\Model\Product\Attribute\Repository');
    }

    public function getOptionBrands(){
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $brands = explode(',' , $this->getData('brands'));
        $linkto = explode(',' , $this->getData('linkto'));
        $img = explode(',' , $this->getData('img'));

        $options = array();
        for ($i = 0; $i < count($brands); $i++) {
            $setImg =  '';
            if($img[$i]){
                if(strpos($img[$i], 'http') === 0){
                    $arrImg = explode('pub/media/',$img[$i]);
                    unset($arrImg[0]);
                    $setImg =  implode('',$arrImg);
                    $setImg = $mediaUrl.$setImg;
                }else{
                    $setImg = $mediaUrl.$img[$i];
                }
            }
            $options[] = array(
                'label' => $brands[$i],
                'image' => $setImg,
                'linkto' => $linkto[$i]
            );
        }

        return $options;
    }
}
