<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-22
 * Time: 11:09
 */

namespace Admin\Model;
use Think\Model;

class OrderModel extends Model{
    protected $insertFields = array();
    protected $updateFields = array();
    protected $_validate = array(
        array('phoen','^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$','手机号码错误！','0','regex',1)
    );



    public function lst($pageSize = 1){
        $where = array();
        $user_name = session('stu_username');                      //或者老师的（username）  等前端传
        $where['user_name'] =  array('like', "%$user_name%");
            $count = $this->where($where)->count();                 //订单总记录数
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->limit($Page->firstRow.','.$Page->listRows)
                     ->where($where)
                     ->select();
            $page = $Page->show();

            return $data = array('info'=>$info,
                                 'page'=>$page);

    }
}