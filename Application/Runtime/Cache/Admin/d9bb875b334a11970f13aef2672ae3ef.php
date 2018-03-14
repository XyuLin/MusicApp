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
  <div class="body-content">
    <form method="post" class="form-x" action="/appmusic/index.php/Admin/Musical/add.html" enctype="multipart/form-data">
      <div class="form-group">
        <div class="label">
          <label>乐器名称：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value=""  name="musical_name" data-validate="required:请输入乐器的名称" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>价格：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" value="<?php echo ($info['price']); ?>"  name="price" data-validate="required:请输入乐器的名称" />
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>商品品牌：</label>
        </div>
        <div id="brand_list" class="field">

          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>所属类型：</label>
        </div>
        <div class="field">
          <select name="type_id" id="type_id" class="input w50">
              <option value="<?php echo ($v['id']); ?>">请选择...</option>
            <?php if(is_array($type_info)): foreach($type_info as $key=>$v): ?><option value="<?php echo ($v['id']); ?>"><?php echo str_repeat('-',8*$v['level']).$v['type_name'];?></option><?php endforeach; endif; ?>
          </select>
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
        </div>
        <div class="field">
          <div id="attr_list"></div>
          <div class="tips"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="label">
          <label>乐器图片：</label>
        </div>
        <div class="field">
          <input type="file" id="url1" name="img" class="input tips" style="width:25%; float:left;"  value=""  data-toggle="hover" data-place="right" data-image="" />
          <input type="button" class="button bg-blue margin-left" id="image1" value="+ 浏览上传"  style="float:left;">
          <div class="tipss">图片尺寸：500*500</div>
        </div>
      </div>


      <div class="form-group">
        <div class="label">
          <label>乐器说明：</label>
        </div>
        <div class="field">
          <!--<input type="text" class="input w50" value="" id="musical_sm" name="musical_sm" data-validate="required:请输入说明" />-->
          <textarea id="musical_sm" name="musical_sm"></textarea>
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

<link href="/appmusic/Public/umeditor1_2_2-utf8-php/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/appmusic/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/appmusic/Public/umeditor1_2_2-utf8-php/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/appmusic/Public/umeditor1_2_2-utf8-php/umeditor.min.js"></script>
<script type="text/javascript" src="/appmusic/Public/umeditor1_2_2-utf8-php/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="/appmusic/Public/JQuery/jquery-1.8.3.min.js/"></script>

<script type="text/javascript">
    var um =  UM.getEditor('musical_sm',{
        initialFrameWidth:980,
        initialFrameHeight:160
    });


    $("select[name=type_id]").change(function(){
        var type_id = $(this).val();
        $.ajax({
                  type: "get",
                 url: "<?php echo U('ajaxGetAttr','',FALSE)?>/type_id/" + type_id,
                dataType: "json",
                success: function (data) {
                    var li = "";
                    $(data).each(function (k, v) {
                        li +='<li>';
                        if(v.attr_sel !== "" ) {
                            li += '<a onclick="addNewAttr(this);">[+]</a>';
                            li += v.attr_name + '：';
                            if (v.attr_option_values == "") {
                                li += '<input type="text" name="attr_value[' + v.id + '][]" value=""/>';         //属性的id作为下标二维下标用来存储属性
                            } else {
                                // li += '<a onclick="addNewAttr(this);">[+]</a>';
                                // li += v.attr_name + '：';
                                li += '<select name="attr_value[' + v.id + '][]"><option name="" value="">请选择...</option>';
                                var _attr = v.attr_option_values.split(',');
                                // console.log(v.attr_option_values);
                                for (var i = 0; i < _attr.length;  i++) {
                                    li += '<option name="" value="' + _attr[i] + '">';
                                    li += _attr[i];
                                    li += '</option>';
                                }
                                li += '</select>'
                            }
                        }
                        li += '</li>';
                    });
                    $("#attr_list").html(li);
                }
        })
    });


    function addNewAttr(a){
        var li = $(a).parent();             //当点击“a” 触发这里的事件 +号的 就克隆一个li
        if($(a).text()=='[+]'){
            var newli = li.clone();               //注意克隆的是li标签 不是a 这里不能是$(a)
            newli.find("a").text('[-]');        //新的a要变减号
        }else{
            li.remove();
        }
        li.after(newli);
    }


    $(function(){
        $.ajax({
            type: "get",
            url: "<?php echo U('musical/ajaxGetbrand','',FALSE)?>",
            dataType: "json",
            success: function (data) {
                var select = "";
                select +='<select name="brand_id"  value="" class="selectpicker remove-example" data-style="btn-primary"><option name="" value="">请选择...</option>';
                $(data).each(function (k, v) {
                    select +='<option name="" value="'+v.id+'">';
                    select +=v.brand_name;
                    select +='</option>';
                    // console.log(v.brand_name);
                });
                select+='</select>';
                $("#brand_list").html(select);
            }
        })

    });



</script>


</html>