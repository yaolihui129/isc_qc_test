<?php

namespace Api\Controller;
class InfoController extends BasicController
{
    public function index()
    {
        $api=I('api',11);
        $m=M('tp_apitest');
        $info=$m->field('id,agreement,domain,adress,way')->find($api);
        $info=json_encode($info,JSON_PRETTY_PRINT);
        header('Content-type:text/json');
        echo $info;
    }


}