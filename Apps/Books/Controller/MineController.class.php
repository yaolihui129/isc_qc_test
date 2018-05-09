<?php

namespace Books\Controller;
class MineController extends BasicController
{
    public function index()
    {
        $m=M('tp_device_loaning_record');
        //借用历史
        $where=array('borrower'=>$_SESSION['account'],'type'=>'2','deleted'=>'0');
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data2', $data);
        //借用中
        $where['type']='0';
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data0', $data);
        //预约中
        $where['type']='1';
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data1', $data);

        $this->display();
    }

    function cancel(){
        $_GET['deleted']='1';
        $_GET['table']='tp_device_loaning_record';
        $this->update();
    }


}