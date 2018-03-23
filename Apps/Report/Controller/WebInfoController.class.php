<?php

namespace Report\Controller;

use Think\Controller;

class WebInfoController extends Controller
{
    //空方法
    function _empty()
    {
        //错误网页重新定向到首页
        $this->display('index');
    }

}