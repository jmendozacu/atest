/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 * CleverSoft iOne Theme.
 */

(function($) {
    "use strict";
/* Show-hide search. */
    $(document).ready(function() {
        $('.page-wrapper .block-search .action.search').click(function(){
            $('.page-wrapper .page-header .header-content .block.block-search .block-content .minisearch .control').toggle('slow');
            $('.page-wrapper .page-header .header-content .block.block-search').toggleClass('active');
        });
        /* Click tools setup */
        $('#tool-setup i').on('click', function() {
            $('#tool-setup').toggleClass('active');
        })
        $('#tool-setup .content-setup .content .show-hide-background-img button').on('click', function() {
            $('body').toggleClass('background-img');
        })
        $('#tool-setup .content-setup .content .show-hide-slider button').on('click', function() {
            $('.main-content .main-slide').toggleClass('none');
        })
        $('#tool-setup .content-setup .content .show-hide-box-img button').on('click', function() {
            $('.main-content .box-content').toggleClass('none');
        })
        $('#tool-setup .content-setup .content .show-hide-new-arrivals button').on('click', function() {
            $('.main-content .carousel-product .products-grid').toggleClass('none');
        })
        $('#tool-setup .content-setup .content .show-hide-instagram button').on('click', function() {
            $('.main-content .our-collections').toggleClass('none');
        })
        $(document).ready(function($){
            var slider = $.fn.fsvs({
                autoPlay            : false,
                speed               : 1000,
                bodyID              : 'fsvs-body',
                selector            : '> .slide',
                mouseSwipeDisance   : 40,
                afterSlide          : function(){},
                beforeSlide         : function(){},
                endSlide            : function(){},
                mouseWheelEvents    : true,
                mouseWheelDelay     : false,
                mouseDragEvents     : false, /* Click body scroll up. */
                touchEvents         : true,
                arrowKeyEvents      : true,
                pagination          : true,
                nthClasses          : 2,
                detectHash          : false /* Hash id url brower */
            });
        });
        $('.page-header .full-sc-search .block-content #search_mini_form .input-text').blur(function() {
            $('.page-header .full-sc-search .block-search .block-content .minisearch .field.search .control').removeClass("focus");
          })
        .focus(function() {
            $('.page-header .full-sc-search .block-search .block-content .minisearch .field.search .control').addClass("focus")
        });
    });

    /* Load page and resize */
    $(window).on('load',function() {
        var heightimg = $( window ).height() - 120;
        $('.main-slide-lookbook').height(heightimg);
        $('.main-slide-lookbook .img-left-slider').height(heightimg);
        $('.main-slide-lookbook .lookbook').height(heightimg);
        $('#fsvs-body').parent().height(heightimg);
    }).on('resize',function() {
        var heightimgr = $( window ).height() - 120;
        $('.main-slide-lookbook').height(heightimgr);
        $('.main-slide-lookbook .img-left-slider').height(heightimgr);
        $('.main-slide-lookbook .lookbook').height(heightimgr);
        $('#fsvs-body').parent().height(heightimgr);
        /* Cart right sidebar.*/
        var height = $(window).height() - 297;
        $('#minicart-content-wrapper .block-content .minicart-items-wrapper').height(height);
    });
})(jQuery);