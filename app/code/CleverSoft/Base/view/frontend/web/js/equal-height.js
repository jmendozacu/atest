define([
    "jquery",
    "jquery/ui"
], function ($) {
    'use strict';
    /* set Height for element */
    $.widget('mage.equalHeight', {
        options: {
            target: '.product-item-details'
        },
        _create: function(){
            var $_e = this.element;
            if($_e.length){
                var $listItems = $_e.find(this.options.target);
                $listItems.css('height', '');
                var lenLi = $listItems.length;
                if(lenLi>1){
                    var $_maxHeight= 0;
                    for(var j=0;j<lenLi;j++){
                        $_maxHeight = Math.max($_maxHeight, $listItems.eq(j).outerHeight());
                    }
                    $listItems.css('height', $_maxHeight + 'px');
                }
            }
        }
    });
    return $.mage.equalHeight;
});


