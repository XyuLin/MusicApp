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
    protected $insertFields = array('username','password','cpassword','age','sex','phoen','car','education_age','office_address','defaul','stu_num','home_address','addtime','name');
    protected $updateFields = array('id','username','password','cpassword','age','sex','phoen','car','education_age','office_address','defaul','stu_num','home_address','addtime','name');
    protected $_validate = array(
        array('username', 'require', '请输入手机号！', 2, 'regex', 3),
        array('username', '1,11', '请输入11位正确的手机号', 2, 'length', 3),
        array('password', 'require', '密码不能为空！', 2, 'regex', 3),
        array('cpassword', 'password', '俩次密码必须一致！', 2, 'confirm', 3),
        array('username', '', '该s手机已被注册，请直接登录！', 2, 'unique', 3),
        array('name', '', '该用户已存在，请重新注册！', 2, 'unique', 3),
        array('phoen', '/^1[3|4|5|6｜7|8][0-9]{9}$/', '手机格式错误', 2, 'regex', 3),
    );

    public function _before_insert(&$data){
        $password = $this->password;
        $data['password'] = md5($password);
        $data['addtime'] = date('Y-m-d H:i:s',time());
        $data['phoen'] = $this->username;
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
        $str="我忽而听到夜半的笑声吃吃地似乎不愿意惊动睡着的人然而四围的空气都应和着笑夜半没有别的人我即刻听出这声音就在我嘴里我也立即被这笑声所驱逐回进自己的房灯火的带子也即刻被我旋高了后窗的玻璃上下丁地响还有许多小飞虫乱撞不多久几个进来了许是从窗纸的破孔进来的他们一进个又在玻璃的灯罩上撞得了丁丁地响一个从上面撞进去了他于是遇到火而且我以为这火是真的两三个却休息在灯的纸罩上喘气那罩是昨晚新换的罩雪白的纸折出波浪纹的叠痕一角还画出一枝猩红色的栀子猩红的栀子开花时枣树又要做小粉红花的梦青葱地弯成弧形了我又听到夜半的笑声";
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
        if($data['password']){
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
	}
	$id  = I('post.id');
        $data['addtime'] = date('Y-m-d H:i:s',time());
        $data['phoen'] = $this->username;
        $data['name'] = $this->username;               # 默认注册的时候 昵称就等于用户名就等于电话 如果没有传 则还是等于手机号
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

}
