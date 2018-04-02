<?php
class makeorder{
	
	
	/*获取*/
public function make($arr_id,$openId,$carnum,$ordertail,$moeny,$order_timea,$poundage){
		
		$db="mysql:host=localhost;dbname=caocao";
		$user="root";
		$pas="root";
		$pdo =new PDO($db,$user,$pas);
		$sql="select* from z_orderdetail where ordertail ='".$ordertail."'";

		

		$result=$pdo->query($sql);
		
		$rows=$result->fetchAll(PDO::FETCH_BOTH);
		if(!$rows){
		$pdo->exec("insert into z_orderdetail(arr_date,openid,carnum,ordertail,money,time,poundage) values('".$arr_id."','".$openId."','".$carnum."','".$ordertail."','".$moeny."','".$order_timea."','".$poundage."')");	
		}
		
	
	}
	//获取优惠券
	public function coupon($openid)
	{
		$db="mysql:host=localhost;dbname=caocao";
		$user="root";
		$pas="root";
		$pdo =new PDO($db,$user,$pas);
		$sql="select* from z_coupon where openid ='".$openid."'";
		$result=$pdo->query($sql);
		
		$rows=$result->fetchAll(PDO::FETCH_BOTH);
	
		
		
		
		if($rows){
		
		return $rows[0]['coupon'];		
		}else{
			$a = 0;
			return $a;
			
			
		}
		
		
	}
	
	
	
}
