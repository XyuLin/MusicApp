<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-16
 * Time: 13:42
 */
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model{
    protected $insertFields = array('username','password','cpassword','chkcode');
    protected $updateFields = array('id','username','password','cpassword','chkcode');
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！', 2, 'regex', 3),
        array('username', '1,30', '的值最长不能超过 30 个字符！', 2, 'length', 3),
        array('password', 'require', '密码不能为空！', 2, 'regex', 3),
        array('cpassword', 'password', '俩次密码必须一致！', 2, 'confirm', 3),
        array('username', '', '该用户已存在，请重新注册！', 2, 'unique', 3),
        array('chkcode', 'require', '验证码不能为空！', 1),
        array('chkcode', 'check_verify', '验证码不正确！', 1, 'callback'),
    );


//	为登录表单定义一个验证规则
    public $_login_validate=array(
        array('username', 'require', '用户名不能为空！', 1),
        array('password', 'require', '密码不能为空！', 1),
        array('chkcode', 'require', '验证码不能为空！', 1),
        array('chkcode', 'check_verify', '验证码不正确！', 1, 'callback'),

    );

    /**
     * @param $code
     * @param $id
     * @return bool  验证码验证
     */
    function check_verify($code,$id=''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }



    /******************添加管理员**************/
    public function _before_insert(&$data){
        $password = $this->password;
        $data['password'] = md5($password);
        $data['addtime'] = date('Y-m-d H::i:s',time());
    }


    /******************判断登录**************/
    public function login(){
        $username= $this->username;
        $password= $this->password;
        $username = trim($username);
        $password = trim($password);
        //先查询这个用户名是否存在
        $user = $this->where(array(
            'username'=>array('eq',$username),
        ))->find();
        if($user){
            if($user['password'] == md5($password)){
                session('id',$user['id']);
                session('username',$user['username']);
                return true;
            }else{
                $this->error = "密码不正确！请重新输入...";
                return FALSE;
            }
        }else{
            $this->error = "用户名不存在！";
            return FALSE;
        }
    }


    /******************更新管理员前**************/
    public function _before_update(&$data,$opt){
        $data['addtime'] = date('Y-m-d H:i:s',time());
        if($data['password']){
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }
    }



    /******************删除管理员前**************/
    public function _before_delete($opt){
        if($opt['where']['id'] == 1){
            $this->error="超级用户 不可删除！";
            return FALSE;
        }
    }


    /******************管理员列表**************/
    public function lst($pageSize = 2){
        $where = array();
        $userName = I('get.userName');
        $where['userName'] = array('like',"%$userName%");
        $count = $this->where($where)->count();
        $Page = new \Think\Page($count,$pageSize);
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $data = $this->where($where)
             ->limit($Page->firstRow.','.$Page->listRows)
             ->select();
        $page = $Page->show();
         return $data = array('info'=>$data,'page'=>$page);
    }



    /******************退出登录**************/
    public function log_out(){
        session(null);
    }
}