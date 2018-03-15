<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/5
 * Time: 15:09
 */

namespace Home\Controller;


use Think\Controller;

class FollowController extends Controller{

    # 添加关注好友
    public function addFollow()
    {
        if (IS_POST) {
            $model = D('follow');
            $where['follow_user_id'] = I('post.user');
            $where['befollow_user_id'] = I('post.befollow');
            $info = $model->where($where)->find();
            if($info){
                $model->delete($info['id']);
                $data = array(
                    'code' => 1,
                    'msg'  => '取消关注成功',
                );
                $this->ajaxReturn($data);
            }
            if ($model->create($where, 1)) {
                if ($id = $model->add()) {

                    if ($id) {
                        $data = array(
                            'code' => 1,
                            'msg' => '关注成功',
                            'data' => $id,
                        );
                    } else {
                        $data = array(
                            'code' => 0,
                            'msg' => '请求失败',
                        );
                    }
                    $this->ajaxReturn($data);
                }
            }
        }
    }

    /**
    * @param $id 用户id
    * @param $follow  为 1 是我的粉丝(关注我的用户) 为 2 是我关注的用户 默认为 1 
    */
    // 查询关注我的用户 (粉丝)
    public function getFollowMe($id,$follow = '1')
    {
        if($follow == 1){
            $list = D('follow')->where(['befollow_user_id'=>$id])->getField('follow_user_id',true);
        }

        if($follow == 2){
            $list = D('follow')->where(['follow_user_id'=>$id])->getField('befollow_user_id',true);
        }
         
        $model = D('user');
        $data = [];
        foreach($list as $key => $value){
            $data[$key] = $model->getInfo($value);
        }
        $this->ajaxReturn($data);
    }

}