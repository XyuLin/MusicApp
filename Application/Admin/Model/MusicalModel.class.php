<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-18
 * Time: 11:14
 */

namespace Admin\Model;


use Think\Model;

class MusicalModel extends Model{

    //需要改验证
    protected $insertFields = array('musical_name','img','xiaoliang','type_id','musical_sm','price','brand_id');
    protected $updateFields = array('id','musical_name','img','xiaoliang','type_id','musical_sm','price','brand_id');
    protected $_validate = array(
        array('musical_name', 'require', '乐器不能为空！', 2, 'regex', 3),
        );

    public function _before_insert(&$data, $opt){
        $data['up_date'] = date('Y-m-d H:i:s',time());
//        var_dump($_FILES);die;
        if($_FILES['img']['error'] == 0){
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728 ;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'Goods/';
            $info = $upload->upload();

            if(!$info){
                $this->error = $upload->getError();
                return false;
            }else{
                $data['img'] = $logo = $info['img']['savepath'].$info['img']['savename'];
            }
        }
    }


    /******************商品修改前 （类型 属性 库存）**************/
    public function _before_update(&$data, $opt){

        //更新商品之前先删除 对应商品的商品属性
        $musical_attr = D('goodsattr');
        $musical_attr->delete($data['id']);
        $data['up_date'] = date('Y-m-d H:i:s',time());
        $data['musical_sm'] = rmoveXss($_POST['musical_sm']);
        if($_FILES['img']['error'] == 0){
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728 ;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'Goods';
            $info = $upload->upload();
            if(!$info){
                $this->error($upload->getError());
                return false;
            }else{
                $data['img'] = $logo = $info['img']['savepath'].$info['img']['savename'];
                $oldLogo = $this->field('img')->find($opt['where']['id']);
                unlink('./Public/Upload/'.$oldLogo['img']);
            }
        }
    }


    public function _after_update(&$data, $opt){
        /*********************更新完成之后处理添加商品属性信息*******************/
        $model = D('goodsattr');
        $attrValue = I('post.attr_value');
        foreach($attrValue as $k2=>$v2) {
            foreach ($v2 as $k3 => $v3) {
                $model->add(array(
                    'goods_id' => $data['id'],
                    'attr_id' => $k2,
                    'attr_value' => $v3,
                ));
            }
        }
    }

    /******************商品列表**************/
    public function lst($goods_id,$pageSize = 5){
        $where = array();
//        $goods_id = I('get.id');
        $where['id'] =  array('eq', $goods_id);
        if($goods_id){
            $count = $this->where($where)->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->alias('a')
                ->field('a.*,b.type_name,c.brand_name')
                ->join('LEFT JOIN __TYPE__ b ON a.type_id = b.id
                        LEFT JOIN __BRAND__ c ON a.brand_id = c.id ')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->where($where)
                ->select();
            $page = $Page->show();
            return $data = array('info'=>$info,
                'page'=>$page);

        }else{
            $count = $this->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->alias('a')
                ->field('a.*,b.type_name,c.brand_name,GROUP_CONCAT(d.attr_value) attr_value')
                ->join('LEFT JOIN __TYPE__ b ON a.type_id = b.id
                         LEFT JOIN __BRAND__ c ON a.brand_id = c.id
                          LEFT JOIN __GOODSATTR__ d ON d.goods_id = a.id')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->group('a.id')
                ->select();
            $page = $Page->show();
            return $data = array('info'=>$info,
                'page'=>$page);
        }
    }



    /******************商品del（类型 属性 库存）**************/
    public function _before_delete($data,$option){
        $goods_id = $option['where']['id'];
        var_dump($goods_id);die;
        $sdkModel = D('sdk');
        $sdkModel->where(array('goods_id'=>array('eq',$goods_id)))->delete();
    }


    public function _after_insert(&$data,$opt){
        /*********************处理添加商品属性信息*******************/
        $model = D('goodsattr');
        $brand_model = D('brand');
        $time = date('Y-m-d H:i:s',time());
        $attrValue = I('post.attr_value');
//        echo '<pre>';
//        var_dump($attrValue);die;
        foreach($attrValue as $k2=>$v2) {
            foreach ($v2 as $k3 => $v3) {
                $model->add(array(
                    'goods_id' => $data['id'],
                    'attr_id' => $k2,
                    'attr_value' => $v3,
                ));
            }
        }
    }


    /*********************获取库存 所需的商品信息和类型*******************/
    public function sdk()
    {
        $goods_id = I('get.id');
        $model = D('goodsattr');
        $musical_sdk = $model->alias('a')
            ->field('a.*,b.attr_name')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON b.id = a.attr_id
                    ')
            ->where(array(
                'a.goods_id' => array('eq', "$goods_id"),
                'b.attr_sel' => array('eq', '可选'),
            ))
            ->select();
        $sdkInfo = array();
        foreach ($musical_sdk as $k => $v) {
            $sdkInfo[$v['attr_name']][] = $v;
        }
        return $sdkInfo;
    }
}