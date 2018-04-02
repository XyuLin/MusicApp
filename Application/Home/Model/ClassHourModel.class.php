<?php


namespace Home\Model;
use Think\Model;
class ClassHourModel extends Model
{
	// 课程表
	public function returnForm($id,$week)
	{
		$subject = D('sendSubjects');

		if($week == '1'){
			$time = time();
		}else{
			$time = time()+604800;
		}

		$sunday =['1','2','3','4','5','6','7'];
		// 计算当前本周时间，与下周时间
		$date = $this->getTime($time); 
		$begin = $date['begin'];
		$end = $date['end'];
		// 计算本周课程表

		// 判断用户是否是教师
		$isTeacher = D('user')->isTeacher($id);
		if($isTeacher != false){
			// 查询老师发布的课程
			$idl = $subject->where(['teacher_id'=>$id])->getField('id',true);
			$con = [
				'subject_id' => ['in',$idl],
				'start_time' => ['eq',$begin],
				'end_time'	 => ['eq',$end]
			];

			foreach ($sunday as $k => $v) {
				# code...
				$con['sunday'] = $v;
				$beginW[$k] = $this->where($con)->order('sunday asc, node asc')->select();
				foreach ($beginW[$k] as $key => &$value) {
					# code...
					$value['courseName'] = $subject->where(['id'=>$value['subject_id']])->getField('course_name');
				}
			}			
		}else{
			$con = [
				'stu_id' 	 => ['eq',$id],
				'start_time' => ['eq',$begin],
				'end_time'	 => ['eq',$end],
			];

			foreach ($sunday as $k => $v) {
				# code...
				$con['sunday'] = $v;
				$beginW[$k] = $this->where($con)->order('sunday asc, node asc')->select();
				foreach ($beginW[$k] as $key => &$value) {
					# code...
					$value['courseName'] = $subject->where(['id'=>$value['subject_id']])->getField('course_name');
				}
			}
			
		}


		// V($beginW);die;
		if($beginW){
			$msg = returnMsg(1,'请求成功',$beginW);
			return $msg;
		}else{
			$msg = returnMsg(0,'暂时没有课时');
		}

	}



	public function formClass($id,$week)
	{
		// 获取时间
		// 获取本周下周时间

		if($week == '1'){
			$time = time();
		}else{
			$time = time()+604800;
		}

		$sunday =['1','2','3','4','5','6','7'];
		// 计算当前本周时间，与下周时间

		$date = $this->getTime($time); 
		$begin = $date['begin'];
		$end = $date['end'];

		$isTeacher = D('user')->isTeacher($id);

		if($isTeacher){
			// 查询老师发布的课程
			$idl = $subject->where(['teacher_id'=>$id])->getField('id',true);
			$con = [
				'subject_id' => ['in',$idl],
				'start_time' => ['eq',$begin],
				'end_time'	 => ['eq',$end]
			];

			foreach ($sunday as $k => $v) {
				# code...
				$con['sunday'] = $v;
				$beginW[$k] = $this->where($con)->order('sunday asc, node asc')->select();
				foreach ($beginW[$k] as $key => &$value) {
					# code...
					$value['courseName'] = $subject->where(['id'=>$value['subject_id']])->getField('course_name');
				}
			}			
		}else{
			$con = [
				'stu_id' 	 => ['eq',$id],
				'start_time' => ['eq',$begin],
				'end_time'	 => ['eq',$end],
			];

			foreach ($sunday as $k => $v) {
				# code...
				$con['sunday'] = $v;
				$beginW[$k] = $this->where($con)->order('sunday asc, node asc')->select();
				foreach ($beginW[$k] as $key => &$value) {
					# code...
					$value['courseName'] = $subject->where(['id'=>$value['subject_id']])->getField('course_name');
				}
			}
			
		}

		if($beginW){
			$msg = returnMsg(1,'请求成功',$beginW);
			return $msg;
		}else{
			$msg = returnMsg(0,'暂时没有课时');
		}

	}

	public function getTime($now)
	{
	   $time = ('1' == date('w')) ? strtotime('Monday', $now) : strtotime('last Monday', $now);  

	  //下面2句就是将上面得到的时间做一个起止转换

	  //得到本周开始的时间，时间格式为：yyyy-mm-dd hh:ii:ss 的格式
	  $date['begin'] = date('Y-m-d 00:00:00', $time);  

	  //得到本周末最后的时间
	  $date['end'] = date('Y-m-d 23:59:59', strtotime('Sunday', $now));

	  return $date;
	}
}