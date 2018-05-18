<?php

namespace Device\Controller;
class BorrowController extends BasicController
{
    public function index()
    {
        $arr=M('tp_device')->find(I('id'));
        $this->assign('arr', $arr);
        $where=array('deleted'=>'0');
        $user=M('user')->where($where)->order('account')->select();
        $this->assign('user', $user);
        $this->assign('borrower', I('borrower'));

        $source=I('source');
        $this->assign('source', $source);
        $this->assign('search', I('search'));
        $this->assign('url', 'Device/Manager/'.$source);
        $this->assign('riqi', date("Y-m-d", time()));
        if ($source=='index'){
            $this->assign('rules', C(DEVICE_RULES));
        }elseif ($source=='books'){
            $this->assign('rules', C(BOOKS_RULES));
        }else{

        }

        $this->display();
    }

    public function yuding(){
        $id=I('id');
        $arr=M('tp_device')->find($id);
        $this->assign('arr', $arr);

        $where=array('device'=>$id,'type'=>'1','deleted'=>'0');
        $data=M('tp_device_loaning_record')->where($where)->order('end_time desc')->select();
        $this->assign('data', $data);
        $source=I('source');
        $this->assign('source', $source);
        $this->assign('search', I('search'));
        $this->assign('url', 'Device/Index/'.$source);
        $this->assign('riqi', date("Y-m-d", time()+24*60*60));
        if ($source=='index'){
            $this->assign('rules', C(DEVICE_RULES));
        }elseif ($source=='books'){
            $this->assign('rules', C(BOOKS_RULES));
        }else{

        }


        $this->display();
    }
    //借出操作
    function lend(){
        if($_POST['borrower']){
            if($_POST['remark']){
                //插入记录
                $m=D('tp_device_loaning_record');
                $time = strtotime(date("Y-m-d", time()));
                $week = date('w', $time);
                if($_POST['leibie']=='1'){//设备
                    if ($week == 5) {//3天后的9:15
                        $_POST['end_time'] = date('Y-m-d H:i:s', $time + 3 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                    } elseif ($week == 6) {//+2天后的9:15
                        $_POST['end_time'] = date('Y-m-d H:i:s', $time + 2 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                    } else {//+1天后的9:15
                        $_POST['end_time'] = date('Y-m-d H:i:s', $time + 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                    }
                }elseif ($_POST['leibie']=='3'){//图书
                    if ($week == 5) {//3天后的9:15
                        $_POST['end_time'] = date('Y-m-d H:i:s', $time + 10 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                    } elseif ($week == 6) {//+2天后的9:15
                        $_POST['end_time'] = date('Y-m-d H:i:s', $time + 9 * 24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                    } else {//+1天后的9:15
                        $_POST['end_time'] = date('Y-m-d H:i:s', $time + 8*24 * 60 * 60 + 9 * 60 * 60 + 15 * 60);
                    }
                }

                $_POST['adder'] = $_SESSION['account'];
                $_POST['moder'] = $_SESSION['account'];
                $_POST['ctime'] = time();
                if (!$m->create()) {
                    $this->error($m->getError());
                }
                $id=$m->add();
                if ($id) { //更新设备状态
                    $var['id']=$_POST['device'];
                    $var['borrower']=$_POST['borrower'];
                    $var['loaning']='1';
                    if(D('tp_device')->save($var)){
                        if ($_POST['url']){
                            $this->success("成功",U($_POST['url']));
                        }else{
                            $this->success("成功");
                        }
                    }else{//回滚并删除记录数据
                        $m->delete($id);
                        $this->error("失败");
                    }
                } else {
                    $this->error("失败");
                }
            }else{
                $this->error('请填写借用用途');
            }
        }else{
            $this->error('请先从左侧选择借用人！');
        }

    }
    //预约操作
    function bespeak(){
    //指定日期有预约或已借出，不能被预约
        if($_POST['remark']){
            $table='tp_device_loaning_record';
            $start_time=I('start_time');
            $where=array('device'=>I('device'),'start_time'=>$start_time,'deleted'=>'0');
            $var=M($table)->where($where)->select();
            if($var){//当天有预约或借用
                $this->error($start_time.'有人使用该设备');
            }else{//无记录，插入预订记录
                $_POST['table']=$table;
                $this->insert();
            }
        }else{
            $this->error('请如实填写借用的用途');
        }
    }
}