<?php

namespace Device\Controller;
class MineController extends BasicController
{
    public function index()
    {
        $m=M('tp_device_loaning_record');
        $riqi = date("Y-m-d", time());
        //预约中
        $where=array('borrower'=>$_SESSION['account'],'leibie'=>'1','type'=>'1');
        $where['start_time']=array('egt',$riqi);
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data1', $data);
        //借用历史,'deleted'=>'0'
        $where['deleted']='0';
        $where['start_time']=array('lt',$riqi);
        $where['type']='2';
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data2', $data);
        //借用中
        $where=array('borrower'=>$_SESSION['account'],'leibie'=>'1','type'=>'0');
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data0', $data);

        $this->display();
    }

    public function books(){
        $m=M('tp_device_loaning_record');
        $riqi = date("Y-m-d", time());
        //预约中
        $where=array('borrower'=>$_SESSION['account'],'leibie'=>'3','type'=>'1');
        $where['start_time']=array('egt',$riqi);
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data1', $data);
        //借用历史,'deleted'=>'0'
        $where['deleted']='0';
        $where['start_time']=array('lt',$riqi);
        $where['type']='2';
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data2', $data);
        //借用中
        $where=array('borrower'=>$_SESSION['account'],'leibie'=>'3','type'=>'0');
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data0', $data);

        $this->display();
    }
    //主动取消
    function cancel(){
        $_GET['deleted']='1';
        $_GET['reject']='1';
        $_GET['table']='tp_device_loaning_record';
        $this->update();
    }
    //续期
    function renewal(){
        $table='tp_device_loaning_record';
        $_GET['renewal']='1';
        $m=M($table);
        $arr=$m->find(I('id'));

        $time = strtotime(I('end_time'));
        $start_time=date('Y-m-d', $time);
        $week = date('w', $time);
        if ($week == 5) {//3天后
            $time=$time + 3*24 * 60 * 60;
        } elseif ($week == 6) {//+2天后
            $_GET['end_time'] = date('Y-m-d H:i:s', $time + 2 * 24 * 60 * 60);
            $time=$time + 2*24 * 60 * 60;
        } else {//+1天后
            $time=$time + 24 * 60 * 60;
        }
        $_GET['end_time']=date('Y-m-d H:i:s', $time);
        //todo
        //当前日期有预约不可以续期
        $where=array('start_time'=>$start_time,'device'=>$arr['device'],'deleted'=>'0');
        $var=$m->where($where)->select();
        if($var){
            $this->error($start_time.'有'.getZTUserName($var[0]['borrower']).'的预订,不能续期');
        }else{
            $_GET['table']=$table;
            $this->update();
        }

    }
}