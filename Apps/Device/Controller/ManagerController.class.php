<?php

namespace Device\Controller;
class ManagerController extends BasicController
{
    public function index()
    {
        $search = I('search');
        $this->assign('search', $search);
        $where = array('type' => '1', 'manager' => $_SESSION['account'], 'deleted' => '0');
        $where['brand|ts|serial|asset_no'] = array('like', '%' . $search . '%');
        $data = M('tp_device')->where($where)->select();
        $this->assign('data', $data);
        $this->assign('riqi', date("Y-m-d", time()));

        $this->display();
    }

    public function add()
    {
        $source = I('source', 'index');
        $this->assign('source', $source);
        $this->assign('type', I('type', '1'));

        $search = I('search');
        $this->assign('search', $search);

        $url = 'Device/Manager/' . $source . '?search=' . I('search');

        $this->assign('url', $url);
        $this->display();
    }

    public function mod()
    {
        $source = I('source', 'index');
        $this->assign('source', $source);

        $search = I('search');
        $this->assign('search', $search);

        $url = 'Device/Manager/' . $source . '?search=' . I('search');

        $this->assign('url', $url);

        $arr = M('tp_device')->find(I('id'));
        $this->assign('arr', $arr);
        $this->display();
    }

    public function img()
    {
        $source = I('source', 'index');
        $this->assign('source', $source);

        $search = I('search');
        $this->assign('search', $search);

        $url = 'Device/Manager/' . $source . '?search=' . I('search');

        $this->assign('url', $url);

        $arr = M('tp_device')->find(I('id'));
        $this->assign('arr', $arr);
        $this->display();
    }

    function img_update()
    {
        $this->dataUpdate('tp_device', 'Device', $_POST);
    }

    public function books()
    {
        $search = I('search');
        $this->assign('search', $search);
        $where = array('type' => '3', 'manager' => $_SESSION['account'], 'deleted' => '0');
        $where['brand|ts|serial|asset_no'] = array('like', '%' . $search . '%');
        $data = M('tp_device')->where($where)->select();
        $this->assign('data', $data);
        $this->assign('riqi', date("Y-m-d", time()));

        $this->display();
    }

    //预订列表页面
    public function yuding()
    {
        $m = M('tp_device_loaning_record');
        $riqi = date("Y-m-d", time());
        $this->assign('riqi', $riqi);
        $device=I('device');
        if($device){
            $where = array('device'=>$device,'manager' => $_SESSION['account'], 'start_time' => $riqi, 'type' => '1', 'deleted' => '0');
        }else{
            $where = array('manager' => $_SESSION['account'], 'start_time' => $riqi, 'type' => '1', 'deleted' => '0');
        }
        $data = $m->where($where)->order('start_time,ctime')->select();
        $this->assign('data', $data);
        $where['deleted'] = '1';
        $data = $m->where($where)->order('start_time,ctime')->select();
        $this->assign('data1', $data);

        $this->display();
    }

    //管理员驳回
    function reject(){
        $_GET['deleted']='1';
        $_GET['reject']='2';
        $_GET['table']='tp_device_loaning_record';
        $this->update();
    }

