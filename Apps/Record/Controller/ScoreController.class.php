<?php

namespace Record\Controller;

class ScoreController extends CommonController
{
    public function index()
    {
        $_SESSION['proid'] = I('proid');



        $this->display();
    }

    public function mod()
    {



        $this->display();
    }

}