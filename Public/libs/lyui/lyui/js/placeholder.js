/* ========================================================================
 * cui: placeholder.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';

    var isPlaceholderSupport = (function(){
        return 'placeholder' in document.createElement('input');
    })();
    if(!isPlaceholderSupport){
        $("input.form-control").show(function(){
            var place_text = $(this).attr('placeholder');
            $(this).val(place_text);
        })
        .focus(function(){
            $(this).val("");
            $(this).val($(this).val());
        })
        .blur(function(){
            if($(this).val()=="") $(this).val($(this).attr("placeholder"));
        });
    }

}(jQuery);
