<?php

namespace Report\Controller;
class RulesController extends WebInfoController
{
    public function prorules()
    {
        $_SESSION['proid'] = I('proid');
        $where = array("zt_projectstory.project" => $_SESSION['proid'], 'zt_story.deleted' => '0');
        $data=M('story')->join('zt_projectstory ON zt_projectstory.story =zt_story.id')->where($where)
            ->field('
                        zt_story.id as id,
                        zt_story.branch as branch,
                        zt_story.module as module,
                        zt_story.title as title,
                        zt_story.version as version
                ')
            ->order('zt_projectstory.order')->select();

        $this->assign("data", $data);


        $this->display();
    }

}