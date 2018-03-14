<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-26
 * Time: 12:00
 */

namespace Home\Model;
use Think\Model;

class FollowModel extends Model{
    protected $insertFields = array('follow_user_id','befollow_user_id','addtime');
    protected $_validate = array(
    );

    public function _before_insert(&$data){
        $data['addtime'] = date('Y-m-d H:i:s',time());
    }

    public function follow_lst($id){
        $where = array();
        if($id){
            $where['id'] = array('eq',"$id");
            $data = $this->alias('a')
                ->field('b.send_content,c.car,c.name,c.id')
                ->join('LEFT JOIN __USER_DTAI__ b ON a.befollow_user_id = b.user_id
            LEFT JOIN __USER__ c ON a.befollow_user_id = c.id')
                ->where($where)
                ->select();
            return $data;
        }
    }



    //查找互相关注的人
    public function follow_mutual(){   //待传过来的当前登录的用户id
        $id = 10;
        if($id){
            $info = $this->where(array('follow_user_id'=>array('eq',$id)))->select();
            $userId = array();
            foreach($info as $v){
                $userId[] = $v['befollow_user_id'];
            }

            $user_all_id = implode(',',$userId);
            $us = $this->where(array('follow'=>array('in',$user_all_id) and array('befollow_user_id'=>array('eq',$id))))->select();
            $us_id = array();
            foreach ($us as $v){
                $us_id[] = $v['follow_user_id'];
            }
            $us_id = implode(',',$us_id);
            if($us){
                $model = D('user');
                $data = $model->field('id,name,car')->where(array('id'=>array('in',$us_id)))->select();
                return $data;
            }
        }
    }
}
