<?php

namespace Record\Controller;

class RiskController extends CommonController
{
    public function index()
    {
        $_SESSION['proid'] = I('proid');
        $m = M("project");
        $where = array("testgp" => 'YX', "deleted" => '0');
        $pros = $m->where($where)->order("end desc")->select();
        $this->assign("pros", $pros);

        $arr = $m->find($_SESSION['proid']);
        $this->assign("arr", $arr);

        $m = D("tp_risk");
        $where = array("proid" => $_SESSION['proid']);
        $risks = $m->where($where)->select();
        $this->assign("risks", $risks);

        $count = $m->where($where)->count() + 1;
        $this->assign('c', $count);
        $this->assign("state", formselect("打开", "state", "rstate"));
        $this->assign("level", formselect("C", "level", "risklevel"));
        $this->assign("tamethod", PublicController::editor("amethod", "暂无方案"));


        $this->display();
    }

    public function mod()
    {
        $m = D("tp_risk");
        $where = array("proid" => $_SESSION['proid']);
        $data = $m->where($where)->select();

        $this->assign("data", $data);

        $risk = $m->find(I('id'));
        $this->assign("risk", $risk);
        $this->assign("level", formselect($risk['level'], "level", "risklevel"));
        $this->assign("state", formselect($risk['state'], "state", "rstate"));
        $this->assign("tamethod", PublicController::editor("amethod", $risk['amethod']));


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