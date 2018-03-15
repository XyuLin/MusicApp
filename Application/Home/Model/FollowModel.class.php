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
    protected $insertFields = array('follow_user_id','befollow_user_id','follow_time');
    protected $_validate = array(
    );

    public function _before_insert(&$data){
        $data['follow_time'] = date('Y-m-d H:i:s',time());
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
    public function follow_mutual($id){   //待传过来的当前登录的用户id
        $id = 10;
        if($id){
            $info = $this->where(array('follow_user_id'=>array('eq',$id)))->select();
            $userId = array();
            foreach($info as $v){
                $userId[] = $v['befollow_user_id'];
            }
            $id_arr = [];
            $user_all_id = implode(',',$userId);

            $where['follow_user_id'] = ['in',$user_all_id];
            $where['befollow_user_id'] = ['eq',$id];
            $us = $this->where($where)->select();

            foreach($us as $k => $v){
                $id_arr[] = $v['follow_user_id'];
            }
            $us_id = implode(',',$id_arr);
            if($us){
                $model = D('user');
                $data = $model->field('id,name,car')->where(array('id'=>array('in',$us_id)))->select();
                return $data;
            }
        }
    }
}
