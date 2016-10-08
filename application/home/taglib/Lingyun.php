<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\home\taglib;
use think\template\TagLib;
/**
 * 标签库
 * @author jry <598821125@qq.com>
 */
class Lingyun extends TagLib {
    /**
     * 定义标签列表
     * @author jry <598821125@qq.com>
     */
    protected $tags = array(
        'sql'   => array('attr' => 'sql,result', 'close' => 0),             //SQL查询
        'nav_list'    => array('attr' => 'name,pid,group', 'close' => 1),         //导航列表
        'slider_list' => array('attr' => 'name,limit,page,order', 'close' => 1),  //幻灯列表
        'post_ist'   => array('attr' => 'name,limit,page,order,cid', 'close' => 1),  //文章列表
    );

    /**
     * SQL查询
     */
    public function tagSql($tag, $content) {
        $sql    =    $tag['sql'];
        $result =    !empty($tag['result']) ? $tag['result'] : 'result';
        $parse  =    '<?php $'.$result.' = M()->query("'.$sql.'");';
        $parse .=    'if($'.$result.'):?>'.$content;
        $parse .=    "<?php endif;?>";
        return $parse;
    }

    /**
     * 导航列表
     */
    public function tagNav_list($tag, $content) {
        $name   = $tag['name'];
        $pid    = $tag['pid'] ? : 0;
        $group  = $tag['group'] ? '\''.$tag['group'].'\'' : 'main';
        $parse  = '<?php ';
        $parse .= '$__NAV_LIST__ = D(\'Admin/Nav\')->getNavTree('.$pid.', '.$group.');';
        $parse .= ' ?>';
        $parse .= '<volist name="__NAV_LIST__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /**
     * 幻灯列表
     * @author jry <598821125@qq.com>
     */
    public function tagSlider_list($tag, $content) {
        $name   = $tag['name'];
        $limit  = isset($tag['limit']) ? : 10;
        $page   = isset($tag['page']) ? : 1;
        $order  = isset($tag['order']) ? : 'sort desc,id desc';
        $parse  = '<?php ';
        $parse .= '$map["status"] = array("eq", "1");';
        $parse .= '$__SLIDER_LIST__ = D("Admin/Slider")->getList('.$limit.', '.$page.', "'.$order.'", $map);';
        $parse .= ' ?>';
        $parse .= '<volist name="__SLIDER_LIST__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /**
     * 文章列表
     * @author jry <598821125@qq.com>
     */
    public function tagPost_list($tag, $content) {
        $name   = $tag['name'];
        $cid    = $tag['cid'];
        if (!$cid) {
            return;
        }
        $limit  = $tag['limit'] ? : 10;
        $page   = $tag['page'] ? : 1;
        $order  = $tag['order'] ? : 'sort desc,id desc';
        $parse  = '<?php ';
        $parse .= '$map["status"] = array("eq", "1");';
        $parse .= '$__POST_LIST__ = D("Admin/Post")->getList('.$cid.', '.$limit.', '.$page.', "'.$order.'", $map);';
        $parse .= ' ?>';
        $parse .= '<volist name="__POST_LIST__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}