    //设备OR图书借出
    function lend()
    {
        $m = D('tp_device_loaning_record');
        $arr = $m->find(I('id'));

        //0判断设备状态如果硬借出直接驳回
        $t = D('tp_device');
        $var = $t->where(array('id' => $arr['device'], 'loaning' => '1', 'deleted' => '0'))->find();
        if ($var) {
            $this->error("已借给" . getZTUserName($var['borrower']) . '了，他归还了吗？');
        } else {
            //1.更新预约状态
            $_GET['type'] = '0';
            $time = strtotime($arr['start_time']);
            $week = date('w', $time);
            if($arr['leibie']=='1'){
                if ($week == 5) {//3天后的9:15
                    $_GET['end_time'] = date('Y-m-d H:i:s', $time + 3 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                } elseif ($week == 6) {//+2天后的9:15
                    $_GET['end_time'] = date('Y-m-d H:i:s', $time + 2 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                } else {//+1天后的9:15
                    $_GET['end_time'] = date('Y-m-d H:i:s', $time + 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                }
            }elseif ($arr['leibie']=='3'){
                if ($week == 5) {//10天后的9:15
                    $_GET['end_time'] = date('Y-m-d H:i:s', $time + 10 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                } elseif ($week == 6) {//+9天后的9:15
                    $_GET['end_time'] = date('Y-m-d H:i:s', $time + 9 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                } else {//+8天后的9:15
                    $_GET['end_time'] = date('Y-m-d H:i:s', $time + 8*24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                }
            }

            $_GET['moder'] = $_SESSION['account'];
            if ($m->save($_GET)) {
                //2.更新设备信息
                $_POST['id'] = $arr['device'];
                $_POST['loaning'] = '1';
                $_POST['borrower'] = $arr['borrower'];
                if (D('tp_device')->save($_POST)) {
                    $this->success("成功");
                } else {
                    // 回滚数据
                    $_GET['id'] = $arr['id'];
                    $_GET['type'] = '1';
                    $_GET['end_time'] = $arr['end_time'];
                    $m->save($_GET);
                    $this->error("失败！");
                }
            }
        }
    }

    //借出设备&图书待归还列表
    public function guihuan()
    {
        $where = array('manager' => $_SESSION['account'], 'type' => '0', 'deleted' => '0');
        $data = M('tp_device_loaning_record')->where($where)->order('end_time')->select();
        $this->assign('data', $data);

        $this->display();
    }

    //借用者主动申请归还
    function take_back()
    {
        $m = D('tp_device_loaning_record');
        $arr = $m->find(I('id'));
        $_GET['type'] = '2';
        $_GET['moder'] = $_SESSION['account'];
        $_GET['end_time'] = date('Y-m-d H:i:s', time());
        if ($m->save($_GET)) {
            $t = D('tp_device');
            $var = $t->where(array('id' => $arr['device'], 'loaning' => '1', 'borrower' =>  $arr['borrower'], 'deleted' => '0'))->find();
            if($var){//借出状态，借用人一致，正常处理;不一致就不再处理设备信息
                //2.更新设备信息
                $_POST['id'] = $arr['device'];
                $_POST['loaning'] = '0';
                if (D('tp_device')->save($_POST)) {
                    $this->success("成功");
                } else { // 回滚数据
                    $_GET['id'] = $arr['id'];
                    $_GET['type'] = '1';
                    $_GET['end_time'] = $arr['end_time'];
                    $m->save($_GET);
                    $this->error("失败！");
                }
            }
        }else{
            $this->error("失败！");
        }
    }
    //管理员收回
    function hui_shou(){
        $_GET['loaning']='0';
        if (D('tp_device')->save($_GET)) {
            $m = D('tp_device_loaning_record');
            $where=array('device'=>I('id'),'type'=>'0','deleted'=>'0');
            $arr=$m->where($where)->select();
            $_POST['id']=$arr[0]['id'];
            $_POST['type']='2';
            $_POST['end_time']=date('Y-m-d H:i:s', time());
            if ($m->save($_POST)){
                $this->success("成功");
            }else{
                $this->error("失败！");
            }
        }else{
            $this->error("失败！");
        }
    }
    //管理员借出
    function loan(){
        $device=I('device');
        $source=I('source','index');
        $search=I('search');
        $m = D('tp_device_loaning_record');
        //今天的预订
        $where=array('device'=>$device,'type'=>'1','start_time'=>date('Y-m-d',time()),'deleted'=>'0');
        $arr = $m->where($where)->find();
        if($arr){//如果有预订，跳转至预订页面
            $this->redirect('Device/Manager/yuding?device='.$device);
        }else{//如果没有预订，跳转至借出页面
            if($search){
                $url='Device/Borrow/index/id/'.$device.'/source/'.$source.'/search/'.$search;
            }else{
                $url='Device/Borrow/index/id/'.$device.'/source/'.$source;
            }

            $this->redirect($url);
        }

    }



}