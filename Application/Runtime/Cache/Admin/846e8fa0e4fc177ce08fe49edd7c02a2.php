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
    <div class="panel-head"><strong class="icon-reorder"> 库存</strong> <a href="" style="float:right; display:none;">库存</a></div>
    <table class="table table-hover text-center">
        <tr>
            <th width="1%" style="text-align:left; padding-left:20px;">ID</th>
            <th width="8%">乐器</th>
            <th width="8%">乐器图片</th>
            <th width="8%">品牌</th>
            <th width="25%">操作</th>
        </tr>

        <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
                <td style="text-align:left; padding-left:20px;"><?php echo $v['id'];?></td>
                <td><?php echo $v['attr_value'];?></td>
                <td><?php echo $v['musical_sm'];?></td>
                <td><?php echo $v['up_date'];?></td>
                <td>
                    <div class="button-group">
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



</html>