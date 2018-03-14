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
<form method="post" action="/appmusic/index.php/Admin/musical/goods_lst/id/19.html" id="listform">

    <table class="table table-hover text-center">
        <tr>
            <?php foreach($info as $k=>$v):?>
               <th width="20%" style="text-align:left; padding-left:20px;"><?php echo ($k); ?></th>
            <?php endforeach;?>
            <th width="15%">库存量</th>
            <th width="15%"></th>
            <th width="20%">操作</th>
        </tr>


        <?php if($gnData): ?>
                    <!--有库存量的取出来放到td里-->
                <?php foreach($gnData as $k1=>$v1):?>
                        <tr>
                            <?php foreach($info as $k2=>$v2):?>
                                 <td style="text-align:left; padding-left:20px;">
                                     <select name="goods_attr_id[]" id="">
                                         <option value="">请选择...</option>
                                         <?php foreach($v2 as $k3=>$v3): $attr_id=explode(',',$v1['attr_id']); if(in_array($v3['attr_id'],$att_id)){ $selected='selected="selected"'; }else{ $selected="";} ?>
                                         <option selected="selected" value="<?php echo $v3['id'];?>"><?php echo $v3['attr_value'];?></option>
                                         <?php endforeach;?>
                                     </select>
                                 </td>
                            <?php endforeach;?>
                            <td><input type="text" name="goods_number[]" value="<?php echo ($v1['num']); ?>"/></td>
                            <td><input onclick="addNewTr(this)" type="button" value="+"></td>
                            <div style="position: absolute;left: 1020px;top: 36px" class="button-group">
                                <button class="button bg-main icon-check-square-o" type="submit"> 提交</button>
                            </div>
                        </tr>
                    <?php endforeach; ?>
            <?php else: ?>
                <tr>
                        <?php foreach($info as $k2=>$v2):?>
                            <td style="text-align:left; padding-left:20px;">
                                <select name="goods_attr_id[]" id="">
                                    <option value="">请选择...</option>
                                    <?php foreach($v2 as $k3=>$v3):?>
                                        <option value="<?php echo ($v3['attr_id']); ?>"><?php echo ($v3['attr_value']); ?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        <?php endforeach;?>
                        <td><input type="text" name="goods_number[]" value=""/></td>
                        <td><input onclick="addNewTr(this)" type="button" value="+"></td>
                        <div style="position: absolute;left: 1020px;top: 36px" class="button-group">
                            <button class="button bg-main icon-check-square-o" type="submit"> 提交</button>
                        </div>
                </tr>
        <?php endif; ?>
    </table>
    </div>
</form>



<script type="text/javascript" src="/appmusic/Public/JQuery/jquery-1.8.3.min.js/"></script>
<!--引入行高亮显示-->
<script type="text/javascript">
    function addNewTr(but){
        var Tr = $(but).parent().parent();
        if($(but).val() == "+"){
            var newTr = Tr.clone();
            newTr.find(":button").val("-");
            $(but).parent().parent().after(newTr);
        }else{
            Tr.remove();
        }
    }
</script>

</html>