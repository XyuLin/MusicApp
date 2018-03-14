<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-18
 * Time: 10:00
 */

namespace Admin\Model;
use Think\Model;

class BannerModel extends Model{
    protected $insertFields = array('img','title','deso','sort');
    protected $updateFields = array('img','title','deso','sort');
    protected $_validate = array(
        array('title', 'require', '不能为空！', 2, 'regex', 3),
    );

    /******************banner添加**************/
        public function _before_insert(&$data,$opt){
            $data['up_time'] = date('Y-m-d H:i:s',time());
//            echo '<pre>';
//            var_dump($_FILES['img']);die;
            if($_FILES['img']['error'] == 0){
                 $upload = new \Think\Upload();
                 $upload->maxSize = 3145728 ;
                 $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                 $upload->rootPath = './Public/Upload/';
                 $upload->savePath = 'Banner/';
                 $info = $upload->upload();
//                 echo '<pre>';
//                 var_dump($info);die;
                 if(!$info) {
                 $this->error = $upload->getError();
                 return false;
                }else{
                     $data['img'] = $logo = $info['img']['savepath'].$info['img']['savename'];
                 }
            }
        }

    /******************banner修改**************/
        public function edit($option){
            $id = $option['where']['id'];
            if($_FILES['banner']['error'] == 0){
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728 ;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $upload->rootPath = '.Public/Upload/';
                $upload->savePath = 'Banner';
                $info = $upload->upload();
                if(!$info) {
                    $this->error = $upload->getError();
                    return false;
                }else{
                    $data['img'] = $logo = $info['img']['savepath'].$info['img']['savename'];
                    $oldLogo = $this->field('img')->find("$id");
                    unlink('./Public/Upload/'.$oldLogo['img']);
                    $this->success('修改成功！');
                }
            }
        }

    /******************banner列表**************/
        public function lst($pageSize = 2){
            $count = $this->count();
            $Page = new \Think\Page($count,$pageSize);
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $data = $this->limit($Page->firstRow.','.$Page->listRows)->select();
            $page= $Page->show();
//            var_dump($page);die;
            return $data = array('info'=>$data,'page'=>$page);
        }



}