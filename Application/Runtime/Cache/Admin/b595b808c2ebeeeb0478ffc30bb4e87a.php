<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理中心</title>
    <link rel="stylesheet" href="/appMusic/Public/Admin/css/pintuer.css">
    <link rel="stylesheet" href="/appMusic/Public/Admin/css/admin.css">
    <script src="/appMusic/Public/Admin/Js/jquery.js"></script>
</head>
<body style="background-color:#f2f9fd;">


<div class="header bg-main">
  <div class="logo margin-big-left fadein-top">
    <h1><img src="../images/y.jpg" class="radius-circle rotate-hover" height="50" alt="" />后台管理中心</h1>
  </div>
  <div class="head-l"><a class="button button-little bg-green" href="" target="_blank"><span class="icon-home"></span> 前台首页</a> &nbsp;&nbsp;
    <a  id="cache" href="#" class="button button-little bg-blue"><span class="icon-wrench"></span> 清除缓存</a> &nbsp;&nbsp;
    <a class="button button-little bg-red" href="<?php echo U('Login/log_out');?>"><span class="icon-power-off"></span> 退出登录</a>
  </div>
</div>
<div class="leftnav">
  <div class="leftnav-title"><strong><span class="icon-list"></span>菜单列表</strong></div>
  <h2><span class="icon-user"></span>基本设置</h2>
  <ul style="display:block">
    <li><a href="<?php echo U('Info/info');?>" target="right"><span class="icon-caret-right"></span>后台首页</a></li>
    <!--<li><a href="<?php echo U('Admin/edit');?>" target="right"><span class="icon-caret-right"></span>修改密码</a></li>-->
    <li><a href="<?php echo U('Banner/lst');?>" target="right"><span class="icon-caret-right"></span>首页轮播</a></li>
    <li><a href="<?php echo U('Order/lst');?>" target="right"><span class="icon-caret-right"></span>订单管理</a></li>
  </ul>   
  <h2><span class="icon-pencil-square-o"></span>栏目管理</h2>
  <ul>
    <li><a href="<?php echo U('Musical/lst');?>" target="right"><span class="icon-caret-right"></span>乐器列表</a></li>
    <li><a href="<?php echo U('brand/lst');?>" target="right"><span class="icon-caret-right"></span>品牌管理</a></li>
    <li><a href="<?php echo U('type/lst');?>" target="right"><span class="icon-caret-right"></span>分类管理</a></li>
  </ul> 
   <h2><span class="icon-pencil-square-o"></span>用户管理</h2>
  <ul>
    <li><a href="<?php echo U('Admin/lst');?>" target="right"><span class="icon-caret-right"></span>管理员列表</a></li>
      <li><a href="<?php echo U('User/teaLst');?>" target="right"><span class="icon-caret-right"></span>教师管理</a></li>
      <li><a href="<?php echo U('User/stulst');?>" target="right"><span class="icon-caret-right"></span>学生信息表</a></li>
  </ul> 
    <h2><span class="icon-pencil-square-o"></span>课程管理</h2>
  <ul>
    <li><a href="<?php echo U('Admin/sendSubjects/verifySub');?>" target="right"><span class="icon-caret-right"></span>课程管理</a></li>
    <li><a href="<?php echo U('Admin/order/stuSubList');?>" target="right"><span class="icon-caret-right"></span>课程订单管理</a></li></ul> 
</div>
<div id="sess">您好：<?php echo session('username');?></div>
<script type="text/javascript">
$(function(){
  $(".leftnav h2").click(function(){
	  $(this).next().slideToggle(200);
	  $(this).toggleClass("on");
  })
  $(".leftnav ul li a").click(function(){
	    $("#a_leader_txt").text($(this).text());
  		$(".leftnav ul li a").removeClass("on");
		$(this).addClass("on");
  })
});


$("#cache").click(function(){
    if(confirm("确定要清理缓存吗？")){
        $.ajax({
            url:"<?php echo U('Index/cache');?>",
            type:"get",
            dataType:"json",
            success:function (cache) {
                if(cache.status == "1"){
                    alert("缓存清理成功！")
                }
            }
        })
    }
})

</script>


<ul class="bread">
    <li><a href="<?php echo U('Index/index');?>" target="right" class="icon-home"> 首页</a></li>
    <li><a href="##" id="a_leader_txt">网站信息</a></li>
    <li><b>当前语言：</b><span style="color:red;">中文</php></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;切换语言：<a href="##">中文</a> &nbsp;&nbsp;<a href="##">英文</a> </li>
</ul>
<div class="admin">
    <iframe scrolling="auto" rameborder="0" src="<?php echo U('Info/info');?>" name="right" width="100%" height="100%"></iframe>
</div>
<div style="text-align:center;">
    <p>来源:<a href="http://www.mycodes.net/" target="_blank"></a></p>
</div>
</body>
</html>