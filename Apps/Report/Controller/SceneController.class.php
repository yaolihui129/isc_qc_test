<?php

namespace Report\Controller;
class SceneController extends WebInfoController
{
    public function index()
    {
        $_SESSION['proid'] = I('proid');
        $m = D("tp_scene");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $this->display();
    }

}