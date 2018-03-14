<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-22
 * Time: 11:08
 */

namespace Admin\Controller;
use Think\Controller;

class OrderController extends Controller
{
    public function lst(){
        $model = D('order');
      $data =  $model->lst();
        $this->assign(array('info'=>$data['info'],'page'=>$data['page']));
        $this->display();
    }
}