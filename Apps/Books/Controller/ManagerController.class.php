<?php

namespace Books\Controller;
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

        $this->display();
    }

    public function add()
    {
        $source = I('source', 'index');
        $this->assign('source', $source);
        $this->assign('type', I('type', '1'));

        $search = I('search');
        $this->assign('search', $search);

        $url = 'Books/Manager/' . $source . '?search=' . I('search');

        $this->assign('url', $url);
        $this->display();
    }

    public function mod()
    {
        $source = I('source', 'index');
        $this->assign('source', $source);

        $search = I('search');
        $this->assign('search', $search);

        $url = 'Books/Manager/' . $source . '?search=' . I('search');

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

        $url = 'Books/Manager/' . $source . '?search=' . I('search');

        $this->assign('url', $url);

        $arr = M('tp_device')->find(I('id'));
        $this->assign('arr', $arr);
        $this->display();
    }

    function img_update()
    {
        $this->dataUpdate('tp_device', 'books', $_POST);
    }

    public function books()
    {

        $this->display();
    }

    //预订列表页面
    public function yuding()
    {
        $m = M('tp_device_loaning_record');
        $riqi = date("Y-m-d", time());
        $this->assign('riqi', $riqi);
        $where = array('manager' => $_SESSION['account'], 'start_time' => $riqi, 'type' => '1', 'deleted' => '0');
        $data = $m->where($where)->order('start_time,ctime')->select();
        $this->assign('data', $data);


        $where['deleted'] = '1';
        $data = $m->where($where)->order('start_time,ctime')->select();
        $this->assign('data1', $data);


        $this->display();
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
            if ($week == 5) {//3天后的9:15
                $_GET['end_time'] = date('Y-m-d H:i:s', $time + 3 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
            } elseif ($week == 6) {//+2天后的9:15
                $_GET['end_time'] = date('Y-m-d H:i:s', $time + 2 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
            } else {//+1天后的9:15
                $_GET['end_time'] = date('Y-m-d H:i:s', $time + 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
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

    //归还
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



}