<?php

namespace Record\Controller;
class IndexController extends CommonController
{

    public function index()
    {
        $search = I('search');
        $this->assign('search', $search);
        $map['testgp'] = 'YX';

        $map['name|code'] = array('like', '%' . $search . '%');
        $map['deleted'] = '0';
        $arr = M('project')->where($map)->order("status,end desc")->select();
        $this->assign('arr', $arr);

        $this->display();
    }


    public function func(){//迭代涉及的功能点
        $_SESSION['proid']=I('proid');
        $m = M("tp_func_range");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0','type'=>'1');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);


        $map = array("testgp" => 'YX', "deleted" => '0');
        $pros = M('project')->where($map)->order("status,end desc")->select();
        $this->assign('pros', $pros);

        $this->display();
    }

    public function modFunc(){
        $_SESSION['modFunc']['branch'] = I('branch');
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0','type'=>'1');
        $data= M("tp_func_range")->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $map=array("project"=>$_SESSION['proid']);
        $branch=M("projectproduct")->where($map)->select();
        if(!$branch[0]['branch']=='0'){//不为0
            foreach ($branch as $k=>$br){
                $branch[$k]=$br['branch'];
            }
        }else{//为0
            $var=array('product'=>$branch[0]['product'],'deleted'=>'0');
            $branch=M('branch')->where($var)->select();
            foreach ($branch as $k=>$br){
                $branch[$k]=$br['id'];
            }
        }

        $this->assign('branch', $branch);

        $rr=array('branch'=>$_SESSION['modFunc']['branch'],"deleted"=>'0');
        $func=M('tp_func')->where($rr)->select();
        $this->assign('func', $func);


        $this->display();
    }




    public function range(){//迭代测试（含影响范围）
        $_SESSION['proid']=I('proid');
        $m = D("tp_func_range");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $map = array("testgp" => 'YX', "deleted" => '0');
        $pros = M('project')->where($map)->order("status,end desc")->select();
        $this->assign('pros', $pros);



        $map=array("project"=>$_SESSION['proid']);
        $branch=M("projectproduct")->where($map)->select();
        if(!$branch[0]['branch']=='0'){//不为0
            foreach ($branch as $k=>$br){
                $branch[$k]=$br['branch'];
            }
        }else{//为0
            $var=array('product'=>$branch[0]['product'],'deleted'=>'0');
            $branch=M('branch')->where($var)->select();
            foreach ($branch as $k=>$br){
                $branch[$k]=$br['id'];
            }
        }
        $this->assign('branch', $branch);
        $rr=array('branch'=>$_SESSION['modFunc']['branch'],"deleted"=>'0');
        $func=M('tp_func')->where($rr)->select();
        $this->assign('func', $func);

        $this->display();
    }

    public function modRange(){
        $m = D("tp_func_range");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $this->display();
    }

    public function musttest(){
        //迭代必测点
        $_SESSION['proid']=I('proid');
        $m = D("tp_scene");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $scene=$m->where($where)->order('sn')->select();
        $this->assign("scene",$scene);

        $map = array("testgp" => 'YX', "deleted" => '0');
        $pros = M('project')->where($map)->order("status,end desc")->select();
        $this->assign('pros', $pros);

        $where=array("proid"=>$_SESSION['proid'],"copy"=>$_SESSION['copy']);

        $count=$m->where($where)->count()+1;
        $this->assign('c',$count);

        $this->display();
    }


    function getFunc(){
        $project=$_SESSION['proid'];
        $where=array("project"=>$project,"deleted"=>'0');
        $data=M("tp_func")->where($where)->order('sn')->select();
        if($data){
            $m = D("tp_func_range");
            $m->create($_GET);
            $_GET['adder'] = $_SESSION['account'];
            $_GET['moder'] = $_SESSION['account'];
            foreach ($data as $da){
                $c=$m->where($where)->count();
                $_GET['sn']        = $c+1;
                $_GET['func']      = $da['id'];
                $_GET['type']      = 1;
                $_GET['module']      = $da['module'];
                $_GET['branch']      = $da['branch'];
                $_GET['project']     = $project;
                $_GET['ctime']     = time();
                $m->add($_GET);
            }
            $this->success("拉取功能点成功！");
        }else{
            $this->error("没有标记的功能点！");
        }

    }

}