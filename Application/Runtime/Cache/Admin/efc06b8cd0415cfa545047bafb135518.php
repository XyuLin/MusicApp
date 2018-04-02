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
  <div class="panel-head"><strong class="icon-reorder"> 课程列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
  <div class="padding border-bottom">
    <ul class="search" style="padding-left:10px;">
      <li> <a class="button border-main icon-plus-square-o" href="#"> 添加信息</a> </li>
      <li>搜索：</li>
      <li>
        <input type="text" placeholder="请输入搜索关键字" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" />

        <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
    </ul>
  </div>
  <table class="table table-hover text-center">
    <tr>
      <th width="5%" style="text-align:left; padding-left:20px;">ID</th>
      <th width="5%">教师名称</th>
      <th width="5%">课程类型</th>
      <th width="5%">课程备注</th>
      <th width="5%">课时类型</th>
      <th width="8%">课时价格</th>
      <th width="7%">课时时长</th>
      <th width="10%">课程名称</th>
      <th width="5%">创建时间</th>
      <th width="10%">审核状态</th>
      <th width="35%">操作</th>
    </tr>

    <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
        <td style="text-align:left; padding-left:20px;"><?php echo $v['id'];?></td>
        <td><?php echo $v['name'];?></td>
        <td><?php echo $v['subject_name'];?></td>
        <td><?php echo $v['class_desc'];?></td>
        <td><?php if($v['class_hour_type'] == 1){echo '15课时起售';} ?></td>
        <td><?php echo $v['class_hour_price'];?></td>
        <td><?php if($v['class_hour_type'] == 1){echo '45分钟/课时';}?></td>
        <td><?php echo $v['course_name'];?></td>
        <td><?php echo $v['create_time'];?></td>
        <td><?php if($v['status'] == 1 ){echo '已通过';}elseif($v['status'] == 2 ){echo '审核中';} ; ?></td>
        <td>
          <div class="button-group">
           <?php if($v["status"] != 1 ): ?><a class="button border-main" href="<?php echo U('sendSubjects/verify?id='.$v['id']);?>"><span class="icon-edit"></span>通过审核</a><?php endif; ?>
            <a class="button border-red" href="<?php echo U('sendSubjects/del?id='.$v['id']);?>" onclick=" return confirm('请确认删除该教程！！！');"><span class="icon-trash-o"></span>删除教程</a>
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