<?php

namespace Api\Controller;

use Think\Controller;

class BasicController extends Controller
{
    function _empty()
    {//空方法
        echo '方法不存在';
    }
    //通用封装方法
    function json($code,$message = '',$data = array())
    {
        if (!is_numeric($code)){
            return '错误';
        }
        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        echo json_encode($result);
        exit;
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

    //插入数据
    function insert($var,$table)
    {
        $m = D($table);
        $var['ctime'] = time();
        if (!$m->create($var)) {
            echo $m->getError();
        }
        if ($m->add($var)) {
            echo 1;
        } else {
            echo 0;
        }
    }
    //更新数据
    function update($var,$table){
        if (D($table)->save($var)) {
            echo 1;
        } else {
            echo 0;
        }
    }
    //逻辑删除
    function del($var,$table){
        $var[id] = $var;
        $var['deleted'] = 1;
        if (D($table)->save($var)) {
            echo 1;
        } else {
            echo 0;
        }
    }
    //物理删除
    function realdel($var,$table)
    {
        $count = D($table)->delete($var);
        if ($count > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }

}