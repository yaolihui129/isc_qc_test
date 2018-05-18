<?php

namespace Report\Controller;
class SceneController extends WebInfoController
{
    public function index()
    {
        $_SESSION['proid'] = I('proid');
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data= M("tp_scene")->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $this->display();
    }


    public function func(){
        $scene=I('scene');
        $arr=M("tp_scene")->find($scene);
        $this->assign("arr",$arr);

        $map=array('scene'=>$scene,"deleted"=>'0');
        $func=M('tp_scene_func')->where($map)->order('sn')->select();
        $this->assign("func",$func);

        $this->display();
    }
}