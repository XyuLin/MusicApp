<?php
/**
 * Created by PhpStorm.
 * User: GZJ
 * Date: 2017/5/18
 * Time: 10:43
 */

/*公共函数
*/
/***************过滤器*******************/
function rmoveXss($data){
    require_once './library/HTMLPurifier.auto.php';
    $_clean_xss_config = HTMLPurifier_Config::createDefault();
    $_clean_xss_config->set('Core.Encoding', 'UTF-8');
    //设置要保留的标签
    $_clean_xss_config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
    $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
    $_clean_xss_config->set('HTML.TargetBlank', TRUE);
    $_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
    //执行过滤
      return $_clean_xss_obj->purify($data);

}


function return_json($data){
    if($data){
        $data = array(
            'code'=>1,
            'msg'=>'获取成功',
            'data'=>$data,
        );
    }else{
        $data = array(
            'code'=>0,
            'msg'=>'获取失败',
        );
    }
    return json_encode($data);
}

function V($data){
    echo '<pre>';
    var_dump($data);
}


function sendCode($phone,$code)
{
      $post_data = array();
      $post_data['userid'] = '400';

      $post_data['account'] = 'zyjy';

      $post_data['password'] = 'zyjy123';
      $post_data['cmd'] = '1'; //指令1表示验证帐号
      $a = rand(1000,9999);
  
      $post_data['mobile'] = $phone;
      $post_data['content'] = "【艺家教】你的验证码为[$code]五分钟内有效。";
      $post_data['sendTime'] = ''; //指令1表示验证帐号
      $post_data['action'] = 'send'; //指令1表示验证帐号
      $post_data['cmd'] = '1'; //指令1表示验证帐号

      $post_data['extno'] = ''; //指令1表示验证帐号
      $url='http://123.firesms.cn/sms.aspx';
      $o='';


   
      foreach ($post_data as $k=>$v)
      {
         $o.="$k=".$v.'&';
      }


      $post_data=substr($o,0,-1);



      // 初始化一个curl会话
      $ch = curl_init();
      

      $this_header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");


      curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);

      //设置提交的方式(post);
      curl_setopt($ch, CURLOPT_POST, 1);
      // 设置header
      curl_setopt($ch, CURLOPT_HEADER, 0);
      // 设置需要抓取的URL(http://116.255.238.170:7518/sms.aspx)
      curl_setopt($ch, CURLOPT_URL,$url);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER,$url);
      //请求的参数 你吧                                                                                                             
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。


      $result = curl_exec($ch);
}

function tokenValidate()
{  
  $token = $_POST['token'];
  $server_token = C('TOKEN');// md5('@*daike_quanApp');  
  if(empty($token)){  
      $result=array('code' =>0,'msg'=>"缺少serviceToken");      
      return ($result); exit;  
  }  

  if($token!=$server_token){ 
       $result=array('code' =>0,'err_msg'=>"serviceToken不匹配");      
      return ($result); exit;  
  }  
  
}

function upNumber($str)
{
    return preg_replace('/\D/s', '', $str);
}

function returnMsg($code,$msg,$data = '')
{
  if($code != 0){
      $data = array(
                'code'  =>  $code,
                'msg'   =>  $msg,
                'data'  =>  $data,
              );
  }else{
      $data = array(
                  'code'  =>  $code,
                  'msg'   =>  $msg,
                );
  }
    return $data;
}
