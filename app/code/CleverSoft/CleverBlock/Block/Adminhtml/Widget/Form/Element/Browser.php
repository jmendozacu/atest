<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Block\Adminhtml\Widget\Form\Element;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
class Browser extends \Magento\Backend\Block\Widget implements RendererInterface{
    protected $_element;
    protected $_template = 'widget/form/element/browser.phtml';
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
        $browserBtn = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'label'     => '...',
            'title'     => __('Click to browser media'),
            'type'      => 'button',
            'onclick'   => sprintf('MediabrowserUtility.openDialog(\'%s\',\'%s\',\'%s\')',
                $this->getUrl('cms/wysiwyg_images/index', [
                    'static_urls_allowed'   => 1,
                    'target_element_id'     => $this->getElement()->getHtmlId(),
                    'type'     => 'image'
                ]),'false','false'
            )
        ]);
        $this->setChild('browserBtn', $browserBtn);
        $clearBtn = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'label'     => 'x',
            'title'     => __('Click to clear value'),
            'type'      => 'button',
            'onclick'   => "on_{$this->getElement()->getHtmlId()}_clear_click();"
        ]);
        $this->setChild('clearBtn', $clearBtn);
        return parent::_beforeToHtml();
    }
}