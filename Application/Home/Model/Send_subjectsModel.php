<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 9:58
 */

namespace Home\Model;
use Think\Model;

class Send_subjectsModel extends Model
{

    /**
     *@param $stu_id /学生的id			  
     * @param $data  /返回学生的上课时间和上课内容 教学老师
     */
    public function get_stu_class($stu_id){
	    $model = D('stu_subject'); 
	    $send_model = D('send_subjects');
        if($stu_id){
		$data = $model->where(array('stu_id'=>array('eq',$stu_id)))->select();
		$sub_id = array();   
		
		foreach($data as $v){
			$stu_id[] = $v['subjects_id'];
		}
		$subjects_id = impload(',',$sub_id);    
		$data = $send_model->alias('a')->field('a.class_time,b.subject_name,c.name')->join('LEFT JOIN __SUBJECTS_TYPE__ b ON a.subjects_id = b.id LEFT JOIN __USER__ c ON b.teacher_id = c.id')->where(array('a.subjects_id'=>array('in',$subjects_id)))->select();
	}
	    return $data;
    }
}
