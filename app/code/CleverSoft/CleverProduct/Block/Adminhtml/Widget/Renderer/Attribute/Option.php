<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Block\Adminhtml\Widget\Renderer\Attribute;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Option extends \Magento\Backend\Block\Template implements RendererInterface{

    protected $_template = 'widget/option.phtml';

    public function render(AbstractElement $element){
        $this->setElement($element);
        return $this->toHtml();
    }

    public function setElement($element){
        $this->_element = $element;
    }

    public function getElement(){
        return $this->_element;
    }

}