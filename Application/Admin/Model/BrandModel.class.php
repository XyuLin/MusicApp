<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-20
 * Time: 14:41
 */

namespace Admin\Model;
use Think\Model;

class BrandModel extends Model{
    protected $insertFields = array('brand_name','img','up_time');
    protected $updateFields = array('id','brand_name','img','up_time');
    protected $_validate = array(
        array('brand_name', 'require', '品牌名称不能为空！', 2, 'regex', 3),
    );

    public function lst($braname,$pageSize = 1){
        $where = array();
        $braname = I('get.braname');
        $braname = $braname?$braname:'';
        $where['brand_name'] =  array('eq', $braname);
        if($braname){
            $count = $this->where($where)->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->where($where)
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
            $page = $Page->show();
            return $data = array('info'=>$info,
                'page'=>$page);

        }else{
            $count = $this->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $info = $this->limit($Page->firstRow.','.$Page->listRows)
                ->select();
            $page = $Page->show();
            return $data = array('info'=>$info,
                'page'=>$page);
        }
    }
    public function _before_insert(&$data){
        $data['up_time'] = date('Y-m-d H:i:s',time());
        if($_FILES['img']['error'] == 0){
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728 ;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'Brand/';
            $info = $upload->upload();
            if(!$info) {
                $this->error = $upload->getError();
                return false;
            }else{
                $data['img'] = $logo = $info['img']['savepath'].$info['img']['savename'];
            }
        }
    }

    public function _before_update(&$data){
        $data['up_time'] = date('Y-m-d H:i:s',time());
        if($_FILES['img']['error'] == 0){
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728 ;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'Brand/';
            $info = $upload->upload();
            if(!$info) {
                $this->error = $upload->getError();
                return false;
            }else{
                $data['img'] = $logo = $info['img']['savepath'].$info['img']['savename'];
            }
        }
    }


    //删除品牌前 删除所有品牌下的乐器
    public function _before_delete(&$data,$option){
        $id = $option['where']['id'];
        $data['up_time'] = date('Y-m-d H:i:s',time());
        if($id){
            $musical = D('musical');
            $data = $musical->where(array('brand_id'=>$id))->delete();
        }
    }

}