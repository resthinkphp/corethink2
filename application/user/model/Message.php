<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\user\model;
use app\common\model\Model;
/**
 * 消息模型
 * @author jry <598821125@qq.com>
 */
class Message extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $table = 'user_message';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title','require','消息必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,1024', '消息长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('to_uid','require','收信人必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('is_read', '0', self::MODEL_INSERT),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options) {
        $result['title'] = strip_tags($result['title']);
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options) {
        foreach($result as &$record){
            $this->_after_find($record, $options);
        }
    }

    /**
     * 消息类型
     * @author jry <598821125@qq.com>
     */
    public function message_type($id) {
        $list[0] = '系统消息';
        $list[1] = '评论消息';
        return $id ? $list[$id] : $list;
    }

    /**
     * 发送消息
     * @param $send_data 消息类型
     * @param $send_type.email 是否通过系统消息通知
     * @param $send_type.email 是否通过邮件通知
     * @param $send_type.weixin 是否通过微信公众号推送（用户帐号需要绑定了微信）
     * @param $send_type.push 是否通过APP推送（用户帐号需要在某一台手机登陆）
     * @author jry <598821125@qq.com>
     */
    public function sendMessage($send_data, $send_type = array('message', 'email', 'weixin', 'push')) {
        $send_data['content']  = $send_data['content'] ? : $send_data['title']; //消息内容
        $msg_data['title']    = $send_data['title']; //消息标题
        $msg_data['content']  = $send_data['content'] ? : $send_data['title']; //消息内容
        $msg_data['to_uid']   = $send_data['to_uid']; //消息收信人ID
        $msg_data['type']     = $send_data['type'] ? : 0; //消息类型
        $msg_data['from_uid'] = $send_data['from_uid'] ? : 0; //消息发信人
        $msg_data['url']      = $send_data['url']; //消息额外URL
        $msg_data['remark']   = $send_data['remark']; //消息备注
        $data = $this->create($msg_data);
        if($data){
            // 系统消息通知（写进记录至user_message表即可）
            if (in_array('message', $send_type)) {
                $message_result = $this->add($data);
                if ($message_result) {
                    $return['message'] = true;
                }
            }

            // APP推送（默认采用极光推送，依赖官方的极光推送插件，如果需要其它方式需要自己写对应的插件）
            if (in_array('push', $send_type) && (D('Admin/Addon')->where('name="Jpush" and status="1"')->count())) {
                $push_result = D('Addons://Jpush/Jpush')->send($send_data);
                if ($push_result) {
                    $return['push'] = true;
                }
            }

            // 微信消息通知
            if (!$return['push'] && in_array('weixin', $send_type) && (D('Admin/Module')->where('name="Weixin" and status="1"')->count())) {
                $from = D('Admin/User')->getUserInfo($send_data['from_uid'], 'nickname') ? : '系统';
                $wxdata['touser']      = D('Weixin/UserBind')->getFieldByUid($send_data['to_uid'], 'openid');
                $wxdata['template_id'] = $send_data['template_id'];
                $wxdata['url']         = $send_data['url'];
                $wxdata['first']       = '来自' . $from . '的消息';
                $wxdata['keyword1']    = html2text($send_data['title']);
                $wxdata['keyword2']    = time_format(time());
                $wxdata['keyword3']    = html2text($send_data['content']);
                $wxdata['remark']      = html2text($send_data['remark']);
                $weixin_result = D('Weixin/Index')->SendMessage($wxdata);
                if ($weixin_result) {
                    $return['weixin'] = true;
                }
            }

            // 邮件通知（写进记录至addon_email表即可，后续真实的发送操作由ThinkPHP的Cron触发）
            if (in_array('email', $send_type) || (!$return['push'] && !$return['weixin'])) {
                hook('SendMessage', $send_data); //发送消息钩子，用于消息发送途径的扩展
                $return['email'] = true;
            }

            // 返回结果
            return $return;
        } else {
            return false;
        }
    }

    /**
     * 获取当前用户未读消息数量
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function newMessageCount($type = null) {
        $map['status'] = array('eq', 1);
        $map['to_uid'] = array('eq', is_login());
        $map['is_read'] = array('eq', 0);
        if($type !== null){
            $map['type'] = array('eq', $type);
        }
        return $this->where($map)->count();
    }
}
