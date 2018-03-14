<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-20
 * Time: 10:20
 */

namespace Admin\Controller;


use Think\Controller;

class AttributeController extends Controller{
    public function add(){
        if(IS_POST){
            $model = D('attribute');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success("添加成功",U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        //获取类型列表
        $type_model = D('type');
        $type_info = $type_model->getTree();
        $this->assign(array(
            'type_info'=>$type_info
        ));
        $this->display();
    }


    /******************属性列表**************/
    public function lst(){
        $model = D('attribute');
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
//        echo '<pre>';
//        var_dump($_POST);die;
        $model = D('attribute');
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

        //获取类型列表
        $type_model = D('type');
        $type_info = $type_model->getTree();
        $this->assign(array(
            'info'=>$info,
            'type_info'=>$type_info
        ));
        $this->display();

    }


    /******************删除属性**************/
    public function del(){
        $id = I('get.id');
        $model = D('attribute');
        $model->delete($id);
        $this->success("删除成功",U('lst'));
    }
}