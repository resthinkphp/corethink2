/* ========================================================================
 * cui: ajax-get.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';

    //ajax get请求
    $(document).on('click', '.ajax-get', function() {
        var target;
        var that = this;
        if ($(this).hasClass('confirm')) {
            if (!confirm('确认要执行该操作吗?')) {
                return false;
            }
        }
        if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            $(this).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
            $.ajax({
                dataType: "json",
                url: target,
                type: "get",
                success: function(data) {
                    if (data.status == undefined) {
                        alert(data);
                        $(that).removeClass('disabled').prop('disabled', false);
                    } else {
                        if (data.status == 1) {
                            if (data.url && !$(that).hasClass('no-refresh')) {
                                var message = data.info + ' 页面即将自动跳转~';
                            } else {
                                var message = data.info;
                            }
                            $.alertMessager(message, 'success', $(this).attr('alert-style'));
                            setTimeout(function() {
                                $(that).removeClass('disabled').prop('disabled', false);
                                if ($(that).hasClass('no-refresh')) {
                                    return false;
                                }
                                if (data.url && !$(that).hasClass('no-forward')) {
                                    location.href = data.url;
                                } else {
                                    location.reload();
                                }
                            }, 2000);
                        } else {
                            if (data.login == 1) {
                                $('#login-modal').modal(); //弹出登陆框
                            } else {
                                $.alertMessager(data.info, 'danger', $(this).attr('alert-style'));
                            }
                            setTimeout(function() {
                                $(that).removeClass('disabled').prop('disabled', false);
                            }, 2000);
                        }
                    }
                },
                error: function(e) {
                    if (e.responseText) {
                        alert(e.responseText);
                    }
                    $(that).removeClass('disabled').prop('disabled', false);
                }
            });
        }
        return false;
    });

}(jQuery);
