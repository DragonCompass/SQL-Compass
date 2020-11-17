<?php
    exec("python python/tool.py",$str);

    $result = json_decode($str,true);
    $check_page_num=$result['alistlen'];
    $vul_page_num='';
    $page_ratio=$vul_page_num/$check_page_num*100;
    $vul_num=$result['vlistlen'];
    $high_vul_num='';
    $low_vul_num='';

    #동그라미 도표 데이터
    $normal = 50;#$check_page_num-$vul_page_num/$check_page_num*100."%";
    $high = 30;#$high_vul_num/$check_page_num*100;
    $low = 20;#$low_vul_num/$check_page_num*100;


    $vlist= $result['alistlen'];

    $data1=[1000,2000,3000,4000,5000,6000]; #막대그래프 데이터
    $data2=[6000,5000,4000,3000,2000,1000];

    #$tableData=[{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'}
    #,{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'}];
    $tableData = [['a','b','c'],['a','b','c'],['a','b','c']];



    #막대그래프 변경
    echo ("<script type='text/javascript' src='vendor/chart.js/Chart.js'></script>"); 
    echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>myBarChart.data.datasets[0].data=$data1</script>");
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>updateBarGraph($data1,$data2)</script>");

    #동그라미 도표 변경
    echo ("<script type='text/javascript' src='js/demo/chart-pie-demo.js'>myPieChart.data.datasets[0].data=[$normal,$high,$low]</script>");
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>updateBarGraph(data1,data2)</script>");


?>