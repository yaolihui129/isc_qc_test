<?php

namespace Record\Controller;

class ScoreController extends CommonController
{

    //我的积分
    public function index()
    {
        $user=$_SESSION['account'];
        $this->assign("myScore", sumScore($user,C(KH_QUARTER)));

        $where=array('user'=>$user,'quarter'=>C(KH_QUARTER),'deleted'=>'0');
        $data=M('tp_my_score')->where($where)->order('ctime desc')->limit(10) ->select();
        $this->assign("data", $data);

        $this->display();
    }
    //规则
    public function rules(){
        $where=array('deleted'=>'0');
        $data=M('tp_score_rules')->where($where)->order('cate desc')->select();
        $this->assign("data",$data);
        $type = array(
            array('key'=>1,'value'=>'加分项'),
            array('key'=>2,'value'=>'减分项'),
        );

        $type = $this->select($type, 'type');
        $this->assign("type", $type);

        $project = array(
            array('key'=>1,'value'=>'关联迭代'),
            array('key'=>0,'value'=>'不关联'),
        );

        $project = $this->select($project, 'project');
        $this->assign("project", $project);


        $this->display();
    }
    //更多
    public function gengduo(){
        $where=array('user'=>$_SESSION['account'],'quarter'=>C(KH_QUARTER),'deleted'=>'0');
        $data=M('tp_my_score')->where($where)->order('ctime desc') ->select();
        $this->assign("data", $data);

        $score=sumScore($_SESSION['account'],C(KH_QUARTER));
        $this->assign("score",$score);

        $this->display();
    }

    //申诉
    public function appeal(){
        $arr=M('tp_my_score') ->find(I('id'));
        if($arr['dissent']){
            $this->assign("arr",$arr);
            $shix=$arr['ctime'] +7*24*3600;
            $this->assign("shix",date('Y-m-d H:i:s',$shix));
            $this->display();
        }else{
            dump('考核不允许申诉');
        }


    }

}