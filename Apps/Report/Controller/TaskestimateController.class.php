<?php

namespace Report\Controller;
class TaskestimateController extends WebInfoController
{
    function getInfo()
    {
        $data = array(
            'table' => 'taskestimate',
            'name' => 'Taskestimate'
        );
        return $data;
    }

    public function index()
    {
        $info = $this->getInfo();
        $m = M($info['table']);
        $map['account'] = array('in', C(QA_TESTER));
        $data = $m->where($map)->select();
        $arr[] = $data[0]['account'];
        foreach ($data as $d) {
            if (!in_array($d['account'], $arr)) {
                $arr[] = $d['account'];
            }
        }
        $this->assign('arr', $arr);

        $where['zt_taskestimate.account'] = array('in', C(QA_TESTER));
        $join = 'zt_task ON zt_taskestimate.task=zt_task.id';
        $var = $m->join($join)->where($where)->order('zt_task.project desc')->select();
        $project[] = $var[0]['project'];
        foreach ($var as $v) {
            if (!in_array($v['project'], $project)) {
                $project[] = $v['project'];
            }
        }
        $this->assign('project', $project);
        $this->display();
    }

    public function daily()
    {//项目人员投入表
        $project = I('project');
        $this->assign("project", $project);
        $info = $this->getInfo();
        $m = M($info['table']);
        $where['zt_task.project'] = I('project');
        $join = 'zt_task ON zt_taskestimate.task=zt_task.id';
        $data = $m->join($join)->where($where)->group('name')->order('account desc')->select();
        $this->assign("data", $data);
        $data = $m->join($join)->where($where)->group('name')->order('date desc')->select();
        $arr[] = $data[0]['date'];
        foreach ($data as $d) {
            if (!in_array($d['date'], $arr)) {
                $arr[] = $d['date'];
            }
        }
        $this->assign('arr', $arr);
        $this->display();
    }

    public function task()
    {//人员每日任务投入分布表
        $day = I('day', 7);
        $this->assign('day', $day);
        $info = $this->getInfo();
        $m = M($info['table']);
        $riqi = date("Y-m-d", time() - $day * 24 * 3600);
        $map['date'] = array('egt', $riqi);
        $map['account'] = array('in', C(QA_TESTER));
        $join = 'zt_task ON zt_taskestimate.task=zt_task.id';
        $data = $m->join($join)->where($map)->group('name')->order('date desc')->select();
        $this->assign('data', $data);

        $arr[] = $data[0]['date'];
        foreach ($data as $d) {
            if (!in_array($d['date'], $arr)) {
                $arr[] = $d['date'];
            }
        }
        $this->assign('arr', $arr);
        $this->display();

    }

    public function input()
    {//人员阶段投入分布表
        $day = I('day', 7);
        $this->assign('day', $day);
        $info = $this->getInfo();
        $m = M($info['table']);
        $riqi = date("Y-m-d", time() - $day * 24 * 3600);
        $map['date'] = array('egt', $riqi);
        $map['account'] = array('in', C(QA_TESTER));
        $data = $m->where($map)->field('account,date,sum(consumed) as consumed')->group('account,date')->order('date desc')->select();
        $var[] = $data[0]['account'];
        foreach ($data as $d) {
            if (!in_array($d['account'], $var)) {
                $var[] = $d['account'];
            }
        }
        $this->assign('var', $var);
        $arr[] = $data[0]['date'];
        foreach ($data as $d) {
            if (!in_array($d['date'], $arr)) {
                $arr[] = $d['date'];
            }
        }
        $this->assign('arr', $arr);
        $this->display();

    }

}