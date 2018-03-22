<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 13:38
 */

namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{
    /**
     * @return mixed /获得老师的个人信息 可根据name搜索
     */
	public function showTeach(){
	$name = I('get.name');	
    V($name);			#老师的名字
	$education_age = I('get.education_age');        #老师的教龄
        $model = D('user');
        $data = $model->getTeach($name);
        $this->ajaxReturn($data);
    }

    public function index()
    {
        $data = [];
        // 课程数据
        $model = D('sendSubjects');
        // 获取筛选条件
        $screen = I('post.');
        
          // 判断是否选择页码
          if(I('post.page')){
            $page = I('post.page');
          }else{
            $page = 1;
          }
        //筛选条件
        $type = D('subjectsType');
        $list = [
            'type'  => $type->getType(1),
            'range' => $type->getType(2),
            'price' => $type->getType(3),
            'educa' => $type->getType(4),
            'sex'   => $type->getType(5),
        ];

        // $data['screenList'] = $list;

        // V($data);die;
        // banner 数据
        // $data['banner'] = D('banner')->getInfo();

        $data['list'] = $model->getClassList($screen,$page);

        $msg = [
            'code' => '1',
            'msg'  => '请求成功',
            'data' => [$data],
        ];
        $this->ajaxReturn($msg);
    }

    public function indexSearch()
    {
        $str = I('post.str');

    }

    // 课程详情
    public function subjectInfo()
    {
        $id = I('post.id');
        $model = D('sendSubjects');
        $info = $model->getInfo($id,1);
        $msg = returnMsg(1,'请求成功',$info);
        $this->ajaxReturn($msg);
    }

}
