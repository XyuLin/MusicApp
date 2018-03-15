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

    }
}
