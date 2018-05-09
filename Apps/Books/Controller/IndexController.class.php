<?php

namespace Books\Controller;
use Think\Controller;
class IndexController extends Controller
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

    public function books()
    {
        $m=M('tp_device');
        $search = I('search');
        $this->assign('search', $search);
        $where=array('type'=>'3','deleted'=>'0');
        $where['brand|ts|serial|name'] = array('like', '%' . $search . '%');
        $data=$m->where($where)->select();
        $this->assign('data', $data);


        $this->display();
    }

    public function history()
    {
        $id=I('id');
        $arr=M('tp_device')->find($id);
        $this->assign('arr', $arr);
        $this->assign('source', I('source'));
        $this->assign('search', I('search'));
        $where=array('device'=>$id,'deleted'=>'0');
        $data=M('tp_device_loaning_record')->where($where)->order('end_time desc')->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function rules()
    {
        $data='
        <div style="font-family: &quot;Microsoft YaHei UI&quot;; font-size: 14px; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 21px; white-space: normal; widows: 1; background-color: rgb(255, 255, 255);">
            <h2 id="id-移动设备管理规范-1.规范目的" style="margin: 30px 0px 0px; padding: 0px; color: rgb(51, 51, 51); font-size: 20px; font-weight: normal; line-height: 1.5; border-bottom-color: rgb(204, 204, 204); font-family: Arial, sans-serif; widows: 2;">
                1.规范目的</h2>
            <p style="margin: 10px 0px 0px; padding: 0px; color: rgb(51, 51, 51); font-family: Arial, sans-serif; widows: 2;">
                移动设备范围包括产品、开发、测试部门采购的各型号手机、Pad（包括但不限于苹果、安卓、win）、iTouch无线上网等设备及配件。</p>
            <p style="margin: 10px 0px 0px; padding: 0px; color: rgb(51, 51, 51); font-family: Arial, sans-serif; widows: 2;">
                该规范适用于运营、产品、开发、测试等相关使用移动设备人员。</p>
            <p style="margin: 10px 0px 0px; padding: 0px; color: rgb(51, 51, 51); font-family: Arial, sans-serif; widows: 2;">
                随着项目的深入，用于项目的移动设备越来越多，为了使采购的设备能够充分利用、统一调配、统一保管，故建立此规范。</p>
            <h2 id="id-移动设备管理规范-2.规范细则" style="margin: 30px 0px 0px; padding: 0px; color: rgb(51, 51, 51); font-size: 20px; font-weight: normal; line-height: 1.5; border-bottom-color: rgb(204, 204, 204); font-family: Arial, sans-serif; widows: 2;">
                2.规范细则</h2>

            <h4 id="id-移动设备管理规范-2.1采购申请" style="margin: 20px 0px 0px; padding: 0px; color: rgb(51, 51, 51); line-height: 1.42857; font-family: Arial, sans-serif; widows: 2;">
                2.1 采购申请</h4>
            <div>
                <ul style="list-style-position: inside;">
                    <li>待补充</li>
                </ul>
            </div>
            <h4 id="id-移动设备管理规范-2.2设备保管" style="margin: 10px 0px 0px; padding: 0px; color: rgb(51, 51, 51); line-height: 1.42857; font-family: Arial, sans-serif; widows: 2;">
                2.2 设备保管</h4>
            <div>
                <ol style="list-style-position: inside;">
                    <li>保险中心的测试设备统一由腰立辉进行保管</li>
                    <li>车服中心的测试设备由秦振霞、赵辉进行保管</li>
                    <li>特殊机型的所有者可以共享出自己的手机由所有人自己保管</li>
                    <li>在<a href="http://qc.zhidaoauto.com/index.php/Books">图书&设备管理平台</a>进行公示（品牌、型号、系统版本、当前借用人、预约情况及借用的历史记录）</li>
                </ol>
            </div>
            <h4 id="id-移动设备管理规范-2.3设备的借用/归还" style="margin: 10px 0px 0px; padding: 0px; color: rgb(51, 51, 51); line-height: 1.42857; font-family: Arial, sans-serif; widows: 2;">
                2.3 设备的借用/归还</h4>
            <div>
                <ol style="list-style-position: inside;">
                    <li>测试设备随用随还，加快流转（无效占用可耻）</li>
                    <li>测试设备的借用，必须声明设备的用途，保管员区分优先级</li>
                    <li>测试设备务必当天借用当天归还（特殊情况加班需要使用的，第二个工作日9:00-9:15期间必须归还至保管人处）</li>
                    <li>测试设备需要联系使用的，必须在平台再次办理借用手续（前提是这台设备没有被他人预约）</li>
                    <li>建立预约优先的机制，同一台设备每人只保留一条有效的预约，如果同一天内多个人预约，按预约单先建立的优先</li>
                    <li>在计划有变的情况下，预约可以取消，如果有三次有预约但不借用的情况，此人的预约先用权无效，这个准则有保管员自行掌握</li>
                    <li>如果有紧急情况需要使用某设备，找当前借用人私下解决，但是当天也必须归还保管人处</li>
                    <li>保管员本人用于工作用途也要办理借用手续，否则有人借用或预约就必须借出不得私自扣留</li>
                </ol>
            </div>
            <h4 id="id-移动设备管理规范-2.3设备的损坏/丢失" style="margin: 10px 0px 0px; padding: 0px; color: rgb(51, 51, 51); line-height: 1.42857; font-family: Arial, sans-serif; widows: 2;">
                2.3 设备的损坏/丢失</h4>
        </div>
        <div style="font-family: &quot;Microsoft YaHei UI&quot;; font-size: 14px; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 21px; white-space: normal; widows: 1; background-color: rgb(255, 255, 255);">
            <ul style="list-style-position: inside;">
                <li>
                    待补充</li>
            </ul>
        </div>       
        ';
        $this->assign('data', $data);

        $this->display();
    }

}