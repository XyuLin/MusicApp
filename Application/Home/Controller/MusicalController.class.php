<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 10:26
 */
namespace Home\Controller;
use Think\Controller;
class MusicalController extends Controller{

    //获得商品信息
    public function getMusical(){
        $model = D('musical');
        $data = $model->select();
        return return_json($data);
    }

    # 商品详情 展示
    public function showMusicalDetails(){
        $model = D('musical');
        $data = $model->getMusicalDetails();
//        V($data);
//        return return_json($data);
    }
}