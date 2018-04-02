<?php 

/**
* 
*/
namespace Admin\Model;
use Think\Model;
class SendSubjectsModel extends Model
{
	
	// 未审核课程
	public function noVerify($pageSize = '5')
	{
		$count = $this->count();
		$Page = new \Think\Page($count,$pageSize);
		$Page->setConfig('prev', '上一页');
		$Page->setConfig('next', '下一页');
		$list = $this->alias('a')->field('a.*,b.subject_name,c.name')->join('LEFT JOIN __SUBJECTS_TYPE__ b ON a.subjects_type = b.id')->join('LEFT JOIN __USER__ c ON a.teacher_id = c.id')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$page = $Page->show();
		return $data = [
		'info' => $list,
		'page' => $page,
		];
	}
}