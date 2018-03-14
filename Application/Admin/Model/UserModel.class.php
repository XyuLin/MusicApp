<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 18:51
 */

namespace Admin\Model;
use Think\Model;

class UserModel extends Model
{

    protected $insertFields = array('username','password','cpassword','age','sex','phoen','car','education_age','home_address','office_address','chkcode');
    protected $updateFields = array('id','username','password','age','sex','phoen','education_age','car','home_address','office_address','chkcode');
    protected $_validate = array(
        array('username', 'require', '手机不能为空！', 2, 'regex', 3),
        array('username','^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$','手机号码错误！','0','regex',1),
        array('password', 'require', '密码不能为空！', 2, 'regex', 3),
        array('cpassword', 'password', '俩次密码必须一致！', 2, 'confirm', 3),
        array('username', '', '该用户已存在', 2, 'unique', 3),
        array('age', 'require', '年龄不能为空！', 2, 'regex', 3),
        array('sex', 'require', '性别不能为空！', 2, 'regex', 3),
        array('teacher_carid', '1,18', '请输入18位的身份证号码', 2, 'length', 3),
        array('education_age', 'number', '教育年限只能为纯数字！', 2, 'regex', 3),
        array('phoen','^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$','手机号码错误1！','0','regex',1)
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

    /******************用户注册之前**************/
    public function _before_insert(&$data,$opt){
        $data['password'] = md5($data['password']);
        $data['addtime'] = date('Y-m-d H:i:s',time());
    }


    /******************查老师信息**************/
    public function teach($pageSize = 3){
        $where = array();
        $userName = I('get.userName');
        if($userName){
            $where['username'] = array('like',"%$userName%");
        }
        $where['defaul'] = array('eq','1');
            $count = $this->where(array(
                'defaul'=>array('eq','1')))->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->where(array('defaul'=>array('eq','1')))->select();
            $page = $Page->show();
            return $data = array('info'=>$info,
                'page'=>$page);
    }


    /******************查看某个老师所有评论 这里只取到老师发表的动态评论**************/
    public function com_to_teacher($id,$pageSize = 10){

        $count = $this->count();
        $Page = new \Think\Page($count,$pageSize);
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $info = $this->alias('a')->field('c.*,d.username,b.send_content')
            ->join('LEFT JOIN __USER_DTAI__ b ON b.user_id = a.id 
                    LEFT JOIN __COM_TEACH__ c ON c.dtai_id = b.id
                     LEFT JOIN __USER__ d ON c.stu_id = d.id')
            ->where(array('a.id' =>array('eq',"$id")))
            ->select();
        $page = $Page->show();
        return $data = array('info'=>$info,'page'=>$page);

    }


    /******************更新老师信息前**************/
    public function _before_update(&$data,$opt)
    {
        $data['addtime'] = date('Y-m-d H:i:s', time());
        $data['password'] = md5($this->password);
        if ($_FILES['car']['error'] == 0) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'TeacherImages/';
            $info = $upload->upload();

            if (!$info) {
                $this->error = $upload->getError();
                return false;
            } else {
                $data['teacher_car'] = $logo = $info['teacher_car']['savepath'] . $info['teacher_car']['savename'];
            }
        }
    }


    /******************查看某个老师所有教程**************/
    public function see_video($id,$pageSize = 2){

        $info = $this->alias('a')->field('c.subject_name,b.number,b.up_date,b.id')
            ->join('LEFT JOIN __VIDEO__ b ON a.id = b.teacher_id and b.statu = "1"
                    LEFT JOIN __SUBJECTS_TYPE__ c ON b.subjects_id = c.id ')
            ->where(array('a.id' =>array('eq',"$id")))
            ->select();
        $count =count($info);
        $Page = new \Think\Page($count,$pageSize);
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $page = $Page->show();
        return $data = array('info'=>$info,'page'=>$page);

    }






    /************查看学生的信息***************/
    public function stu($pageSize = 5){
        $where = array();
        $id = I('get.id');
        $userName = I('get.userName');
        if($id){
            $where['goods_name'] =array('eq',"$id");
        }
        if($userName){
            $where['username'] = array('like',"%$userName%");
        }
        $where['defaul'] = array('eq','2');
            $count = $this->where($where)->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->alias('a')->field('a.*,GROUP_CONCAT(DISTINCT(d.subject_name)) subject_name')
                ->join('LEFT JOIN __STU_SUBJECT__ b ON a.id = b.stu_id
                        LEFT JOIN __SEND_SUBJECTS__ c ON b.subjects_id = c.id
                         LEFT JOIN __SUBJECTS_TYPE__ d ON c.subjects_id = d.id ')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->where($where)
                ->group('a.id')
                ->select();
//            echo '<pre>';
//            var_dump($info);die;
            $page = $Page->show();
            return $data = array('info'=>$info,
                'page'=>$page);
    }

}