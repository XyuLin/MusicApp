<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-25
 * Time: 13:38
 */

namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{

    public $dateList = [
        '1' => '08:00-08:45',
        '2' => '09:00-09:45',
        '3' => '10:00-10:45',
        '4' => '11:00-11:45',
        '5' => '12:00-12:45',
        '6' => '13:00-13:45',
        '7' => '14:00-14:45',
        '8' => '15:00-15:45',
        '9' => '16:00-16:45',
        '10' => '17:00-17:45',
        '11' => '18:00-18:45',
        '12' => '19:00-19:45',
        '13' => '20:00-20:45',
        '14' => '21:00-21:45',
        '15' => '22:00-22:45',

    ];
    /**
     * @return mixed /获得老师的个人信息 可根据name搜索
     */
	public function showTeach(){
	$name = I('get.name');	
    V($name);			#老师的名字
	$education_age = I('get.education_age');        #老师的教龄
        $model = D('user');
        $data = $model->getTeach($name);
        $this->ajaxReturn($data);
    }

    public function index()
    {
        $data = [];
        // 课程数据
        $model = D('sendSubjects');
        // 获取筛选条件
        $screen = I('post.');
        
          // 判断是否选择页码
          if(I('post.page')){
            $page = I('post.page');
          }else{
            $page = 1;
          }
        //筛选条件
        $type = D('subjectsType');
        $list = [
            'type'  => $type->getType(1),
            'range' => $type->getType(2),
            'price' => $type->getType(3),
            'educa' => $type->getType(4),
            'sex'   => $type->getType(5),
        ];

        $data['screenList'] = $list;

        // V($data);die;
        // banner 数据
        $data['banner'] = D('banner')->getInfo();

        $data['list'] = $model->getClassList($screen,$page);

        $msg = [
            'code' => '1',
            'msg'  => '请求成功',
            'data' => [$data],
        ];
        $this->ajaxReturn($msg);
    }

    public function indexSearch()
    {
        $str = I('post.str');
        $model = D('teacher');
        $where['name|education_age']  = ['like','%'.$str.'%'];
        $userL = $model->alias('a')->field('a.id')->join(' LEFT JOIN __USER__ b ON a.id = b.id' )
                ->where($where)->select();

                foreach ($userL as $k => &$v) {
                    # code...
                    $v = $v['id'];
                }
                unset($v);
 
        $sub_type = D('subjects_type');
        $isT = $sub_type->where(['subject_name'=>['like','%'.$str.'%']])->find();

        $user = D('user');
        if($isT){
            $subject = D('sendSubjects');
            $list = $subject->distinct(true)->where(['subjects_type'=>$isT['id'],'status'=>'1'])->getField('teacher_id',true);

            if(empty($userL) == false && empty($list) == false){
                $data = array_merge($userL,$list);
                $data = array_unique($data);
                $data = array_merge($data);
                    
                    foreach ($data as $key => &$value) {
                        # code...
                        $value = $user->getInfo($value);
                    }
                    unset($value);
            }    
        }


        if(empty($userL) && empty($isT)){
            $msg = returnMsg(0,'没有匹配的数据');
            $this->ajaxReturn($msg);
        }

        if(empty($userL)){
            unset($userL);
            $data = $list;
            foreach ($data as $key => &$value) {               
                $value = $user->getInfo($value);
            }
                unset($value);
        }
        if(empty($isT)){
            unset($list);
            $data = $userL;
            foreach ($data as $key => &$value) {
                $value = $user->getInfo($value);
            }
                unset($value);
        }

        $msg = returnMsg(1,'请求成功',$data);
        $this->ajaxReturn($msg);

    }

    // 课程详情
    public function subjectInfo()
    {
        $id = I('post.id');
        $model = D('sendSubjects');
        $info = $model->getInfo($id,1);
        $msg = returnMsg(1,'请求成功',$info);
        $this->ajaxReturn($msg);
    }


    public function nd()
    {
        echo "nihao";
    }

    public function sendC()
    {
        // $model = D('classHour');
        // $time = $model->getTime(time());

        // // 当前时间
        // $day = date('Y-m-d H:i:s',time());
        // // 几号
        // $timeD = date('d',strtotime($day));

        // $weekD = date('d',strtotime($time['begin']));

        // $sunday = ($timeD - $weekD)+1;

        // $where = [
        //     'start_time' => ['eq',$time['begin']],
        //     'end_time'   => ['eq',$time['end']],
        //     'remind'     => ['eq','1'],
        //     'sunday'     => ['eq',$sunday],
        // ];
        // // 查询需要提醒的用户
        // $ulist = $model->distinct(true)->where($where)->getField('stu_id',true);

        // $user = D('user');
        // foreach($ulist as $value){
        //     $data =  $user->field('id,name,phone')->where(['id'=>$value])->find();
        //     $data['message'] = $data['name']."同学：今天有安排课程哦。请不要迟到！";
        //    // sendCode($data['phone'],$data['message'],1);
        // }
        // unset($value);
        $str = '同学：今天有安排课程哦。请不要迟到！';
        $phone = '13861532950';
        sendCode($phone,$str);
    }

    public function typeList()
    {
        $model = D('subjectsType');
        $list = $model->getType('1');

        $msg = returnMsg(1,'请求成功',$list);
        $this->ajaxReturn($msg);
    }
}
