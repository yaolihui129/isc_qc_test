<?php

namespace Report\Controller;
class RiskController extends WebInfoController
{
    public function index()
    {
        $_SESSION['proid'] = I('proid');
        $where = array("proid" => I('proid'));
        $data = M("tp_risk")->where($where)->select();
        $this->assign("data", $data);

        $this->display();
    }

    public function pro_risk(){
        $where=array('deleted'=>'0');
        $data = M("tp_risk")->where($where)->order('ctime desc')->select();
        $this->assign("data", $data);

        $this->display();
    }


    function make(){

        $this->display();
    }


    function add(){
        //ajaxReturn(数据,'提示信息',状态)
        dump($_GET);
        $m=D('tp_risk');
        if($m->add($_GET)){
            $this->ajaxReturn($_GET,'添加信息成功',1);
        }else{
            $this->ajaxReturn(0,'添加信息失败',0);
        }
    }
}