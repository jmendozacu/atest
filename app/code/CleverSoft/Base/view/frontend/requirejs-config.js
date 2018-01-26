/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

var config = {
    map: {
        '*': {
            equalHeight : 'CleverSoft_Base/js/equal-height',
            cleverSwatchRenderer : 'CleverSoft_Base/js/clever-product-renderer',
            cleverSwatchRendererProductDetails : 'CleverSoft_Base/js/clever-product-renderer',
            cleverInnerZoom : 'CleverSoft_Base/js/clever-product-renderer',
            stickyCustom : 'CleverSoft_Base/js/clever-product-renderer',
            jQueryLibMin : 'CleverSoft_Base/js/jquery-lib.min',
            cleverJsTheme : 'CleverSoft_Base/js/theme',
            'magnifier/magnifier' : 'CleverSoft_Base/js/magnifier'
        },
        shim:{
            "cleverInnerZoom": ["jquery"],
            "cleverJsTheme": ["jquery"]
        }
    }

};