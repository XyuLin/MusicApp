<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
	<form action="http://localhost/appMusic/index.php/Home/User/avatar" method="post" enctype="multipart/form-data" >

 	 <label>选择图片文件</label>

 	<input name="avatar" type="file" accept="image/gif, image/jpeg"/>

  <input name="upload" type="submit" value="上传" />

</form>
</body>
</html>