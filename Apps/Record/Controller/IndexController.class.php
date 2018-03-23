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
        if(I('branch')){
            $_SESSION['modFunc']['branch'] = I('branch');
        }
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

        foreach ($data as $da){
            $a[]=$da['func'];
        }
        if($a){
            $rr['id']=array('not in',$a);
        }
        $rr['branch']=$_SESSION['modFunc']['branch'];
        $rr['deleted']='0';
        $func=M('tp_func')->where($rr)->select();
        $this->assign('func', $func);


        $this->display();
    }

    //场景功能点配置
    public function modTestFunc(){
        if(I('branch')){
            $_SESSION['modTestFunc']['branch'] = I('branch');
        }

        $id=I('scene');
        $m = D("tp_scene");
        $arr=$m->find($id);
        $this->assign("arr",$arr);

        $map=array('scene'=>$id,"deleted"=>'0');
        $data=M('tp_scene_func')->where($map)->select();
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

        $rr['branch']=$_SESSION['modFunc']['branch'];
        $rr['deleted']='0';
        $func=M('tp_func')->where($rr)->select();
        $this->assign('func', $func);

        $this->display();
    }

    function add(){
        $id=I('funcid');
        $arr=M("tp_func")->find($id);
        $project=$_SESSION['proid'];
        $m = D("tp_func_range");
        $where=array("project"=>$project,"deleted"=>'0');
        $c=$m->where($where)->count();
        if (!$m->create($_GET)) {
            $this->error($m->getError());
        }
        $_GET['func']    = $id;
        $_GET['sn']      = $c+1;
        $_GET['type']    = I('type');
        $_GET['path']    = $arr['path'];
        $_GET['module']  = $arr['module'];
        $_GET['branch']  = $arr['branch'];
        $_GET['project'] = $project;
        $_GET['adder']   = $_SESSION['account'];
        $_GET['moder']   = $_SESSION['account'];
        $_GET['ctime']   = time();
        if($m->add($_GET)){
            $this->success("添加成功！");
        }else {
            $this->error("添加失败");
        }
    }

    function addTestFunc(){
        $id=I('funcid');
        $arr=M("tp_func")->find($id);
        $scene=I('scene');
        $m = D("tp_scene_func");
        $where=array("scene"=>$scene,"deleted"=>'0');
        $c=$m->where($where)->count();
        if (!$m->create($_GET)) {
            $this->error($m->getError());
        }
        $_GET['func']    = $id;
        $_GET['sn']      = $c+1;
        $_GET['scene']    = $scene;
        $_GET['path']    = $arr['path'];
        $_GET['module']  = $arr['module'];
        $_GET['branch']  = $arr['branch'];
        $_GET['adder']   = $_SESSION['account'];
        $_GET['moder']   = $_SESSION['account'];
        $_GET['ctime']   = time();
        if($m->add($_GET)){
            $this->success("添加成功！");
        }else {
            $this->error("添加失败");
        }
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
        if(I('branch')){
            $_SESSION['modFunc']['branch'] = I('branch');
        }
        $m = D("tp_func_range");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
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

        foreach ($data as $da){
            $a[]=$da['func'];
        }
        if($a){
            $rr['id']=array('not in',$a);
        }
        $rr['branch']=$_SESSION['modFunc']['branch'];
        $rr['deleted']='0';
        $func=M('tp_func')->where($rr)->select();
        $this->assign('func', $func);


        $this->display();
    }
    //迭代必测点
    public function musttest(){

        $_SESSION['proid']=I('proid');
        if(I('copy')){
            $_SESSION['copy']=I('copy');
        }

        $m = D("tp_scene");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $map = array("testgp" => 'YX', "deleted" => '0');
        $pros = M('project')->where($map)->order("status,end desc")->select();
        $this->assign('pros', $pros);

         if($_SESSION['proid']==$_SESSION['copy']){

         }else{
             if($_SESSION['copy']){
                 $this->assign('copy', '【当前迭代：'.getProname($_SESSION['copy']).'】');
             }
         }
        $count=$m->where($where)->count()+1;
        $this->assign('c',$count);
        //获取项目数据
        $project = $this->projectDict();
        //封装下拉列表
        $project = $this->select($project, 'project', $_SESSION['proid']);
        $this->assign("project", $project);

        $this->display();
    }
    //修改场景
    public function modMust(){
        $m = D("tp_scene");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $scene=$m->find(I('id'));
        $this->assign("scene",$scene);

        $this->display();
    }
    //场景功能点
    public function mustTestFunc(){
        $m = D("tp_scene");
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=$m->where($where)->order('sn')->select();
        $this->assign("data",$data);
        $id=I('id');
        $arr=$m->find($id);
        $this->assign("arr",$arr);

        $map=array('scene'=>$id,"deleted"=>'0');
        $func=M('tp_scene_func')->where($map)->select();
        $this->assign("func",$func);

        $this->display();
    }

    //场景复制
    function copy(){
        if($_SESSION['copy']){
            $id=I('scene');
            $m = D("tp_scene");
            $arr=$m->find($id);
            $_POST['sn']=$m->where(array("project"=>$_SESSION['proid'],"deleted"=>'0'))->count()+1;
            $_POST['level']=$arr['level'];
            $_POST['swho']=$arr['swho'];
            $_POST['swhen']=$arr['swhen'];
            $_POST['scene']=$arr['scene'];
            $_POST['status']='4';
            $_POST['project']=$_SESSION['copy'];
            $_POST['flow']=$arr['flow'];
            $_POST['sourceid']=$id;
            $_POST['adder'] = $_SESSION['account'];
            $_POST['moder'] = $_SESSION['account'];
            $_POST['ctime'] = time();
            if (!$m->create()) {
                $this->error($m->getError());
            }
            $sceneid=$m->add();
            if ($sceneid) {
                //查询原场景的功能点
                $table=D('tp_scene_func');
                $map=array('scene'=>$id,"deleted"=>'0');
                $data=$table->where($map)->order('sn,id')->select();
                if (!$table->create($_GET)) {
                    $this->error($table->getError());
                }
                foreach ($data as $da){
                    $map=array('scene'=>$sceneid,"deleted"=>'0');
                    $c=$table->where($map)->count();
                    $_GET['func']    = $da['func'];
                    $_GET['sn']      = $c+1;
                    $_GET['scene']    = $sceneid;
                    $_GET['path']    = $da['path'];
                    $_GET['module']  = $da['module'];
                    $_GET['branch']  = $da['branch'];
                    $_GET['adder']   = $_SESSION['account'];
                    $_GET['moder']   = $_SESSION['account'];
                    $_GET['ctime']   = time();
                    $a[]=$table->add($_GET);
                }
                if($a){
                    $this->success("复制成功");
                }else{
                    if($m->delete($sceneid)){
                        $this->error("插入功能点失败，过程将回滚");
                    }
                }
            } else {
                $this->error("复制失败");
            }
        }else{
            $this->error("无当前迭代信息");
        }
    }
    //拉取当前迭代的功能点
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
                $_GET['path']      = $da['path'];
                $_GET['module']    = $da['module'];
                $_GET['branch']    = $da['branch'];
                $_GET['project']   = $project;
                $_GET['ctime']     = time();
                $m->add($_GET);
            }
            $this->success("拉取功能点成功！");
        }else{
            $this->error("没有标记的功能点！");
        }

    }

}