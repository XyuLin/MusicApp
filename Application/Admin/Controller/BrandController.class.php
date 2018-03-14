<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-20
 * Time: 14:41
 */

namespace Admin\Controller;


use Think\Controller;

class BrandController extends Controller{
    public function add(){
        if(IS_POST){
            $model = D('brand');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success("添加成功",U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->assign(array());
        $this->display();
    }


    public function lst(){
        $model = D('brand');
        $data = $model->lst();
        $this->assign(array(
                'info'=>$data['info'],
                'page'=>$data['page'],
            )
        );
        $this->display();
    }


    /******************修改**************/
    public function edit(){
        $model = D('brand');
        if(IS_POST){
            if($model->create(I('post.'),2)){
                if($model->save() !== false){
                    $this->success("修改成功",U('lst'));
                }
            }
        }
        //得到属性的信息
        $id = I('get.id');
        $info = $model->find($id);
        $this->assign(array(
            'info'=>$info,
        ));
        $this->display();

    }


    /******************删除属性**************/
    public function del(){
        $id = I('get.id');
        $model = D('brand');
        $model->delete($id);
        $this->success("删除成功",U('lst'));
    }

}