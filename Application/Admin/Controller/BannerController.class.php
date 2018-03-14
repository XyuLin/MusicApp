<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-18
 * Time: 9:59
 */
namespace Admin\Controller;
use Think\Controller;

class BannerController extends BaseController{

    /******************banner添加**************/
    public function add(){
        if(IS_POST){
            $model = D('banner');
            if($model->create(I('post.'),1)){
                if($model->add()){
                    $this->success("上传成功",U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->display();
    }


    /******************banner修改**************/
    public function edit(){
        if(IS_POST){
            $model = D('banner');
            if($model->create(I('post.'),2)){
                if($model->save() !== false){
                    $this->success("修改成功",U('lst_banner'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $id = I('get.id');
        $model = D('banner');
        $data = $model->select($id);
        $this->assign(
                'info',$data
        );
        $this->display();
    }


    /******************banner列表**************/
    public function lst(){

        $model = D('banner');
        $data = $model->lst();
        $this->assign(array(
                'info'=>$data['info'],
                'page'=>$data['page'],
            )
        );
        $this->display();
    }


    /******************banner删除**************/
    public function del(){
        $id = I('get.id');
        $model = D('banner');
        $model->delete($id);
        $this->success("删除成功",U('lst'));
    }
}