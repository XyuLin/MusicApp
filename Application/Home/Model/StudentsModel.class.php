<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 9:58
 */

namespace Home\Model;
use Think\Model;

class StudentsModel extends Model
{
	// 添加或修改用户课时数量
	public function editData($data)
	{
		// 查询是否购买相同的课程
		$where = [
			'stu_id' => $data['stu_id'],
			'subject_id' => $data['subject_id'],
		];
		$student = $this->where($where)->find();

		if(!empty($student)){
			// 学生购买课时
			if($data['total_hours']){
				$con['total_hours'] = $student['total_hours'] + $data['total_hours'];
				$res = $this->where($where)->save($con);
				return $res;
			}

			// 教师安排学生课时
			if($data['done_hours']){
				$con['done_hours'] =  $student['done_hours'] + $data['done_hours'];
				$res = $this->where($where)->save($con);
				return $res;
			}
		}

		$res = $this->add($data);
		return $res;

	}

	// 学生课时详情
	public function getInfo($stu_id,$subject_id,$type = '')
	{
		// 查询学生信息
		$info = $this->where(['stu_id'=>$stu_id,'subject_id'=>$subject_id])->find();

		if($type == ''){
			// 学生看老师
			$id = D('sendSubjects')->where(['id'=>$subject_id])->getField('teacher_id');
			$info['user'] = D('user')->getInfo($id,1);	
			$isC = D('comment')->where(['teacher_id'=>$id,'stu_id'=>$stu_id])->find();
			if($isC){
				$info['isComment'] = false;
			}else{
				$info['isComment'] = true;
			}		
		}else{
			// 老师看学生
			$info['user'] = D('user')->getInfo($stu_id,1);
		}

		$subject = D('stuSubject')->where(['stu_id'=>$stu_id,'subject_id'=>$subject_id])->find();

		$info['courseName'] = $subject['stu_order_title'];

		$info['pay_time'] = $subject['pay_time'];

		$info['total_name'] = $info['total_hours'] . '课时';

		$info['done_name'] = $info['done_hours'] . '课时';

		$info['surplus_name'] = $info['total_hours'] - $info['done_hours'] . '课时';
		
		return($info);
	}
}	
