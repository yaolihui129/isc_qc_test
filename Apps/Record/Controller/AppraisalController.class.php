<?php

namespace Record\Controller;

class AppraisalController extends CommonController
{
    public function index()
    {
        $user=['2,4,7'];
        $_SESSION['id'] = I('proid');
        if(1){
            $this->display();
        }else{
            dump('你没有权限访问此功能');
        }

    }

    public function mod()
    {



        $this->display();
    }

}