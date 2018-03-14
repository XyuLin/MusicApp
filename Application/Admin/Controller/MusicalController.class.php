<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-18
 * Time: 11:13
 */

namespace Admin\Controller;
use Think\Controller;

class MusicalController extends BaseController{
    public function add(){

        if(IS_POST){
            $model = D('musical');
            if($model->create(I('post.'),1)){
                if($model->add()){
//                    echo  $model->getLastSql();die;
                    $this->success("添加成功",U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        //获得类型信息
        $type_model = D('type');
        $type_info = $type_model->getTree();
//        var_dump($type_info);

        $this->assign(array('type_info'=>$type_info));
        $this->display();
}



    public function lst(){
        $model = D('musical');
        $data = $model->lst();
        $this->assign(array(
            'info'=>$data['info'],
            'page'=>$data['page'],
        ));
        $this->display();
    }

    public function edit(){

        $model = D('musical');
        if(IS_POST){
            if($model->create(I('post.'),2)){
                if($model->save() !== false){
                    $this->success("修改成功",U('lst'));
                }

            }        }
        //获得类型信息
        $type_model = D('type');
        $type_info = $type_model->getTree();

        $id = I('get.id');
        $info = $model->find($id);
        $this->assign(array('info'=>$info,'type_info'=>$type_info));
        $this->display();
    }


    /******************banner删除**************/
    public function del(){
        $id = I('get.id');
        $model = D('musical');
        $model->delete($id);
        $this->success("删除成功",U('lst'));
    }


    /***************获取属性*************/
    public function ajaxGetAttr(){
        $typeId = I('get.type_id');
        $attrModel = D('Attribute');
        $attrData =  $attrModel->where(array(
            'type_id'=>array('eq',$typeId),
        ))->select();
        echo json_encode($attrData);
//        echo '<pre>';
//        var_dump($attrData);die;
    }


    /***************获取品牌信息 放到添加属性*************/
    public function ajaxGetbrand(){
        $model = D('brand');
        $info = $model->select();

        echo json_encode($info);
    }


    /***************设置乐器每个属性组合的库存*************/
    public function goods_lst(){
        $attr_model = D('sdk');
        $goods_id = I('get.id');
        if(IS_POST) {
            $attr_model->where(array('goods_id'=>array('eq',$goods_id)))->delete();
                $num = I('post.goods_number');                                     //获取库存量-数组
                $attId = I('post.goods_attr_id');                                  //获取属性id-数组
                $attCount = count($attId);
                $numCount = count($num);
                $count = ceil($attCount / $numCount);                     //商品属性和库存的倍数
                foreach ($num as $v) {
                    $goodsattrid = array();
                    for ($i = 0; $i < $count; $i++) {
                        $goodsattrid[] = $attId[$i];
                    }
                    # 购物车时保持一致
                    sort($goodsattrid, SORT_NUMERIC);
                    $goods_attr = (string)implode(',', $goodsattrid);
                    $sdk_id = $attr_model->add(array(
                        'goods_id' => $goods_id,
                        'attr_id' => $goods_attr,
                        'num' => $v,
                    ));
                }
            $this->success('设置成功',U('lst'));

        }

            //获得库存表的信息
        $gnData = $attr_model->where(array(
            'goods_id'=>$goods_id,
        ))->select();

        //获得商品属性信息
        $model = D('musical');
        $info =$model->sdk();
        $infoCount = count($info);
        $this->assign(array(
            'gnData'=>$gnData,
            'info'=>$info,
            'infoCount'=>$infoCount,
        ));
        $this->display();
    }
}