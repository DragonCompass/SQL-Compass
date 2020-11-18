<?php
    /*exec("./test",$res);
    echo "<br>".$res[0];
    echo "<br>".$res[1];*/


    exec("python python/tool.py",$str);
    exec("cd /var/www/html/KSJ_SQLI_Project/python/dist && ./tool")
    /*$check_page_num='$cha';
    $vul_page_num='';
    $page_ratio='';
    $vul_num='';
    $high_vul_num='';
    $low_vul_num='';*/

    $result = json_decode($str,true);
    $check_page_num=$result['alistlen'];
    $vul_page_num=$result['vpagelen'];
    $page_ratio='';
    $vul_num=$result['vlistlen'];
    $high_vul_num=$result['high'];
    $low_vul_num=$result['low'];
    $vlist= $result['alistlen'];
?>