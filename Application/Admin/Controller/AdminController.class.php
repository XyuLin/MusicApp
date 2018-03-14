<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-16
 * Time: 14:05
 */
namespace Admin\Controller;
use Think\Controller;
class AdminController extends BaseController{

    /******************后台**************/
    public function add(){
//        echo '<pre>';
//        var_dump($_POST);die;
        if(IS_POST){
            $admin_model = D('Admin');
            if($admin_model->create(I('post.'),1)){
                if($admin_model->add()){
                    $this->success('添加成功',U('lst'));
                    exit;
                }
            }
            $this->error($admin_model->getError());
        }
        $this->display();
    }



    /******************管理员列表**************/
    public function lst(){
        $model = D('Admin');
        $data = $model->lst();
        $this->assign(array('info'=>$data['info'],
                            'page'=>$data['page'],)
    );
        $this->display();
    }


    /******************更新管理员**************/
    public function edit(){
        $admin_id = I('get.id');
        $model = D('Admin');
        if(IS_POST){
            if($model->create(I('post.'),2)){
                if(FALSE !== $model->save()){
                    $this->success('修改成功！',U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $data = $model->find($admin_id);
        $this->assign('data',$data);
        $this->display();
    }



    /******************删除管理员**************/
    public function del(){
        $model = D('Admin');
       if($model->delete(I('get.id',0)) !== FALSE){
           $this->success('删除成功！',U('lst'));
           exit;
       }else{
           $this->error($model->getError());
       }
        $this->display();
    }


}