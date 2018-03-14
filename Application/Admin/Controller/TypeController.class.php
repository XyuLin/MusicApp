<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-20
 * Time: 9:32
 */
namespace Admin\Controller;
use Think\Controller;

class TypeController extends Controller{
    public function add(){
        $model = D('type');
        if(IS_POST){
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        /*******************取出所有的分类做下拉框*****************/
        $typeData = $model->getTree();
        $this->assign(array(
            'typeData'=> $typeData,
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }

    /*******************取出类型列表*****************/
    public function lst()
    {
        $model = D('type');
        $data = $model->getTree();
        // 设置页面信息
        $this->assign(array(
            'info' => $data,
        ));
        $this->display();
    }



    /**************修改分类列表********************/
    public function edit(){
//
//        echo '<pre>';
//        var_dump($_POST);die;
        $model = D('type');
        $id = I('get.id');
        if(IS_POST)
        {
            if($model->create(I('post.'), 2))
            {
                if($model->save() !== FALSE)
                {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }


        /*************************修改分类信息*****************/
        //取出当前id的信息
        $data = $model->find($id);
        //取出当前id的子分类
        $children = $model->getChilder();
        //取出所有分类
        $catdata = $model->getTree();

        $this->assign(array(
            'children'=>$children,
            'data'=>$data,
            'catdata'=>$catdata,
        ));

        // 设置页面中的信息
        $this->assign(array(
            '_page_title' => '修改分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }


    /************删除分类*************/
    public function delete(){
        $id = I('get.id');
        $Model = D('type');
        if($id){
            if(FALSE !== $Model ->delete($id)){
                $this->success('删除成功',U('lst'));
            }else{
                $this->error('删除失败！ 原因：'.$Model->getError());
            }
        }
    }


}