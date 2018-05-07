<?php

namespace Api\Controller;
class AdminController extends BasicController
{
    function index()
    {
        $url='http://127.0.0.1:70/isc_qc_test/index.php/Api/Oauth/token/grant_type/client_credential/appid/asdasdasdasdasdasd/secret/f4wq2wr42wq2asw3e4we345rft65tr43';
        $data=httpGet($url);
        $arr = json_decode($data);
        dump($arr);
    }



}