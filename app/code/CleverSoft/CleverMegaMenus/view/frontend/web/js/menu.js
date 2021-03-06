/**
 * @category    CleverSoft.
 * @package     CleverMegaMenus.
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

define([
    "jquery",
    "jquery/ui",
    'domReady'
],function($){
    var cleverMegaMenu = new function(){
        this.o = {
            direction: 'horizontal',
            animation: 'normal',
            action: '.nav-sections  [data-action="toggle-nav"], .header-content [data-action="toggle-nav"]',
            parent: '.parent',
            openClass:'open',
            verticalMenu: 'clever-vertical-menu',
            child_menu: '.clever-mega-menu-sub, .category-menu .clever-mega-menu-nondrop',
            toogleCanvas: 'slicknav_arrow',
            swipeArea:'.nav-sections',
            activeMode: false,
            mm_timeout: 100,
            mobile: 991,
            topLevelClass:'.level-top',
            horizontalClass: 'clever-horizontal-menu',
            uniqueMenu: '#clever-megamenu'
        },
            cleverMenu = this;
        cleverMenu._init = function(otps){
            $.extend(cleverMenu.o,otps);
            cleverMenu.element = $(cleverMenu.o.uniqueMenu);
            cleverMenu.bindAnimation(cleverMenu.o.animation);
            if (cleverMenu.element.hasClass(cleverMenu.o.horizontalClass)) {
                $(cleverMenu.o.swipeArea + ',' + cleverMenu.o.action).unbind();
                $(cleverMenu.o.action).on('click',function(event){
                    event.stopPropagation();
                    if ($('body').hasClass('nav-open')) {
                        $('body').removeClass('nav-open').removeClass('nav-before-open');
                    } else {
                        $('body').addClass('nav-before-open').addClass('nav-open');
                    }
                })
            }
            $(cleverMenu.element).find('.no-dropdown').each(function () {
                $(this).children('.clever-mega-menu-sub').removeClass('clever-mega-menu-sub').addClass('groupmenu-nondrop');
            });
            cleverMenu.changesOnResize(cleverMenu.getScreenMode(),cleverMenu.o);
            cleverMenu.addResizeEvent();
        },
            cleverMenu.bindAnimation = function (type) {
                $(cleverMenu.element).find(cleverMenu.o.topLevelClass).each(function () {
                    var $this = $(this);
                    var $child_menu = $this.children('.clever-mega-menu-sub');
                    //cleverMenu.bindMouseOverEvent($child_menu,$this,type);
                    //cleverMenu.bindMouseOutEvent($child_menu,$this,type);
                });
            },
            cleverMenu.bindMouseOverEvent = function($child_menu,$this,type){
                $this.on('mouseover', function () {
                    if (cleverMenu.getScreenMode() == 'desktop') {
                        if (type == 'fade') {
                            $child_menu.fadeIn(cleverMenu.o.mm_timeout, function () {
                                $this.addClass('open');
                            });
                        } else if (type == 'slide') {
                            $child_menu.slideDown(cleverMenu.o.mm_timeout, function () {
                                $this.addClass('open');
                            });
                        } else if (type == 'normal') {
                            $child_menu.show();
                            $this.addClass('open');
                        }
                    }
                });
            },
            cleverMenu.bindMouseOutEvent = function($child_menu,$this,type){
                $this.on('mouseleave', function () {
                    if (cleverMenu.getScreenMode() == 'desktop') {
                        if (type == 'fade') {
                            $child_menu.fadeOut(cleverMenu.o.mm_timeout, function () {
                                $this.removeClass('open');
                            });
                        } else if (type == 'slide') {
                            $child_menu.slideUp(cleverMenu.o.mm_timeout, function () {
                                $this.removeClass('open');
                            });
                        } else if (type == 'normal') {
                            $this.removeClass('open');
                        }
                    }
                });
            },
            cleverMenu.changesOnResize = function(screen,options) {
                var $menu = cleverMenu.element;
                if (screen == 'desktop') {
                    $($menu).find(options.child_menu).css('display', '').removeClass('open');
                    $($menu).find(options.parent).removeClass('open');
                } else {
                    $($menu).find(options.child_menu).hide();
                    var $a = $($menu).find(options.parent);
                    $($menu).find(options.parent).each(function () {
                        var $parent = $(this);
                        if($parent.children('.'+options.toogleCanvas).length == 0) {
                            $parent.children(options.child_menu).each(function () {
                                var $this = $(this);
                                $('<i class="' + options.toogleCanvas + '" />').insertBefore($this).on('click', function (event) {
                                    event.stopPropagation();
                                    $parent.toggleClass('open');
                                    $this.toggleClass('open').slideToggle();
                                });
                            });
                        }
                    });
                }
            },
            cleverMenu.getScreenMode = function () {
                return $(window).width() < cleverMenu.o.mobile ? 'mobile' : 'desktop';
            },
            cleverMenu.addResizeEvent = function () {
                $(window).on('resize', function () {
                    cleverMenu.changesOnResize(cleverMenu.getScreenMode(),cleverMenu.o);
                });
            }

    }
    window.cleverMegaMenu = cleverMegaMenu;
});