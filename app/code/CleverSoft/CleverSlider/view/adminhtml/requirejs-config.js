/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

var config = {
    map: {
        '*': {
            flexsliderJS: 'CleverSoft_CleverSlider/js/jquery.flexslider',
            slideLayout: 'CleverSoft_CleverSlider/js/slider-layout',
            jqueryTmpl: "CleverSoft_CleverSlider/js/jquery.tmpl",
            cleverJqueryUi: "CleverSoft_CleverSlider/js/jquery-ui.min",
            wysiwygEditorImg: 'CleverSoft_CleverSlider/js/wysiwyg-editor-img'
        }
    },
    shim:{
        flexsliderJS: ["jquery"],
        slideLayout: ["jqueryTmpl","cleverJqueryUi","wysiwygEditorImg","flexsliderJS"],
        jqueryTmpl: ["jquery"],
        cleverJqueryUi: ["jquery/jquery-ui"],
        wysiwygEditorImg: ["jquery"]
    }
};
