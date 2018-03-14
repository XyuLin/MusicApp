<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title></title>
    <link rel="stylesheet" href="/appmusic/Public/Admin/css/pintuer.css">
    <link rel="stylesheet" href="/appmusic/Public/Admin/css/admin.css">
    <link rel="stylesheet" href="/appmusic/Public/Admin/css/h_banner.css">
    <script src="/appmusic/Public/Admin/Js/jquery.js"></script>
    <script src="/appmusic/Public/Admin/Js/pintuer.js"></script>
</head>


<body>
<div class="panel admin-panel">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>学员注册</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="/appmusic/index.php/Admin/User/edit/id/1.html" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo ($info['id']); ?>" />
      <div class="form-group">
        <div class="label">
          <label>姓名：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['username']); ?>" name="username" data-validate="required:请输入姓名" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>密码：</label>
        </div>
        <div class="field">
          <input type="password" class="input w50" value="<?php echo ($info['password']); ?>" name="password" data-validate="required:请输入密码" />
          <div class="tips"></div>
        </div>
      </div>



      <div class="form-group">
        <div class="label">
          <label>性别：</label>
        </div>
        <div class="field">

          <select name="sex" class="input w50">
            <option value="">请选择...</option>
            <?php if($info['sex'] == '男'): ?><option selected="selected" value="男" >男</option>
              <?php elseif($info['sex'] == '女'): ?>
              <option selected="selected" value="女">女</option><?php endif; ?>
            <option  value="女">女</option>

          </select>
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>年龄：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['age']); ?>" name="age" data-validate="required:请输入年龄" />
          <div class="tips"></div>
        </div>
      </div>


      <div class="form-group">
        <div class="label">
          <label>办公地址：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['office_address']); ?>" name="office_address" data-validate="required:请输入地址" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>家庭地址：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['home_address']); ?>" name="home_address" data-validate="required:请输入地址" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>手机号：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['phoen']); ?>" name="phoen" data-validate="required:请输入电话" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>教育年限：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['education_age']); ?>" name="education_age" data-validate="required:请输入电话" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>证件照：</label>
        </div>
        <div class="field">
          <input type="file" id="url1" name="car" class="input tips" style="width:25%; float:left;"  value=""  data-toggle="hover" data-place="right" data-image="" />
          <input type="button" class="button bg-blue margin-left" id="image1" value="+ 浏览上传"  style="float:left;">
          <div class="tipss">图片尺寸：500*500</div>
        </div>
      </div>


      <div class="form-group">
        <div class="label">
          <label>验证码：</label>
        </div>
        <div class="field">
          <input type="text" style="width: 245px;" class="input input-big" name="chkcode" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
          <img style="position:absolute;left: 158px;top: 1px;width: 85px;height: 42px;" src="<?php echo U('Login/chkcode');?>" onclick="this.src='<?php echo U('Login/chkcode'); ?>#'+Math.random();" alt="" width="100" height="32" class="passcode" style="height:43px;cursor:pointer;">
          <div class="tips"></div>
        </div>
      </div>



      <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main icon-check-square-o" type="submit"> 提交</button>
        </div>
      </div>
    </form>
  </div>
</div>

</body>

</html>