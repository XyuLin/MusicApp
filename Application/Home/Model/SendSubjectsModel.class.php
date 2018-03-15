<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 9:58
 */

namespace Home\Model;
use Think\Model;

class SendSubjectsModel extends Model
{
    // 价格类型
    protected $price = [1=>'0-500','500-1000','1000-1500','1500-2000','2000-2500','2500-3000','3000'];
    // 教龄选择
    protected $educa = [1=>'0-1','1-2','2-3','3-4','4'];
    // 性别选择
    protected $sex   = [1=>'男','女'];
    // 课时类型
    protected $class_hour_type = ['12课时起售','24课时起售'];
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

    /**
    *@param $screen  //数组筛选条件 
    *@param $data // 返回筛选的课程列表
    */
    public function getClassList($screen = '')
    {
    	// 判断是否选择乐器类型
    	if($screen['type']){
    		$where['subjects_id'] = ['eq',$screen['type']];
    	}
		
		// 判断是否选择价格类型
		if($screen['price']){
			$price = $this->price[$screen['price']];
			if($screen['price'] == 7){
				$where['class_hour_price'] = ['gt',$price];
			}else{
				$in = explode('-',$price);
    			$where['class_hour_price'] = [['EGT',$in[0]],['ELT',$in[1]]];
			}
		}    		
  	
  		// 判断是否选择教龄年限
  		if($screen['educa']){
  			$educa = $this->educa[$screen['educa']];
			if($screen['price'] == 5){
				$wher['education_age'] = ['gt',$educa];
			}else{
				$ing = explode('-',$educa);
    			$wher['education_age'] = [['EGT',$ing[0]],['ELT',$ing[1]]];
			}
  		}

  		// 判断是否选择性别类型
  		if($screen['sex']){
  			$wher['sex'] = ['eq',$screen['sex']];
  		}

  		// 筛选满足条件的老师
  		if($wher['sex'] || $wher['education_age']){
  			$teach = D('teacher');
  			$list = $teach->where($wher)->getField('id',true);
  		}

  		// 筛选满足条件老师的课程
  		if($where['subjects_id'] || $where['class_hour_price']){
  			if($list){
  				$where['teacher_id'] = ['in',$list];
  			}
  			$data = $this->where($where)->select();
  		}else{
  			if($list){
  				$where['teacher_id'] = ['in',$list];
  				$data = $this->where($where)->select();
  			}else{
  				// 如何没有筛选 按最新课程排序所有课程
  				$data = $this->order('class_time desc')->select();
  			}
  			
  		}
  		return $data;
  	
    }

    // 教师发布的所有课程
    public function returnClass($id)
    {
    	$data = $this->where(['teacher_id' => $id])->select();

      foreach($data as $k => &$v){
          $v['class_hour_type'] = $this->class_hour_type[$v['class_hour_type']];
      }
    	return $data;
    }

}
