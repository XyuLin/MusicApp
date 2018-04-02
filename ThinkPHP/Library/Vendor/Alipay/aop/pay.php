<?php 


require_once 'AopClient.php';
require_once './request/AlipayTradeAppPayRequest.php';

$aop = new AopClient;
$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
$aop->appId = "2017112200094270";
$aop->rsaPrivateKey = "MIICXAIBAAKBgQCWtJCtNp/1pTJ5g/7qLLV76zpdc6AdO2OmWJQpXzBZY082E0stVNa9e1LQ/bsFbo176CDLP2ghsC2j2Uo9uiTIoI7GSNrE5OXwpzZTk5jcuMGUoqDloMMqP1XZDNfl690SuqQILhDpASSXd+kJ8gMpPXklqZY64NdQWwk8egCZMQIDAQABAoGAYKXJxpuqd9GKji1dVHZ5qM/Q7U+SlkyY+nwCAIpAKoMqsGYtJxQqZvfow4iE4NXmPFlAdrfiIwCmNFbMasuufFOy9dZJTECJpYxiit3L1EtrQ2QAcBPsywrQmgfXneDTw4ZdgD562H/yia2xGtQENvV2bVHGvIODFX7WPe28b7ECQQDF5BsQhEaH3fSmop4HV9C2N0D+QsU896neQ+TLgiBKmg0mXvf3OaTkqfjA8luabsxrhmDiWJ2ZqveTx5Ei4SA1AkEAwvVom2bO5miw8TiPugF0u0WYv9ItsL/z/UbeLCouH/53D3eC+0g/raDIVuQcxZPFmynIwSY5GOh/rRjd7S3sjQJAXLrVC9pQCp6xY9xT3PEDdj9SD1NOhZEwYlzCO7LQWuTxQIfD/m9em2Ailpd64VUGKWSgxa/sufNpbDBaDFQd+QJAGCoIxDIy9NYHEG79SYXCrS+MJkJrzVuNZ1gwU3MK8oY8xZr/jhu/YMrr6fEuK8MMHBZKvr35F8BBivfpEeu+RQJBALWE+xobfQYPrs5bwh+TbqCeCv0j1jUWiYyDD4ZNm5/1ejic3Sd2LZiEPmz4p4Cwxfu0J03CRZc4p71ev4K73xI=";
$aop->format = "json";
$aop->charset = "UTF-8";
$aop->signType = "RSA";
$aop->alipayrsaPublicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';//对应填写
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
$request = new AlipayTradeAppPayRequest();
//SDK已经封装掉了公共参数，这里只需要传入业务参数

if($_POST['order']){

$order_trade_no = $_POST['order'];

}else{


	return json_encode(['code'=>2,'支付失败']);


	die;


}



//********注意*************************下面除了body描述不是必填，其他必须有，否则失败
$bizcontent = "{\"body\":\"做雷锋精神的种子证书邮费\"," 
                . "\"subject\": \"做雷锋精神的种子证书邮费\","
                . "\"out_trade_no\": \"$order_trade_no\","
                . "\"timeout_express\": \"30m\"," 
                . "\"total_amount\": \"22\","
                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                . "}";

$request->setNotifyUrl("http://songfuniaops.com/lfpay/Alipay/notify.php");//你在应用那里设置的异步回调地址

$request->setBizContent($bizcontent);



//这里和普通的接口调用不同，使用的是sdkExecute		
$response = $aop->sdkExecute($request);

 //$response = str_replace("%7B","{",$response);
 //$response = str_replace("%7D","}",$response);
 //$response = str_replace("%22",'"',$response);
 
 
echo  json_encode(['code'=>1,'orderstring'=>$response]);

//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题//就是orderString 可以直接给客户端请求，无需再做处理。这里就是方便打印给你看，具体你直接可以在方法那里return出去，不用加htmlspecialchars，或者响应给app端让他拿着这串东西调起支付宝支付



 ?>
