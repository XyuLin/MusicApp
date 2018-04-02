<?php

        require_once('./aop/AopClient.php');
        $aop = new \AopClient;
        //$public_path = "key/rsa_public_key.pem";//公钥路径
        $aop->alipayrsaPublicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB";
        //此处验签方式必须与下单时的签名方式一致
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        //验签通过后再实现业务逻辑，比如修改订单表中的支付状态。
        /**
         *  ①验签通过后核实如下参数out_trade_no、total_amount、seller_id
         *  ②修改订单表
        **/
        //打印success，应答支付宝。必须保证本界面无错误。只打印了success，否则支付宝将重复请求回调地址。
       


		if($flag){
			  


			  $out_trade_no = $_POST['out_trade_no'];
			
		      $post_data = array();
		      $post_data['type'] = '2';

		      $post_data['code'] = $out_trade_no;


		      $url='http://songfuniaops.com/leifeng/index.php/index/order/update';
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
				
		    echo 'success';	
					
	        echo file_put_contents("test.txt",$_POST['out_trade_no']);
		
		
		}else{
			
			
			echo file_put_contents("test.txt","fail");
		    echo 'fail';
			
			
		}





?>

