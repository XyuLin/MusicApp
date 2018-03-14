<?php
return array(
    'DB_TYPE'               =>  'mysqli',
    'DB_HOST'               =>  'localhost',
    'DB_NAME'               =>  'music_app',
    'DB_USER'               =>  'root',
    'DB_PWD'                =>  'root',
    'DB_PORT'               =>  '3306',
    'DB_PREFIX'             =>  'music_',
    'DB_PARAMS'          	=>  array(),
//    'DB_DEBUG'  			=>  TRUE,           // 数据库调试模式 开启后可以记录SQL日志
//    'DB_FIELDS_CACHE'       =>  true,         // 启用字段缓存
    'DB_CHARSET'             =>  'utf8',
    'DEFAULT_FILTER'         => 'trim,htmlspecialchars',
    'HTML_CACHE_ON'          => false,
    'HTML_CACHE_TIME'        => 60,
    'HTML_FILE_SUFFIX'       => '.shtml',
    //静态缓存规则 前面 控制器：方法=> array（’缓存文件的名称‘，缓存的时间）
    'HTML_CACHE_RULES' => array(
          'Index:index'=>array('index',86400),
          'Musical:lst'=>array('musical-{p}',86400),

    ),
);