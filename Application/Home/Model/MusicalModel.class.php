<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12
 * Time: 16:47
 */

namespace Home\Model;


use Think\Model;

class MusicalModel extends Model
{

    public function getMusicalDetails(){
        $id = I('get.goods_id');
        $id = 34;
        # 取出商品的属性
        $model = D('goodsattr');
        $data = $model->alias('a')
            ->field('')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id = b.id')
            ->where(array('goods_id'=>array('eq',$id)))
            ->select();
        # 分离属性
        $checkboxAttr = array();      # 多选 属性
        $radioAttr = array();         # 唯一属性
        foreach($data as $v){
            if($v['attr_sel'] == '唯一'){
                $radioAttr[] = $v;
            }else{
                $checkboxAttr[$v['attr_name']][] = $v;   # 再把多选的属性 以属性名称为下标 把这个属性的数字存到另一个数组中
            }
        }

        # 获取商品详情
        $goods_info = $this->find($id);


        $data = array();
        $data['checkboxAttr'] = $checkboxAttr;
        $data['radioAttr'] = $radioAttr;
        $data['goods_info'] = $goods_info;
        return $data;
    }

}