<?php

namespace Api\Controller;
class RecordController extends BasicController
{
    function index()
    {
        $this->json(200,'成功',array('user'=>yaolihui,'name'=>'腰立辉'));
    }

    function add(){
        $m=M('tp_overtime');
        $where = array('userid' => I('userid'));
        $where['type']='1';
        $jiab= $m->where($where)->order('riqi desc')->limit(10)->select();
        $this->json(200,'成功',$jiab);
    }

}