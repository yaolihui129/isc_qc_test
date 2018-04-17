<?php

namespace Api\Controller;
class SceneController extends BasicController
{
    public function index()
    {
        $api=I('api',11);
        $where=array('api'=>$api,'deleted'=>'0');
        $m=M('tp_api_scene');
        $info=$m->where($where)->order('sn,id')->field('id,status,scene')->select();
        $info=json_encode($info,JSON_PRETTY_PRINT);
        header('Content-type:text/json');
        echo $info;
    }

    public function parameter(){

        $scene=I('scene',1);
        $where=array('scene'=>$scene,'deleted'=>'0');
        $m=M('tp_api_scene_parameter');
        $info=$m->where($where)->order('sn,id')->field('id,status,scene')->select();
        $info=json_encode($info,JSON_PRETTY_PRINT);
        header('Content-type:text/json');
        echo $info;
    }

}