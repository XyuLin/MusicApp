<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12
 * Time: 10:47
 */

namespace Home\Controller;
use Think\Controller;

class VideoController extends Controller{

    # 待审核信息 指定老师
    public function getShenHe(){
        $model = D('video');
        $data = $model->showShenHe();
        return return_json($data);
    }
}