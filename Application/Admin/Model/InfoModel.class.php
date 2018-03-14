<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-22
 * Time: 14:26
 */

namespace Admin\Model;
use Think\Model;

class InfoModel extends Model
{
    protected $insertFields = array('title','logo','www','crux','desc','con','phone','tel','fax','qq','email','address','but','service_tel','sentitle','qqu');
    protected $updateFields = array('id','title','logo','www','crux','desc','con','phone','tel','fax','qq','email','address','but','service_tel','sentitle','qqu');
    protected $_validate = array(
        array('phone','^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$','手机号码错误！','0','regex',1)
    );

    public function _before_insert(&$data){
        $crux = $this->crux;
        $crux = str_replace('，',',',$crux);
        $data['crux'] = $crux;
        if($_FILES['logo']['error'] == 0) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Public/Upload/';
            $upload->savePath = 'logo/';
            $info = $upload->upload();
            if (!$info) {
                $this->error = $upload->getError();
                return false;
            } else {
                $data['logo'] = $logo = $info['logo']['savepath'] . $info['logo']['savename'];
            }
        }
    }
}