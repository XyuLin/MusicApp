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
    <div class="panel-head"><strong class="icon-reorder"> 学员列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
    <div class="padding border-bottom">
        <ul class="search" style="padding-left:10px;">
            <li> <a class="button border-main icon-plus-square-o" href="<?php echo U('add');?>"> 添加学员</a> </li>
            <li>搜索：</li>
            <li>
                <input type="text" placeholder="请输入搜索关键字" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" />

                <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
        </ul>
    </div>
    <table class="table table-hover text-center">
        <tr>
            <th width="2%" style="text-align:left; padding-left:20px;">ID</th>
            <th width="10%">学员姓名</th>
            <th width="5%">学员年龄</th>
            <th width="5%">学员性别</th>
            <th width="15%">学员地址</th>
            <th width="10%">所学科目名称</th>
            <th width="10%">身份证</th>
            <th width="5%">头像</th>
            <th width="7%">学习科目数量</th>
            <th width="10%">注册时间</th>
            <th width="30%">操作</th>
        </tr>

        <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
                <td style="text-align:left; padding-left:20px;"><?php echo $v['id'];?></td>
                <td><?php echo $v['username'];?></td>
                <td><?php echo $v['age'];?></td>
                <td><?php echo $v['sex'];?></td>
                <td><?php echo $v['phoen'];?></td>
                <td><?php echo $v['subject_name'];?></td>
                <td><img style="width: 55px" src="/appMusic/Public/Upload/<?php echo $v['car'];?>"/></td>
                <td><img style="width: 55px" src="/appMusic/Public/Upload/<?php echo $v['img'];?>"/></td>
                <td><?php echo $v['stu_num'];?></td>
                <td><?php echo $v['addtime'];?></td>
                <td>
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

</html>