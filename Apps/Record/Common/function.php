<?php

    function countFuncRangeId($table,$name,$value){
        $where=array($name=>$value,"deleted"=>'0','type'=>'1');
        $count=M($table)->where($where)->count();
        return $count;
    }

    