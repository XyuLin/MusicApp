<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-16
 * Time: 14:16
 */
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller{
    /******************验证码**************/
    public function chkcode(){
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useNoise' => false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    public function avatar()
    {
        $this->display();
    }


    /******************登录**************/
    public function login(){
        if(IS_POST){
            $model = D('admin');
            if($model->validate($model->_login_validate)->create()){
                if($model->login()){
                    $this->success('登录成功！',U('Index/index'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->display();
    }



    /******************退出登录**************/
    public function log_out(){
        $model = D('Admin');
        $model->log_out();
        redirect('login');
    }

}