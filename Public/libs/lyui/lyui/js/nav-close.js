/* ========================================================================
 * cui: nav-close.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';

    // 给Bootstrap标签切换增加关闭功能
    $('body').delegate('.nav-close .close', 'click', function() {
        var id = $(this).closest('a[data-toggle="tab"]').attr('href');
        if(id) {
            if ($(id).hasClass('active')) {
                $(this).closest('li').prev().addClass('active');
                $($(this).closest('li').prev().find('a').attr('href')).removeClass('fade').addClass('active');

            }
            // 删除标签对应的内容
            if ($(id).remove()) {
                $(this).closest('li').remove();  // 删除标签
            };
        }
    });

}(jQuery);
