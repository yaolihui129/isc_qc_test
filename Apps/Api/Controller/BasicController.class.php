<?php

namespace Api\Controller;

use Think\Controller;

class BasicController extends Controller
{
    function _empty()
    {//空方法
        echo '方法不存在';
    }

    function json_public($content,$requestId,$appId='TOB')
    {
        $arr=array(
            "appId"=>$appId,
            "requestId"=> $requestId,
            "content"=>$content
        );
        $arr=json_encode($arr,JSON_PRETTY_PRINT);
        header('Content-type:text/json');
        echo $arr;
    }


    public function insert($var,$table)
    {
        $m = D($table);
        $var['ctime'] = time();
        if (!$m->create($var)) {
            echo $m->getError();
        }
        if ($m->add($var)) {
            echo 'ok';
        } else {
            echo 'fail';
        }
    }


}