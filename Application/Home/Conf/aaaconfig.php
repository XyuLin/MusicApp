<?php
return array(
    'TOKEN'                 => '094bf185f364e3afe938abd59f154948',
    'DB_TYPE'               =>  'mysqli',       # 数据库类型
    'DB_HOST'               =>  'localhost',    # 服务器地址
    'DB_NAME'               =>  'music_app',    # 数据库名
    'DB_USER'               =>  'root',         # 用户名
    'DB_PWD'                =>  'root',         # 密码
    'DB_PORT'               =>  '3306',         # 端口
    'DB_PREFIX'             =>  'music_',       # 数据库表前缀
    'DB_PARAMS'             =>  array(),        # 数据库连接参数
#    'DB_DEBUG'  	    =>  TRUE,            数据库调试模式 开启后可以记录SQL日志
#    'DB_FIELDS_CACHE'      =>  true,            启用字段缓存
    'DB_CHARSET'             =>  'utf8',        # 数据库编码默认采用utf8
    'DEFAULT_FILTER'         => 'trim,htmlspecialchars',
    'HTML_CACHE_ON'          => true,           # 开启静态缓存
    'HTML_CACHE_TIME'        => 60,             # 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'       => '.shtml',       # 设置静态缓存文件后缀
    # 静态缓存规则 前面 控制器：方法=> array（’缓存文件的名称‘，缓存的时间）
    'HTML_CACHE_RULES' => array(
        'Index:index'=>array('index',86400),
        'Musical:lst'=>array('musical-{p}',86400),
    ),
);
