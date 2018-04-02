<?php 

namespace Home\Model;

class AlipayModel {
	<?php

/**
* 
*/
namespace Home\Controller;
use Think\Controller;

class AlipayController extends Controller
{
	public function orderNext()
	{
		vendor('Alipay.aop.AopClient');
		Vendor('Alipay.aop.request.AlipayTradeAppPayRequest');

		$aop = new \AopClient;

		$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$aop->appId = "2018031502380752";
		$aop->rsaPrivateKey = "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCzmMdIgsIwSF9zfI742T2RCnTGfwZsyQfxCv5XtvRpAItzNlJpx4QtszGnZMvAbSWTazZQmd3LkB6AzQIpJ6RMCIIC8GAzqt2IaiiHqOxW0+XrQneLiYZoqgxpVeSDroO3gEdGff9mbwK0tOG/gR24TSUpMrW88YZaTavuBrX6JZ4ItozVFrUqMRIBflDPcoBPnfIkK5qhe/nYuGh8IfqC7PhnaFYP8cKYbv6UBo6h8fQSVSGRbmbbgn/tRo3FAYNVx0kyrtn1hZtJyJ+ATLNnr1ttnzDqtL6yT/znC7FrXb6czwQ3h2R6KDygHg0+RmxnBSbzDG4fGyPcqje4ICU/AgMBAAECggEAcaTVRX7oXiENtUg2OCVPHf5d0lUCvcefrNlmBB8THXZox2iyUZkcfMaNPv07KPJn+pa28d3LSUpS4vHW0i/xeONRUYbFeSw/rlhueRldI7xGGs4eUz+cu5IO9ICTbvf5BR64+1QzTqAkODDVQJb1NXAreZxK/9pdR7sqdh3s/PHMT6a4qKSVWKKIg7qaihiK8S+iN4sqTeZcX8BrvMXmljo0O9cMZro0ltojKHZJ6hL2Vo1Np8w9Co7yOYnzaY11G5wWVjhrbsh5pqZaJ2BpDX1EYugLRLmKOhomKxAhbG23wpjNBGP92qnfNB8qJq0ssGA/wZsgGowVZU81R2BjYQKBgQDqiXsJ8N7Nr/uwLJn8mnxGRiiScvWgDmvOOJvegRwk+2GY6xLj65pqb+H/UbwHYBIFY0IFU6DnUJf9s3onctgmB5FmrhLT62DqgJhIkHswYduhVs85zBakEfdfLNmIKxrKwUBzPt0GHHMH1Vs0JwsILwWKffsPPUtO6hZ0Gn3x9wKBgQDECDU/9DL6ZY3PuWPNdJAqfqIoTLD5LDoYv048Y6KPyNnxHBYum/XjGMrdPcNyCCaGm5gtW+EACam5E8h+DbVd/YUsRdLxJG3aFu7skB5zfeSBmpTc1GMFbhUH7CjTCJXLfugwtmJs5MDJsCo76UuxRk1RWxX6Qw/yRhJCquuU+QKBgHqCb8Okjy1P5J+E3LanF405ro25APanMcbZqQmT1Vi3+qX+/LITamh4ostULWyI0UEBNQZFRcIuRgZejss2YROUVm4VNX5+7/PuUh1tYvvF0gy3pK0jHxx6ygVsDwDiyy7nvTu1tYwwpiGMYASg9FFFBpbt8YtnUAQboLjq8ajHAoGAPBVj9NktMtAjmhJxg4yBj3TG0OFIsOacW+9u1FWZgsaF/j+kkN6ei9+SdiMzH2VeIY3ZI7XwjaloiuCEaBZdi5Rj2AECuYR34wt6aal2m+FvQ0YU8GZsf0KRUZ9Rl/UerIOWOS/9lPC8IlVLbJeB6rDoi9IGN3GMlq5hdGucz0kCgYEAh/CkVKdESyFlvw7LoyRc0RddouX1wvfRC6KtjTB4WLidQ1f37zj+P4qlgXRJ/JatGIdoQgmi76Ve/klOow0h4mVQudyjoAr0xI0J3rGkrH6XWTAEf2U5/WoHfs+kQKKlItbUrcpgBcPc6tTgBjvSVigju4vLRTpECG2SE9zPkf4=";
		$aop->format = "json";
		$aop->charset = "UTF-8";
		$aop->signType = "RSA";
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAs5jHSILCMEhfc3yO+Nk9kQp0xn8GbMkH8Qr+V7b0aQCLczZSaceELbMxp2TLwG0lk2s2UJndy5AegM0CKSekTAiCAvBgM6rdiGooh6jsVtPl60J3i4mGaKoMaVXkg66Dt4BHRn3/Zm8CtLThv4EduE0lKTK1vPGGWk2r7ga1+iWeCLaM1Ra1KjESAX5Qz3KAT53yJCuaoXv52LhofCH6guz4Z2hWD/HCmG7+lAaOofH0ElUhkW5m24J/7UaNxQGDVcdJMq7Z9YWbScifgEyzZ69bbZ8w6rS+sk/85wuxa12+nM8EN4dkeig8oB4NPkZsZwUm8wxuHxsj3Ko3uCAlPwIDAQAB';//对应填写
		//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
		$request = new \AlipayTradeAppPayRequest();
		//SDK已经封装掉了公共参数，这里只需要传入业务参数

		if($_POST['order']){

			$order_trade_no = $_POST['order'];

		}else{
			return json_encode(['code'=>2,'支付失败']);
			die;
		}

		//********注意*************************下面除了body描述不是必填，其他必须有，否则失败
		$bizcontent = "{\"body\":\"艺家教测试支付\"," 
		                . "\"subject\": \"钢琴教学课\","
		                . "\"out_trade_no\": \"$order_trade_no\","
		                . "\"timeout_express\": \"30m\"," 
		                . "\"total_amount\": \"58\","
		                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
		                . "}";

		$request->setNotifyUrl("http://49.4.70.109/Home/Alipay/notify.php");//你在应用那里设置的异步回调地址

		$request->setBizContent($bizcontent);



		//这里和普通的接口调用不同，使用的是sdkExecute		
		$response = $aop->sdkExecute($request);

		 //$response = str_replace("%7B","{",$response);
		 //$response = str_replace("%7D","}",$response);
		 //$response = str_replace("%22",'"',$response);
		 
		 
		echo  json_encode(['code'=>1,'orderstring'=>$response]);

		//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题//就是orderString 可以直接给客户端请求，无需再做处理。这里就是方便打印给你看，具体你直接可以在方法那里return出去，不用加htmlspecialchars，或者响应给app端让他拿着这串东西调起支付宝支付
	}

}
