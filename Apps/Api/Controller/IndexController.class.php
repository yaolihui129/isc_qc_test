<?php

namespace Api\Controller;
class IndexController extends BasicController
{
    public function index()
    {
        //练习封装JSon
        $people = array('name'=>"Bill", 'age'=>"Steve", 'hight'=>"Mark");
        $this->json_public($people,'1221312312');
    }


    public function ins(){
        //记录接口执行结果
        $table='tp_api_test_result';
        $_POST['adder']=$_POST['tester'].'-JMeter';
        $_POST['moder']=$_POST['tester'].'-JMeter';
        $this->insert($_POST,$table);
    }
}