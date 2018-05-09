<?php
    /*
     * 设备&图书的预约数量
     */
    function count_yd($id,$manager=''){
        if($manager){
            $where=array('device'=>$id,'type'=>'1','manager'=>$manager,'deleted'=>'0');
        }else{
            $where=array('device'=>$id,'type'=>'1','deleted'=>'0');
        }
        $count=M('tp_device_loaning_record')->where($where)->count();
        return $count;
    }
    /*
     * 设备&图书的可借用的状态
     * 借阅：0-可借，1-借出，2-待归还
     */
    function book_status($status){
        if ($status==1){
            return '借出';
        }elseif ($status==2){
            return '借出=';
        }elseif ($status==0){
            return '可借';
        }else{
            return '';
        }
    }

    /*
     * 设备&图书的借出状态
     * 分类：0-借阅，1-预订，2-归还
     */
    function book_history_status($status){
        if ($status==1){
            return '预订';
        }elseif ($status==2){
            return '归还';
        }elseif ($status==0){
            return '借用';
        }else{
            return '';
        }
    }