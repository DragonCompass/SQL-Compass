<?php
    #exec("cd /var/www/html/KSJ_SQLI_Project/python/dist && ./tool", $str);
    if(isset($_POST["mode"])){
        $mode = $_POST["mode"];
        exec("tool.exe ".$mode,$str);
        if($mode==" -m result"){
            echo implode($str);
        }else{
            echo $str[0];
        }
        
        
        #if($mode==" -m result"){
        #$result = json_decode($str[0],true);
        /*$check_page_num=$result['apagelen'];
        $vul_page_num=$result['vpagelen'];
        $page_ratio=round($vul_page_num/$check_page_num*100,1);
        $vul_num=$result['vparlen'];
        $high_vul_num=$result['high'];
        $low_vul_num=$result['low'];
        $form_num =$result['aparlen'];

        #동그라미 도표 데이터
        #$normal = round($check_page_num-$vul_page_num/$check_page_num*100,0);
        $normal = $result['sparlen'];
        $high = round($high_vul_num/$form_num*100,0);
        $low = round($low_vul_num/$form_num*100,0);

        $barlist = $result['vpage'];
        $barData=Array();
        foreach ($result['vpage'] as $tmp1){
            $barlist = json_decode($tmp1,true);
            array_push($barData,
                array(
                    $barlist['url'],
                    $barlist['fname'],
                    $barlist['query'],
                )
            );
        }

        $vlist= $result['vlist'];
        $tableData=Array();
        foreach ($result['vlist'] as $tmp2){
            $vlist = json_decode($tmp2,true);
            array_push($tableData,
                array(
                    $vlist['url'],
                    $vlist['fname'],
                    $vlist['query'],
                )
            );
        }}*/
    }
    else{
        exec("tool.exe",$str);
        echo $str[0];
    }
    
    /*if(isset($_POST["url"]))
        $url = $_POST["url"];
    if(isset($_POST["option"]))
        $option = $_POST["option"];

    $repeatCheck ="";

    if(isset($_POST["href"]))
        $href = $_POST["href"];
    if(isset($_POST["flist"]))
        $flist= $_POST["flist"];
    if(isset($_POST["formNum"]))
        $formNum = $_POST["formNum"];
    if(isset($_POST["repeatCheck"]))
        $repeatCheck = $_POST["repeatCheck"];

    if($url){
        #exec("cd C:\xampp\htdocs\project(kshild)\python && tool.exe",$str);
        exec("tool.exe",$str);
        echo $str[0];
    }elseif ($href!=-1 && $flist==-1) {
        if($repeatCheck=="h1" && $href !=$formNum){
            exec("tool.exe -pl hreflist -pt sqli -pi $href",$str);
            echo $str[0];
        }else if($repeatCheck=="h1" && $href==formNum){
            $repeatCheck="h2";
            echo $str[0]."^".$repeatCheck;
        }
        exec("tool.exe -pl hreflist -pt voper -pi $href",$str);
//        $data = $str[0]."^".$repeatCheck;
        if($repeatCheck==""&&$href==$formNum){
            $repeatCheck="h1";
            echo $str[0]."^".$repeatCheck;    
        }else{
            echo $str[0];
        }
    }elseif ($flist!=-1){
        if($repeatCheck=="f1"){
            exec("tool.exe -pl flist -pt sqli -pi $href",$str);
            echo $str[0]."^".$repeatCheck;
        }
        exec("tool.exe -pl flist -pt voper -pi $href",$str);
        if($repeatCheck==""&&$flist==$formNum-1){
            $repeatCheck="f1";
            echo $str[0]."^".$repeatCheck;    
        }else{
            echo $str[0];
        }
    }else if($href==$formNum && $repeatCheck=="h2"){
        exec("tool.exe -m result",$str);
        echo $str[0];
    }*/
/*    
    $result = json_decode($str[0],true);
    $check_page_num=$result['alistlen'];
    $vul_page_num=$result['vpagelen'];
    $page_ratio=round($vul_page_num/$check_page_num*100,1);
    $vul_num=$result['vlistlen'];
    $high_vul_num=$result['high'];
    $low_vul_num=$result['low'];

    #동그라미 도표 데이터
    #$normal = round($check_page_num-$vul_page_num/$check_page_num*100,0);
    $normal = round($vul_num/$check_page_num*100,0);
    $high = round($high_vul_num/$check_page_num*100,0);
    $low = round($low_vul_num/$check_page_num*100,0);


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
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>updateBarGraph(1000,2000)</script>"
);
    echo ("<script> var data1= new Array($datav,'5000','4000','3000','2000','1000') </script>");
    echo ("<script> var data2= new Array($datav,'2000','3000','4000','5000','6000') </script>");
    #echo ("<script> var data2= $data2 </script>");


    
#동그라미 도표 변경
    #echo ("<script type='text/javascript' src='js/demo/chart-pie-demo.js'>myPieChart.data.datasets[0].data=[$normal,$high,$low]</script>");
    #echo ("<script type='text/javascript' src='js/demo/chart-bar-demo.js'>updateBarGraph(data1,data2)</script>");

    #echo ("<script> var pieData= new Array($normal,$high,$low) </script>");
    echo ("<script> var pieData= new Array(71,20,10) </script>");
    */
?>
