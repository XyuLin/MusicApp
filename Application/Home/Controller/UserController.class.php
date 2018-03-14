<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 11:06
 */

namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{

    // public function reg()
    // {
    //     if (IS_POST) {
    //         $model = D('user');
    //         if ($model->create(I('post.'), 1)) {
    //             if ($id = $model->add()) {
    //                 if ($id) {
    //                     $data = $model->find($id);
    //                     if ($data) {
    //                         $data = array(
    //                             'code' => 1,
    //                             'msg' => '恭喜您，注册成功！',
    //                         );
    //                     } else {
    //                         $data = array(
    //                             'code' => 0,
    //                             'msg' => '注册失败'.$this->error($model->getError()),
    //                         );
    //                     }
    //                     return json_encode($data);
    //                 }
    //             }

    //         }
    //     }
    // }


    public function reg()
    {
        if(IS_POST){
            $tel = I('post.tel');
            $code = I('post.code');
            if($tel && $code){
                
            }
        }
    }
    /**
     * 更新个人信息
     * @param $id  /接收的登录用户id
     */
    public function edit(){
//        $id = I('get.id');
        if(IS_POST){
            $model = D('user');
            if($model->create(I('post.'),1)){
                if($ret_id = $model->save() !== false) {
                    $data = $model->find($ret_id);
                    if ($data) {
                        $data = array(
                            'code' => 1,
                            'msg' => '修改成功',
                        );
                    } else {
                        $data = array(
                            'code' => 0,
                            'msg' => '修改失败'.$this->error($model->getError()),
                        );
                    }
                    return json_encode($data);
                }
            }

        }
    }



}