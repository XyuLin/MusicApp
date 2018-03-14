<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 16:02
 */

namespace Home\Controller;
use Think\Controller;

class messageController extends Controller
{

    # 发送信息
    public function sendMessage(){
        $model = D('message');
        if(IS_POST){
            if($model->create(I('post.'),1)) {
                if ($id = $model->add()) {
                    if ($id) {
                        $data = array(
                            'code' => 1,
                            'msg' => '发送成功',
                        );
                    } else {
                        $data = array(
                            'code' => 0,
                            'msg' => '发送失败',
                        );
                    }
                    return json_encode($data);
                }
            }
        }
    }



    #接收信息 包含有信息内容和信息条数
    public function getMessage(){
        $model = D('message');
        $data = $model->showMessage();
        if ($data) {
            $data = array(
                'code' => 1,
                'msg' => '获取信息成功',
                'data'=>$data['info'],
            );
        } else {
            $data = array(
                'code' => 0,
                'msg' => '获取失败',
            );
        }
        return json_encode($data);
    }


    # 获取被人未读的信息
    public function showMessageNum(){
        $model = D('message');
        $data = $model->showMessage();
        if ($data) {
            $data = array(
                'code' => 1,
                'msg' => '获取信息数量',
                'data'=>$data['count'],
            );
        } else {
            $data = array(
                'code' => 0,
                'msg' => '获取信息失败',
            );
        }
        return json_encode($data);

    }



    # 接收用户读取信息时的反馈
    public function gai(){
        $model = D('message');
        $recevie_id = I('get. recevie_id ');                   # 当前用户id 是接收人
        $send_id =I('get.send_id');                            # 发送人id
        $gai = I('get.gai');                                   # 查看事件回馈
        $where = array();
        $where['send_id'] = $send_id;
        $where['recevie_id'] = $recevie_id;
        if($gai){
            $data = array('status'=>1);
            $model->where($where)->setField($data);               # 用户读取信息 标记为已读（点开具体某人发送的）
        }

    }
}