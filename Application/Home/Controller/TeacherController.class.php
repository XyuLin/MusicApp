<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 13:38
 */

namespace Home\Controller;
use Think\Controller;

class TeacherController extends Controller
{

    public function __construct()
    {
        $res = tokenValidate(); // 验证接口是否合理调用
        if($res != NULL){
            $this->ajaxReturn($res);
        }
    }


    // 我的课程
    public function mySubject($id)
    {
        $teach = D('user')->isTeacher($id);
        if($teach != false){
            $list['teacher'] = $teach;
            $subjects = D('sendSubjects');
            $list['classList'] = $subjects->returnClass($id);
            $this->ajaxReturn($list);
        }

    }

    // 添加课程
    public function addSubject()
    {
        $code = D('user')->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }
        $teach = D('user')->isTeacher($id);
        if($teach == false){
            $msg = returnMsg(0,'不是教师');
            $this->ajaxReturn($msg);
        }
        $id = I('post.id');
        if(!$id){
            $msg = returnMsg(0,'缺少用户id');
            $this->ajaxReturn($msg);
        }
        
        $data = I('post.');
        $model = D('sendSubjects');
        if($model->create($data)){
            $info = $model->add();
            if($info){
                $info = $model->getInfo($info);
                $this->ajaxReturn(1,'添加课程成功',$info);
            }
        }
    }

    public function info()
    {
        $model = D('sendSubjects');
        $ab = $model->getInfo(I('post.id'));
        $this->ajaxReturn($ab);
    }
}
