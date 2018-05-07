<?php
return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => __ROOT__ . '/Public/css/',
        '__JS__' => __ROOT__ . '/Public/js/',
        '__IMAGES__' => __ROOT__ . '/Public/images/Books',
        '__IMG__' => __ROOT__ . '/Public/images',
        '__UPLOAD__' => __ROOT__ . '/Upload'
    ),
    'DEFAULT_THEME' => 'Amaze',//开启模板
    'PRODUCT' => 'Books',//定义产品编号
    'DB_HOST' => '192.168.155.54',
    'DB_NAME' => 'zentao171226',
    'DB_USER' => 'root',
    'DB_PWD' => 'chexian',
    'DB_PORT' => '3306',
);