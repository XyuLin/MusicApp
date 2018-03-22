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

    // 发送验证码短信
    public function regSendCode()
    {
        $token = I('post.token');
        if($token == C('TOKEN')){
            if(IS_POST){
                $phone = I('post.phone');
                $code = I('post.code');
                $type = I('post.type');
                $model = D('user');
                if($type == 1){
                    $isT = $model->where(['phone'=>$phone])->find();
                    if($isT){
                        $msg = returnMsg(0,'手机号已被注册。');
                        $this->ajaxReturn($msg);
                    }
                }

                if($type == 2){
                    $isT = $model->where(['phone'=>$phone])->find();
                    if(!$isT){
                        $msg = returnMsg(0,'此手机号未被注册');
                        $this->ajaxReturn($msg);
                    }
                    
                }
                if($phone && $code){
                    $res = sendCode($phone,$code);
                    $msg = [
                        'code' => 1,
                        'msg'  => '发送成功',
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
                        'data' => [],
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
                        'data' => [$info],
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

    // 选择角色
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
            if(!$isT){
                    $data = [
                    'id'        => $id,
                    'addtime'   => date('Y-m-d H:i:s',time()),
                    'sex'       => $model->where(['id'=>$id])->getField('sex'),
                ];
                D('teacher')->add($data);
            }else{

            }

        }
        $msg = [
            'code' => 1,
            'msg'  => '角色选择成功',
            'data' => [$model->getInfo($id,1)],
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
                $isT = $model->where(['phone' => $phone])->find();
                if(!$isT){
                    $msg = returnMsg(0,'此号码未注册');
                    $this->ajaxReturn($msg);
                }
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
                        'data' => [$user],
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
                'data' => [],
            ];
            $this->ajaxReturn($msg);
        }

        $res = $model->where(['id'=>$post['id']])->save(['password'=>md5($post['epass'])]);

        if($res){
            $msg = [
                'code' => '1',
                'msg'  => '密码修改成功',
                'data' => [],
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
                $model = D('user');
                $isT = $model->where(['phone' => $tel])->find();
                if(!$isT){
                    $msg = returnMsg(0,'此号码未注册');
                    $this->ajaxReturn($msg);
                }
                $id = $model->where(['phone' => $tel])->save(['password'=>md5($pass)]);
                if($id != 0){
                    $data = array(
                        'code' => 1,
                        'msg' => '重置成功',
                        'data' => [],
                    );
                } else {
                    $data = array(
                        'code' => 0,
                        'msg' => '重置失败',
                        'data' => [],
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

            // 判断是否错误填写角色
            if($data['defaul']){
                unset($data['defaul']);
            }

            // 判断是否有头像上传
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
                        'msg'  => '上传错误',
                        'data' => [],
                    ];
                    $this->ajaxReturn($msg);
                } else {
                    $data['car'] = $info['car']['savepath'] . $info['car']['savename'];
                }
            }

            // 修改资料
            $ret_id = $model->where(['id'=>$data['id']])->save($data);

            if ($ret_id != false) {
                $data = array(
                    'code' => 1,
                    'msg' => '修改成功',
                    'data' => [],
                );
            } else {
                $data = array(
                    'code' => 0,
                    'msg' => '修改失败'.$this->error($model->getError()),
                    'data' => [],
                );
            }

            $this->ajaxReturn($data);
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
                    'msg'  => '上传失败 ',
                    'data' => [],
                ];
            } else {
                $msg = [
                    'code' => 0,
                    'msg'  => '上传成功',
                    'data' => [
                        'avatar' => $info['avatar']['savepath'] . $info['avatar']['savename'],
                    ],
                ];
                $this->ajaxReturn($msg);
            }
        }
    }

    /** 
    * 添加课程类别
    *@param subject_name str 课程名称  
    */ 
    public function addSubjectsType()
    {
        $model = D('subjectsType');
        $post = I('post.');
        $post['up_date'] = date('Y-m-d H:i:s',time());
        $model->create($post);
        $id = $model->add();
        if($id){
           $msg = returnMsg(1,'添加成功',$id);
        }else{
           $msg = returnMsg(0,'添加失败');
        }
        $this->ajaxReturn($msg);
    }

    /** 
    * 购买课程。生成的订单  (未完成)
    *@param id          用户id
    *@param user_token  用户token
    *@param subject_id  课程id
    */
    public function buyCoures()
    {
        // 验证用户是否登录
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        // 获取资料
        $post = I('post.');
        $post['stu_id'] = I('post.id');
        unset($post['id']);

        // 获取课程详情
        $subject = D('sendSubjects');
        $subjectInfo = $subject->getInfo($post['subject_id'],1);
        $post['class_hour_type'] = $subjectInfo['class_hour_type'];
        $post['class_hour_price'] = $subjectInfo['class_hour_price'];
        $num = upNumber($subject->class_hour_type[$subjectInfo['class_hour_type']]);
        $post['subject_total'] = $num * $post['class_hour_price'];

        $model = D('stuSubject');
        $id = $model->add($post);
        if($id){
            // 订单生成之后，支付

            // 支付成功。生成学生课程详情
            $student = D('students');
            $stu = [
                'stu_id' => $post['stu_id'],
                'subject_id' => $post['subject_id'],
                'total_hours' => $num,
            ];

            $res = $student->editData($stu);
            
            if($res){
                $msg = returnMsg(1,'购买成功');
                $this->ajaxReturn($msg);
            }  
        }
    }

    /** 
    * 我的课程(教师/学生) 
    *@param id str 用户id
    *@param user_token str 用户token  
    */ 
    public function mySubjects()
    {
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        $id = I('post.id');
        $teach = $user->isTeacher($id);   

        if($teach != false){
            $list = $user->subjectList($id,1);
        }else{
            $list = $user->subjectList($id,2);
        }
        $msg = returnMsg(1,'请求成功',$list);
        $this->ajaxReturn($msg);
    }


    // 学生上课详情
    public function stuInfo()
    {
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        $model = D('students');
        $post = I('post.');
        $info = $model->getInfo($post['stu_id'],$post['subject_id']);
        $msg = returnMsg(1,'请求成功',$info);
        $this->ajaxReturn($msg);
    }
}