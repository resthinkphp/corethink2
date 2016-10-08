<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model;
/**
 * 公共模型
 * @author jry <598821125@qq.com>
 */
class Model extends \think\Model {
    // 操作状态
    const MODEL_INSERT    = 1; //  插入模型数据
    const MODEL_UPDATE    = 2; //  更新模型数据
    const MODEL_BOTH      = 3; //  包含上面两种方式
    const MUST_VALIDATE   = 1; // 必须验证
    const EXISTS_VALIDATE = 0; // 表单存在字段则验证
    const VALUE_VALIDATE  = 2; // 表单值不为空则验证

    /**
     * 获取当前模型的数据库查询对象
     * @access public
     * @param bool $baseQuery 是否调用全局查询范围
     * @return Query
     */
    public function db($baseQuery = true)
    {
        $model = $this->class;
        if (!isset(self::$links[$model])) {
            // 设置当前模型 确保查询返回模型对象
            $query = \think\Db::connect($this->connection)->model($model, $this->query);

            // 设置当前数据表和模型名
            if (!empty($this->table)) {
                if (C('database.prefix')) {
                    $query->setTable(C('database.prefix') . $this->table);
                } else {
                    $query->setTable($this->table);
                }
            } else {
                $query->name($this->name);
            }

            if (!empty($this->pk)) {
                $query->pk($this->pk);
            }

            self::$links[$model] = $query;
        }
        // 全局作用域
        if ($baseQuery && method_exists($this, 'base')) {
            call_user_func_array([$this, 'base'], [ & self::$links[$model]]);
        }
        // 返回当前模型的数据库查询对象
        return self::$links[$model];
    }
}
