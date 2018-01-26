<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Block\Adminhtml\Widget\Form\Element;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Images extends \Magento\Backend\Block\Widget implements RendererInterface{

    protected $_element;

    protected $_images;

    protected $_template = 'widget/form/element/images.phtml';


    public function getElement(){
        return $this->_element;
    }

    public function setElement(AbstractElement $element){
        return $this->_element = $element;
    }

    public function render(AbstractElement $element){
        $this->setElement($element);
        return $this->toHtml();
    }

    protected function _beforeToHtml(){
        $addBtn = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'label'     => __('Add Image'),
            'onclick'   => 'window.kenburnsImages.add()',
            'class'     => 'add'
        ]);
        $this->setChild('addBtn', $addBtn);

        $delBtn = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'onclick'   => 'window.kenburnsImages.remove({{id}})',
            'class'     => 'delete'
        ]);
        $this->setChild('delBtn', $delBtn);

        return parent::_beforeToHtml();
    }

    public function getImages(){
        if ($this->_images) return $this->_images;

        $this->_images = array();
        $values = $this->getElement()->getValue();
        if (is_array($values)){
            foreach ($values as $value){
                if ($value) $this->_images[] = $value;
            }
        }

        return $this->_images;
    }

    public function getStoreManager()
    {
        return $this->_storeManager;
    }
}
