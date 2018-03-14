<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-22
 * Time: 13:55
 */

namespace Admin\Controller;
use Think\Controller;

class InfoController extends BaseController
{
    public function info(){
        if(IS_POST){
            $model = D('info');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success("设置成功",U('Index/index'));
                    exit;
                }
            }
        }
        $this->display();
    }

}