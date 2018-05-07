<?php

namespace Report\Controller;
class OvertimeController extends WebInfoController
{
    public function index()
    {
        $where['role']='qa';
        $where['deleted']='0';
        $where['account'] = array('in', C(QA_TESTER));
        $data=M('user')->where($where)->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function xiangq()
    {
        $type=I('type');
        $this->assign('type', $type);
        $name=I('name');
        $this->assign('name', $name);

        $where = array('userid' => I('user'),'type'=>I('type'));
        $data= M('tp_overtime')->where($where)->order('riqi desc')->select();
        $this->assign('data', $data);

        $this->display();
    }



}