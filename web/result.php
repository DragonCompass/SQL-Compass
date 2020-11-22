<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>취약점 분석 결과</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    type="text/javascript"></script>
    <style type="text/css" >
        .wrap-loading{ /*화면 전체를 어둡게 합니다.*/
            position: fixed;
            left:0;
            right:0;
            top:0;
            bottom:0;
            background: rgba(0,0,0,0.2); /*not in ie */
            filter: progid:DXImageTransform.Microsoft.Gradient(startColorstr='#20000000',endColorstr='#20000000');    /* ie */
        }
        .wrap-loading div{ /*로딩 이미지*/
            position: fixed;
            top:50%;
            left:50%;
            margin-left: -21px;
            margin-top: -21px;
        }
        .display-none{ /*감추기*/
            display:none;
        }
    #progressBar{
        height 15%;
        width : 70%;
        z-index : 11;
        position : absolute;
        top:20%;
        left:15%;
        margin: 0 auto;
        display : block;		
	}
	#mask {
		background-color : black;
		opacity : 0.5;
		display : block;
		z-index : 10;
		position : absolute;
		height : 100%;
		width : 100%;	
		top : 0;
		left : 0;
    }	
    </style>
    <?php 
		$url = $_POST["search"];
        $option=$_POST['option'];
        //echo $url;
        //include "handle.php";
    ?>
    <script>
        var sum = 0;
        var total = 0;
        var url = "<?=$url?>";
        var result;
        var pieData=[];
        var barLabel=[];
        var barNum=[];
        var barVul=[];
        var max=0;
        //$('#mask').css('display','block');
        //console.log($('#mask').text());
        if(url){
            //$('#loading').css('display','block');
            $('#mask').css('display','block');
            console.log($('#mask').text());
            $.ajax({
                type:"POST"
               ,url: "handle.php"
               ,data: { url: "<?=$url?>", option:"<?=$option?>"}//, mode:""
               ,beforeSend:function(){
                    //$('.wrap-loading').removeClass('display-none');
                    /*$('#loading').css('display','block');
                    $('#mask').css('display','block');
                    console.log($('#mask').css('display'));*/
                }
               ,success:function(res){
                    console.log(res);
                    sum = res*2;
                    total = res;
                    //result = JSON.parse(res);
                    /*form = res-1;
                    sum = res*2;
                    console.log("c0"+sum);*/
               }
               ,error:function(e){
                   //alert(e);
               }   
            }).done(
               function(){
                    vulSearch();
                    //data_set();
               }
           );
        }
        console.log("c1"+sum);
        function vulSearch(){
            var pl = ["hreflist"];
            var pt = ["voper","sqli"];
            var percent="";
            var modei="";
            check=0;
            var countNum=1;
            for(var i=0; i<1; i++){
                for(var j=0; j<2; j++){
                    for(var index=0; index<total; index++){
                        console.log("index"+index);
                        $.ajax({
                            type:"POST"
                            ,url: "handle.php"
                            ,data: {mode: " -pl "+pl[i]+" -pt "+pt[j]+" -pi "+index}
                            ,success:function(res){
                                //console.log(res);
                                check = check+Number(res[0]);
                                console.log(check);
                                console.log("count:"+countNum);
                                percent=Math.floor((check)/sum*100);
                                $('#progressRatio').width(percent+"%");
                                $('#progressRatio').html(percent+"%");
                            }
                            ,complete:function(){
                                // $('.wrap-loading').addClass('display-none');
                            }
                            ,error:function(e){
                            //alert(e);   
                            }
                        }).done(
                            function(){
                                countNum +=1;
                                if(countNum==sum+1){//index==total && j==1
                                    console.log("마지막 실행")
                                    $.ajax({
                                        type:"POST"
                                        ,url: "handle.php"
                                        ,data: {mode: " -m result"}
                                        ,success:function(res){
                                            result = JSON.parse(res);
                                            console.log(result);
                                        }
                                        ,complete:function(){
                                            // $('.wrap-loading').addClass('display-none');
                                        }
                                        ,error:function(e){
                                        //alert(e);
                                        }   
                                    }).done(
                                        //continue;
                                        function(){
                                            data_set();
                                        }
                                    );
                                }
                            }
                        );
                    }
                }
                console.log("for마지막")
            }
            console.log("끝");
        }
        /*function vulSearch(h,f){
            var percent="";
            var check=0;
            var repeat="";
            console.log("c2"+sum);
            $.ajax({
                type:"POST"
               ,url: "handle.php"
               ,data: {url: 0,formNum: form, href:h, flist:f, repeatCheck: repeat}
               ,success:function(res){
                    console.log(h);
                    var data= res.split("^");
                    if(data[1]=="h1"){
                        console.log(data[1]);
                        repeat="h1"
                        h=-1;
                    }else if(data[1]=="h2"){
                        repeat="h2"
                        console.log(h);
                    }
                    check = check+data[0];
                    percent=Math.floor((check)/sum*100);
                    $('#progressRatio').width(percent);
                    $('#progressRatio').html(percent+"%");
               }
               ,complete:function(){
                   // $('.wrap-loading').addClass('display-none');
                }
               ,error:function(e){
                   //alert(e);
               }   
            }).done(
               function(){
                console.log("c3"+sum);
                   if(percent=="100"){
                        $('.wrap-loading').addClass('display-none');
                   }else{
                       if(h<form){
                           h +=1;
                           vulSearch(h,f);
                       }else if(h==form && repeat=="h2"){
                            vulSearch(h,f);
                       }
                       
                   }
               }
           );
        }*/
        function data_set(){
            $("#apagelen").text(result.apagelen);
            $("#vpagelen").text(result.vpagelen);
            $("#page_ratio").text(Math.round(result.vpagelen/result.apagelen*100,1)+"%");
            $(function() {
                console.log($("#ratioBar").css('width'));
                
            });
            $("#ratioBar").css('width', Math.round(result.vpagelen/result.apagelen*100,1));    
            $("#ratioBar").css('aria-valuenow', Math.round(result.vpagelen/result.apagelen*100,1));
            $("#vparlen").text(result.vparlen);
            $("#high").text(result.high);
            $("#low").text(result.low);

            var normal = Math.round(result.sparlen/result.aparlen*100,0);
            var high = Math.round(result.high/result.aparlen*100,0);
            var low = Math.round(result.low/result.aparlen*100,0);
            console.log("normal:"+normal+"high:"+high+"low:"+low);
            pieData= [normal,high,low];
            updatePieGraph();

            var barData=Array();
            for(var i=0; i<result.vpage.length;i++){
                var bar = JSON.parse(result.vpage[i]);
                barData.push({'url':bar.url, 'safe':bar.safe, 'high':bar.high, 'low':bar.low});
                //barData.push([table.url,table.method, table.fname,table.war, table.query]);
            }
            console.log(barData);
            for(var i=0; i<barData.length; i++){
                //if(barData[i].safe.length==0){
                    barLabel.push(barData[i].url);
                    barNum.push(barData[i].safe.length+barData[i].high.length+barData[i].low.length);
                    barVul.push(barData[i].high.length+barData[i].low.length);
                    if(max<barData[i].safe.length+barData[i].high.length+barData[i].low.length)
                        max=barData[i].safe.length+barData[i].high.length+barData[i].low.length;
                //}
            }
            updateBarGraph();

            var tableData=Array();
            for(var i=0; i<result.vlist.length;i++){
                var table = JSON.parse(result.vlist[i]);
                //console.log(table);
                //tableData.push({'url':table.url, 'method':table.method, 'fname':table.fname, 'war':table.war, 'query':table.query});
                tableData.push([table.url,table.method, table.fname,table.war, table.query]);
            }
            console.log(tableData);
            /*var tag=[];
            for(var i=0; i<result.vlist.length;i++){
                var url = ;
                var method = $tableData[$i][1];
                var fname = $tableData[$i][2];
                var war = $tableData[$i][3];
                var query = $tableData[$i][4];
                console.log(tableData[i][0]);
                tag.push("<tr>");
                tag.push("<td>"+tableData[i][0]+"</td>");
                tag.push("<td>"+tableData[i][1]+"</td>");
                tag.push("<td>"+tableData[i][2]+"</td>");
                tag.push("<td>"+tableData[i][3]+"</td>");
                tag.push("<td>"+tableData[i][4]+"</td>");
                tag.push("</tr>");
            }*/
            //console.log(tag);
            
            var table = $("#dataTable").DataTable();
            table.rows.add(tableData).draw();

            var queryData = Array();
            //위의 table에서 체크하면 될듯
            var table1 = JSON.parse(result.vlist[0]);
            var countCheck=0;
            queryData.push({'query': table1.query, 'count': 0});
            for(var i=1; i<result.vlist.length; i++)                             {
                var table = JSON.parse(result.vlist[i]);
                for(var j=0; j<queryData.length; j++){
                    if(queryData[j].query == table.query){
                        queryData[j].count +=1;
                        countCheck=1;
                        break;
                    }/*else{
                        queryData.push({'query': table.query, 'count': 0});
                    }*/
                }
                if(countCheck==0){
                    queryData.push({'query': table.query, 'count': 0});
                }else{
                    countCheck=0;
                }
            }
            /*var max =[0,0,0,0,0];
            var maxQuery = [];
            var maxCount = [];
            for(var i=0; i<queryData.length; i++){
                for(var j=0; j<max.length; j++){
                    if(max[j]<queryData[i].count){
                        maxQuery[j] = queryData[i].query;
                        
                        break;
                    }
                }
            }

            for(var i=0; i<queryData.length; i++){
                for(var j=0; j<maxQuery.length; j++){
                    if(maxQuery[j]==queryData[i].query){
                        maxCount.push(queryData[i].count);
                        break;
                    }
                }
            }
            console.log(maxQuery);*/

            console.log(queryData);
            var classData = ['bg-danger','bg-warning','','bg-info','bg-success'];
            for(var i=0; i<5; i++){
                /*$("#ratio").append("<h4 class='small font-weight-bold'>");
                $("#ratio").append(queryData[i].query+"<span class='float-right'>20%</span></h4>");
                $("#ratio").append("<div class='progress mb-4'> <div class='progress-bar bg-danger' role='progressbar' style='width:");
                $("#ratio").append(queryData[i].count+"% aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'></div></div>");*/
                $("#ratio").append("<h4 class='small font-weight-bold'>"+queryData[i].query+"<span class='float-right'>"+Math.round(queryData[i].count/result.aparlen*100,0)+"% </span></h4><div class='progress mb-4'> <div class='progress-bar "+classData[i]+"' role='progressbar' style='width:"+Math.round(queryData[i].count/result.aparlen*100,0)+"%' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'></div></div>");
                //$("#ratio").append("<h4 class='small font-weight-bold'>"+maxQuery[i]+"<span class='float-right'>"+Math.round(maxCount[i]/result.aparlen*100,0)+"% </span></h4><div class='progress mb-4'> <div class='progress-bar "+classData[i]+"' role='progressbar' style='width:"+Math.round(maxCount[i]/result.aparlen*100,0)+"%' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'></div></div>");
            }

            $('#progressBar').css('display','none');
            $('#mask').css('display','none');
        }
    </script>


