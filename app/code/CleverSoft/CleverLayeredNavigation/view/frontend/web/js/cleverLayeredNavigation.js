/**
 * @category    CleverSoft
 * @package     CleverLayeredNavigation
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
define([
    "jquery",
    "jquery/ui",
    "mage/tooltip"
], function ($) {
    'use strict';

    $.widget('mage.cleverLayeredNavigationFilterAbstract',{
        options: {
            isAjax: 0
        },
        apply: function(link){
            window.location = link;
        },
        hideSideBar : function ($item) {
            if($item.closest('#sidebar-without').length > 0 ) {
                $('body').toggleClass('show-sidebar');
                $('#sidebar-without').removeAttr('style');
            }
        }
    });

    $.widget('mage.cleverLayeredNavigationFilterItemDefault', $.mage.cleverLayeredNavigationFilterAbstract, {
        options: {
        },
        _create: function () {
            var self = this;
            $(function(){

                var link = self.element;
                var parent = link.parents('.item');
                var checkbox = parent.find('input[type=checkbox]');

                var params = {
                    parent:parent,
                    checkbox:checkbox,
                    link:link
                };

                checkbox.bind('click',params,function(e){
                    self.hideSideBar($(this));
                    var link = e.data.link;
                    self.apply(link.prop('href'));
                    e.stopPropagation();
                });

                link.bind('click',params,function(e){
                    self.hideSideBar($(this));
                    $(this).prop('checked',true);
                    $(this).children('input[type=checkbox]').prop('checked',true);
                    self.apply(e.data.link.prop('href'));
                    e.stopPropagation();
                    e.preventDefault();
                });

                parent.bind('click',params,function(e){
                    self.hideSideBar($(this));
                    $(this).prop('checked',true);
                    $(this).children('input[type=checkbox]').prop('checked',true);
                    var link = e.data.link;
                    self.apply(link.prop('href'));
                    e.stopPropagation();
                });
            })
        }
    });

    $.widget('mage.cleverLayeredNavigationFilterDropdown', $.mage.cleverLayeredNavigationFilterAbstract, {
        options: {
        },
        _create: function () {
            var self = this;
            $(function(){
                var $select = $(self.element[0]);
                $select.change(function() {
                    self.hideSideBar($(this));
                    self.apply($select.val());
                });
            })
        }
    });

    $.widget('mage.cleverLayeredNavigationFilterSlider', $.mage.cleverLayeredNavigationFilterAbstract, {
        options: {
        },
        _create: function () {
            var self = this;
            $(function(){
                var elementID = self.element[0].id;
                //$( "#" + elementID + "_display" ).html( " <span class='price_min'> " + self.renderLabel(self.options.from)+ " </span><span class='price_max'> " + self.renderLabel(self.options.to) + " </span> ");
                var $slider = $(self.options.sliderClass,$("#" + elementID));
                $slider.slider({
                    step: parseFloat(self.options.step),
                    range: true,
                    min: parseFloat(self.options.min),
                    max: parseFloat(self.options.max),
                    values: [ parseFloat(self.options.from), parseFloat(self.options.to)],
                    create:function(event, ui){
                        $(self.options.minClass,$("#" + elementID)).html(self.options.currency_symbol + self.options.from);
                        $(self.options.maxClass,$("#" + elementID)).html(self.options.currency_symbol + self.options.to);
                    },
                    slide: function( event, ui ) {
                        var handleIndex = $(ui.handle).index();
                        var label = handleIndex == 1 ? self.options.minClass : self.options.maxClass;
                        $(label,$("#" + elementID)).html(self.options.currency_symbol + ui.value);

                    },
                    change: function( event, ui ) {
                        var linkHref = self.options.url.replace('clevershopby_slider_from', ui.values[ 0 ]).replace('clevershopby_slider_to', ui.values[1]);
                        self.apply(linkHref);
                        self.hideSideBar($(this));
                    }
                });
            });
        },
        renderLabel:function(value) {
            return this.options.template.replace('{amount}', value);
        }
    });

    $.widget('mage.cleverLayeredNavigationFilterFromToOnly', $.mage.cleverLayeredNavigationFilterAbstract, {
        options: {
        },
        _create: function () {
            var self = this;
            $(function(){
                var elementID = self.element[0].id;
                var $from = $("#" + elementID + "-from");
                var $to = $("#" + elementID + "-to");
                var $go = $("#" + elementID + "-go");
                $go.on('click',function(e){
                    self.hideSideBar($(this));
                    var linkHref = self.options.url.replace('clevershopby_slider_from', $from.val()).replace('clevershopby_slider_to', $to.val());
                    self.apply(linkHref);
                    e.stopPropagation();
                    e.preventDefault();
                });
            });
        },
        renderLabel:function(value) {
            return this.options.template.replace('{amount}', value);
        }
    });

    $.widget('mage.cleverLayeredNavigationFilterSearch',{
        options: {
            highlightTemplate: "",
            itemsSelector: ""
        },

        previousSearch: '',

        _create: function () {
            var self = this;
            var $items = $(this.options.itemsSelector + " .item");
            $(self.element).keyup(function(){
                self.search(this.value, $items);
            });
        },

        search: function(searchText, $items) {
            var self = this;

            searchText = searchText.toLowerCase();
            if (searchText == this.previousSearch) {
                return;
            }
            this.previousSearch = searchText;

            if (searchText != '') {
                $(this.element).trigger('search_active');
            }

            $items.each(function(key, li) {
                if (li.hasAttribute('data-label')) {
                    var val = li.getAttribute('data-label').toLowerCase();
                    if (!val || val.indexOf(searchText) > -1) {
                        if (searchText != '' && val.indexOf(searchText) > -1) {
                            self.hightlight(li, searchText);
                        } else {
                            self.unhightlight(li);
                        }
                        $(li).show();
                    }
                    else {
                        self.unhightlight(li);
                        $(li).hide();
                    }
                }
            });

            if (searchText == '') {
                $(this.element).trigger('search_inactive');
            }
        },
        hightlight: function (element, searchText) {
            this.unhightlight(element);
            var $a = $(element).find('a');
            var label = $(element).attr('data-label');
            var newLabel = label.replace(new RegExp(searchText,'gi'), this.options.highlightTemplate);
            $a.find('.label').html(newLabel);
        },
        unhightlight: function(element) {
            var $a = $(element).find('a');
            var label = $(element).attr('data-label');
            $a.find('.label').html(label);
        }
    });

    $.widget('mage.cleverLayeredNavigationFilterHideMoreOptions',{
        options: {
            numberUnfoldedOptions: 0,
            _hideCurrent: false,
            buttonSelector: ""
        },
        _create: function () {
            var self = this;

            if ($(this.element).find(".item").length <= this.options.numberUnfoldedOptions) {
                $(this.options.buttonSelector).parent().hide();
                return;
            }

            $(this.element).parents('.filter-options-content').on('search_active', function() {
                if (self.options._hideCurrent) {
                    self.toggle(self.options.buttonSelector);
                }
                $(self.options.buttonSelector).parent().hide();
            });

            $(this.element).parents('.filter-options-content').on('search_inactive', function() {
                if (!self.options._hideCurrent) {
                    self.toggle(self.options.buttonSelector);
                }
                $(self.options.buttonSelector).parent().show();
            });

            $(this.options.buttonSelector).click(function(){
                self.toggle(this);
                return false;
            });
            $(this.options.buttonSelector).parent().click(function(){
                self.toggle(self.options.buttonSelector);
            });

            // for hide in first load
            $(this.options.buttonSelector).click();
        },

        toggle: function(button){
            var $button = $(button);
            if(this.options._hideCurrent) {
                this.showAll();
                $button.html($button.attr('data-text-less'));
                $button.attr('data-is-hide', 'false');
                this.options._hideCurrent = false;
            } else {
                this.hideAll();
                $button.html($button.attr('data-text-more'));
                $button.attr('data-is-hide', 'true');
                this.options._hideCurrent = true;
            }
        },

        hideAll: function () {
            var self = this;
            var count = 0;
            $(this.element).find(".item").each(function(){
                count++;
                if(count > self.options.numberUnfoldedOptions) {
                    $(this).hide();
                }
            });
        },
        showAll: function () {
            $(this.element).find(".item").show();
        },
    });

    $.widget('mage.cleverLayeredNavigationFilterAddTooltip',{
        options: {
            content: "",
            tooltipTemplate: ""
        },
        _create: function () {
            var template = this.options.tooltipTemplate.replace('{content}', this.options.content);
            var $template = $(template);

            var $place =  $(this.element).parents('.zoo-filter-options-item').find('.zoo-filter-options-title');
            if($place.length == 0) {
                $place = $(this.element).parents('dd').prev('dt');
            }
            if($place.length > 0) {
                $place.append($template);
            }

            $template.tooltip({
                position: {
                    my: "left bottom-10",
                    at: "left top",
                    collision: "flipfit flip",
                    using: function( position, feedback ) {
                        $( this ).css( position );
                        $( "<div>" )
                            .addClass( "arrow" )
                            .addClass( feedback.vertical )
                            .addClass( feedback.horizontal )
                            .appendTo( this );
                    }
                }
            });
        }
    });
});
