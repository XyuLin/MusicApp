<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 11:06
 */

namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{

    // public function reg()
    // {
    //     if (IS_POST) {
    //         $model = D('user');
    //         if ($model->create(I('post.'), 1)) {
    //             if ($id = $model->add()) {
    //                 if ($id) {
    //                     $data = $model->find($id);
    //                     if ($data) {
    //                         $data = array(
    //                             'code' => 1,
    //                             'msg' => '恭喜您，注册成功！',
    //                         );
    //                     } else {
    //                         $data = array(
    //                             'code' => 0,
    //                             'msg' => '注册失败'.$this->error($model->getError()),
    //                         );
    //                     }
    //                     return json_encode($data);
    //                 }
    //             }

    //         }
    //     }
    // }


    // 发送验证码短信
    public function regSendCode()
    {
        $token = I('post.token');
        if($token == C('TOKEN')){
            if(IS_POST){
                $phone = I('post.phone');
                $code = I('post.code');
                if($phone && $code){
                    $res = sendCode($phone,$code);
                    $msg = [
                        'code' => 1,
                        'msg'  => '发送成功'
                    ];
                    $this->ajaxReturn($msg);
                }
            }
        }else{
            $msg = [
                'code' => '4',
                'msg'  => '缺少ServerToken',
                'data' => [],
            ]; 
            $this->ajaxReturn($msg);
        }
    }

    // 注册用户
    public function reg()
    {
        $token = I('post.token');
        if($token == C('TOKEN')){
            if(IS_POST){
                $model = D('user');
                // 判断手机号是否存在
                $isReg = $model->where(['phone'=> I('post.phone')])->find();
                if(!empty($isReg)){
                    $data = [
                        'code' => '0',
                        'msg'  => '手机号已被注册。',
                    ];
                    $this->ajaxReturn($data);
                }

                // 添加用户
                $id = $model->add(I('post.')); 

                if($id){
                        // 创建token
                        $con['user_token'] = $model->createToken($id);
                        $con['expire_time'] = date('Y-m-d H:i:s',time()+86400);
                        $model->where(['id'=>$id])->save($con);
                        // 初始化个人信息
                        $info = $model->getInfo($id);
                        session($id ,$info);
                        $msg = [
                        'code' => 1,
                        'msg'  => '注册成功',
                        'data' => $info,
                        ];
                        $this->ajaxReturn($msg);
                    }           
            }
        }else{
             $msg = [
                'code' => '4',
                'msg'  => '缺少ServerToken',
                'data' => [],
            ]; 
            $this->ajaxReturn($msg);
        }
    }

    public function optRole()
    {
        $model = D('user');
        $code = $model->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }
        $id = I('post.id');
        $role = I('post.opt');
      
        $res = $model->where(['id'=>$id])->save(['defaul'=>$role]);
        if($role == 1){
            D('teacher')->add(['id'=>$id]);
        }
        $msg = [
            'code' => 1,
            'msg'  => '角色选择成功',
            'data' => $model->getInfo($id),
        ];
        $this->ajaxReturn($msg);
        
    }

    // 登录
    public function loginU()
    {
        $token = I('post.token');
        if($token == C('TOKEN')){
            if(IS_POST){        
                $pass = I('post.password');
                $phone = I('post.phone');
                $model = D('user');
                $user = $model->where(['phone' => $phone])->where(['password' => md5($pass)])->find();
                if($user){
                    // 创建token
                    $con['user_token'] = $model->createToken($user['id']);
                    $con['expire_time'] = date('Y-m-d H:i:s',time()+86400);
                    $is = $model->where(['id'=>$user['id']])->save($con);

                    // 重新初始化用户信息
                    $user = $model->getInfo($user['id']);
                    session('user',$user);
                    $msg = [
                        'code' => 1,
                        'msg'  => '登录成功',
                        'data' => $user,
                    ];
                    $this->ajaxReturn($msg);
                }else{
                    $msg = [
                        'code' => 0,
                        'msg'  => '密码错误',
                        'data' => [],
                    ];
                    $this->ajaxReturn($msg);
                }
            }
        }else{
             $msg = [
                'code' => '4',
                'msg'  => '缺少ServerToken',
                'data' => [],
            ]; 
            $this->ajaxReturn($msg);
        }
    }

    //修改密码
    public function editPass()
    {
        $model = D('user');
        $code = $model->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }
        $post = I('post.');
       

        // 校验密码
        $pass = $model->where(['id'=>$post['id']])->getField('password');

        if($pass != md5($post['password'])){
            $msg = [
                'code' => '0',
                'msg'  => '密码错误,修改失败',
            ];
            $this->ajaxReturn($msg);
        }

        $res = $model->where(['id'=>$post['id']])->save(['password'=>md5($post['epass'])]);

        if($res){
            $msg = [
                'code' => '1',
                'msg'  => '密码修改成功',
            ];
            $this->ajaxReturn($msg);
        }
    }

    //重置密码
    public function resetPass()
    {
        $token = I('post.token');
        if($token == C('TOKEN')){
            if(IS_POST){
                $pass = I('post.password');
                $tel = I('post.phone');
                $id = D('user')->where(['phone' => $tel])->save(['password'=>md5($pass)]);
                if($id != 0){
                    $data = array(
                        'code' => 1,
                        'msg' => '重置成功',
                    );
                } else {
                    $data = array(
                        'code' => 0,
                        'msg' => '重置失败',
                    );
                }
                $this->ajaxReturn($data);
            }
        }else{
             $msg = [
                'code' => '4',
                'msg'  => '缺少ServerToken',
                'data' => [],
            ]; 
            $this->ajaxReturn($msg);
        }
    }

    /**
     * 更新个人信息
     * @param $id  /接收的登录用户id
     */
    public function edit()
    {
        // 检测user_token
        $model = D('user');
        $code = $model->checkToken();

        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        if(IS_POST){
            
            $data = I('post.');
            if($model->create($data)){

                if ($_FILES['car']) {
                    $upload = new \Think\Upload();
                    $upload->maxSize = 3145728;
                    $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                    $upload->rootPath = './Public/Upload/';
                    $upload->savePath = 'userImages/';
                    $info = $upload->upload();

                    if (!$info) {
                        $msg = [
                            'code' => 0,
                            'msg'  => $upload->getError(),
                            'data' => [],
                        ];
                        $this->ajaxReturn($msg);
                    } else {
                        $data['car'] = $info['car']['savepath'] . $info['car']['savename'];
                    }
                }

                $ret_id = $model->where(['id'=>$data['id']])->save();
                if ($ret_id != false) {
                    $data = array(
                        'code' => 1,
                        'msg' => '修改成功',
                    );
                } else {
                    $data = array(
                        'code' => 0,
                        'msg' => '修改失败'.$this->error($model->getError()),
                    );
                }
                $this->ajaxReturn($data);
            }  
        }    
    }


    public function avatar()
    {
        if ($_FILES['avatar']['error'] == 0) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'userImages/';
            $info = $upload->upload();

            if (!$info) {
                $msg = [
                    'code' => 0,
                    'msg'  => $upload->getError(),
                    'data' => [],
                ];
            } else {
                $msg = [
                    'code' => 0,
                    'msg'  => $upload->getError(),
                    'data' => [
                        'avatar' => $info['avatar']['savepath'] . $info['avatar']['savename'],
                    ],
                ];
                $this->ajaxReturn($msg);
            }
        }
    }


}