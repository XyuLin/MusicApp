<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 9:58
 */

namespace Home\Model;
use Think\Model;

class StuSubjectModel extends Model
{

    // 获取学生购买的课程
  //   public function getStuSubjects($id)
  //   {
		// $list = $this->where(['stu_id'=>$id])->getField('subjects_id',true);

		// $model = D('sendSubjects');
		// foreach($list as $k => &$v){
		// 	$v = $model->get_stu_class($v);
		// }
		// return($list);

  //   }


	// 创建购买课程订单
	public function createOrder($data)
	{

        // 生成 支付关键参数 商品标题 商品唯一订单号 

        $orderData = [
            'stu_id'            => $data['stu_id'],
            'subject_id'        => $data['subject_id'],
            'teach_mode'        => '1',
            'stu_order_id'      => $this->orderNumber(),
            'stu_order_title'   => $data['course_name'],
            'subject_total'     => $data['subject_total'],
            'pay_status'        => '0',
            'create_time'       => date('Y-m-d H:i:s',time()),
        ];


        $id = $this->add($orderData);
        if($id){
        	return $orderData;
        }else{
        	return false;
        }
	}


	public function OrderNumber()
	{
		$danhao = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
		return $danhao;
	}

	public function orderInfo($id)
	{
        $info = $this->find($id);
        $info['subject_id'] = D('sendSubjects')->getInfo($info['subject_id'],1);
        $info['stu_id'] = D('user')->getInfo($info['stu_id']);

        return $info;
	}
}   
