<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-27
 * Time: 9:08
 */

namespace Home\Controller;
use Think\Controller;

class Send_subjectsController extends Controller{

    //获得老师发布的课程表

    /**
     * @param $id    /当前登录的用户id
     * @return mixed /获得上课时间 上课的内容 老师的name值
     */
    public function teac_class_lst($id){                //接收老师登录后传过来的id
        $model = D('send_subjects');
        $data = $model->alias('a')
            ->field('a.*,b.subject_name,c.name')
            ->join('LEFT JOIN __SUBJECTS_TYPE__ b ON a.subjects_id = b.id
             LEFT JOIN __USER__ c ON a.teacher_id = c.id')
            ->where(array('teacher_id'=>array('eq',$id)))
            ->select();
        return return_json($data);
    }


    /******************学生的上课信息时间*****************/
    public function show_stu_class(){
        $stu_id = I('get.id');
        $model = D('send_subjects');
        $data = $model->get_stu_class($stu_id);
        return return_json($data);
    }

}