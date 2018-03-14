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
            if ($model->create(I('post.'), 1)) {
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
                    return json_encode($data);
                }
            }
        }
    }
}