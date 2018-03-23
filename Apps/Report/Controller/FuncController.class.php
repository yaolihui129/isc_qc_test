<?php

namespace Report\Controller;
class FuncController extends WebInfoController
{

    public function index()
    {
        $map=array('product'=>I('id',6),'deleted'=>'0');
        $branch=M('branch')->where($map)->select();
        foreach ($branch as $k=>$b){
            $branch[$k]=$b['id'];
        }
        $where['branch']  = array('in',$branch);
        $where['deleted']  = '0';

        $data=M('tp_func')->where($where)->select();
        $this->assign("data", $data);


        $this->display();
    }


    public function func(){
        $_SESSION['proid'] = I('proid');
        $m = M("tp_func_range");
        $where=array("project"=>I('proid'),"deleted"=>'0','type'=>'1');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);


        $this->display();
    }
    public function range()
    {
        $_SESSION['proid'] = I('proid');
        $m = D("tp_func_range");
        $where=array("project"=>I('proid'),"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $this->display();
    }


}