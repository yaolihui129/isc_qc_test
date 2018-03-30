<?php

namespace Record\Controller;
class IndexController extends CommonController
{
    //迭代列表
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
    //迭代涉及的功能点
    public function func(){
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
    //修改功能点
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

        $rr['branch']=$_SESSION['modTestFunc']['branch'];
        $rr['deleted']='0';
        $func=M('tp_func')->where($rr)->select();
        $this->assign('func', $func);

        $this->display();
    }
    //单个添加功能点（或影响）
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
    //添加必测点功能
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
        $_GET['func']    = $arr['id'];
        $_GET['sn']      = $c+1;
        $_GET['scene']    = $scene;
        $_GET['path']    = $arr['path'];
        $_GET['module']  = $arr['module'];
        $_GET['branch']  = $arr['branch'];
        $_GET['adder']   = $_SESSION['account'];
        $_GET['moder']   = $_SESSION['account'];
        $_GET['ctime']   = time();
//        dump($_GET);
        if($m->add($_GET)){
            $this->success("添加成功！");
        }else {
            $this->error("添加失败");
        }
    }
    //影响范围
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
    //修改影响范围
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
    //场景分派
    public function assignment(){
        $where=array("project"=>$_SESSION['proid'],"deleted"=>'0');
        $data=M("tp_scene")->where($where)->order('sn')->select();
        $this->assign("data",$data);

        $where['owner']=I('user',$_SESSION['account']);
        $this->assign("owner",$where['owner']);
        $myScene=M("tp_my_scene")->where($where)->order('sn')->select();
        $this->assign("myScene",$myScene);

        $users=['yaolihui','fanqiao','wangchenzi','menghuihui','lixm','qinzx'];
        $this->assign("users",$users);
//        dump($myScene);
        $this->display();
    }
    //我的必测任务
    public function myMustTest(){
        $map['testgp'] = 'YX';
        $map['deleted'] = '0';
        $map['status'] = array('in', array('wait', 'doing'));
        $arr = M('project')->where($map)->select();
        foreach ($arr as $k=>$ar){
            $arr[$k]=$ar['id'];
        }
        $where['project']=array('in',$arr);
        $where['owner']=$_SESSION['account'];
        $where['deleted'] = '0';

        $m = D("tp_my_scene");
        $data=$m->where($where)->order('project,ctime')->select();
        if($data){
            $project=array();
            foreach ($data as $k=>$da){
                if(!in_array($da['project'],$project)){
                    $project[$k]=$da['project'];
                }
            }
            $this->assign("project",$project);
            $pro=I('project',$project[0]);
            $this->assign("pro",$pro);
            $where['project']=$pro;
            $data=$m->where($where)->order('ctime')->select();
            $this->assign("data",$data);
        }else{
            $this->error('还没有给你分派必测点！');
        }

        $this->display();
    }

    //执行我的测试
    public function runMyTest(){
        $where=array('project'=>I('project'),'deleted'=>'0');
        $myScene= D("tp_my_scene")->where($where)->order('project,ctime')->select();
        $this->assign("myScene",$myScene);
        $scene=I('myScene');
        $this->assign("scene",$scene);
        $where=array('myscene'=>$scene,'deleted'=>'0');
        $data=M('tp_my_scene_func')->where($where)->order('ctime')->select();
        $this->assign("data",$data);

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
    //标记成功
    function pass(){
        $_GET['result'] = 1;
        $_GET['moder'] = $_SESSION['account'];
        if (D('tp_my_scene_func')->save($_GET)) {
            //todo
            //查询功能点数和成功的功能点数
            //成功数等于功能点数标记场景测试结果


            $this->success("OK！");
        } else {
            $this->error("修改失败！");
        }
    }
    //标记失败
    function fail(){
        $_POST['result'] = 2;
        $_POST['moder'] = $_SESSION['account'];
        $m=D('tp_my_scene_func');
        $arr=$m->find(I('id'));
        if ($m->save($_POST)) {
            //更新我的场景
            $_GET['id']=$arr['myscene'];
            D('tp_my_scene')->save($_POST);
            //更新场景
            $_GET['id']=$arr['scene'];
            D('tp_scene')->save($_POST);
            //更新场景功能点
            $_GET['id']=$arr['scenefunc'];
            D('tp_scene_func')->save($_POST);
            //更新功能点
            $_GET['id']=$arr['func'];
            $_GET['result'] = '失败';
            D('tp_func')->save($_POST);
            $this->success("OK！");
        } else {
            $this->error("修改失败！");
        }

    }
    //标记阻塞
    function block(){
        $_GET['result'] = 3;
        $_GET['moder'] = $_SESSION['account'];
        $m=D('tp_my_scene_func');
        $arr=$m->find(I('id'));
        //更新我的功能点
        if ($m->save($_GET)) {
            //更新我的场景
            $_GET['id']=$arr['myscene'];
            D('tp_my_scene')->save($_GET);
            //更新场景
            $_GET['id']=$arr['scene'];
            D('tp_scene')->save($_GET);
            //更新场景功能点
            $_GET['id']=$arr['scenefunc'];
            D('tp_scene_func')->save($_GET);
            //更新功能点
            $_GET['id']=$arr['func'];
            $_GET['result'] = '阻塞';
            D('tp_func')->save($_GET);
            $this->success("OK！");
        } else {
            $this->error("修改失败！");
        }



    }

}