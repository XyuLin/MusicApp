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
        return return_json($data);
    }

    public function index()
    {
        $data = D('user')->find('1');
        V($data);
        return return_json($str);
    }
}
