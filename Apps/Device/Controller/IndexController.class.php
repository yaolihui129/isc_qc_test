<?php

namespace Device\Controller;
use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $search = I('search');
        $this->assign('search', $search);
        $where=array('type'=>'1','deleted'=>'0');
        $where['brand|ts|serial|asset_no'] = array('like', '%' . $search . '%');
        $data=M('tp_device')->where($where)->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function books()
    {
        $m=M('tp_device');
        $search = I('search');
        $this->assign('search', $search);
        $where=array('type'=>'3','deleted'=>'0');
        $where['brand|ts|serial|name'] = array('like', '%' . $search . '%');
        $data=$m->where($where)->select();
        $this->assign('data', $data);


        $this->display();
    }

    public function history()
    {
        $id=I('id');
        $arr=M('tp_device')->find($id);
        $this->assign('arr', $arr);
        $this->assign('source', I('source'));
        $this->assign('search', I('search'));

        $m=M('tp_device_loaning_record');
        $riqi = date("Y-m-d", time());
        //预约中
        $where=array('device'=>$id,'type'=>'1');
        $where['start_time']=array('egt',$riqi);
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data1', $data);
        //借用历史,'deleted'=>'0'
        $where['deleted']='0';
        $where['start_time']=array('elt',$riqi);
        $where['type']='2';
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data2', $data);
        //借用中
        $where=array('device'=>$id,'type'=>'0');
        $data=$m->where($where)->order('start_time desc')->select();
        $this->assign('data0', $data);

        $this->display();
    }

    public function rules()
    {
        $data=C(DEVICE_RULES);
        $this->assign('data', $data);

        $this->display();
    }

    public function yuqi(){
        $where=array("deleted"=>"0");
        $data=M("tp_device_overdue")->where($where)->order('end_time desc')->select();
        $this->assign('data',$data);

        $this->display();
    }


}