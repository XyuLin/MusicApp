<?php 
/**
* 
*/
namespace Home\Controller;
use Think\Controller;

class CircleController extends Controller
{
	
	public function __construct(argument)
	{
		# code...
		// 验证用户是否登录
       $code = D('user')->checkToken();
	    if($code['code'] == 2){
	        $this->ajaxReturn($code);
	    }
	}


	/**
	*@param user_id 
	*@param  
	**/
	// 发布圈子
	public function addCircle()
	{
		$data = I('post.');
		$data['user_id'] = $data['id'];
		unset($data['id']);
		$model = D('circle');

		$isT = $model->create($data);
	

	}

	// 收藏
	public function collectCircle()
	{

	}

	// 我的收藏
	public function myCollect()
	{

	}

	// 圈子列表。
	public function circleList()
	{

	}







}

 ?>