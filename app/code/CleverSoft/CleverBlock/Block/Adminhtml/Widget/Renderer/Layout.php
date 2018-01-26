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

class Layout extends Template{
    public function prepareElementHtml(AbstractElement $element){
        $html = "

<script type='text/javascript'>
    require([
        'CleverSoft_CleverBlock/js/widget',
        'prototype'
    ], function(){

        document.observe('dom:loaded', function(){
            window.layout_{$this->getData("target")}_object = new MTLayoutPreview('layout_{$this->getFieldsetId()}_preview_{$this->getData("target")}', '{$this->getFieldsetId()}', '{$this->getData("target")}');
        });
        setTimeout(function(){
            window.layout_{$this->getData("target")}_object = new MTLayoutPreview('layout_{$this->getFieldsetId()}_preview_{$this->getData("target")}', '{$this->getFieldsetId()}', '{$this->getData("target")}');
        }, 100);
    });
</script>
";
        $element->setData('after_element_html', $html);
        return $element;
    }
}