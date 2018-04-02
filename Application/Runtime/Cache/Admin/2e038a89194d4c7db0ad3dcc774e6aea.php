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
<form method="post" action="" id="listform">
  <div class="panel-head"><strong class="icon-reorder"> 用户列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
  <div class="padding border-bottom">
    <ul class="search" style="padding-left:10px;">
      <li> <a class="button border-main icon-plus-square-o" href="<?php echo U('add');?>"> 添加信息</a> </li>
      <li>搜索：</li>
      <li>
        <input type="text" placeholder="请输入搜索关键字" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" />

        <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
    </ul>
  </div>
  <table class="table table-hover text-center">
    <tr>
      <th width="3%" style="text-align:left; padding-left:20px;">ID</th>
      <th width="3%">姓名</th>
      <th width="5%">年龄</th>
      <th width="5%">性别</th>
      <th width="8%">办公地址</th>
      <th width="7%">家庭住址</th>
      <th width="10%">毕业院校</th>
      <th width="5%">教育年限</th>
      <th width="5%">审核状态</th>
      <th width="5%">注册时间</th>
      <th width="35%">操作</th>
    </tr>

    <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
        <td style="text-align:left; padding-left:20px;"><?php echo $v['id'];?></td>
        <td><?php echo $v['name'];?></td>
        <td><?php echo $v['age'];?></td>
        <td><?php if($v['sex'] == 1){echo '男';}else{echo '女';} ;?></td>
        <td><?php echo $v['teacher']['office_address'];?></td>
        <td><?php echo $v['teacher']['home_address'];?></td>
        <td><?php echo $v['teacher']['graduate'];?></td>
       <!--  <td><img style="width: 55px" src="/appMusic/Public/Upload/<?php echo $v['car'];?>"/></td> -->
        <td><?php echo $v['teacher']['education_age'];?></td>
        <td><?php if($v['defaul'] == 1 ){echo '已通过';}elseif($v['defaul'] == 3 ){echo '审核中';}else{echo '未选择';} ; ?></td>
        <td><?php echo $v['addtime'];?></td>
        <td>
          <div class="button-group">
            <?php if($v["defaul"] != 1 & $v["defaul"] != 0 ): ?><a class="button border-main" href="<?php echo U('user/verify?id='.$v['id']);?>"><span class="icon-edit"></span>通过审核</a><?php endif; ?>
            <a class="button border-main" href="<?php echo U('sel?id='.$v['id']);?>"><span class="icon-edit"></span>查看评论</a>
            <a class="button border-main" href="<?php echo U('see_video?id='.$v['id']);?>"><span class="icon-edit"></span>他的教程</a>
            <a class="button border-main" href="<?php echo U('teacherEdit?id='.$v['id']);?>"><span class="icon-edit"></span>编辑</a>
            <a class="button border-red" href="<?php echo U('delTeacher?id='.$v['id']);?>" onclick=" return confirm('请确认删除当前账号！！！');"><span class="icon-trash-o"></span>删除</a>
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