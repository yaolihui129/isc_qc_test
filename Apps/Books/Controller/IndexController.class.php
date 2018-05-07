<?php

namespace Books\Controller;
use Think\Controller;
class IndexController extends Controller
{
    public function index()
    {
        $search = I('search');
        $this->assign('search', $search);
        $where=array('type'=>'1','deleted'=>'0');
        $where['brand|ts|serial|asset_no'] = array('like', '%' . $search . '%');
        $data=M('tp_device')->where($where)->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function books()
    {
        $m=M('tp_device');
        $search = I('search');
        $this->assign('search', $search);
        $where=array('type'=>'3','deleted'=>'0');
        $where['brand|ts|serial|name'] = array('like', '%' . $search . '%');
        $data=$m->where($where)->select();
        $this->assign('data', $data);


        $this->display();
    }

    public function history()
    {
        $id=I('id');
        $arr=M('tp_device')->find($id);
        $this->assign('arr', $arr);
        $this->assign('source', I('source'));
        $this->assign('search', I('search'));
        $where=array('device'=>$id,'deleted'=>'0');
        $data=M('tp_device_loaning_record')->where($where)->order('end_time desc')->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function rules()
    {
        $data=array(
            '1.临时紧急追加的迭代任务，由平台负责人自行在禅道中建立迭代（按照标题和简称规范）',
            '2.平台负责人，直接分派给迭代负责人（目前先按分派执行，以后打算按抢单的模式来）',
            '3.迭代负责人，按照精简模式在Tower上给出排期的里程碑',
            '4.迭代负责人，更新禅道迭代最后日期',
            '5.迭代负责人，在禅道建立测试任务并分派给自己',
        );
        $this->assign('data', $data);

        $this->display();
    }

}