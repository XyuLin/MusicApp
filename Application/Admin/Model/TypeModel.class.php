<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-20
 * Time: 9:34
 */

namespace Admin\Model;
use Think\Model;

class TypeModel extends Model{
    protected $insertFields = 'type_name,parent_id,is_floor';
    protected $updateFields = 'id,cat_name,parent_id,is_floor';
    protected $_validate = array(
        array('type_name', 'require', '分类名称不能为空！', 2, 'regex', 3),
    );

    /***********找一个分类所有子分类的ID************/
    public function getChilder($typeId){
        $data =$this->select();
        return $this->_getChilder($data,$typeId,TRUE);
    }

    //得到当前id下的所有子类型
    private function _getChilder($data,$typeId,$isClear = FALSE){
        static $_ret = array();
        if($isClear){
            $_ret=array();
        }
        foreach($data as $k => $v){
            if($v['parent_id'] == $typeId){
                $_ret[] = $v['id'];
                $this->_getChilder($data,$v['id']);
            }
        }
        return $_ret;
    }


    /***********打印树形结构图****************/
    public function getTree(){
        $data = $this->select();
        return $this->_getTree($data);
    }
    /***************递归找到所有分类信息*********************/
    private function _getTree($data,$parent_id=0,$level=0){
        static $_ret = array();
        foreach ($data as $k =>$v){
            if( $v['parent_id'] == $parent_id){
                $v['level'] = $level;
                $_ret[] = $v;
                $this->_getTree($data ,$v['id'],$level+1);
            }
        }
        return $_ret;
    }

    /********删除之当前分类前 先删除当前分类下的所有子分类**********************/
    protected function _before_delete($option){
        $id = $option['where']['id'];
        $Childer = $this->getChilder($id);
        if($Childer){
            $Childer = implode(',',$Childer);
            $molde = new\Think\Model();
            $molde->table('__CATEGORY__')->delete($Childer);
        }
    }



}