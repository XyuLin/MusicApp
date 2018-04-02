<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-18
 * Time: 10:00
 */

namespace Home\Model;
use Think\Model;

class BannerModel extends Model{

	public function getInfo($id = '')
	{
		if($id != ''){
			$data = $this->where(['id'=>$id])->find();
			return $data;
		}else{
			$data = $this->order('sort desc')->select();

			foreach($data as &$v){
				$v['img'] = 'http://49.4.70.109/yjj/public/upload/' . $v['img'];
			}
			return $data;
		}
	}
}