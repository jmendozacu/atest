define([
    "jquery",
    "domReady"
],(function($){
    $.extend( $.ui, {
        plugin: {
            add: function( module, option, set ) {
                var i,
                    proto = $.ui[ module ].prototype;
                for ( i in set ) {
                    proto.plugins[ i ] = proto.plugins[ i ] || [];
                    proto.plugins[ i ].push( [ option, set[ i ] ] );
                }
            },
            call: function( instance, name, args ) {
                var i,
                    set = instance.plugins[ name ];
                if ( !set || !instance.element[ 0 ].parentNode || instance.element[ 0 ].parentNode.nodeType === 11 ) {
                    return;
                }
                for ( i = 0; i < set.length; i++ ) {
                    if ( instance.options[ set[ i ][ 0 ] ] ) {
                        set[ i ][ 1 ].apply( instance.element, args );
                    }
                }
            }
        },
        contains: $.contains,
        hasScroll: function( el, a ) {
            if ( $( el ).css( "overflow" ) === "hidden") {
                return false;
            }
            var scroll = ( a && a === "left" ) ? "scrollLeft" : "scrollTop",
                has = false;

            if ( el[ scroll ] > 0 ) {
                return true;
            }
            el[ scroll ] = 1;
            has = ( el[ scroll ] > 0 );
            el[ scroll ] = 0;
            return has;
        },
        isOverAxis: function( x, reference, size ) {
            return ( x > reference ) && ( x < ( reference + size ) );
        },
        isOver: function( y, x, top, left, height, width ) {
            return $.ui.isOverAxis( y, top, height ) && $.ui.isOverAxis( x, left, width );
        }
    });

    $.fn.menuLayout = function(options){
        var defaultConfig = {
            menuParentClass: 'menu',
            depthClass: 'slider-item-depth-',
            transportClass: 'slider-item-transport',
            placeholderClass: 'sortable-placeholder',
            editClass: 'item-edit',
            extraButtons: '#clever-open-type-box',
            deleteClass: 'item-delete',
            cancelClass: 'item-cancel',
            ungroupClass: 'item-ungroup',
            activeClass: 'slider-item-edit-active',
            inactiveClass: 'slider-item-edit-inactive',
            deletingClass: 'deleting',
            addToSliderClass: 'add-to-menu',
            hideChildrenClass:'hide-children',
            items: '.slider-item',
            itemHandle: '.slider-item-handle',
            itemSettings: '.slider-item-settings',
            itemBtn: '.slider-btn',
            typeItemsList:'#type-items',
            minDepth: 0,
            maxDepth: 11,
            step: 40,
            isRTL: false,
            negateIfRTL: 1,
            targetTolerance: 0,
            menuItemTypes: [],
            menuItems: [],
            phrase: {
                untitled: 'Untitled',
                maximumColumns: 'Maximum of columns is ',
                preview: 'Preview',
                warning: 'Warning',
                removeItemWarning:'Are you sure to delete this item? All its children will be also removed.'
            },
            previewUrl: null,
            previewBtn: '#preview-btn',
            previewForm: '#edit_form',
            previewArea: '#preview-area',
            contentField: 'input[name="content"]',
            alert: false,
            confirm: false,
            modal: false,
            windowId: 'clevermenu',
            mediaUrl: '',
            imagePlaceholder: '',
            imageIconChar: 'file-image-o',
            imageIconStyle: 'color:#aaa6a0',
            spinner: '#slider-spinner'
        }

        var autoNum = 0;
        var lastSearch = '';
        var conf = $.extend({},defaultConfig,options);
        var $spinner = $(conf.spinner);

        window.getTmplById = function(id,data){
            return $('<div></div>').append($('#'+id).tmpl(data));
        }
        window.uniqid = function(prefix){
            if(typeof prefix !== 'string') prefix = '';
            return prefix + Math.floor(Math.random()*10000);
        }
        String.prototype.equalsTo = function(string) {
            return (this == string);
        };
        $.extend(jQuery.tmpl.tag, {
            "for": {
                _default: {$2: "var i=1;i<=1;i++"},
                open: 'for ($2){',
                close: '};'
            }
        });
        return this.each(function(id,el){
            var $this = $(this);
            $this.addClass(conf.menuParentClass);
            $.fn.extend({
                menuDepthClass: function(){
                    var $item = $(this);
                    var matchExp = new RegExp(conf.depthClass+'\\d','g');
                    if($item.attr('class')){
                        var depthClass = $item.attr('class').match(matchExp);
                        return (depthClass === null)?null:depthClass[0];
                    }else{
                        return null;
                    }
                },
                menuItemDepth: function(){
                    var $item = $(this);
                    var margin = conf.isRTL ? this.eq(0).css('margin-right') : this.eq(0).css('margin-left');
                    return pxToDepth( margin && -1 != margin.indexOf('px') ? margin.slice(0, -2) : 0 );

                    /*var $item = $(this);
                     var depthClass = $item.menuDepthClass();
                     return (depthClass == null)?0:parseInt(depthClass.replace(conf.depthClass,''));*/
                },
                filterDepth: function(depth){
                    var $item = $(this);
                    var $prev = $item.prev();
                    var $next = $item.next();
                    if($prev.length == 0){
                        depth = conf.minDepth;
                    }else{
                        prevDepth = $prev.menuItemDepth();
                        if(depth > prevDepth) depth = prevDepth + 1;
                        if($next.length > 0){
                            nextDepth = $next.menuItemDepth();
                            if(nextDepth > prevDepth){
                                depth = nextDepth;
                            }
                        }
                        if(depth > conf.maxDepth) depth = conf.maxDepth;
                        if(depth < conf.minDepth) depth = conf.minDepth;
                    }
                    return depth;
                },
                getChildren: function(){
                    var $item = $(this);
                    var check = false;
                    var depth = $item.menuItemDepth();
                    var $next = $item.next();
                    var $children = $();
                    while(($next.menuItemDepth() > depth) && ($next.length)){
                        $children.push($next);
                        $next = $next.next(conf.items);
                    }
                    return $children;
                },
                updateNoChildrenClass: function(){
                    return this.each(function(){
                        $menu = $(this);
                        $(conf.items,$menu).each(function(){
                            var $item = $(this);
                            if($item.getChildren().length == 0){
                                $item.addClass('no-children');
                            }else{
                                $item.removeClass('no-children');
                            }
                        });
                    });
                },
                updateDepthClass: function(depth, oldDepth){
                    return this.each(function(){
                        var $item = $(this),
                            oldDepth = oldDepth || $item.menuItemDepth();
                        $item.removeClass(conf.depthClass + oldDepth).addClass(conf.depthClass + depth);
                    });
                },
                shiftDepthClass : function(change) {
                    return this.each(function(){
                        var $item = $(this),
                            depth = $item.menuItemDepth();
                        $item.updateDepthClass(depth + change);
                    });
                },
                removeSliderItem: function(){
                    return this.each(function(){
                        var $item = $(this),
                            removeItem = function(){
                                $children = $item.getChildren();
                                $item.addClass(conf.deletingClass).animate({
                                    opacity : 0,
                                    height: 0
                                }, 350, function(){
                                    var id = $item.data('id');
                                    id = (parseInt(id) - 1 ) > 0 ? (parseInt(id) - 1 ) : 0;
                                    $item.remove();
                                    //$children.shiftDepthClass(-1);
                                    $children.each(function(){
                                        $(this).remove();
                                    });
                                    menuActions(id);
                                    $('#slider-to-edit '+conf.items).addClass(conf.inactiveClass).removeClass(conf.activeClass);
                                    $('#clever-left-slide-'+id).removeClass(conf.inactiveClass).addClass(conf.activeClass);
                                    $this.updateNoChildrenClass();
                                });

                            }
                        clevermenu.confirm(conf.phrase.warning,conf.phrase.removeItemWarning,{confirm: removeItem});
                    });
                },
                resetFieldId: function(){
                    return this.each(function(){
                        var $item = $(this);
                        $('.slider-field',$item).each(function(index, element) {
                            var $field = $(this);
                            var type = $field.data('type');
                            var oldId = $field.attr('id');
                            newId = uniqid(type+'_'),
                                $parent = $field.parents('.slider-item-field');
                            if(oldId){
                                $field.attr('id',newId);
                                $('.content-btn',$parent).each(function(){
                                    var $btn = $(this);
                                    var onclick = $btn.attr('onclick');
                                    if(onclick){
                                        onclick = onclick.replace(oldId,newId);
                                        $btn.attr('onclick',onclick);
                                    }
                                });
                            }
                            if(type == 'icon'){
                                $('.preview-icon',$parent).attr('id','preview-'+newId);
                            }
                        });
                    });
                },
                eventOnClickEditLink: function(){
                    return this.each(function(){
                        var $btn = $(this), $settings, $item;
                        $item = $btn.parents(conf.items).first();
                        $settings = $(conf.itemSettings,$item);
                        if($item.hasClass(conf.activeClass)){
                            $settings.slideUp(200);
                            $item.removeClass(conf.activeClass).addClass(conf.inactiveClass);
                        }else{
                            var $id = $item.parent().attr('id');
                            $("#"+$id+" ."+$settings.attr('class')).not($settings).slideUp('fast');
                            $remain = $("#"+$id+" ."+$item.attr('class')).not($item);
                            $("#"+$id+" "+conf.items).removeClass(conf.activeClass).addClass(conf.inactiveClass);
                            $settings.slideDown(200);
                            $item.addClass(conf.activeClass).removeClass(conf.inactiveClass);
                        }
                        $('.clever-slider').flexslider($item.data('id'));
                    });
                },
                eventOnClickCancelLink: function(){
                    return this.each(function(){
                        var $btn = $(this);
                        $btn.eventOnClickEditLink();
                    });
                },
                eventOnClickDeleteLink: function(){
                    return this.each(function(){
                        var $btn = $(this), $item = $btn.parents(conf.items).first();
                        $item.removeSliderItem();
                    });
                },
                eventOnclickUngroupLink: function(){
                    return this.each(function(){
                        var $btn = $(this), $item = $btn.parents(conf.items).first();
                        var depth = $item.menuItemDepth(), childDepth = depth + 1;
                        var $next = $item.next();
                        var $children = $item.getChildren();
                        var length = $children.length;
                        $item.toggleClass(conf.hideChildrenClass);
                        if(length > 0){
                            $children.each(function(i,el){
                                var $child = $(this);
                                if($item.hasClass(conf.hideChildrenClass)){
                                    $child.fadeOut(200);
                                    $child.addClass(conf.hideChildrenClass);
                                }else{
                                    if($child.menuItemDepth() == (depth+1)){
                                        $child.fadeIn(200);
                                        //$child.removeClass(conf.hideChildrenClass);
                                    }
                                }
                            });
                        }else{
                            $item.removeClass(conf.hideChildrenClass);
                        }
                    });
                },
                eventOnClickAddLink: function($type){
                    if ($type != 'image' && $type != 'video') return;
                    //find last id
                    var last = $('#slider-to-edit '+conf.items).last().data('id');
                    if(last) var id = parseInt(last) + 1;
                    else var id = 0;

                    //
                    var $cloneItem = $(conf.typeItemsList+' .type-'+ $type).wrapAll('<div>').parent().html();
                    $cloneItem = $($cloneItem);
                    $cloneItem.attr('id','clever-left-slide-'+id);
                    $cloneItem.attr('data-id',id);
                    //add corlor picker
                    addColorPicker($cloneItem);
                    //
                    $cloneItem.eventOnClickAllLinks();
                    $cloneItem.resetFieldId();


                    //$cloneItem.removeClass(conf.activeClass).addClass(conf.inactiveClass);
                    $($cloneItem).hide().appendTo($this).fadeIn('slow');

                    var itemTop = $cloneItem.offset().top,
                        itemHeight = $cloneItem.height(),
                        winHeight = $(window).height(), winTop = $(window).scrollTop();
                    if( (winTop + winHeight) < itemTop ){
                        $('html,body').animate({scrollTop: itemTop - 10},300);
                    }
                    $this.sortable('refresh');
                    $this.updateNoChildrenClass();
                    $('.content-row',$cloneItem).cleverAccordion();
                    menuActions(id);
                    $('#slider-to-edit '+conf.items).addClass(conf.inactiveClass).removeClass(conf.activeClass);
                    $('#clever-left-slide-'+id).removeClass(conf.inactiveClass).addClass(conf.activeClass);
                },
                eventOnClickAllLinks: function(){
                    return this.each(function(){
                        var $item = $(this);
                        $(conf.itemBtn,$item).each(function(){
                            var $btn = $(this);
                            if($btn.hasClass(conf.editClass)){
                                $btn.click(function(){
                                    $btn.eventOnClickEditLink();
                                });
                            }else if($btn.hasClass(conf.cancelClass)){
                                $btn.click(function(){
                                    $btn.eventOnClickCancelLink();
                                });
                            }else if($btn.hasClass(conf.deleteClass)){
                                $btn.click(function(){
                                    $btn.eventOnClickDeleteLink();
                                });
                            }else if($btn.hasClass(conf.addToSliderClass)){
                                $btn.click(function(){
                                    $btn.eventOnClickAddLink($btn.data('type'));
                                });
                            }else if($btn.hasClass(conf.ungroupClass)){
                                $btn.click(function(){
                                    $btn.eventOnclickUngroupLink();
                                });
                            }
                        });
                    });
                },
                updateGroup: function(){
                    return this.each(function(){

                    });
                }
            });

            //Variables

            var transport = '.'+conf.transportClass;
            var currentDepth = 0, originalDepth, minDepth, maxDepth,
                $prev, $next, prevBottom, nextThreshold, helperHeight, $transport, maxChildDepth,
                menuMaxDepth = initialSliderMaxDepth(),
                menuEdge = $this.offset().left;

            function initialSliderMaxDepth(){
                var maxDepth = 0;
                $this.find(' > '+conf.items).each(function(){
                    var $item = $(this);
                    if(maxDepth < $item.menuItemDepth()){
                        maxDepth = 	$item.menuItemDepth();
                    }
                });
                return maxDepth;
            };
            function updateSharedVars(ui) {
                var depth;
                $prev = ui.placeholder.prevAll( conf.items+':visible' ).first();
                $next = ui.placeholder.nextAll( conf.items+':visible' ).first();
                if( $prev[0] == ui.item[0] ) $prev = $prev.prevAll( conf.items +':visible' ).first();
                if( $next[0] == ui.item[0] ) $next = $next.nextAll( conf.items +':visible').first();

                prevBottom = ($prev.length) ? $prev.offset().top + $prev.height() : 0;
                nextThreshold = ($next.length) ? $next.offset().top + $next.height() / 3 : 0;
                minDepth = ($next.length) ? $next.menuItemDepth() : 0;
                if( $prev.length ){
                    maxDepth = ( (depth = $prev.menuItemDepth() + 1) > conf.maxDepth ) ? conf.maxDepth : depth;
                }else{
                    maxDepth = 0;
                }
            }
            function updateCurrentDepth(ui, depth) {
                ui.placeholder.updateDepthClass(depth, currentDepth);
                currentDepth = depth;
            }
            function updateSliderMaxDepth(depthChange) {
                var depth, newDepth = menuMaxDepth;
                if ( depthChange === 0 ) {
                    return;
                } else if ( depthChange > 0 ) {
                    depth = maxChildDepth + depthChange;
                    if( depth > menuMaxDepth )
                        newDepth = depth;
                } else if ( depthChange < 0 && maxChildDepth == menuMaxDepth ) {
                    while( ! $(conf.depthClass + newDepth, $this).length && newDepth > 0 )
                        newDepth--;
                }
                menuMaxDepth = newDepth;
            }
            function depthToPx(depth){
                return depth * conf.step;
            };
            function pxToDepth(px){
                return Math.floor(px / conf.step);
            }

            function initSortable(){
                $this.sortable({
                    items: conf.items,
                    handle: conf.itemHandle,
                    placeholder: conf.placeholderClass,
                    stop: function(e, ui){
                        menuActions(0);
                    }
                });
            };

            var $menuTypeTmpl = $('#slider-item-type-tmpl'), $menuTypeList = $(conf.typeItemsList);
            var $itemContent = $('#slider-item-content-tmpl');
            var menuTypesObject;
            function attacheEventToButtons(){
                $(conf.items,$this).eventOnClickAllLinks();
                $(conf.items,conf.extraButtons).eventOnClickAllLinks();
            };
            function menuItemTypeTmpl(){
                $(conf.menuItemTypes).each(function(id,typeItem){
                    var $typeItem = $menuTypeTmpl.tmpl(typeItem);
                    $typeItem.appendTo($menuTypeList);
                    if(typeItem.name != 'heading'){
                        $itemContent.tmpl(typeItem.content).appendTo($('.slider-item-fields',$typeItem));
                    }
                });
                $(conf.items,$menuTypeList).eventOnClickAllLinks();
                $(conf.items).eq(0).removeClass(conf.inactiveClass).addClass(conf.activeClass);
            };
            function reformatTypesArray(typeArray){
                var cloneTypeArray = JSON.parse(JSON.stringify(typeArray));
                var typeOject = {};
                $(cloneTypeArray).each(function(i,el){
                    var key = el.name;
                    typeOject[key] = el;
                    var content = {};
                    $(typeOject[key].content).each(function(id1,el1){
                        var ctKey = el1.name;
                        content[ctKey] = el1;
                    });
                    //typeOject[key]['content'] = content;
                });
                return typeOject;
            }
            function filterImage(value){
                if( (typeof value !== 'undefined') && (value != '')){
                    var patt = new RegExp('url="\\S+"','g');
                    if( patt.test(value)){
                        var src = conf.mediaUrl + (value).match(patt)[0].replace(/"/g,'').replace(/url=/,"");
                    }else{
                        var src = value;
                    }
                }else{
                    var src = conf.imagePlaceholder;
                }
                return src;
            }
            function loadSlider(menuItems){
                $spinner.show();
                if(!menuItems){
                    menuItems = conf.menuItems;
                }
                $this.empty();
                var menuTypesObject = reformatTypesArray(conf.menuItemTypes);

                $(menuItems).each(function(id,el){
                    var typeItem = menuTypesObject[el.item_type];
                    if(typeof typeItem !== 'undefined'){
                        var $item = $menuTypeTmpl.tmpl(menuTypesObject[el.item_type]);
                        $item.attr('id','clever-left-slide-'+id);
                        $item.attr('data-id',id);
                        $item.find('.slider-item-bar').on('click',function(event){
                            $('.clever-slider').flexslider(id);
                        });

                        $item.appendTo($this);

                        if(el.content.content){
                            $(typeItem.content).each(function(ctId,ctEl){
                                if(ctEl.name == 'content'){
                                    if(el.content.content.constructor === Array){
                                        ctEl.columns = el.content.content.length;
                                    }else{
                                        ctEl.columns = 1;
                                    }
                                }
                            });
                        }
                        $item.updateDepthClass(el.depth);
                        $itemContent.tmpl(typeItem.content).appendTo($('.slider-item-fields',$item));
                        $item.find('.slider-item-fields .field__page_selections').remove();

                        $('.type__editor',$item).each(function(){
                            var $fieldEditor = $(this);
                            $('.content-col',$fieldEditor).each(function(fid,fel){
                                var $col = $(this);
                                $('.column-number',$col).text(fid+1);
                                $('[data-name="content"]',$col).data('columns', fid );
                            });
                        });

                        $item.eventOnClickAllLinks();
                        $('.add-to-menu',$item).remove();
                        $('.slider-field',$item).each(function(){
                            var $field = $(this);
                            var type = $field.data('type');
                            var name = $field.data('name');
                            var value = el.content[name];
                            switch(type){
                                case 'image':
                                    $field.val(value);
                                    if($field.parent().find('.preview-image').length && (value != '')){
                                        var $preview = $field.parent().find('.preview-image');
                                        var src = filterImage(value);
                                        $preview.data('href',src);
                                        $preview.find('img').attr('src',src);
                                    }

                                    break;
                                case 'text':
                                    $field.val(value);
                                    if(name == 'label'){
                                        if(value!=''){
                                            var label = value;
                                        }else{
                                            var label = conf.phrase.untitled;
                                        }
                                        $('.slider-item-bar .link-title',$item).text(label);
                                    }
                                    break;
                                case 'icon':
                                    $field.val(value);
                                    break;
                                case 'dropdown':
                                    $('option[value="'+value+'"]',$field).attr('selected','selected');
                                    break;
                                case 'textarea':
                                case 'editor':
                                    if(value.constructor === Array){
                                        $field.val(value[$field.data('columns')]);
                                    }else{
                                        $field.val(value);
                                    }
                                    break;
                                case 'checkbox':
                                    $field.val(value);
                                    break;
                                default:
                                    $field.val(value);
                                    break;
                            }
                        });
                        $('.field-icon_type',$item).each(function(){
                            var $iconType = $(this), iconType = $iconType.val(),
                                $typeParent = $iconType.parents('.slider-item-field'),
                                $fontParent = $typeParent.next(),
                                $imageParent = $typeParent.next().next();
                            if(iconType == 0){
                                var $iconFont = $fontParent.find('.field-icon_font');
                                var value = $iconFont.val();
                                if(value != ''){
                                    attachIconToItemHeading($iconFont,value,0);
                                }
                                $fontParent.show();
                                $imageParent.hide();
                            }else{
                                var $iconImage =  $imageParent.find('.field-icon_img');
                                var value = $iconImage.val();
                                attachIconToItemHeading($iconImage,value,1);
                                $fontParent.hide();
                                $imageParent.show();
                            }
                        });
                        $('.field-layout',$item).each(function(){
                            var $layout = $(this), $parent = $layout.parents('.slider-item-field');
                            var layout = $layout.val().split(',');
                            var $row = $parent.next().find('.content-row');
                            changeColumnWidth($row,layout);
                            var $previewLayout = $('.preview-layout',$parent).addClass('layout-'+layout.join('-'));
                            var previewHtml = '';
                            $(layout).each(function(id,el){
                                previewHtml += '<span class="layout-col layout-col-'+el+'"></span>';
                            });
                            $previewLayout.html(previewHtml);
                        });
                        addColorPicker($item);
                    }

                });
                $spinner.hide();
            };
            function menuItemToJson(){
                var menuItems = [];
                $(conf.items,$this).each(function(){
                    var $item = $(this), $fieldsList = ('.slider-item-fields',$item), itemObject = {};
                    itemObject.depth = $item.menuItemDepth();
                    itemObject.item_type = $item.data('itemtype');
                    itemObject.content = {};
                    $('.slider-item-field',$fieldsList).each(function(){
                        var $fieldwrap = $(this);
                        var $field = $('.slider-field',$fieldwrap);
                        if($field.length > 1){
                            fieldCol = $field.first().data('name');
                            itemObject.content[fieldCol] = [];
                            $field.each(function(id,el){
                                var $f = $(this);
                                itemObject.content[fieldCol].push($f.val());
                            });
                        }else{
                            itemObject.content[$field.data('name')] = $field.val();
                        }
                    });
                    menuItems.push(itemObject);
                });
                return menuItems;
            };
            var $previewBtn = $(conf.previewBtn), $previewForm = $(conf.previewForm), previewUrl = conf.previewUrl,
                $previewArea = $(conf.previewArea);

            function menuActions(startat){
                //$previewBtn.on('click',function(){
                if ($('#slider-to-edit '+conf.items).length == 0 ) return;
                if(previewUrl != null){
                    var menuJson = clevermenu.menuItemToJson();
                    $(conf.contentField,$previewForm).val(JSON.stringify(menuJson));
                    var $controlNav = $previewForm.find('#menu_show_pagination').val();
                    var $autoRotate = $previewForm.find('#menu_auto_rotate').val();
                    var $showNav = $previewForm.find('#menu_show_nav').val();
                    $controlNav = $controlNav == 1 ? true : false;
                    $autoRotate = $autoRotate == 1 ? true : false;
                    $showNav = $showNav == 1 ? true : false;
                    var $slideshowSpeed = $previewForm.find('#menu_slide_animation').val();
                    //var $slideheight = $previewForm.find('#menu_slide_height').val();
                    //$previewArea.height($slideheight);
                    $.ajax({
                        url: previewUrl,
                        context: $('body'),
                        method:'POST',
                        isAjax: 'true',
                        data: $previewForm.serialize(),
                        beforeSend: function() {
                            $('#slider-spinner').show();
                        },
                        success: function(res){
                            $previewArea.html(res);
                            $('#slider-spinner').hide();
                            $('.clever-slider').flexslider({
                                animation: "slide",
                                controlNav: $controlNav,
                                directionNav: $showNav,
                                slideshow: $autoRotate,
                                startAt: startat,
                                slideshowSpeed: $slideshowSpeed,
                                start: function() {
                                    setTimeout(function(){
                                        jQuery('#preview-area .slides iframe').css('height',jQuery('#preview-area .slides').height());
                                    }, 500);
                                },
                                before:function(slider){
                                    window.action_slide = false;
                                },
                                after: function(slider) {
                                    if(!window.action_slide){
                                        var current = slider.currentSlide;
                                        if($('#clever-left-slide-'+current+'-slideshow').find('iframe').length > 0) {
                                            var auto = $('#clever-left-slide-'+current).find('.field-style_video').val();
                                            var src = $('#clever-left-slide-'+current+'-slideshow').find('iframe').attr('src');
                                            if(src.indexOf("autoplay=0") > 0) {
                                                $('#clever-left-slide-'+current+'-slideshow').find('iframe').attr('src',src.replace('autoplay=0','autoplay=1')) ;
                                            } else if(src.indexOf("autoplay=1") > 0){

                                            } else {
                                                $('#clever-left-slide-'+current+'-slideshow').find('iframe').attr('src',src+'&autoplay=1');
                                            }
                                            if(auto == '1') {
                                                $('#clever-left-slide-'+current+'-slideshow #play-'+current).hide();
                                                $('#clever-left-slide-'+current+'-slideshow #pause-'+current).show();
                                            }

                                            if (auto == '2' || auto == 2) {
                                                $('#clever-left-slide-'+current+'-slideshow').find('iframe').css('pointer-events','none');
                                            }
                                        }
                                        window.action_slide = true;
                                    }
                                }
                            });
                            //$('.clever-slider').flexslider(startat);
                            if($('#clever-left-slide-'+startat+'-slideshow').find('iframe').length > 0) {
                                var auto = $('#clever-left-slide-'+startat).find('.field-style_video').val();
                                var src = $('#clever-left-slide-'+startat+'-slideshow').find('iframe').attr('src');
                                if(src.indexOf("autoplay=0") > 0) {
                                    $('#clever-left-slide-'+startat+'-slideshow').find('iframe').attr('src',src.replace('autoplay=0','autoplay=1')) ;
                                } else if(src.indexOf("autoplay=1") > 0){

                                } else {
                                    $('#clever-left-slide-'+startat+'-slideshow').find('iframe').attr('src',src+'&autoplay=1');
                                }
                                if(auto == '1') {
                                    $('#clever-left-slide-'+startat+'-slideshow #play-'+startat).hide();
                                    $('#clever-left-slide-'+startat+'-slideshow #pause-'+startat).show();
                                }

                                if (auto == '2' || auto == 2) {
                                    $('#clever-left-slide-'+startat+'-slideshow').find('iframe').css('pointer-events','none');
                                }
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                        }
                    }).always(function(){ });
                }
                //});
            };
            function addColorPicker($item){
                $('input[data-custom="colorpicker"]',$item).each(function(){
                    var $el = $(this);
                    var $value = $el.val();
                    $el.css("backgroundColor", $value);
                    // Attach the color picker
                    $el.ColorPicker({
                        color: $value,
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                            var $text = $el;
                            var $name = $text.data('name');
                            var $item = $text.closest('.slider-item');
                            var id = $item.attr('id');
                            var currentText = $text.val();
                            switch ($name) {
                                case 'bt_color':
                                    $('#'+id+'-slideshow .bt_label').css('color',currentText);
                                    break;
                                case 'bt_bg_color':
                                    $('#'+id+'-slideshow .bt_label').css('background',currentText);
                                    break;
                                case 'bt_heading_color':
                                    $('#'+id+'-slideshow .heading_align').css('color',currentText);
                                    break;
                                case 'bt_sub_heading_color':
                                    $('#'+id+'-slideshow .sub_heading_align').css('color',currentText);
                                    break;
                                case 'bt_lb_color':
                                    $('#'+id+'-slideshow .bt_label').hover(function(){
                                        $(this).css('color',currentText);
                                    },function(){
                                        $(this).css('color',$('#'+id).find('.field-bt_color').val())
                                    });
                                    break;
                                case 'bt_bg_color_hover':
                                    $('#'+id+'-slideshow .bt_label').hover(function(){$(this).css('background',currentText)},function(){$(this).css('background',$('#'+id).find('.field-bt_bg_color').val())});
                                    break;
                            }
                        }
                    });
                });
            }
            function submitForm(){
                $previewForm.on('beforeSubmit',function(){
                    if( document.activeElement.id == 'duplicate' ){
                        $previewForm.append('<input type="hidden" name="duplicate" value="1" />');
                    }
                    var menuJson = clevermenu.menuItemToJson();
                    $(conf.contentField,$previewForm).val(JSON.stringify(menuJson));
                });
            };

            function attachIconToItemHeading(textEl,value, type){
                var full = $('#clever-temporary-images').val();
                var $textEl = $(textEl);
                var $class = $textEl.data('name');
                var id =   $textEl.closest('.slider-item').attr('id');
                if(typeof type === 'undefined'){
                    var type = 0;
                }
                var $parent = $textEl.parents('.slider-item').first();
                var $previewIcon = $('.slider-item-handle .preview-icon',$parent);
                if(type == 0){
                    $previewIcon.html('<i class="fa fa-'+value+'" ></i>');
                    if($textEl.parent().find('.preview-icon').length){
                        var $preview = $textEl.parent().find('.preview-icon');
                        $('i',$preview).removeAttr('class').addClass('fa fa-'+value);
                    }
                }else{
                    value = filterImage(value);
                    if($textEl.data('name') == 'icon_img'){
                        $previewIcon.html('<a class="preview-image" onclick="clevermenu.viewfull(this)" href="javascript:void(0)" data-href="'+value+'"><img src="'+value+'" /></a>');
                    }
                    if($textEl.parent().find('.preview-image').length){
                        var $preview = $textEl.parent().find('.preview-image');
                        $preview.data('href',value);
                        $('img',$preview).attr('src',value);
                    }
                }
                $('#'+id+'-slideshow .'+$class).attr('src',full);
                $('#clever-temporary-images').remove();
            }
            function changeColumnWidth($row,layout){
                var totalPart = sum(layout);
                $('.content-col',$row).each(function(id,el){
                    var $col = $(this);
                    var width = layout[id]*100/totalPart;
                    $col.css({width: width +'%'});
                });
            }
            function sum(numArray){
                var total = 0;
                $(numArray).each(function(id,el){
                    total += parseInt(numArray[id]);
                });
                return total;
            }
            var clevermenu = {
                maxColNum: 6,
                columnRow: '.content-row',
                columnCol: '.content-col',
                columnNumSpan: '.column-number',
                contentBody: '.content-body',
                editorTmpl: '#slider-item-content-type-editor-tmpl',
                menuItemField: '.slider-item-field',
                menuItemToJson: menuItemToJson,
                refresColumnNumber: function($row){
                    /*var self = this;
                     $(self.columnCol,$row).each(function(id,el){
                     var $col = $(this);
                     $(self.columnNumSpan,$col).text(id+1);
                     $('[data-name="content"]',$col).data('columns', id );
                     });*/
                },
                addNewColumn: function(btn){
                    var self = this;
                    var $btn = $(btn), $fieldParent = $btn.parents(self.menuItemField);
                    var $row = $(self.columnRow,$fieldParent);
                    if($row.find(self.columnCol).length < this.maxColNum){
                        $(self.editorTmpl).tmpl({type:'editor',name:'content',loop:1}).appendTo($row);
                        $row.cleverAccordion();
                        //$row.find(self.columnCol).last().removeClass('active').find(self.contentBody).hide();
                        this.refresColumnNumber($row);
                    }else{
                        this.alert(conf.phrase.maximumColumns+self.maxColNum);
                    };
                },
                deleteColumn: function(btn){
                    var self = this;
                    var $btn = $(btn), $row = $btn.parents(self.columnRow).first();
                    var $col = $btn.parents(self.columnCol).first();
                    $col.find(self.contentBody).slideUp(300,function(){
                        $col.remove();
                        self.refresColumnNumber($row);
                    });
                },
                removeIcon: function(btn){
                    var self = this;
                    var $btn = $(btn), $fieldParent = $btn.parents(self.menuItemField);
                    $('[data-type="icon"]',$fieldParent).val('').trigger('change');
                    $('.preview-icon i',$fieldParent).removeAttr('class');
                },
                removeImage: function(btn){
                    var self = this;
                    var $btn = $(btn), $fieldParent = $btn.parents(self.menuItemField);
                    $('[data-type="image"]',$fieldParent).val('').trigger('change');
                },
                attachLabel: function(text){
                    var self = this;
                    var $text = $(text);
                    var currentText = $text.val();
                    if(currentText == ''){
                        currentText = conf.phrase.untitled;
                    }
                    var $item = $text.parents(conf.items);
                    $('.slider-item-bar .link-title',$item).text(currentText);
                },

                attachSlide: function(text){
                    var self = this;
                    var $text = $(text);
                    var currentText = $text.val();
                    var $item = $text.closest('.slider-item');
                    var id = $item.attr('id');
                    var name = $text.data('name');
                    switch (name) {
                        case 'slide_link':
                            $('#'+id+'-slideshow .'+name).attr('href',currentText);
                            break;
                        case 'youtube_id':
                            var video_type = $text.closest('.slider-item-fields').find('select.field-video_type').val();
                            var video_style = $text.closest('.slider-item-fields').find('select.field-style_video').val();
                            if(video_type=='0'){
                                if (video_style == '0') {
                                    var $you_params = '?iv_load_policy=3&modestbranding=1&controls=1&showinfo=0&wmode=opaque&branding=0&autohide=0&rel=0&enablejsapi=1';
                                } else if(video_style == '1') {
                                    var $you_params = '?iv_load_policy=3&modestbranding=1&controls=1&showinfo=0&wmode=opaque&branding=0&autohide=0&rel=0&enablejsapi=1';
                                } else {
                                    var $you_params = '?iv_load_policy=3&modestbranding=1&autoplay=1&controls=0&showinfo=0&wmode=opaque&branding=0&autohide=0&rel=0&enablejsapi=1';
                                }
                                $('#'+id+'-slideshow iframe.'+name).attr('src','https://www.youtube.com/embed/'+currentText+$you_params);
                            }else{

                            }
                            break;
                        default :
                            $('#'+id+'-slideshow .'+name).text(currentText);
                            break;
                    }
                },
                changeOnPreview:function(element){
                    var $element = $(element);
                    var $val = $element.val();
                    var $name = $element.data('name');
                    var $id = $element.closest('.slider-item').attr('id');
                    var $list_class  = 'align-top align-center align-right align-left align-bottom align-middle';
                    var $targetElement = $('#'+$id+'-slideshow .'+$name);
                    if($name == 'style_video') {
                        menuActions($element.closest('.slider-item').data('id'));
                    } else if($name == 'show_overlay'){
                        $targetElement = $('#'+$id+'-slideshow > div').first();
                        $targetElement.removeClass($list_class).removeClass($name).addClass($val);
                    } else {
                        $targetElement.removeClass($list_class).addClass('align-'+$val);
                    }
                },
                alert: function(content){
                    if(typeof conf.alert == 'function'){
                        conf.alert({content: content});
                    }else{
                        alert(content);
                    }
                },
                confirm: function(title,content,actions){
                    if(typeof conf.confirm == 'function'){
                        return conf.confirm({
                            title: title,
                            content: content,
                            actions: {
                                confirm: (typeof actions.confirm !== 'undefined')?actions.confirm:function(){},
                                cancel: (typeof actions.cancel !== 'undefined')?actions.cancel:function(){},
                                always: (typeof actions.always !== 'undefined')?actions.cancel:function(){}
                            }
                        });
                    }else{
                        return confirm(content);
                    }
                },
                switchIconChooser: function(dropdown){
                    var self = this, $dropdown = $(dropdown),
                        $parent = $dropdown.parents('.slider-item-field');

                    var $icon = $parent.next();
                    var $image = $parent.next().next();
                    var $iconField = $('.field-icon_font',$icon);
                    var $imageField = $('.field-icon_img',$image);
                    if(dropdown.value == 1){
                        $icon.hide();
                        $image.show();
                        var src = filterImage($imageField.val());
                        attachIconToItemHeading($imageField,src,1);
                    }else{
                        $icon.show();
                        $image.hide();
                        attachIconToItemHeading($iconField,$iconField.val(),0);
                    }
                },
                viewfull: function(obj){
                    var src = $(obj).data('href');
                    $('img',$imageModal).attr('src',src);
                    $imageModal.modal('openModal');
                },
                attachIconToItemHeading: attachIconToItemHeading,
                changeContentLayout: function(btn,layout){
                    var self = this; $btn = $(btn), colNum = layout.length,
                        $parent = $btn.parents('.slider-item-field').first(),
                        $row = $parent.next().find(self.columnRow).first();
                    var oldColNum = $(self.columnCol,$row).length;
                    if(colNum > oldColNum){
                        var addNum = colNum - oldColNum;
                        $(self.editorTmpl).tmpl({type:'editor',name:'content',loop:addNum}).appendTo($row);
                    }else if(colNum < oldColNum){
                        var removeNum = oldColNum - colNum;
                        for(var i=0; i<removeNum; i++){
                            $(self.columnCol,$row).last().remove();
                        }
                    }
                    changeColumnWidth($row,layout);
                    this.toggleLayoutPanel($btn);
                    $('[data-type="layout"]',$parent).val(layout);
                    $('.preview-layout',$parent).html($btn.html());
                    $('.preview-layout',$parent).attr('class','preview-layout layout-'+layout.join('-'))
                },
                toggleLayoutPanel: function(btn,toggleType){
                    var $btn = $(btn);
                    var $layout = $btn.parents('.content-layout-wrap').first();
                    if(typeof toggleType === 'undefined'){
                        toggleType = 0;
                    }
                    if(toggleType == 0){
                        $layout.toggleClass('open');
                        $('.content-layout-chooser',$layout).fadeToggle('fast');
                    }else if(toggleType == 1){
                        $layout.addClass('open');
                        $('.content-layout-chooser',$layout).fadeIn('fast');
                    }else if(toggleType == 2){
                        $layout.removeClass('open');
                        $('.content-layout-chooser',$layout).fadeOut('fast');
                    }
                }
            }
            initSortable();
            menuItemTypeTmpl();
            attacheEventToButtons();
            loadSlider();
            menuActions(0);
            submitForm();

            $.fn.cleverAccordion = function(options){
                //no here
            }
            $('.content-row').cleverAccordion();


            var $imageModal = $('<div class="img-modal"><img src="" /></div>');
            $imageModal.modal({
                title: '',
                modalClass: '_image-box',
                clickableOverlay: true,
                buttons: []
            });


            eval('window.'+conf.windowId+' = clevermenu');
        });
    };
}));