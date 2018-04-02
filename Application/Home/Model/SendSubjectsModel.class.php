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
    // 课时类型
    public $class_hour_type = [1=>'12课时起售','24课时起售'];
    // 科目时长
    public $class_time_long = [1=>'45分钟','50分钟','55分钟'];
    // 教学方式
    // protected $teach_mode = ['教师上门','学生上门'];

    /**
    *@param $screen  //数组筛选条件 
    *@param $data // 返回筛选的课程列表
    */
    public function getClassList($screen = '',$page)
    {
      $typesModel = D('subjectsType');
    	// 判断是否选择乐器类型
    	if($screen['type'] && $screen['type'] != 0){
    		$where['subjects_type'] = ['eq',$screen['type']];
    	}
		
    	// 判断是否选择价格类型
  		if($screen['price'] && $screen['price'] != 0){

  			$price = $typesModel->getType('3',$screen['price']);
  			if($screen['price'] == 18){
  				$where['class_hour_price'] = ['gt',upNumber($price['subject_name'])];
  			}else{
  				$in = explode('-',$price['subject_name']);
      			$where['class_hour_price'] = [['EGT',upNumber($in[0])],['ELT',upNumber($in[1])]];
  			}
  		}    		
  	
  		// 判断是否选择教龄年限
  		if($screen['educa'] && $screen['educa'] != 0){
    			$educa = $typesModel->getType('4',$screen['educa']);
  			if($screen['educa'] == 23){
  				$wher['education_age'] = ['gt',upNumber($educa['subject_name'])];
  			}else{
  				$ing = explode('-',$educa['subject_name']); 
      			$wher['education_age'] = [['EGT',upNumber($ing[0])],['ELT',upNumber($ing[1])]];
  			}
  		}

  		// 判断是否选择性别类型
  		if($screen['sex']){
  			$wher['sex'] = ['eq',$screen['sex']];
  		}

    		// 筛选满足条件的老师
  		if($wher['sex'] || $wher['education_age']){
  			$teach = D('teacher');
        // 获取老师id列表
  			$list = $teach->distinct(true)->where($wher)->getField('id',true);
        // V($wher);die;
        if(!$list){
          return [];
        }
  	  }


  		// 筛选满足条件老师
  		if($where['subjects_type'] || $where['class_hour_price']){
  			if($list){
  				$where['teacher_id'] = ['in',$list];
          $where['status'] = ['eq','1'];
  			}

  			$data = $this->distinct(true)->where($where)->order('id desc')->limit(10)->page($page)->getField('teacher_id',true);


        if(empty($data)){
             return [];
        }
  		}else{
  			if($list){
  				$where['teacher_id'] = ['in',$list];
          $where['status'] = ['eq','1'];
  				$data = $this->distinct(true)->where($where)->order('id desc')->limit(10)->page($page)->getField('teacher_id',true);

          if(empty($data)){
             return [];
          }
          // 不为空，满足条件
  			}else{
  				// 如何没有筛选 按最新课程排序所有课程
  				$data = $this->distinct(true)->where(['status'=>'1'])->order('id desc')->limit(10)->page($page)->getField('teacher_id',true);
  			}
  		}
      // V($data);die;
      $user = D('user');
      foreach($data as &$v){
        $dataList[] = $user->getInfo($v);
      }
  		return $dataList;
    }

    //搜索课程或老师
    public function search($str)
    {

    } 

    // 教师发布的所有课程
    public function returnClass($id,$type = '')
    {
      $haveModel = D('haveCourse');
      $list = $haveModel->distinct(true)->where(['user_id'=>$id])->getField('subject_type',true);
      
      if($type != ''){
          $model = D('stuSubject');
          $idlist = $model->distinct(true)->where(['stu_id'=>$id])->getField('subject_id',true);
          if(!$idlist){
              $msg = returnMsg(0,'暂时没有购买过课程');
              return $msg;
          }

          foreach($list as $key => $value){
              $data[$key]['type'] = $value;

              foreach($idlist as $v){
                $info = $this->getInfo($v,1);

                if($info['subjects_type'] == $value){
                  $data[$key]['typeName'] = $info['subjects_name'];
                  $data[$key]['list'][] = $info;
                }
              }
          }
          // V($data);die;
          $msg = returnMsg(1,'请求成功',$data);
          return $msg;  
      }

      if(!$list){
          $msg = returnMsg(0,'暂时没有发布过课程');
          return $msg;
      }

      foreach($list as $k => $v){
        $idlist = $this->where(['teacher_id'=>$id,'subjects_type'=>$v,'status'=>'1'])->getField('id',true);

        if(empty($idlist)){
          $msg = returnMsg(0,'暂时没有课程,如果您已发布课程,请等待后台审核通过');
          return $msg;
        }
          $info[$k]['type'] = $v; 

          foreach($idlist as $key => $value){
            $inf = $this->getInfo($value,1);
            if($inf['subjects_type'] == $v){
              $info[$k]['typeName'] = $inf['subjects_name'];
              $info[$k]['list'][] = $inf;
            }
          }
      }
       // V($info);die;
      $msg = returnMsg(1,'请求成功',$info);
      return $msg; 
    }

      /**
     *@param $stu_id /学生的id       
     * @param $data  /返回学生的上课时间和上课内容 教学老师
     */
    public function get_stu_class($stu_id){
      $model = D('stu_subject'); 
      $send_model = D('send_subjects');
        if($stu_id){
          $data = $model->where(['stu_id'=>$stu_id])->select();
         //  V($data);die;
          foreach($data as &$v){
            $v = $this->getInfo($v['subjects_id'],1);
          }
      }
      return $data;
    }


    // 获取课程详情
    public function getInfo($id,$type = '')
    {
      $info = $this->find($id);
      // 课程名字
      $subject_name = D('subjectsType')->getType(1,$info['subjects_type']);
      
      $info['subjects_name'] = $subject_name['subject_name'];
      // 教学方式
      // $info['teach_mode'] = $this->teach_mode[$info['teach_mode']];
      // 科目时长
      $info['time_long_Name'] = $this->class_time_long[$info['class_time_long']];
      // 课时价格
      $info['hour_price_Name'] = $info['class_hour_price'] . '元';
      // 课时类型
      $info['hour_type_Name']  = upNumber($this->class_hour_type[$info['class_hour_type']]);

      if($type == ''){
        // 教师信息
        $info['teacherInfo'] = D('user')->getInfo($info['teacher_id']);
      }
     
      return $info;
    }
}
