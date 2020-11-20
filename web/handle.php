<?php
    #exec("python python/tool.py",$str);
    #exec("cd /var/www/html/KSJ_SQLI_Project/python/dist && ./tool", );
    exec("cd /var/www/html/python/dist && ./tool  ",$str);
    $result = json_decode($str[0],true);
    $check_page_num=$result['alistlen'];
    $vul_page_num=$result['vpagelen'];
    $page_ratio=$vul_page_num/$check_page_num*100;
    $vul_num=$result['vlistlen'];
    $high_vul_num=$result['high'];
    $low_vul_num=$result['low'];

    #동그라미 도표 데이터
    $normal = $check_page_num-$vul_page_num/$check_page_num*100;
    $high = $high_vul_num/$check_page_num*100;
    $low = $low_vul_num/$check_page_num*100;


    $vlist= $result['vlist'];

    $data1=[1000,2000,3000,4000,5000,6000]; #막대그래프 데이터
    $data2=[6000,5000,4000,3000,2000,1000];

    #$tableData=[{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'}
    #,{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'},{'url':'a','form':'b','query':'c'}];
$tableData=Array();
foreach ($result['vlist'] as $tmp){
	$vlist = json_decode($tmp,true);
	array_push($tableData,
		array(
			$vlist['url'],
			$vlist['fname'],
			$vlist['query'],
		)
	);
} 

#   $tableData = [['a','b','c'],['a','b','c'],['a','b','c']];


    $datav=10203;
    #막대그래프 변경
    #echo ("<script type='text/javascript' src='vendor/chart.js/Chart.js'></script>"); 
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>myBarChart.data.datasets[0].data=$data1</script>");
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>updateBarGraph(1000,2000)</script>");
    echo ("<script> var data1= new Array($datav,'5000','4000','3000','2000','1000') </script>");
    echo ("<script> var data2= new Array($datav,'2000','3000','4000','5000','6000') </script>");
    #echo ("<script> var data2= $data2 </script>");


    
#동그라미 도표 변경
    #echo ("<script type='text/javascript' src='js/demo/chart-pie-demo.js'>myPieChart.data.datasets[0].data=[$normal,$high,$low]</script>");
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>updateBarGraph(data1,data2)</script>");

    #echo ("<script> var pieData= new Array($normal,$high,$low) </script>");
    echo ("<script> var pieData= new Array(71,20,10) </script>");



?>
