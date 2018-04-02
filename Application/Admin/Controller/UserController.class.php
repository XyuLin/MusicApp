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


    // 添加用户
    public function add(){
        if(IS_POST){
            $model = D('user');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success("注册成功，正在为您跳转首页请稍后...",U('stuLst'));   //后面改为首页
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
        $info_teacher = $model->teachers();
        $this->assign(array(
            'info'=>$info_teacher['info'],
            'page'=>$info_teacher['page'],
        ));

        $this->display();
    }



    /******************更新**************/
    public function teacherEdit(){
        $model = D('teacher');
        $id =I('get.id');
        $user = D('user');

        if(IS_POST){
            $post = I('post.');
            $userdata = [
                'name' => $post['name'],
                'sex'  => $post['sex'],
                'home_address' => $post['home_address'],
            ];
            $teacher = [
                'home_address' => $post['home_address'],
                'office_address' => $post['office_address'],
                'graduate'      => $post['graduate'],
                'education_age'     => $post['education_age'],
            ];
            $userT = $user->where(['id'=>$id])->field('name,sex,home_address')->filter('strip_tags')->save($userdata);
            $teacherT = $model->where(['id'=>$id])->field('education_age,office_address,graduate,home_address')->filter('strip_tags')->save($teacher);

            if($userT == 1 || $teacherT == 1){
                 $this->success("修改成功",U('teaLst'));die;
            }
            $this->success("修改失败",U('teaLst'));die;
        }

        //修改时
        if($id){
            $info = $user->find($id);
            $info['teacher'] = $model->find($id);
        }
        $this->assign(array(
            'info'=>$info,
        ));
        $this->display('teacher');
    }

    /******************删除**************/
    public function del(){
        $model = D('user');
        $id =I('get.id');
        if($model->delete($id) !== FALSE){
            $this->success('删除成功');
        }
    }

    public function delTeacher()
    {
        $model = D('teacher');
        $id =I('get.id');
        $user = D('user');
        if($model->delete($id) !== FALSE){
            $user->delete($id);
            $this->success('删除成功');
        }
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
        $model = D('comment');
        $id =I('get.id');
        if($id){
            if(false !== $model->delete($id)){
                $this->success('删除成功',U("sel",['id'=>$id]));
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
 
        $data = $model->students();


        $this->assign(array(
            'info'=>$data['info'],
            'page'=>$data['page'],
        ));

        $this->display();
    }

    public function verify()
    {
        $model = D('user');

        $id = I('get.id');
        $isT = $model->where(['id'=>$id])->save(['defaul'=>'1']);

        if(($isT)){
            $data = [
                'id'        => $id,
                'addtime'   => date('Y-m-d H:i:s',time()),
                'sex'       => $model->where(['id'=>$id])->getField('sex'),
            ];
            $res = D('teacher')->add($data);
            if($res){
            $this->success('已通过审核',U("teaLst"));
            }
        }

        
    }



}