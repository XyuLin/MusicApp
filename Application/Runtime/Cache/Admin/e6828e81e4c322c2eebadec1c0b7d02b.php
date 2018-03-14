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
    <form method="post" class="form-x" action="/appmusic/index.php/Admin/Type/edit/id/1.html" enctype="multipart/form-data">
      <div class="form-group">
        <div class="label">
          <label>分类姓名：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($data['type_name']); ?>" name="type_name" data-validate="required:分类姓名" />
          <div class="tips"></div>
        </div>
      </div>

      <input type="hidden" name="id" value="<?php echo $data['id']; ?>" />

      <div class="form-group">
        <div class="label">
          <label>分类级别：</label>
        </div>
        <div class="field">
          <select name="parent_id" >
          <option value="0">顶级分类</option>
            <?php foreach($catdata as $v): if($v['id'] == $data['parent_id']){ $selected = 'selected="selected"'; }else{ $selected = ""; } ?>
            <option <?php echo $selected;?>  value="<?php echo ($v['id']); ?>" ><?php echo str_repeat('-',8*$v['level']).$v['type_name'];?></option>
          <?php endforeach; ?>
        </select>
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>是否推荐：</label>
        </div>
        <div class="field">
          <input type="radio" name="is_floor" value="是" <?php if($data['is_floor']=='是'){echo 'checked="checked"';}?>/>是
          <input type="radio" name="is_floor" value="否" <?php if($data['is_floor']=='否'){echo 'checked="checked"';}?>/>否
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