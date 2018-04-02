<?php 




ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();


$db="mysql:host=localhost;dbname=caocao";
$user="root";
$pas="root";
$pdo =new PDO($db,$user,$pas);

$sql="select* from z_query where openid='".$openId."'";



//查询语句使用  query() 返回的是PDOstatement对象
//如果想获得具体的数据   则需要获得这个对象的方法   fetchAll()参数是类常量，表示返回什么格式的数据
$result=$pdo->query($sql);
$row=$result->fetch();


if($row){
	header("Location:http://cczf.vlusi.com/caocao/public/weixin/record?openid=$openId"); 
	
}else{
	header("Location:http://cczf.vlusi.com/caocao/public/weixin/query"); 
	

}


?>

