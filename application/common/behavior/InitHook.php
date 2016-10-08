<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\behavior;
use think\Hook;
defined('THINK_PATH') or exit();
/**
 * 初始化钩子信息
 * @author jry <598821125@qq.com>
 */
class InitHook {
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content) {
        // 安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        // 添加插件配置
        $addon_config['ADDON_PATH'] = './Addons/';
        $addon_config['AUTOLOAD_NAMESPACE'] = C('AUTOLOAD_NAMESPACE');
        $addon_config['AUTOLOAD_NAMESPACE']['Addons'] = $addon_config['ADDON_PATH'];
        C($addon_config);

        $data = S('hooks');
        if (!$data || C('app_debug') === true) {
            $hooks = D('Admin/Hook')->column('name,addons');
            foreach ($hooks as $key => $value) {
                if ($value) {
                    $map['status'] = 1;
                    $names         = explode(',',$value);
                    $map['name']   = array('IN',$names);
                    $data = D('Admin/Addon')->where($map)->column('id,name');
                    if($data){
                        // 过滤掉插件目录不存在的插件
                        foreach ($data as $key => $val) {
                            if (is_dir('.addons/'.$val)) {
                                unset($data[$key]);
                            }
                        }
                        $addons = array_intersect($names, $data);
                        Hook::add($key, array_map('get_addon_class', $addons));
                    }
                }
            }
            S('hooks', Hook::get());
        } else {
            Hook::import($data,false);
        }
    }
}
