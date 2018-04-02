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

    // 购买课程订单管理
    public function stuSubList($pageSize = '8')
    {
    	$model = D('stuSubject');

        $count = $model->count();                    //订单总记录数
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $model->limit($Page->firstRow.','.$Page->listRows)
                     ->order('id desc')->select();
                $user = D('user');
                foreach ($info as $key => &$value) {
                    # code...
                    $value['stuName'] = $user->where(['id'=>$value['stu_id']])->getField('name');
                }
            $page = $Page->show();
            $this->assign(['info'=>$info,'page'=>$page]);
            $this->display();
    	
    }

    public function del()
    {
        $id = I('get.id');

        $model = D('stuSubject');

        if($model->delete($id)){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}