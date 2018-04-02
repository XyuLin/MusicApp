/**
 * Created by vlusi on 2017/1/18.
 */
$(function(){
    $(".read img").click(function(){
        if(($(this).attr("src"))=="img/pic-05.png"){
            $(this).attr("src","img/pic-050.png");
        }else{
            $(this).attr("src","img/pic-05.png");
        }
    });

});