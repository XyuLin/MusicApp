<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-16
 * Time: 16:58
 */

namespace Admin\Controller;
use Think\Controller;

class UserController extends BaseController{
    /******************验证码**************/
    public function chkcode(){
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useNoise' => false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }


    public function add(){
        if(IS_POST){
            $model = D('teacher');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success("注册成功，正在为您跳转首页请稍后...",U('lst'));   //后面改为首页
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->display();
    }


    /******************列表**************/
    public function teaLst(){
        $model = D('user');
        //查看老师信息
        $info_teacher = $model->teach();
        $this->assign(array(
            'info'=>$info_teacher['info'],
            'page'=>$info_teacher['page'],
        ));

        $this->display();
    }



    /******************更新**************/
    public function edit(){
        $model = D('teacher');
        $id =I('get.id');
        if($model->create(I('post.'),2)){
            if(FALSE !== $model->save()){
                $this->success("修改成功",U('lst'));
            }
        }
        //修改时
        if($id){
            $info = $model->find($id);
        }
        $this->assign(array(
            'info'=>$info,
        ));
        $this->display();
    }

    /******************删除**************/
    public function del(){
        $model = D('teacher');
        $id =I('get.id');
        if($model->delete($id) !== FALSE){
            $this->success('删除成功',U('lst'));
        }
        $this->display();
    }


    /******************查看对某位老师的所有评论**************/
    public function sel(){
        $id = I('get.id');
        $model = D('user');
        $data = $model->com_to_teacher($id);
        $this->assign(array('info'=>$data['info'],'page'=>$data['page']));
        $this->display();
    }



    /******************删除对某位老师的恶意评论**************/
    public function del_com_teacher(){
        $model = D('com_teach');
        $id =I('get.id');
        if($id){
            if(false !== $model->delete($id)){
                $this->success('删除成功',U('lst'));
            }
        }
    }


    /******************查看对某位老师的所有教程**************/
    public function see_video(){
        $id = I('get.id');
        $model = D('user');
        $data = $model->see_video($id);
        $this->assign(array('info'=>$data['info'],'page'=>$data['page']));
        $this->display();
    }









    /******************学生列表**************/
    public function stuLst(){
        $model = D('user');
        //查看老师信息
        $data = $model->stu();
        $this->assign(array(
            'info'=>$data['info'],
            'page'=>$data['page'],
        ));

        $this->display();
    }



}