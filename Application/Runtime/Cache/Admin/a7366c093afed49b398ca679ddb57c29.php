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
  <div class="panel-head"><strong class="icon-reorder"> banner列表</strong></div>
  <div class="padding border-bottom">  
  <button type="button" class="button border-yellow" onclick="window.location.href='#add'"><span class="icon-plus-square-o"></span> 添加banner</button>
  </div>
  <table class="table table-hover text-center">
    <tr>
      <th width="10%">ID</th>
      <th width="20%">图片</th>
      <th width="15%">主题</th>
      <th width="20%">描述</th>
      <th width="10%">排序</th>
      <th width="15%">操作</th>
    </tr>

    <?php if(is_array($info)): foreach($info as $key=>$v1): ?><tr>
        <td><?php echo $v1['id']?></td>
        <td><img src="/appMusic/Public/Upload/<?php echo $v1['img']?>" alt="" width="120" height="50" /></td>
        <td><?php echo $v1['title']?></td>
        <td><?php echo $v1['deso']?></td>
        <td><?php echo $v1['sort']?></td>
        <td><div class="button-group">
        <a class="button border-main" href="<?php echo U('edit?id='.$v1['id']);?>"><span class="icon-edit"></span> 修改</a>
        <a class="button border-red" href="<?php echo U('del?id='.$v1['id']);?>" onclick="return confirm('请确认删除当前banner！！！');"><span class="icon-trash-o"></span> 删除</a>
        </div></td>
      </tr><?php endforeach; endif; ?>

  </table>
  <div class="panel admin-panel margin-top" style="width: 1120px;height: 30px;">
    <span style="float: left;margin: 6px 0 2px 500px;"><font color="red"><?php echo ($page); ?></font></span>
  </div>
</div>
<script type="text/javascript">
function del(id,mid){
	if(confirm("您确定要删除吗?")){
	
	}
}
</script>


<div class="panel admin-panel margin-top" id="add">
  <div class="panel-head"><strong><span class="icon-pencil-square-o"></span> 增加内容</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="<?php echo U('Banner/add');?>" enctype="multipart/form-data">
      <div class="form-group">
        <div class="label">
          <label>标题：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="" name="title" data-validate="required:请输入标题" />
          <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>图片：</label>
        </div>
        <div class="field">
          <input type="file" id="url1" name="img" class="input tips" style="width:25%; float:left;"  value="" data-toggle="hover" data-place="right" data-image="" />
          <input type="button" class="button bg-blue margin-left" id="image1" value="+ 浏览上传"  style="float:left;">
          <div class="tipss">图片尺寸：1920*500</div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>描述：</label>
        </div>
        <div class="field">
          <textarea type="text" class="input" name="deso" style="height:120px;" value=""></textarea>
          <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>排序：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="sort" value="100" data-validate="required:默认不填为100,number:排序必须为数字" />
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