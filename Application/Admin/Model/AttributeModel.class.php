<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-20
 * Time: 10:20
 */

namespace Admin\Model;
use Think\Model;

class AttributeModel extends Model{
    protected $insertFields = 'type_id,attr_name,attr_option_values,attr_sel';
    protected $updateFields = 'id,type_id,attr_name,attr_option_values,attr_sel';
    protected $_validate = array(
        array('attr_name', 'require', '1不能为空！', 1, 'regex', 3),
        array('attr_name', '1,30', '的值最长不能超过 30 个字符！', 1, 'length', 3),
        array('type_id', 'require', '4不能为空！', 1, 'regex', 3),
        array('type_id', 'number', '所属类型ID必须是一个整数！', 1, 'regex', 3),
        array('attr_option_values', 'require', '4不能为空！', 1, 'regex', 3),
    );



    /******************查看当前类型的属性**************/
    public function _before_insert(&$data){
        $data['attr_option_values'] = str_replace('，',',',$data['attr_option_values']);
    }

    /******************查看当前类型的属性**************/
    public function lst($pageSize = 1)
    {
        /**************************************** 搜索 ****************************************/
        $where = array();
        if($attr_name = I('get.attr_name'))
            $where['attr_name'] = array('like', "%$attr_name%");
        if($type_id = I('get.type_id'))
            $where['type_id'] = array('eq', $type_id);
        /************************************* 翻页 ****************************************/
        if($where){
            $count = $this->alias('a')->where($where)->count();
            $page = new \Think\Page($count, $pageSize);
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $page = $page->show();
            /************************************** 取数据 ******************************************/
            $info = $this->alias('a')->field('a.*,b.type_name')
                ->join('LEFT JOIN __TYPE__ b ON a.type_id = b.id')
                ->where($where)
                ->group('a.id')
                ->limit($page->firstRow.','.$page->listRows)
                ->select();
        }else{
            $count = $this->alias('a')->count();
            $page = new \Think\Page($count, $pageSize);
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $page = $page->show();
            /************************************** 取数据 ******************************************/
            $info = $this->alias('a')->field('a.*,b.type_name')
                ->join('LEFT JOIN __TYPE__ b ON a.type_id = b.id')
                ->group('a.id')
                ->limit($page->firstRow.','.$page->listRows)
                ->select();
        }

        return $data = array('info'=>$info,'page'=>$page,);
    }


    /***********修改之前***********/

}