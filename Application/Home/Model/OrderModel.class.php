<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12
 * Time: 13:15
 */

namespace Home\Model;


use Think\Model;

class OrderModel extends Model
{
    protected $insertFields = array('number','attr','phone','goods','video','user_name','address','total');
    protected $updateFields = array('id','number','attr','phone','goods','video','user_name','address','total');
    protected $_validate = array(
        array('send_content', '1,200', '输入信息过长', 2, 'length', 3),
        array('phone','^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$','手机号码错误！','0','regex',1),
    );

    # 下订单
    public function ret_order_down(){

    }

    #返回订单详情
    public function ret_order($userId){
        $where = array();
        if($userId){
            $where['userId'] = array('eq',$userId);
        }
       $data =  $this->where($where)->select();
        return $data;
    }


    # 下单前 检查库存量 需要前段升序给出属性的id值 到库存表查该属性的库存是否足够
    public function _before_insert(){
        $sku = ; # 查询库存是否充足
        if($sku>0){
            return true;

            # 并减去相应库存量
            $model = D('sdk');
            if(){
                # 事务处理机制
            }

        }else{
            return false;
        }
    }


}