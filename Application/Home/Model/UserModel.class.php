<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-26
 * Time: 11:05
 */
namespace Home\Model;
use Think\Model;
class UserModel extends Model{
    protected $insertFields = array('password','cpassword','age','sex','phone','car','education_age','office_address','defaul','stu_num','home_address','addtime','name');
    protected $updateFields = array('id','password','cpassword','age','sex','phone','car','education_age','office_address','defaul','stu_num','home_address','addtime','name');
    protected $_validate = array(
        array('phone', 'require', '请输入手机号！', 2, 'regex', 3),
        array('phone', '1,11', '请输入11位正确的手机号', 2, 'length', 3),
        array('password', 'require', '密码不能为空！', 2, 'regex', 3),
        array('cpassword', 'password', '俩次密码必须一致！', 2, 'confirm', 3),
        array('phone', '', '该s手机已被注册，请直接登录！', 2, 'unique', 3),
        array('name', '', '该用户已存在，请重新注册！', 2, 'unique', 3),
        array('phone', '/^1[3|4|5|6｜7|8][0-9]{9}$/', '手机格式错误', 2, 'regex', 3),
    );
    protected $sexList = ['未选择','男','女']; 
    protected $education = [1=>'大专','本科','硕士','博士','教授']; 

    public function _before_insert(&$data){
        $data['password'] = md5($data['password']);
        $data['addtime'] = date('Y-m-d H:i:s',time());
        $data['name'] = $this->getRndwords(3);               //默认注册的时候 昵称就等随机名字 这里设置默认取3个汉字
        if(!empty($_FILES['car']['tmp_name'])) {
            if ($_FILES['car']['error'] == 0) {
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $upload->rootPath = './Public/Upload/';
                $upload->savePath = 'UserImages/';
                $info = $upload->upload();
                if(!$info){
                    $this->error = $upload->getError();
                    return false;
                }else{
                    $data['car'] = $logo = $info['car']['savepath'] . $info['car']['savename'];
                }
            }else{
                return true;
            }
        }
    }


    /**
     * @param string $giveStr  名字里必须要包含的字
     * @param int $num         截取多少个字
     * @return string          返回截取到的汉字
     */
   public function getRndwords($num,$giveStr=""){
        $str="abcdefghijklmnopqrstuvwsyz";
        $newStr  = "";
        $anLo    = array();
        $bit     = 3;
        $anLenth = floor(strlen($giveStr)/$bit);
        $i = 0;
        while ( $i<$anLenth ) {
            $rd = rand( 0, $num-1 );
            if(in_array($rd,$anLo)) continue;
            $anLo[] = $rd;
            $i++;
        }

        for( $j=0; $j<$num;$j++ ){
            if(in_array($j,$anLo)){
                $k = array_search($j,$anLo);
                $newStr .= mb_substr($giveStr,$k*$bit,$bit);

            } else {
                $rd  = rand(0,(strlen($str)-1)/$bit);
                $wd  = mb_substr($str,$rd*$bit,$bit);
                $str = str_replace($wd, '', $str);
                $newStr .= $wd;
            }
        }
        return $newStr;
    }



    # 注册之后 如果是学生就发放优惠券
    public function _after_insert($option){
        $model = D('coupon');
        $stu_id = $option['where']['id'];
        $lastTime = date('Y-m-d H:i:s',time());
        $money = '88';
        $data = $model->add(array(
            'stu_id' => $stu_id,
            'last_time' => $lastTime,
            'money' => $money,
        ));
    }


    /**
     * 获取老师信息展示首页
     * @param $name    / 待前端传来要搜索的老师name值根据老师名字搜索
     * @return array   / 返回的信息
     */
    public function getTeach($name){
        $where = array();
        if($name){
            $where['name'] = array('like',"%$name%");
        }
        $where['defaul'] = array('eq','1');
        $info = $this->where($where)->select();
        return $data = array('info'=>$info);
    }


