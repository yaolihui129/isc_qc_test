<?php

namespace Report\Controller;
class StandardController extends WebInfoController
{
    public function index()
    {
        $var='
        <h3>方案一：需求迭代</h3>

            <b>标准评估里程碑：</b><br />
                &nbsp;&nbsp; 1. 冒烟测试用例<br />
                &nbsp;&nbsp; 2. 准入验收（单次不超过4小时）<br />
                &nbsp;&nbsp; 3. 第一轮测试（不超过8小时，可以由多个人参与）<br />
                &nbsp;&nbsp; 4. 第二轮测试（不超过8小时，可以由多个人参与）<br />
                &nbsp;&nbsp; 5. 第三轮测试（不超过8小时，非必须，可以由多个人参与）<br />
                &nbsp;&nbsp; 6. 预发环境验证<br />
                &nbsp;&nbsp; 7. 线上环境验证<br />    
                
                     
        <b><red>注意：从准入验收通过到达到上线标准，尽量控制一周之内</red></b>
        <h3>方案二：BUG迭代（单功能优化）</h3>
            <b>简化评估里程碑：</b><br />
                &nbsp;&nbsp; 1. 准入验收（单次不超过4小时）<br />
                &nbsp;&nbsp; 2. 预发环境验证<br />
                &nbsp;&nbsp; 3. 线上环境验证<br />
                
        <b><red>注意：尽量在准入验收通过当天上线</red></b>
    ';

        $this->assign('var', $var);

        $this->display();
    }

}