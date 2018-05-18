<?php

namespace Report\Controller;

use Think\Controller;

class WebInfoController extends Controller
{
    //空方法
    function _empty()
    {   //错误网页重新定向到首页
        $this->display('index');
    }

    //检查逾期未归还的设备
    function overdue(){
        $where=array('type'=>'0','deleted'=>'0' );
        $where['end_time']=array('lt',date('Y-m-d H:i:s',time()));
        $data=M('tp_device_loaning_record')->where($where)->select();
        $m=D('tp_device_overdue');
        foreach ($data as $da){
            //检查是否重复
            $map=array('record_id'=>$da['id'],'deleted'=>'0');
            if(!$m->where($map)->find()){
                //将数据写入逾期记录表
                $_POST['borrower']      = $da['borrower'];
                $_POST['device']        = $da['device'];
                $_POST['end_time']      = $da['end_time'];
                $_POST['record_id']     = $da['id'];
                $_POST['moder']         = 'System';
                $_POST['ctime']         =  time();
                if (!$m->create()) {
                    $this->error($m->getError());
                }
                $m->add();
            }
        }
    }
    //检查逾期未确认的BUG
    function bug_unconfirmed(){
        $where['status']="active";
        $where['confirmed']=0;
        $where['deleted']='0';
        $where['assignedTo']=array('in',C(DEV_USER));//只看这些人员
        $datum=date("Y-m-d",time()-24*3600);
        $datum=strtotime($datum);//将日期转化为时间戳
        $datum=date("Y-m-d H:i",$datum+17.5*3600);
        $where['openedDate']  = array('lt',$datum);
        $data=M("bug")->where($where)->order('openedDate')->select();
        $m=D('tp_punish');
        foreach ($data as $da){
            //检查是否重复
            $map=array('bug'=>$da['id'],'status'=>'active','confirmed'=>'0','deleted'=>'0');
            if(!$m->where($map)->find()){
                //将数据写入
                $_POST['bug']       =$da['id'];
                $_POST['owner']     =$da['assignedto'];
                $_POST['punish']    = 'BUG逾期未确认';
                $_POST['confirmed'] = '0';
                $_POST['datum']     =$da['openeddate'];
                $_POST['status']    =$da['status'];
                $_POST['moder']     = 'admin';
                $_POST['ctime']     =date('Y-m-d H:i:s',time()) ;
                if (!$m->create()) {
                    $this->error($m->getError());
                }
                $m->add();
            }
        }
    }
    //检查逾期未复测的BUG
    function bug_noregress(){
        $where['status']="resolved";
        $where['deleted']='0';
        $datum=date("Y-m-d",time()-24*3600);
        $datum=strtotime($datum);
        $datum=date("Y-m-d H:i",$datum+17.5*3600);
        $where['resolvedDate']  = array('lt',$datum);
        $where['assignedTo']=array('in',C(QA_TESTER));//只看这些人员
        $data=M("bug")->where($where)->order('resolvedDate')->select();
        $m=D('tp_punish');
        foreach ($data as $da){
            //检查是否重复
            $map=array('bug'=>$da['id'],'status'=>'resolved','deleted'=>'0');
            if(!$m->where($map)->find()){
                //将数据写入
                $_POST['bug']          = $da['id'];
                $_POST['owner']        = $da['assignedto'];
                $_POST['punish']       = 'BUG逾期未回归';
                $_POST['confirmed']    = $da['confirmed'];
                $_POST['datum']        = $da['resolveddate'];
                $_POST['status']       =$da['status'];
                $_POST['moder']        = 'admin';
                $_POST['ctime']        = date('Y-m-d H:i:s',time()) ;
                if (!$m->create()) {
                    $this->error($m->getError());
                }
                $m->add();
            }
        }
    }

}