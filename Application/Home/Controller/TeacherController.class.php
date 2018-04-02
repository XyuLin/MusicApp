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
     * @param subjects_type 课程类型id
     * @param user_token  用户token
     * @param class_desc  课程备注
     * @param class_hour_price 课时价格 
     * @param course_name  课程名称
     */ 
    public function addSubject()
    {  
        $id = I('post.id');
        $user = D('user');
        $teach = $user->isTeacher($id);
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
        $data['create_time'] = date('Y-m-d H:i:s',time());
        $model = D('sendSubjects');
        if($model->create($data)){
            $info = $model->add();
            if($info){
                // 添加已拥有课程
                $user->haveCourse($data['teacher_id'],'1',$data['subjects_type']);
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
            $subjects = D('sendSubjects');
            $list = $subjects->where(['teacher_id'=>$id])->order('id desc')->limit('3')->getField('id',true);
                foreach ($list as &$v) {
                    # code...
                    $v = $subjects->getInfo($v,1);
                }
            // 去除相同的值
            $model = D('subjectsType');
            foreach($data as $key => &$value){
                $value = $model->where(['id'=>$value])->getField('subject_name');
            }

            $info['user'] = $userInfo;
            $info['subjectList'] = $list;

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
            // 判断用户是选择本周还是下周
            $time = $model->getTime($post['up_time']);

            V($time);die;

            unset($post['id']);
            // 判断用户是否已经存在课时
            $data['start_time'] = $time['begin'];
            $data['end_time'] = $time['end'];
            $data['up_time'] = date('Y-m-d H:i:s',$post['up_time']);
            $data['down_time'] = date('Y-m-d H:i:s',$post['up_time'] + 2700);
            $data['remind'] = $post['remind'];
            $data['stu_id'] = $post['stu_id'];
            $data['desc'] = $post['desc'];
            $data['subject_id'] = $post['subject_id'];

            // 计算 上课时间为星期几
            $data['sunday'] = date('w',strtotime($data['up_time'])) + 1 ;

            $isTrue = $model->field('up_time,down_time')->order('up_time desc')->where([
                    'start_time' =>  $data['start_time'],
                    'end_time'   =>  $data['end_time'],
                    'sunday'     =>  $data['sunday'],
            ])->select();
            V($data);
            V($isTrue);die;
            if(!empty($isTrue)){

                foreach($isTrue as $k => $t){
                   $isT[$k] =  $this->is_time_cross($data['up_time'],$data['down_time'],$t['up_time'],$t['down_time']);
                }

                V($isT);die;
                if(in_array(false,$isT)){
                    $msg = returnMsg(0,'此课时已经安排课程');
                    $this->ajaxReturn($msg);
                }
            }

            $id = $model->add($data);

            if($id){
                 // 安排课时后，减去学生总课时时间。
                $student = D('students');
                $studentData = [
                    'stu_id' => $data['stu_id'],
                    'subject_id' => $data['subject_id'],
                    'done_hours' => 1,
                ];
                $res = $student->editData($studentData);

                if($res){
                    $msg = returnMsg(1,'课时添加成功');
                }else{
                    $msg = returnMsg(0,'课时添加失败');
                }
            } 
            $this->ajaxReturn($msg);
        }
    }

    public function delClassHour()
    {
        $model = D('classHour');
        // 判断当前课时
        $id = I('post.class_id');
        $teacher_id = I('post.id');
        $subject_id = I('post.subject_id');
        $info = $model->find($id);

        if(!$info){
            $msg = returnMsg(0,'没有此课时');
        }else{
            $subInfo = D('sendSubjects');
            $isTrue = $subInfo->where(['id'=>$info['subject_id'],'teacher_id'=>$teacher_id])->find();
            if($isTrue){
                $res = $model->delete($id);
                if($res){
                    $msg = returnMsg(1,'删除成功');
                }else{
                    $msg = returnMsg(0,'删除失败');
                }
            }
        }
        
        $this->ajaxReturn($msg);
    }

    // 重新安排课时
    public function editClassHour()
    {
        // 获取当前id的课时信息
        $id = I('post.hour_id');

        $model = D('classHour');
        $now = $model->find($id);
        
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
            if(!I('post.page')){
                $page = '1';
            }else{
                $page = I('post.page');
            }
            //  查询购买的学生
            $data =$model->where($where)->limit(10)->page($page)->getField('stu_id',true);
            $data = array_unique($data);
            $user = D('user');
            foreach($data as &$v){
                $list[] = $user->getInfo($v);
            }
            $msg = returnMsg(1,'请求成功',$list);
            $this->ajaxReturn($msg);
        }
    }

    public function abcc()
    {
        echo time();
    }

    function is_time_cross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '') {
        $beginTime1 = strtotime($beginTime1);
        $endTime1 = strtotime($endTime1);
        $beginTime2 = strtotime($beginTime2);
        $endTime2 = strtotime($endTime2);

      $status = $beginTime2 - $beginTime1;

      if ($status > 0) {
        $status2 = $beginTime2 - $endTime1;
        if ($status2 >= 0) {
          return false;
        } else {
          return true;
        }
      } else {
        $status2 = $endTime2 - $beginTime1;
        if ($status2 > 0) {
          return true;
        } else {
          return false;
        }
      }
    }


}