    /**
     * 获取个人信息 并修改
     * @return bool
     */
    public function _before_update(&$data){
    //     if($data['password']){
    //         $data['password'] = md5($data['password']);
    //     }else{
    //         unset($data['password']);
	   // }
	    $id  = I('post.id');
        $data['addtime'] = date('Y-m-d H:i:s',time());

        if(!empty($_FILES['car']['tmp_name'])) {
            if ($_FILES['car']['error'] == 0) {
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $upload->rootPath = './Public/Upload/';
                $upload->savePath = 'UserImages/';
                $info = $upload->upload();
                if (!$info) {
                    $this->error = $upload->getError();
                    return false;
                } else {
                    $data['car'] = $logo = $info['car']['savepath'] . $info['car']['savename'];
		    $info = $this->field('car')->find($id);       
		    unlink('./Public/Upload/'.$info['car']);
		}
            } else {
                return true;
            }
        }
    }

    //  查询个人基本信息
    public function getInfo($id,$type = '')
    {
        $user = $this->find($id);
        if($user['defaul'] == 1 && $type == ''){
            $teacher = D('teacher')->find($id);
            if($teacher['tea_defaul_qx'] != 1){

                $teacher['education_age'] .= '年';
                $teacher['sex'] = $this->sexList[$user['sex']]; 
                // 删除无用字段
                unset($teacher['tea_defaul_id']);
                unset($teacher['education']);
                unset($teacher['graduate']);
                $user['teacher'] = $teacher;
            }
        }

        // 判断用户是否修改年龄
        $user['age'] = $user['age'] == ''? '未填写': $user['age'];

        // 判断用户是否填写地址
        $user['home_address'] = $user['home_address'] == '' ? "未填写":  $user['home_address'];

        // 性别信息
        $user['sex'] = $this->sexList[$user['sex']];      

        // 判断用户是否上传头像 未上传提供默认头像
        // $user['car'] = $user['car'] == "" ? "默认头像":'';
        unset($user['password']);
        return $user;
    }

   // 判断是否是老师并返回个人信息
    public function isTeacher($id)
    {
        $model = D('teacher');
        $user = $model->where(['id'=>$id])->find();
        if($user){
            $user['sexName'] = $this->sexList[$user['sex']];
            $user['educationName'] = $this->education[$user['education']];
            return $user;
        }else{
            return false;
        }
    }

    /**
    *@param $id 用户id
    */
    public function createToken($id)
    {
        // 生成user_token
        $user_token = md5($id+ date('Y-m-d',time()) +'musicApp');
        return $user_token;
    }


    // 验证user_token
    public function checkToken()
    {
        $token['user_token'] = ['eq',I('post.user_token')];
        $token['id'] = ['eq',I('post.id')];
        // 判断用户token是否存在
        $info = $this->where($token)->find();
        $time = date('Y-m-d H:i:s',time());
        if($info == NULL){
                $data = [
                    'code' => 2,
                    'msg'  => '用户未登录',
                    'data' => [],
                ];  
                return ($data);
        }
        if( $time > $info['expire_time']){
                $data = [
                    'code' => 2,
                    'msg'  => '登录已超时',
                    'data' => [],
                ];
                return ($data);
        }
        $con['expire_time'] = date('Y-m-d H:i:s',time()+604800);
        $this->where($token)->save($con);


   
        // 通用接口。请求参数，需带有。特色算法的sign 与服务器端校验。通过则返回数据，失败则拒绝.

        // 用户提供认证信息，服务端认证后，返回token，用户请求接口需带有，当前时间，token加 （当前时间+token+musicAPP）生成的sign ，服务端，判断接口请求时间是否超过10分钟，根据参数根据相同算法生成sign。与请求参数sign相匹配。通过则返回数据，失败拒绝。
    }



    /** 
    * 课程列表 
    *@param id str 用户id
    *@param type  用户类型 (1 教师,2 学生)
    */ 
    public function subjectList($id,$type)
    {       
         $model = D('sendSubjects');
        if($type == 1){
            // 获取教师发布的所有课程
            $list = $model->returnClass($id);
            return($list);
        }else{
            // 学生购买的教程
            $list = $model->get_stu_class($id);
            return($list);
        }

    }

}
