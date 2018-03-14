<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12
 * Time: 13:09
 */

namespace Home\Controller;
use Think\Controller;

class OrderController extends Controller
{
    # 下订单 下单前检查库存 （属性）
    public function orderDown(){
//        确认 商品名称 商品id 商品品牌 商品类型 商品属性 商品价格 商品数量 购买人id 姓名 地址 电话
         $model = D('order');
         if(IS_POST){
             if($model->create(I('post.'),1)){
                 if($data = $model->add()){
                     return_json($data);
                  }
             }
         }

    }


        # 订单详情
    public function getOrder(){
//        展示商品名称 商品id 商品品牌 商品类型 商品属性 商品价格 商品数量 购买人id 姓名 地址 电话
        $model = D('order');
        $data = $model->ret_order();
        return_json($data);
    }

}