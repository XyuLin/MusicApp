<?php
/**
 * Created by PhpStorm.
 * User: GZJ
 * Date: 2017/5/18
 * Time: 10:43
 */

/*公共函数
*/
/***************过滤器*******************/
function rmoveXss($data){
    require_once './library/HTMLPurifier.auto.php';
    $_clean_xss_config = HTMLPurifier_Config::createDefault();
    $_clean_xss_config->set('Core.Encoding', 'UTF-8');
    //设置要保留的标签
    $_clean_xss_config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
    $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
    $_clean_xss_config->set('HTML.TargetBlank', TRUE);
    $_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
    //执行过滤
      return $_clean_xss_obj->purify($data);

}


function return_json($data){
    if($data){
        $data = array(
            'code'=>1,
            'msg'=>'获取成功',
            'data'=>$data,
        );
    }else{
        $data = array(
            'code'=>0,
            'msg'=>'获取失败',
        );
    }
    return json_encode($data);
}

function V($data){
    echo '<pre>';
    var_dump($data);
}


