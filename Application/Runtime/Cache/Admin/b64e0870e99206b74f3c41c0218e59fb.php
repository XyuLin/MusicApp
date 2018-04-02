<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title></title>
    <link rel="stylesheet" href="/appMusic/Public/Admin/css/pintuer.css">
    <link rel="stylesheet" href="/appMusic/Public/Admin/css/admin.css">
    <link rel="stylesheet" href="/appMusic/Public/Admin/css/h_banner.css">
    <script src="/appMusic/Public/Admin/Js/jquery.js"></script>
    <script src="/appMusic/Public/Admin/Js/pintuer.js"></script>
</head>


<body>
<div class="panel admin-panel">
  <div class="panel-head"><strong><span class="icon-key"></span> 修改会员密码</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="/appMusic/index.php/Admin/Admin/edit/id/1.html">
      <input type="hidden" name="id" value="<?php echo $data['id'];?>">
      <div class="form-group">
        <div class="label">
          <label for="sitename">管理员帐号：</label>
        </div>
        <div class="field">
          <label style="line-height:33px;">
           <?php echo session('username');?>
          </label>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label for="sitename">账号：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="username" value="<?php echo ($data['username']); ?>" size="50" placeholder="请输入新账号" data-validate="required:请输入新账号,length#>=5:新账号不能小于6位" />
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label for="sitename">新密码：</label>
        </div>
        <div class="field">
          <input type="password" class="input w50" name="password" size="50" placeholder="请输入新密码" data-validate="required:请输入新密码,length#>=5:新密码不能小于5位" />
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label for="sitename">确认新密码：</label>
        </div>
        <div class="field">
          <input type="password" class="input w50" name="cpassword" size="50" placeholder="请再次输入新密码" data-validate="required:请再次输入新密码" />
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label for="sitename">验证码：</label>
        </div>
        <div class="field">
          <input type="text" style="width: 245px;" class="input input-big" name="chkcode" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
          <img style="position:absolute;left: 158px;top: 1px;width: 85px;height: 42px;" src="<?php echo U('Login/chkcode');?>" onclick="this.src='<?php echo U('Login/chkcode'); ?>#'+Math.random();" alt="" width="100" height="32" class="passcode" style="height:43px;cursor:pointer;">
        </div>
      </div>
      
      <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main icon-check-square-o" type="submit" onclick="return confirm('请确认修改当前账号密码！！！');"> 提交</button>
        </div>
      </div>      
    </form>
  </div>
</div>
</body>

</html>