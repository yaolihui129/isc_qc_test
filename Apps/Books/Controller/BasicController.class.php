<?php

namespace Books\Controller;

use Think\Controller;

class BasicController extends Controller
{
    public function _initialize()
    {// 初始化的时候检查用户权限
        if(!$_SESSION['isLogin']=='Books'){
            if($_SESSION['realname'] == ''){
                $this->redirect('Books/Login/index');
            }
        }
    }

    public function _empty()
    { //错误网页重新定向到首页
        $this->redirect('public/404');
    }

    public function insert()
    {
        $m = D(I('table'));
        if (IS_GET) {
            $_GET['adder'] = $_SESSION['account'];
            $_GET['moder'] = $_SESSION['account'];
            $_GET['ctime'] = time();
            if (!$m->create($_GET)) {
                $this->error($m->getError());
            }
            if ($m->add($_GET)) {
                if ($_GET['url']){
                    $this->success("成功",U($_GET['url']));
                }else{
                    $this->success("成功");
                }
            } else {
                $this->error("失败");
            }
        } else {
            $_POST['adder'] = $_SESSION['account'];
            $_POST['moder'] = $_SESSION['account'];
            $_POST['ctime'] = time();
            if (!$m->create()) {
                $this->error($m->getError());
            }
            if ($m->add()) {
                if ($_POST['url']){
                    $this->success("成功",U($_POST['url']));
                }else{
                    $this->success("成功");
                }
            } else {
                $this->error("失败");
            }
        }
    }

    //修改
    public function update()
    {
        if (IS_GET) {
            $_GET['moder'] = $_SESSION['account'];
            if (D(I('table'))->save($_GET)) {
                if ($_GET['url']){
                    $this->success("成功",U($_GET['url']));
                }else{
                    $this->success("成功");
                }
            } else {
                $this->error("失败！");
            }
        } else {
            $_POST['moder'] = $_SESSION['account'];
            if (D(I('table'))->save($_POST)) {
                if ($_POST['url']){
                    $this->success("成功",U($_POST['url']));
                }else{
                    $this->success("成功");
                }
            } else {
                $this->error("失败！");
            }
        }
    }

    public function dataUpdate($table,$savePath,$data,$img='img',$url=''){
        $_POST=$data;
        $_POST['moder']=$_SESSION['realname'];
        //处理上传图片
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize  =     7145728 ;// 设置附件上传大小
        $upload->exts     =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath =  './Upload/Books';// 设置附件上传目录
        $upload->savePath = '/'.$savePath.'/'; // 设置附件上传目录
        $info  =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息或没有上传图片
            if (D($table)->save($_POST)){
                $this->success("修改成功！",U('index',$_POST['url']));
            }else{
                $this->error("失败");
            }
        }else {
            $_POST[$img]='Books'.$info[$img]['savepath'].$info[$img]['savename'];
            if (D($table)->save($_POST)){
                $image = new \Think\Image();
                $image->open('./Upload/Books/'.$info[$img]['savepath'].$info[$img]['savename']);
                $image->thumb(800, 400)->save('./Upload/Books/'.$info[$img]['savepath'].$info[$img]['savename']);
                $this->success("修改成功！",U('index',$_POST['url']));
            }else{
                $this->error("修改失败！");
            }
        }

    }

}