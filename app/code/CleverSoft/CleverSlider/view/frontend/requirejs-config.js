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
            bgLoaderJS: 'CleverSoft_CleverSlider/js/bgLoader',
            froogaLoop: 'CleverSoft_CleverSlider/js/froogaloop2.min'
        }
    },
    shim:{
        "flexsliderJS": ["jquery"],
        "bgLoaderJS": ["jquery"],
        "froogaLoop": ["jquery"]
    }
};
