<?php

namespace Report\Controller;
class FuncController extends WebInfoController
{

    public function index()
    {
        $map=array('product'=>I('id',6),'deleted'=>'0');
        $map['id']=array('not in',['31','33','40']);
        $branch=M('branch')->where($map)->order('`order` desc')->select();
        foreach ($branch as $k=>$b){
            $branch[$k]=$b['id'];
        }
        $this->assign("branch", $branch);

        $where['branch']=I('branch',$branch[0]);
        $where['deleted']  = '0';
        $this->assign("w", $where['branch']);
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