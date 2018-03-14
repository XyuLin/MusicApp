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
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>添加分类</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="/appmusic/index.php/Admin/Type/add.html" enctype="multipart/form-data">
      <div class="form-group">
        <div class="label">
          <label>分类姓名：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="" name="type_name" data-validate="required:分类姓名" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>分类级别：</label>
        </div>
        <div class="field">
          <select name="parent_id" class="input w50">
            <option class="label" value="0">顶级分类</option>
            <?php if(is_array($typeData)): foreach($typeData as $key=>$v): ?><option value="<?php echo ($v['id']); ?>" ><?php echo str_repeat('-',2*$v['level']).$v['type_name']; ?></option><?php endforeach; endif; ?>
          </select>
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>是否推荐：</label>
        </div>
        <div class="field">
          <select name="is_floor" class="input w50">
            <option value="">请选择...</option>
            <option value="1" >是</option>
            <option value="0">否</option>
          </select>
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