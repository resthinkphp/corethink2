/* ========================================================================
 * cui: ajax-get.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';

    //jQuery弹窗提醒插件
    $.alertMessager = function(message, type, time) {
        type = type ? type : 'danger';
        var messager = '<div class="growl" style="top: 50px;left: 0;right:0;margin:0 auto;">'
                          +'<div class="alert alert-full alert-dismissable alert-'+type+'">'
                              +'<button type="button" class="close" data-dismiss="alert">'
                                  +'<span aria-hidden="true">×</span><span class="sr-only">Close</span>'
                              +'</button>'
                              +message
                          +'</div>'
                      +'</div>';
        $('.growl').remove();
        $('body').prepend(messager);
        setTimeout(function(){
            $('.growl').remove();
        }, time ? time : 2000);
    };

}(jQuery);
