<?php 





ini_set('date.timezone','Asia/Shanghai');

require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
require_once 'makeorder.php';

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



//②、统一下单
$input = new WxPayUnifiedOrder();

 //处理订单将订单
$order_timea = date("YmdHis");
// $makeorder->make($arr_id,$openId,$carnum,$ordertail,$moeny,$order_timea,$poundage);
$input->SetBody("加盟支付");
$input->SetAttach("加盟支付");


$input->SetOut_trade_no(WxPayConfig::MCHID.$order_timea);
$input->SetTotal_fee('100000');
//$input->SetTotal_fee('1');
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://cczf.vlusi.com/paydemo/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);








//
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */

 






?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>订单确认</title>

    <link rel="stylesheet" href="./assets/css/main.css">

    <script type="text/javascript" src="./assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/js/js/main.js"></script>

    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
			 //alert(res.err_code+res.err_desc+res.err_msg);
			 window.location.href = 'http://cczf.vlusi.com/caocao/public/weixin/query';
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	//function editAddress()
	//{
		//WeixinJSBridge.invoke(
			//'editAddress',
			//<?php echo $editAddress; ?>,
			//function(res){
				//var value1 = res.proviceFirstStageName;
				//var value2 = res.addressCitySecondStageName;
				//var value3 = res.addressCountiesThirdStageName;
				//var value4 = res.addressDetailInfo;
				//var tel = res.telNumber;
				
				//alert(value1 + value2 + value3 + value4 + ":" + tel);
		//	}
		//);
//	}
	
	//window.onload = function(){
		//if (typeof WeixinJSBridge == "undefined"){
		    //if( document.addEventListener ){
		        //document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    //}else if (document.attachEvent){
		      //  document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        //document.attachEvent('onWeixinJSBridgeReady', editAddress);
		  //  }
		//}else{
			//editAddress();
		//}
	//};
	
	</script>
</head>
<body>
<div class="page page4">
        <div class="info_box">
        

       
            <p class="tip gray">办理周期2-5个工作日</p>
        </div>
        <div class="bottom">
            <span class="fl">合计：<span class="red">1000</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="gray f14">共<span class="f14">1</span>笔</span></span>
           
            <button class="fr" style=" width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
            <div class="clearfix"></div>
        </div>
    </div>

 
</body>
</html>