<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
return array(
    'app_init'     => array('app\\common\\behavior\\InitModule'), //初始化安装的模块行为扩展
    'app_begin'    => array('app\\common\\behavior\\InitConfig'), //初始化系统配置行为扩展
    'action_begin' => array('app\\common\\behavior\\InitHook'), //初始化插件钩子行为扩展
    //'app_end'      => array('Behavior\CronRunBehavior'), //定时任务行为扩展
);
