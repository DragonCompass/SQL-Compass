<?php
    /*exec("./test",$res);
    echo "<br>".$res[0];
    echo "<br>".$res[1];*/


    exec("python python/tool.py",$str);
    /*$check_page_num='$cha';
    $vul_page_num='';
    $page_ratio='';
    $vul_num='';
    $high_vul_num='';
    $low_vul_num='';*/

    $result = json_decode($str,true);
    $check_page_num=$result['alistlen'];
    $vul_page_num='';
    $page_ratio='';
    $vul_num=$result['vlistlen'];
    $high_vul_num='';
    $low_vul_num='';
    $vlist= $result['alistlen'];
?>