</head>

<body id="page-top">
    <div id="mask">a</div>

    <div id='progressBar' class="progress" style="height:30px">
        <div id="progressRatio" class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar"
        style="width:100%; height:30px" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    
                       <!-- <div class="col-xl-8 col-md-6 mb-4">
                            <div class="progress" style="height:20px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:40%; height:20px">40%</div>
                            </div>
                        </div>-->
                    
                    
                       <a href="index.php"><img src="img/logoSample.png" width="40" height="40"></a>
                        
                        <form class="d-none d-sm-inline-block form-inline col-xl-11 col-md-11 mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="검사한 페이지 주소 >> <?=$url?>"
                                    aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    
                    
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div> -->
                    <!--<div class="row">
                        <div class="col-xl-8 col-md-6 mb-4" style="">
                            <div class="progress" style="height:30px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:40%; height:30px">40%</div>
                            </div>
                        </div>
                    </div>-->
                    <!-- Content Row -->
                    <div class="row">
                        <!--<div class="col-xl-12 col-md-12 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                        aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <!-- 검사한 페이지 수-->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                검사한 페이지 수</div>
                                            <div id="apagelen" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 취약점이 발견된 페이지 수 -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                취약점이 발견된 페이지 수</div>
                                            <div id="vpagelen" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--취약점 페이지 비율 -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">취약점 페이지 비율
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div id="page_ratio" class="h5 mb-0 mr-3 font-weight-bold text-gray-800"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div id="ratioBar" class="progress-bar bg-info" role="progressbar"
                                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 총 취약점 개수 -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                총 취약점 개수</div>
                                            <div id="vparlen" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 고위험 취약점 개수 -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                고위험 취약점 개수</div>
                                            <div id="high" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 저위험 취약점 개수 -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                저위험 취약점 개수</div>
                                            <div id="low" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <!--<div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                Card Header - Dropdown
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                Card Body
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">페이지별 파라미터 현황</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                    
                                        <canvas id="myBarChart" width="1093" height="400" class="chartjs-render-monitor" style="display: block; height: 320px; width: 875px;"></canvas>
                                </div>
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">취약 파라미터 비율</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle" style='color: #e74a3b'></i> High
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle" style='color: #f6c23e'></i> Low
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle" style='color: #1cc88a'></i> normal
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">판단된 Query 종류 비율(상위 5개)</h6>
                                </div>
                                <div class="card-body" id="ratio">
                                    
                                </div>
                            </div>

                            <!-- Color System -->
                            <!-- <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            Primary
                                            <div class="text-white-50 small">#4e73df</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            Success
                                            <div class="text-white-50 small">#1cc88a</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            Info
                                            <div class="text-white-50 small">#36b9cc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            Warning
                                            <div class="text-white-50 small">#f6c23e</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-danger text-white shadow">
                                        <div class="card-body">
                                            Danger
                                            <div class="text-white-50 small">#e74a3b</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-secondary text-white shadow">
                                        <div class="card-body">
                                            Secondary
                                            <div class="text-white-50 small">#858796</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-light text-black shadow">
                                        <div class="card-body">
                                            Light
                                            <div class="text-black-50 small">#f8f9fc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-dark text-white shadow">
                                        <div class="card-body">
                                            Dark
                                            <div class="text-white-50 small">#5a5c69</div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                    </div>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4"></div>
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">SQL Injection 결과</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>취약점 페이지</th>
                                        <th>METHOD</th>
                                        <th>취약점 파라미터</th>
                                        <th>Warning</th>
                                        <th>QUERY</th>
                                    </tr>
                                </thead>
                                <tbody id="table">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <!--<script src="js/demo/chart-area-demo.js"></script>-->
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="js/demo/chart-bar-demo.js"></script>
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
