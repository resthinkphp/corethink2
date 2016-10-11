<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\cms\model;
use app\common\model\Model;
/**
 * 文章模型
 * @author jry <598821125@qq.com>
 */
class CmsArticle extends Model {
    /**
     * 模块名称
     * @author jry <598821125@qq.com>
     */
    public $moduleName = 'Cms';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    public $tableName = 'cms_article';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '文章标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );
}
