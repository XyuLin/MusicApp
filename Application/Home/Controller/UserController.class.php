<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 11:06
 */

namespace Home\Controller;
use Think\Controller;
use Org\Util\Wxpay;
use Org\Util\Alipay;

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
                        $con['expire_time'] = date('Y-m-d H:i:s',time()+1209600);
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

        $isT = $model->isTeacher($id);
        if($isT){
            $msg = returnMsg(0,'用户已是教师');
            $this->ajaxReturn($msg);
        }
      
        $res = $model->where(['id'=>$id])->save(['defaul'=>$role]);

        if($res){
             $msg = [
            'code' => 1,
            'msg'  => '角色选择成功',
            'data' => [$model->getInfo($id,1)],
            ];
            $this->ajaxReturn($msg);
        }else{
            $msg = returnMsg(0,'角色选择失败');
            $this->ajaxReturn($msg);
        }  
        
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
                    'code' => 1,
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

        // 获取信息，创建订单
        $post = I('post.');
        $post['stu_id'] = I('post.id');
        unset($post['id']);
        // 获取课程详情
        $subject = D('sendSubjects');
        $subjectInfo = $subject->getInfo($post['subject_id'],1);
        if($subjectInfo['status'] != 1 ){
            $msg = returnMsg(0,'此课程暂未通过审核');
            $this->ajaxReturn($msg);
        }
        // 计算总价
        $bottom = upNumber($subject->class_hour_type[$subjectInfo['class_hour_type']]);
        $num = $post['num'] * $bottom;
        $post['subject_total'] = $num * $subjectInfo['class_hour_price'];
        $post['course_name'] = $subjectInfo['course_name'];
        $model = D('stuSubject');
        $order = $model->createOrder($post);

        if($order){
            $msg = returnMsg(1,'订单创建成功');
            $this->ajaxReturn($msg);
        }else{
            $msg = returnMsg(0,'订单创建失败');
            $this->ajaxReturn($msg);
        }

    }

    // 
    public function prePay()
    {
        // 验证用户是否登录
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        $model = D('stuSubject');
        $type = I('post.type');
        // V($type);die;
        $info = $model->where(['id'=>I('post.order'),'stu_id'=>I('post.id')])->find();

        // 判断订单状态，不可支付状态返回
        if($info['pay_status'] != '0'){
            if($info['pay_status'] == '2'){
                $msg = returnMsg(0,'该订单已被取消');
            }
            if($info['pay_status' == '1']){
                $msg = returnMsg(0,'该订单已支付');
            }
            $this->ajaxReturn($msg);
        }


            $body = '艺家教——'. $info['stu_order_title'] . '购买';
            $out_trade_no = $info['stu_order_id'];
            $total_fee = $info['subject_total'];
            $title = $info['stu_order_id'];
        // 判断支付方式
        if($type == 'wx'){
            $wx = new Wxpay;
            $data['trade_type'] = 'APP';
            $response = $wx->getPrePayOrder($body,$out_trade_no,$total_fee);
            $this->ajaxReturn($response);
        }

        if($type == 'ali'){
            $ali = new Alipay;
            $response = $ali->orderNext($body,$out_trade_no,$total_fee,$title);
            $this->ajaxReturn($response);
        }
    }

    // 
    public function orderEnd()
    {
        // 获取返回信息
        $post = I('post.');

        // 修改订单状态。
        // 支付成功。生成学生课程详情
        $student = D('students');
        $stu = [
            'stu_id' => $post['stu_id'],
            'subject_id' => $post['subject_id'],
            'total_hours' => $post['num'] * 15,
        ];  

        $res = $student->editData($stu);
        // 创建我的课程分类。
        $subInfo = D('sendSubjects')->where(['id'=>$subject_id])->find();
        $user = D('user');
        if($res){
             $user->haveCourse($post['stu_id'],2,$subInfo['subjects_type']);
         }

        // 提示成功
        return 'SUCCESS';
    }

    public function abcd()
    {
        $user = D('user');
        $user->haveCourse('14','2','1');
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
        $model = D('sendSubjects');
        if($teach != false){
            $list = $model->returnClass($id);
        }else{
            $list = $model->returnClass($id,2);
        }
        $this->ajaxReturn($list);
    }


    // 学生上课详情
    public function stuInfo()
    {
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }
        $id = I('post.id');
        $teach = $user->isTeacher($id);
        $post = I('post.');
        $model = D('students');
        if($teach != false){
            // 教师
            $info = $model->getInfo($post['stu_id'],$post['subject_id'],1);
        }else{
            $info = $model->getInfo($post['stu_id'],$post['subject_id']);
        }

       
        $msg = returnMsg(1,'请求成功',$info);
        $this->ajaxReturn($msg);
    }

    public function stuComment()
    {
        // 用户验证
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }
        // 获取评论数据 comment
        // 获取所评论课程id subject_id
        // 获取教师id  teacher_id
        // 获取用户id id
        // 获取评论类型 好  中  差 type 1 2 3 

        // 判断用户是否购买此课程，并且以上课时
        $classHour = D('classHour');
        $post = I('post.');
        $isTrue = $classHour->where(['stu_id'=>$post['id'],'subject_id'=>$post['subject_id']])->find();
        // 判断用户是否已经评论
        $isC = D('comment')->where(['stu_id'=>$post['id'],'subject_id'=>$post['subject_id'],'teacher_id'=>$post['teacher_id']])->find();

        if($isC){
            $msg = returnMsg(0,'用户已评论过该课程');
            $this->ajaxReturn($msg);
        }
        if($isTrue){
            $model = D('comment');
            $post['user_id'] = $post['id'];
            unset($post['id']);
            $post['comment_time'] = date('Y-m-d H:i:s',time());
            $id = $model->add($post);
            if($id){
                $msg = returnMsg(1,'评论成功');
                $this->ajaxReturn($msg);
            }
        }else{
            $msg = returnMsg(0,'安排课时之后才可评论');
            $this->ajaxReturn($msg);            
        }    

    }


    public function abcc()
    {
        $time = ('1' == date('w')) ? strtotime('Monday', $now) : strtotime('last Monday', $now);  

        //下面2句就是将上面得到的时间做一个起止转换

        //得到本周开始的时间，时间格式为：yyyy-mm-dd hh:ii:ss 的格式
        $beginTime = date('Y-m-d 00:00:00', $time);  

        //得到本周末最后的时间
        $endTime = date('Y-m-d 23:59:59', strtotime('Sunday', $now));

        echo $beginTime . '——' . $endTime;
    }

    // 课程表
    public function subjectsForm()
    {
        // 用户验证
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        $model = D('classHour');
        $id = I('post.id');
        if(!I('post.week')){
            $week = '1';
        }else{
            $week = I('post.week');
        }
        $data = $model->returnForm($id,$week);
        $this->ajaxReturn($data);
    }

    // 更多评论
    public function comMore()
    {
        // 用户验证
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }

        $teacher_id = I('post.teacher_id');
        $user = D('user');

        if(I('post.page')){
            $page = I('post.page');
        }else{
            $page = '1';
        }

        $list = D('comment')->where(['teacher_id'=>$teacher_id])->limit('10')->page($page)->getField('id',true);

        if(empty($list)){
            $msg  = returnMsg(0,'暂时没有评论');
            $this->ajaxReturn($msg);
        }

            foreach ($list as $key => &$value) {
                # code...
                $value = $user->commentInfo($value);
            }
        $msg  = returnMsg(1,'请求成功',$list);
        $this->ajaxReturn($msg);
    }

    public function subMore()
    {
        // 用户验证
        $user = D('user');
        $code = $user->checkToken();
        if($code['code'] == 2){
            $this->ajaxReturn($code);
        }   

        $teacher_id = I('post.teacher_id');

        if(I('post.page')){
            $page = I('post.page');
        }else{
            $page = '1';
        }

        $model = D('sendSubjects');
        
        $list = $model->where(['teacher_id'=>$teacher_id,'status'=>'1'])->limit('10')->page($page)->getField('id',true);

        if(empty($list)){
            $msg  = returnMsg(0,'暂时没有发布课程');
            $this->ajaxReturn($msg);
        }

       foreach ($list as $key => &$value) {
            # code...
            $value = $model->getInfo($value,1);
        }

        $msg  = returnMsg(1,'请求成功',$list);
        $this->ajaxReturn($msg);
    }    


}
