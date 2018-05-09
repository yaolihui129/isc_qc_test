<?php

namespace Books\Controller;
class BorrowController extends BasicController
{
    public function index()
    {


        $this->display();
    }

    public function yuding(){
        $id=I('id');
        $arr=M('tp_device')->find($id);
        $this->assign('arr', $arr);

        $where=array('device'=>$id,'type'=>'1','deleted'=>'0');
        $data=M('tp_device_loaning_record')->where($where)->order('end_time desc')->select();
        $this->assign('data', $data);
        $source=I('source');
        $this->assign('source', $source);
        $search = I('search');
        $this->assign('search', $search);
        $url='Books/Index/'.$source;

        $this->assign('url', $url);

        $riqi = date("Y-m-d", time()+24*60*60);
        $this->assign('riqi', $riqi);

        $this->display();
    }
}