<?php

namespace Books\Controller;
class ManagerController extends BasicController
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

    public function add()
    {
        $source=I('source','index');
        $this->assign('source', $source);

        $search=I('search');
        $this->assign('search', $search);

        $url='Books/Manager/'.$source.'?search='.I('search');

        $this->assign('url', $url);
        $this->display();
    }

    public function mod()
    {
        $source=I('source','index');
        $this->assign('source', $source);

        $search=I('search');
        $this->assign('search', $search);

        $url='Books/Manager/'.$source.'?search='.I('search');

        $this->assign('url', $url);

        $arr=M('tp_device')->find(I('id'));
        $this->assign('arr', $arr);
        $this->display();
    }

    public function img()
    {
        $source=I('source','index');
        $this->assign('source', $source);

        $search=I('search');
        $this->assign('search', $search);

        $url='Books/Manager/'.$source.'?search='.I('search');

        $this->assign('url', $url);

        $arr=M('tp_device')->find(I('id'));
        $this->assign('arr', $arr);
        $this->display();
    }

    public function img_update(){//更新

        $this->dataUpdate('tp_device', 'Books', $_POST);
    }

}