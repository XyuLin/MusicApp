<?php   
  
class TokenAction extends action{  

    public function tokenValidate(){  

        $token = $_POST['token'];  
        V($token);
        $server_token =   '8859645';// md5('@*daike_quanApp');  
        if(empty($token)){  
            $result=array('err_no' =>0000,'err_msg'=>"Illegal request");      
            return ($result); exit;  
        }  

        if($token!=$server_token){  
             $result=array('err_no' =>1111,'err_msg'=>"Illegal request");      
            return ($result); exit;  
        }  
        
    }  

}  
  
?>  