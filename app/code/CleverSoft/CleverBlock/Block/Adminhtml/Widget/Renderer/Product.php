<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Block\Adminhtml\Widget\Renderer;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Product extends Template implements RendererInterface{

    protected $_element;

    protected $_template = 'widget/product.phtml';


    public function render(AbstractElement $element){
        $this->setElement($element);
        return $this->toHtml();
    }

    public function setElement(AbstractElement $element){
        $this->_element = $element;
    }

    public function getRandom(){
        return $this->mathRandom;
    }

    public function getElement(){
        return $this->_element;
    }

    public function getProductsChooserUrl(){
        return $this->getUrl('mtwidget/widget/products', array('_current' => true));
    }
}