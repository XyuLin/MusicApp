<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 16:08
 */

namespace Home\Model;
use Think\Model;

class MessageModel extends Model
{
    protected $insertFields = array('send_id','send_content','receive_id','status');
    protected $updateFields = array('id','send_id','send_content','receive_id','status');
    protected $_validate = array(
        array('send_content', '1,200', '输入信息过长', 2, 'length', 3),
    );

    public function _before_insert($data){
        $data['send_time'] = date('Y-m-d H:i:s',time());
    }

    public function showMessage(){
        $userId = I('post.userId');             # 接收到的id
        $info = $this->where(array('id'=>array('eq',$userId)) and array('status'=>array('eq',0)))->select();
        $count = $this->where(array('id'=>array('eq',$userId)) and array('status'=>array('eq',0)))->count();
        $data['info'] = $info;
        $data['count'] = $count;
        return $data;
    }

}