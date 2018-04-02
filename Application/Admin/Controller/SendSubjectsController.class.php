<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-16
 * Time: 16:58
 */

namespace Admin\Controller;
use Think\Controller;

class SendSubjectsController extends BaseController
{

    public function verifySub()
    {
        $model = D('sendSubjects');
        $data = $model->noVerify();

        $this->assign([
            'info' => $data['info'],
            'page' => $data['page'],
        ]);

        $this->display();
        
    }

    public function del()
    {
    	$id = I('get.id');
    	$model = D('sendSubjects');
    	$isT = $model->where(['id'=>$id])->delete();

    	if($isT != false){
    		$this->success('删除成功',U("sendSubjects/verifySub"));
    	}
    }

    public function verify()
    {
    	$id = I('get.id');

    	$isT = D('sendSubjects')->where(['id'=>$id])->save(['status'=>'1']);

    	if($isT){
    		$this->success('已通过审核',U("sendSubjects/verifySub"));
    	}
    }

    public function add()
    {
    	$post = I('post');

    	$isT = D('sendSubjects')->add($post);

  		if($isT){
    		$this->success('添加课程成功,请等待审核',U("sendSubjects/verifySub"));
    	}else{
    		$this->error('添加课程失败');
    	}

    }

}