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
  <div class="panel-head"><strong class="icon-reorder"> 乐器列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
  <div class="padding border-bottom">
    <ul class="search" style="padding-left:10px;">
      <li> <a class="button border-main icon-plus-square-o" href="<?php echo U('add');?>"> 添加乐器</a> </li>
      <li>搜索：</li>
      <li>
        <input type="text" placeholder="请输入搜索关键字" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" />

        <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
    </ul>
  </div>
  <table class="table table-hover text-center">
    <tr>
      <th width="1%" style="text-align:left; padding-left:20px;">ID</th>
      <th width="8%">乐器名称</th>
      <th width="8%">乐器图片</th>
      <th width="8%">品牌</th>
      <th width="5%">销量</th>
      <th width="8%">所属类型</th>
      <th width="15%">属性信息</th>
      <th width="15%">乐器说明</th>
      <th width="10%">上架时间</th>
      <th width="25%">操作</th>
    </tr>

    <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
        <td style="text-align:left; padding-left:20px;"><?php echo $v['id'];?></td>
        <td><?php echo $v['musical_name'];?></td>
        <td><img style="width: 55px" src="/appMusic/Public/Upload/<?php echo $v['img'];?>"/></td>
        <td><?php echo $v['brand_name'];?></td>
        <td><?php echo $v['xiaoliang'];?></td>
        <td><?php echo $v['type_name'];?></td>
        <td><?php echo $v['attr_value'];?></td>
        <td><?php echo $v['musical_sm'];?></td>
        <td><?php echo $v['up_date'];?></td>
        <td>
          <div class="button-group">
            <a class="button border-main" href="<?php echo U('musical/goods_lst?id='.$v['id']);?>"><span class="icon-edit"></span> 查看库存</a>
            <a class="button border-main" href="<?php echo U('edit?id='.$v['id']);?>"><span class="icon-edit"></span> 修改</a>
            <a class="button border-red" href="<?php echo U('del?id='.$v['id']);?>" onclick=" return confirm('请确认删除当前账号！！！');"><span class="icon-trash-o"></span> 删除</a>
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

//全选
$("#checkall").click(function(){ 
  $("input[name='id[]']").each(function(){
	  if (this.checked) {
		  this.checked = false;
	  }
	  else {
		  this.checked = true;
	  }
  });
})

//批量删除
function DelSelect(){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){
		var t=confirm("您确认要删除选中的内容吗？");
		if (t==false) return false;		
		$("#listform").submit();		
	}
	else{
		alert("请选择您要删除的内容!");
		return false;
	}
}

//批量排序
function sorts(){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){	
		
		$("#listform").submit();		
	}
	else{
		alert("请选择要操作的内容!");
		return false;
	}
}


//批量首页显示
function changeishome(o){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){
		
		$("#listform").submit();	
	}
	else{
		alert("请选择要操作的内容!");		
	
		return false;
	}
}

//批量推荐
function changeisvouch(o){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){
		
		
		$("#listform").submit();	
	}
	else{
		alert("请选择要操作的内容!");	
		
		return false;
	}
}

//批量置顶
function changeistop(o){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){		
		
		$("#listform").submit();	
	}
	else{
		alert("请选择要操作的内容!");		
	
		return false;
	}
}


//批量移动
function changecate(o){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){		
		
		$("#listform").submit();		
	}
	else{
		alert("请选择要操作的内容!");
		
		return false;
	}
}

//批量复制
function changecopy(o){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){	
		var i = 0;
	    $("input[name='id[]']").each(function(){
	  		if (this.checked==true) {
				i++;
			}		
	    });
		if(i>1){ 
	    	alert("只能选择一条信息!");
			$(o).find("option:first").prop("selected","selected");
		}else{
		
			$("#listform").submit();		
		}	
	}
	else{
		alert("请选择要复制的内容!");
		$(o).find("option:first").prop("selected","selected");
		return false;
	}
}

</script>
</body>


</html>