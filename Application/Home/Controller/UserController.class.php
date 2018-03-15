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
    public function regSendCode($tel,$code,$token = "sdfadf644849449aefasdf")
    {
        if($token == C('TOKEN')){
            if(IS_POST){
                $tel = I('post.tel');
                $code = I('post.code');
                if($tel && $code){
                    sendCode($tel,$code);
                }
            }
        }else{
            return ; 
        }
    }

    // 注册用户
    public function reg($token = "sdfadf644849449aefasdf")
    {
        if($token == C('TOKEN')){
            if(IS_POST){
                $model = D('user');
                if(I('post.defaul') == 1){
                    $id = $model->add(I('post.'));
                    $teacher['id'] = $id;
                    $teacher['addtime'] = date('Y-m-d H:i:s',time());
                    D('teacher')->add($teacher);
                }   
                if($id){
                        $info = $model->getInfo($id);
                        session('user',$info);
                        $this->ajaxReturn($info);
                    }           
            }
        }else{
            return ;
        }
    }

    // 登录
    public function loginU($token = "sdfadf644849449aefasdf")
    {
        if($token == C('TOKEN')){
            if(IS_POST){        
                $pass = I('post.password');
                $phone = I('post.phone');
                $user = D('user')->where(['phone' => $phone])->where(['password' => md5($pass)])->find();
                if($user){
                    session('user',$user);
                    $this->ajaxReturn($user);
                }
            }
        }else{
            return ;
        }
    }

    //重置密码
    public function resetPass($token = "sdfadf644849449aefasdf")
    {
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
        }
    }

    /**
     * 更新个人信息
     * @param $id  /接收的登录用户id
     */
    public function edit($token = "sdfadf644849449aefasdf"){
        if($token == C('TOKEN')){
            if(IS_POST){
                $model = D('user');
                $data = I('post.');
                if($model->create($data)){
                    $ret_id = $model->save();
                    if ($ret_id != 0) {
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
                $this->error = $upload->getError();
                return false;
            } else {
                $avatar = $info['avatar']['savepath'] . $info['avatar']['savename'];
                $this->ajaxReturn($avatar);
            }
        }
    }

}