<?php

namespace Record\Controller;

class AppraisalController extends CommonController
{
    //绩效考核页面
    public function index()
    {
        $user=['2','4','9'];
        $dissent=array(
            array('key'=>'1','value'=>'允许申诉'),
            array('key'=>'0','value'=>'不允许申诉'),
        );
        $tester= array('fanqiao','wangchenzi','menghuihui', 'lixm');
        if(in_array($_SESSION['id'],$user)){
            $project=$this->projectDict();
            foreach ($project as $pro){
                $a[]=$pro['key'];
            }
            $_SESSION['Appraisal']['project']=I('project',$a[0]);
            $_SESSION['Appraisal']['tester']=I('tester',$tester[0]);
            $this->assign("project", $a);
            $this->assign("tester",$tester);
            $score=sumScore($_SESSION['Appraisal']['tester'],C(KH_QUARTER));
            $this->assign("score",$score);
            $this->assign("quarter",C(KH_QUARTER));

            //封装加分项下拉
            if($_SESSION['Appraisal']['project']){
                $where=array('project'=>'1','type'=>'1','deleted'=>'0');
                $map=array('project'=>'1','type'=>'2','deleted'=>'0');
                $dissent = $this->select($dissent, 'dissent','1');
                $var=array(
                    'project'=>$_SESSION['Appraisal']['project'],
                    'quarter'=>C(KH_QUARTER),
                    'user'=>$_SESSION['Appraisal']['tester'],
                    'deleted'=>'0'
                );
            }else{
                $where=array('project'=>'0','type'=>'1','deleted'=>'0');
                $map=array('project'=>'0','type'=>'2','deleted'=>'0');
                $dissent = $this->select($dissent, 'dissent','0');
                $var=array(
                    'quarter'=>C(KH_QUARTER),
                    'user'=>$_SESSION['Appraisal']['tester'],
                    'deleted'=>'0'
                );
            }
            //人员积分明细
            $m=M('tp_my_score');
            $data=$m->where($var)->order('ctime desc')->select();
            $this->assign("data", $data);

            $count=$m->where(array('status'=>'1','deleted'=>'0')) ->count();
            $this->assign("count", $count);

            $m=M('tp_score_rules');
            $jiaF=$m->where($where)->select();
            foreach ($jiaF as $jia){
                $jiaFen[]=array(
                    'key'=>$jia['id'],
                    'value'=>'【'.$jia['cate'].'】'.$jia['name'].' +'.$jia['score']
                );
            }
            $jiaFen = $this->select($jiaFen, 'rules');
            $this->assign("jiaFen", $jiaFen);

            //封装减分项下拉
            $jianF=$m->where($map)->select();
            foreach ($jianF as $jian){
                $jianFen[]=array(
                    'key'=>$jian['id'],
                    'value'=>'【'.$jian['cate'].'】'.$jian['name'].' -'.$jian['score']
                );
            }
            $jianFen = $this->select($jianFen, 'rules');
            $this->assign("jianFen", $jianFen);

            //封装允许申诉下拉
            $this->assign("dissent", $dissent);
            $this->display();
        }else{
            dump('你没有权限访问此功能');
        }

    }
    //申诉列表
    public function appeal(){
        $where=array('status'=>'1','deleted'=>'0');
        $m=M('tp_my_score');
        $this->assign("data", $m->where($where) ->select());
        $this->assign("acount", $m->where($where) ->count());
        $where=array('quarter'=>C(KH_QUARTER),'status'=>'2','deleted'=>'0');
        $this->assign("dcount", $m->where($where) ->count());
        $where=array('quarter'=>C(KH_QUARTER),'status'=>'3','deleted'=>'0');
        $this->assign("rcount", $m->where($where) ->count());
        $this->display();
    }
    //申诉已完成
    public function done(){
        $where=array('quarter'=>C(KH_QUARTER),'status'=>'2','deleted'=>'0');
        $m=M('tp_my_score');
        $this->assign("data", $m->where($where) ->select());
        $this->assign("dcount", $m->where($where) ->count());
        $where=array('status'=>'1','deleted'=>'0');
        $this->assign("acount", $m->where($where) ->count());
        $where=array('quarter'=>C(KH_QUARTER),'status'=>'3','deleted'=>'0');
        $this->assign("rcount", $m->where($where) ->count());
        $this->display();
    }
    //申诉被驳回
    public function reject(){
        $where=array('quarter'=>C(KH_QUARTER),'status'=>'3','deleted'=>'0');
        $m=M('tp_my_score');
        $this->assign("data", $m->where($where) ->select());
        $this->assign("rcount", $m->where($where) ->count());
        $this->assign("dcount", $m->where($where) ->count());
        $where=array('quarter'=>C(KH_QUARTER),'status'=>'3','deleted'=>'0');
        $where=array('status'=>'1','deleted'=>'0');
        $this->assign("acount", $m->where($where) ->count());
        $this->display();
    }
    //插入数据
    function charu(){
        if(!I('score')){
            $data=M(tp_score_rules)->find(I('rules'));
            $_POST['score']=$data['score'];
        }
        $m=D('tp_my_score');
        $_POST['adder'] = $_SESSION['account'];
        $_POST['moder'] = $_SESSION['account'];
        $_POST['ctime'] = time();
        if (!$m->create()) {
            $this->error($m->getError());
        }
        if ($m->add()) {
            if($this->updateList($_POST['user'],$_POST['quarter'])){
                $this->success("成功");
            }else{
                $this->error("排行榜更新失败");
            }
        } else {
            $this->error("失败");
        }
    }
    //驳回
    function bohui(){
        $_GET['status'] = 3;
        $_GET['moder'] = $_SESSION['account'];
        if (D('tp_my_score')->save($_GET)) {
            $this->success("成功！");
        } else {
            $this->error("失败！");
        }
    }
    //更新积分排行
    function updateList($user,$quarter){
        //1.查询list
        $score=sumScore($user,$quarter);
        $where=array('user'=>$user,'quarter'=>$quarter,'deleted'=>'0');
        $m=D('tp_score_list');
        $arr=$m->where($where)->select();
        //2.判断是否有值
        if($arr){
            //3.有值更新人员积分
            $_GET['id']=$arr[0]['id'];
            $_GET['score']=$score;
            $_GET['moder'] = $_SESSION['account'];
            $id=$m->save($_GET);
        }else{
            //4.无值插入人员积分
            $_GET['user'] = $user;
            $_GET['score'] = $score;
            $_GET['quarter'] = $quarter;
            $_GET['adder'] = $_SESSION['account'];
            $_GET['moder'] = $_SESSION['account'];
            $_GET['ctime'] = time();
            if (!$m->create($_GET)) {
                $this->error($m->getError());
            }
            $id=$m->add($_GET);
        }
        return $id;
    }
}