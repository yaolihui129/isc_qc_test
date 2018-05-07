<?php

namespace Report\Controller;

class ScoreController extends WebInfoController
{
    public function index()
    {
        $saiJi=array();
        $where=array('deleted'=>'0');
        $data=M('tp_my_score')->where($where)->order('ctime desc')->select();
        foreach ($data as $da){
            if(!in_array($da['quarter'], $saiJi)){
                $saiJi[]=$da['quarter'];
            }
        }
        $this->assign("saiJi",$saiJi);

        $quarter=I('quarter',$saiJi[0]);
        $this->assign("quarter",$quarter);

        $where=array('quarter'=>$quarter,'deleted'=>'0');
        $tester= array('fanqiao','wangchenzi','menghuihui', 'lixm');
        $where['user']  = array('in',$tester);
        $data=M('tp_score_list')->where($where)->order('score desc')->select();
        $this->assign("data",$data);

        $this->display();
    }

    public function gengduo(){
        $user=I('user');
        $this->assign("user", $user);
        $quarter=I('quarter');
        $this->assign("quarter", $quarter);
        $this->assign("myScore", sumScore($user,$quarter));

        $where=array('user'=>$user,'quarter'=>$quarter,'deleted'=>'0');

        $data=M('tp_my_score')->where($where)->order('ctime desc')->select();
        $this->assign("data", $data);

        $this->display();

    }

}