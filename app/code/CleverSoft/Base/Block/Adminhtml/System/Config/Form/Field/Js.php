<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\Base\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Js extends Field
{

    protected function _getElementHtml(AbstractElement $element)
    {

        $colorpicker = $this->getViewFileUrl('CleverSoft_Base::js/mcolorpicker/');

        $html = parent::_getElementHtml($element);
        $html .= '
                <style>
                    #row_'. $element->getHtmlId() .' { display: none;}
                </style>
                <script type="text/javascript">
                    require([
                        "CleverSoft_Base/js/mcolorpicker/mcolorpicker"
                    ],function(){
                        jQuery.fn.mColorPicker.init.replace = true;
                        jQuery.fn.mColorPicker.defaults.imageFolder = "'.$colorpicker.'/images/";
                        jQuery.fn.mColorPicker.init.allowTransparency = true;
                        jQuery.fn.mColorPicker.init.showLogo = false;
                    });
                </script>
                ';
        return $html;
    }
}
?>