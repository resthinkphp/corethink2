/* ========================================================================
 * cui: numberGrow.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';

    // 数字动画增加效果
    $.numberGrow = function numberGrow(element, options) {
        options = options || {};

        var $this = $(element),
            time = (options.time || $this.data('time')) * 1000,//总时间，毫秒为单位
            num = options.num || $this.data('value'),//要显示的总数值
            startTime = +new Date(),//记下当前时间作为开始时间
            curTime,//这个变量在setInterval回调中用到，代表每次回调执行时的当前时间
            timer;//定时器变量

        timer = setInterval(function () {
            curTime = +new Date();

            var timeSpan = curTime - startTime,//得到已经运行的时间
                curValue = num * (timeSpan / time);//得到当前应该显示的值，计算公式：当前值 = 总数值 * (已经运行的时间 / 总时间)

            //如果已经运行的时间超出了规定的总时间，则把当前值设置为总数值，并清空计时器
            if(timeSpan >= time) {
                curValue = num;
                //console.log(curTime - startTime);
                clearInterval(timer);
            } else {
                curValue = Math.floor(curValue);
            }

            $this.text(curValue);
        }, 16);
    };

    $(window).on('load', function () {
        $('[data-ride="numberGrow"]').each(function () {
            $.numberGrow(this);
        })
    })

}(jQuery);
