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
<form method="post" action="" id="listform">
  <div class="panel-head"><strong class="icon-reorder"> 老师列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
  <div class="padding border-bottom">
    <ul class="search" style="padding-left:10px;">
      <li> <a class="button border-main icon-plus-square-o" href="<?php echo U('add');?>"> 添加老师信息</a> </li>
      <li>搜索：</li>
      <li>
        <input type="text" placeholder="请输入搜索关键字" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" />

        <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
    </ul>
  </div>
  <table class="table table-hover text-center">
    <tr>
      <th width="5%" style="text-align:left; padding-left:20px;">ID</th>
      <th width="10%">姓名</th>
      <th width="5%">年龄</th>
      <th width="5%">性别</th>
      <th width="15%">办公地址</th>
      <th width="5%">家庭住址</th>
      <th width="10%">身份证</th>
      <th width="8%">教育年限</th>
      <th width="5%">注册时间</th>
      <th width="25%">操作</th>
    </tr>

    <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
        <td style="text-align:left; padding-left:20px;"><?php echo $v['id'];?></td>
        <td><?php echo $v['username'];?></td>
        <td><?php echo $v['age'];?></td>
        <td><?php echo $v['sex'];?></td>
        <td><?php echo $v['office_address'];?></td>
        <td><?php echo $v['home_address'];?></td>
        <td><img style="width: 55px" src="/appmusic/Public/Upload/<?php echo $v['teacher_car'];?>"/></td>
        <td><?php echo $v['education_age'];?></td>
        <td><?php echo $v['addtime'];?></td>
        <td>
          <div class="button-group">
            <a class="button border-main" href="<?php echo U('sel?id='.$v['id']);?>"><span class="icon-edit"></span> 查看评论</a>
            <a class="button border-main" href="<?php echo U('see_video?id='.$v['id']);?>"><span class="icon-edit"></span> 查看教程</a>
            <a class="button border-main" href="<?php echo U('edit?id='.$v['id']);?>"><span class="icon-edit"></span> 编辑修改</a>
            <a class="button border-red" href="<?php echo U('del?id='.$v['id']);?>" onclick=" return confirm('请确认删除当前账号！！！');"><span class="icon-trash-o"></span>删除信息</a>
          </div>
        </td>
      </tr><?php endforeach; endif; ?>
  </table>
  <div class="panel admin-panel margin-top" style="width: 1120px;height: 30px;">
    <span style="float: left;margin: 6px 0 2px 500px;"><font color="red"><?php echo ($page); ?></font></span>
  </div>
  </div>
</form>
<script type="text/javascript">

//搜索
function changesearch(){	
		
}

//单个删除
function del(id,mid,iscid){
	if(confirm("您确定要删除吗?")){
		
	}
}
</script>

</body>


</html>