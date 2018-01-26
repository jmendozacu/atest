/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

define([
    "jquery"
],function($) {
    'use strict';

    var FormVideoField = new function(){
        var t = this;

        t._init = function(fieldId, btnId, indicatorId){
            t.field = $(fieldId);
            t.getBtn = $(btnId);
            t.indicator = $(indicatorId);
        },

            t.getVideoId = function(url){
                var videoId, videoType;
                if (url.indexOf('youtube.com') > 0){
                    videoType = 'youtube';
                    videoId = url.split('v=')[1];
                    videoId = videoId ? (videoId.indexOf('&') > 0 ? videoId.substring(0, videoId.indexOf('&')) : videoId) : videoId;
                }else if (url.indexOf('vimeo.com') > 0){
                    videoType = 'vimeo';
                    videoId = url.replace(/[^0-9]+/g, '');
                }
                return [videoType, videoId ? videoId.trim() : ''];
            },

            t.toggleBusy = function(flag){
                if (flag){
                    //disableElement(t.getBtn);
                    t.getBtn.prop('disabled',true);
                    t.getBtn.addClass('disabled');
                    t.indicator && t.indicator.show();
                }else{
                    //enableElement(t.getBtn);
                    t.getBtn.prop('disabled',false);
                    t.getBtn.removeClass('disabled');
                    t.indicator && t.indicator.hide();
                }
            },

            t.onParallaxVideoYoutubeCallback = function(data){
                t.toggleBusy(false);
                var thumb = data['entry']['media$group']['media$thumbnail'][1]['url'];
                t.preview(thumb);
            },

            t.onParallaxVideoVimeoCallback = function(data){
                t.toggleBusy(false);
                var thumb = data[0]['thumbnail_medium'];
                t.preview(thumb);
            },

            t.preview = function(url){
                if (url){
                    var imgElm = t.field.closest().find('#videoThumbPreview').first();
                    if (!imgElm){
                        var img = $('<img/>', {src: url, id: 'videoThumbPreview'});
                        t.field.closest().append(img);
                    }else imgElm.attr('src',url);
                }
            },

            t.remove = function(){
                var imgElm = t.field.closest().find('#videoThumbPreview').first();
                if (imgElm) imgElm.remove();
                t.field.val('');
                t.field.focus();
            },

            t.search = function(){
                var videoData = t.getVideoId(t.field.val()),
                    urlAPI,
                    head = $('head').first(),
                    script = $('<script/>', {type:'text/javascript'});

                switch (videoData[0]){
                    case 'youtube':
                        urlAPI = 'https://gdata.youtube.com/feeds/api/videos/' +
                        videoData[1] + '?v=2&alt=json-in-script&callback=FormVideoField.onParallaxVideoYoutubeCallback';
                        break;
                    case 'vimeo':
                        urlAPI = 'http://vimeo.com/api/v2/video/' +
                        videoData[1] + '.json?callback=FormVideoField.onParallaxVideoVimeoCallback';
                        break;
                }
                if (urlAPI){
                    script.attr('src',urlAPI);
                    head.append(script);
                    setTimeout(function(){
                        t.toggleBusy(false);
                    }, 5000);
                    t.toggleBusy(true);
                }
            }
    };

    return window.FormVideoField = FormVideoField;
});
