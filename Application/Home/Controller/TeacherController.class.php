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
        $code = D('user')->checkToken();
            if($code['code'] == 2){
                $this->ajaxReturn($code);
            }
    }

    /**
     * 发布课程
     * @param id   用户id
     * @param subjects_id 课程类型id
     * @param user_token  用户token
     * @param class_desc  课程备注
     * @param class_hour_price 课时价格 
     * @param course_name  课程名称
     */ 
    public function addSubject()
    {  
        $id = I('post.id');

        $teach = D('user')->isTeacher($id);
        if($teach == false){
            $msg = returnMsg(0,'用户不是教师');
            $this->ajaxReturn($msg);
        }

        if(!$id){
            $msg = returnMsg(0,'缺少用户id');
            $this->ajaxReturn($msg);
        }
        
        $data = I('post.');

        // 课程包含多少课时，目前写死
        if(empty($data['class_hour_type'])){
            $data['class_hour_type'] = '1';
        }

        // 每课时多长时间，目前写死 
        if(empty($data['class_time_long'])){
            $data['class_time_long'] = '1';
        }
        $data['teacher_id'] = $id;
        unset($data['id']);
        $model = D('sendSubjects');
        if($model->create($data)){
            $info = $model->add();
            if($info){
                // $info = $model->getInfo($info);
                $msg = returnMsg(1,'添加课程成功');
                $this->ajaxReturn($msg);
            }
        }
    }

    /**
    *@param teacher_id 教师id
    */
    // 教师详情
    public function teacherInfo()
    {
        $user = D('user');
        if($_POST){
            $id = I('post.teacher_id');
            $userInfo = $user->getInfo($id);
            $subjects = $user->subjectList($id,1);

            $typeList = [];
            foreach($subjects as $k => $v){
                $typeList[] = $v['subjects_type'];
            }

            // 去除相同的值
            $data = array_unique($typeList);
            $model = D('subjectsType');
            foreach($data as $key => &$value){
                $value = $model->where(['id'=>$value])->getField('subject_name');
            }

            $info['user'] = $userInfo;
            $info['user']['subjectsType'] = $data;
            $info['subjectList'] = $subjects;

            $msg = returnMSg(1,'请求成功',$info);
            $this->ajaxReturn($msg);
        }
        
    }

    /**
    *   添加课时  排课
    *@param 
    **/ 
    public function addClassHour()
    {
        if(IS_POST){
            $post = I('post.');
            // 查询判断此课程是否存在
            $sendModle = D('sendSubjects');
            $isTrue = $sendModle->where(['id'=>$post['subject_id']])->find();
            if(empty($isTrue)){
                $msg = returnMsg(0,'没有此课程');
                $this->ajaxReturn($msg);
            }
            $model = D('classHour');
            // 查询用户上次添加课时时间，判断用户是否符合规定
            $lastTime = $model->where(['subject_id'=>$post['subject_id']])->order('id desc')->getField('end_time');
            $post['end_time'] = date('Y-m-d H:i:s', $post['start_time'] + 2700); 
            $post['start_time'] = date('Y-m-d H:i:s',$post['start_time']);
            if(!empty($lastTime)){
                // 判断用户添加时间是否重复
                if($post['start_time'] < $lastTime){
                    $msg = returnMsg(0,"请在". $lastTime ."后添加课时");
                    $this->ajaxReturn($msg);
                }

                // 判断用户添加时间是否大于最大时间
                $bigTime = date("Y-m-d H:i:s",time() + 1209600) ;
                if($post['start_time'] > $bigTime){
                    $msg = returnMsg(0,"请在". $bigTime ."之前添加课时");
                    $this->ajaxReturn($msg);
                }
            }
            unset($post['id']);
            $id = $model->add($post);
            if($id){
                // 安排课时后，减去学生总课时时间。
                $student = D('students');
                $studentData = [
                    'stu_id' => $post['stu_id'],
                    'subject_id' => $post['subject_id'],
                    'done_hours' => 1,
                ];
                $res = $student->editData($studentData);

                if($res){
                    $msg = returnMsg(1,'课时添加成功');
                }
            }else{
                $msg = returnMsg(0,'课时添加失败');
            }
            
            $this->ajaxReturn($msg);
        }
    }

    /**
    *user_token 验证 id user_token *
    *@param subject_id  课程id
    **/
    // 购买该课程的学生列表
    public function buySubjectList()
    {
        $model = D('stuSubject');
        if(IS_POST){
            $where = [
                'subject_id' => I('post.subject_id'),
                'pay_status' => '1',
            ];
            //  查询购买的学生
            $data =$model->where($where)->getField('stu_id',true);
            $data = array_unique($data);
            $user = D('user');
            foreach($data as &$v){
                $list[] = $user->getInfo($v);
            }
            $msg = returnMsg(1,'请求成功',$list);
            $this->ajaxReturn($msg);
        }
    }


    public function abc()
    {
        echo time();
    }
}
