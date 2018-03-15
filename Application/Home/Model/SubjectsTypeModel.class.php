<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 9:58
 */

namespace Home\Model;
use Think\Model;

class SubjectsTypeModel extends Model
{
	// 获取课程类型列表
    public function getType($id = '')
    {
    	if($id != ''){
    		$data = $this->field('id,subject_name')->where(['id'=>$id])->find();
    	}else{
    		$data = $this->field('id,subject_name')->select();
    	}
        return $data;
    }
}
