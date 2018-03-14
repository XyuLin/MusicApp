<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 13:57
 */

namespace Home\Controller;


use Think\Controller;

class QuanController extends Controller
{
    //获得被关注人的动态和个人信息
    /**
     * @param $id    /当前登录用户的id
     * @return mixed /获得被关注人的id name 头像 动态
     */
    public function show_follow_lst(){
        $id = I('get.id');
        $model = D('follow');
        $info = $model->follow_lst($id);
        return return_json($info);
    }


    /**
     *  @param $getId /当前登录学生的id
     * @return mixed / 获得个人的昵称 头像信息 id值 同学动态   没有发表过动态的默认不取
     * @param $page   /第几页
     * @param $size   /一页显示多少条
     *
     */
    public function show_classmate_lst( $size = 2){
        $getId = I('get.id');            # 需要传过来用户的id
        $getId = 12;
        $dt_model = D('user_dtai');
        $sub_model = D('stu_subject');
        $userModel = D('user');
        # $getId = 10;                                                                        # 当前登录的学生id
        $data = $sub_model->where(array('stu_id'=>array('eq',$getId)))->select();             # 获得当前学生所学的所有课程

        $id_arr = array();
        foreach($data as $v){
            $id_arr[] = $v['subjects_id'];
        }




        # 转字符串
        $stu_id = implode(',',$id_arr);
        $info = $sub_model->where(array('subjects_id'=> array('in',$stu_id)))->select();     # 得到所有同学 指和当前用户所学课程一样的 间接的确认了上课的时间地点也是一样的 在发布的课程表里

        $stuId = array();
        foreach($info as $v){
            $stuId[] = $v['stu_id'];
        }

        $all_id = array_unique($stuId);
        $id = implode(',',$all_id);

        $id = str_replace("$getId",'',$id);

        # 获取同学的动态 获得动态的内容
        $dtInfo = $dt_model->field('id,user_id,send_content')->where(array('user_id'=>array('in',$id)))->select();

        # 取出点赞表的信息  得到动态的id
        $zan_model = D('zan');
        $zan_info = $zan_model ->where(array('user_id'=>array('eq',$getId)))->select();

        # 点赞表的动态id转一维
        $zanId = array();
        foreach($zan_info as $v){
            $zanId[] = $v['dtai_id'];

        }

        # 判断同学的动态是否点赞
        foreach($dtInfo as $k=>&$v){
            if(in_array($v['id'],$zanId)){
                $v['status'] = 1;
            }else{
                $v['status'] = 0;
            }
        }

        # 获得个人信息 获得个人的昵称 头像信息
        $userInfo = $userModel->field('id,car,name')->where(array('id'=>array('in',$id)))->select();



        # 处理所有同学的信息  取出同学发布的个人信息个人动态 没有发布的同学 默认不取
        $userinfoLen = count($userInfo);
        for($i=0;$i<$userinfoLen;$i++){
            foreach($dtInfo as $k => &$v){
                if($userInfo[$i]['id'] == $v['user_id']){
                    $v['userInfo'] = $userInfo[$i];
                }

            }
        }

        #设置分页
        if(!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page=$_GET['page'];
        }
        $pnum = ceil(count($dtInfo) / $size);
        $data = array_slice($dtInfo, ($page-1)*$size, $size);

//        echo'<pre>';
//        var_dump($data);die;

       return return_json($data);
    }





    /**
     * @param $getId /当前登录学生的id
     * @return mixed  /获得老师的动态信息 获得个人的昵称 头像信息 id值
     */
    public function show_teacher_lst($size = 10){
        $getId = I('get.id');
        $dt_model = D('user_dtai');
        $sub_model = D('stu_subject');
        $userInfo = D('user');
        $send_v_model = D('send_subjects');
//        $getId = 10;                                                                        # 当前登录的学生id
        $data = $sub_model->where(array('stu_id'=>array('eq',$getId)))->select();             # 获得当前学生所学的所有课程
        $id_arr = array();
        foreach($data as $v){
            $id_arr[] = $v['subjects_id'];
        }

        $id = implode(',',$id_arr);
        $info = $send_v_model->where(array('id'=>array('in',$id)))->select();

        $teacher_all_id = array();
        foreach($info as $v){
            $teacher_all_id[] = $v['teacher_id'];
        }

        $teachId = array_unique($teacher_all_id);
        $teachId = implode(',',$teachId);

        $teacher_dtai = $dt_model->field('id,send_content')->where(array('user_id'=>array('in',$teachId)))->select();

        # 获得个人信息 获得个人的昵称 头像信息
        $teacher_user = $userInfo->field('id,car,name')->where(array('id'=>array('in',$teachId)))->select();

        $zan_model = D('zan');
        $zan_info = $zan_model ->where(array('user_id'=>array('eq',$getId)))->select();
        # 转一维
        $zanId = array();
        foreach($zan_info as $v){
            $zanId[] = $v['dtai_id'];

        }


        foreach($teacher_dtai as $k=>&$v){
            if(in_array($v['id'],$zanId)){
                $v['status'] = 1;
            }else{
                $v['status'] = 0;
            }
        }



        # 处理所有老师的信息  取出老师发布的个人信息个动态 没有发布的老师 默认不取
        $teacher_userLen = count($teacher_user);
        for($i=0;$i<$teacher_userLen;$i++){
            foreach($teacher_dtai as $k => &$v){
                if($teacher_user[$i]['id'] == $v['user_id']){
                    $v['userInfo'] = $teacher_user[$i];
                }

            }
        }

        #设置分页
        if(!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page=$_GET['page'];
        }
        $pnum = ceil(count($teacher_dtai) / $size);
        $data = array_slice($teacher_dtai, ($page-1)*$size, $size);
        return return_json($data);
    }






    /**
     * 获得互相关注的人
     * @param $id     /当前登录的用户id
     * @return mixed  /获得互相关注人的 头像和name值 id值
     */
    public function show_follow_mutual(){         # 待传过来的当前登录的用户id
        $id = I('get.id');
        $model = D('follow');
        $data = $model->follow_mutual($id);
        return return_json($data);
    }





    /**
     *取消关注
     * @param $follow_user_id     //关注人的id（当前登录用户id）
     * @param $befollow_user_id    //被关注人的id
     * @return mixed
     *
     */
    public function take_off(){         //待传过来的当前登录的用户id
        $follow_user_id = I('get.follow_user_id');
        $befollow_user_id = I('get.befollow_user_id');
        $model = D('follow');
        $data = $model->where(array('follow_user_id'=>array('eq',$follow_user_id),'befollow_user_id'=>array('eq',$befollow_user_id)))->delete();
        if($data){
            $data = array(
                'code'=>1,
                'msg'=>'取关成功',
                'data'=>$data,
            );
        }else{
            $data = array(
                'code'=>0,
                'msg'=>'请求失败',
            );
        }
        return json_encode($data);
    }


    /**
     * 查看关注的好友列表
     * @param $id        /当前用户id
     * @param $be_id    /被关注人id
     * @return mixed    /回执信息
     */
    public function follow_user($follow_user_id,$befollow_user_id){         //待传过来的当前登录的用户id
        $model = D('follow');
       if(IS_POST){
           if($model->create(I('post.'),1)){
               if($data = $model->add()){
                   return return_json($data);
               }
           }
       }

    }
}
