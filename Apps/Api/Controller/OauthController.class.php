<?php

namespace Api\Controller;
class OauthController extends BasicController
{
    function index()
    {
        $this->json(200,'Welcome');
    }

    function token(){
        $grant_type=I('grant_type');
        if($grant_type=='client_credential'){
            $appid=I('appid');
            $m=M('tp_app');
            $where['appid']=$appid;
            $where['status']='0';
            $where['deleted']='0';
            $data=$m->where($where)->select();
            if($data){
                $secret=I('secret');
                if($secret){
                    $where['secret']=$secret;
                    $arr=$m->where($where)->order('ctime desc')->select();
                    if($arr){
                        //签发token
                        $token=getRandCode(36);
                        // 1.查询是否有有效的token
                        $map['app']     =   $arr[0]['id'];
                        $map['expires'] =   array('gt',time());
                        $map['deleted'] =   '0';
                        $m=M('tp_access_token');
                        $var=$m->where($map)->select();
                        if($var){
                            //如果有，作废掉原有的token
                            foreach ($var as $v){
                                $a['id']=$v['id'];
                                $a['deleted']= '1';
                                $this->update($a,'tp_access_token');
                            }
                        }
                        //如果没有写库tp_token表
                        $b['token']=$token;
                        $b['expires']=time()+7200;
                        $b['app']=$arr[0]['id'];
                        $b['name']=$arr[0]['name'];
                        $b['hosts']=get_client_ip();
                        //todo 相同的token同时生效的处理
                        $c=$this->insert($b,'tp_access_token');
                        if($c){
                            $token=array(
                                "access_token"=>$token,
                                "expires_in"=>7200
                            );
                            $this->json(0,'OK',$token);
                        }

                    }else{
                        $this->json(40001,'AppSecret error or AppSecret does not belong to this AppID');
                    }
                }else{
                    $this->json(40001,'AppSecret error or AppSecret does not belong to this AppID');
                }

            }else{
                $this->json(40164,'invalid appid');
            }
        }else{
            $this->json(40002,'type Error');
        }
    }



}