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

    #loading{
	    background-color : white;
        height : 50vh;
        width : 50vw;
        z-index : 11;
        position : absolute;
        top:50%;
        left:10%;
        display : block;		
	}
	#mask {
		background-color : black;
		opacity : 0.5;
		display : block;
		z-index : 10;
		position : absolute;
		height : 100vh;
		width : 100vw;	
		top : 0;
		left : 0;
    }	
    </style>
    <?php 
		$url = $_POST['url'];
        $option=$_POST['option'];
        echo $url;
        include "handle.php";
    ?>
    <script>
        var sum = 0;
        var total = 0;
        var url = "<?=$url?>";
        //$('#mask').css('display','block');
        //console.log($('#mask').text());
        if(url){
            //$('#loading').css('display','block');
            //$('#mask').css('display','block');
            //console.log($('#mask').text());
            $.ajax({
                type:"POST"
               ,url: "handle.php"
               ,data: { url: "<?=$url?>", option:"<?=$option?>", mode:""}
               ,beforeSend:function(){
                    //$('.wrap-loading').removeClass('display-none');
                }
               ,success:function(res){
                    console.log(res);
                    sum = res*2;
                    total = res;
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
                                countNum +=1;
                                if(countNum==sum+1){//index==total && j==1
                                    console.log("마지막 실행")
                                    $.ajax({
                                        type:"POST"
                                        ,url: "handle.php"
                                        ,data: {mode: " -m result"}
                                        ,success:function(res){
                                            var result = JSON.parse(res);
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
    </script>


</head>

<body id="page-top">
    <div id="mask">a</div>
                <div class="wrap-loading" id="loading">
                    <div class="progress" style="height:20px">
                            <div id="progressRatio" class="progress-bar progress-bar-striped progress-bar-animated" style="width:40%; height:20px">40%</div>
                        </div>
                    <div class="col-xl-8 col-md-6 mb-4">
                        
                    </div>
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
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $check_page_num; ?></div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $vul_page_num; ?></div>
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
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $page_ratio."%"; ?></div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?=$page_ratio?>%" aria-valuenow="<?=$page_ratio?>" aria-valuemin="0"
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $vul_num?></div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $high_vul_num?></div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $low_vul_num?></div>
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
                        <!-- <div class="col-xl-8 col-lg-7">
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
                                    <h6 class="m-0 font-weight-bold text-light">Bar Chart</h6>
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
                                    <h6 class="m-0 font-weight-bold text-light">Revenue Sources</h6>
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
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
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
                                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Server Migration <span
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Sales Tracking <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Customer Database <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Payout Details <span
                                            class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account Setup <span
                                            class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
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
                        <h6 class="m-0 font-weight-bold text-light">DataTables Example</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>FORM</th>
                                        <th>QUERY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--<tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>$320,800</td>
                                    </tr>
                                    <tr>
                                        <td>Garrett Winters</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>63</td>
                                        <td>2011/07/25</td>
                                        <td>$170,750</td>
                                    </tr>-->
                                    <?php
                                    for($i=0; $i<count($tableData);$i++){
                                        /*$url = $tableData[$i]['url'];
                                        $form = $tableData[$i]['form'];
                                        $query = $tableData[$i]['query'];*/
                                        $url = $tableData[$i][0];
                                        $form = $tableData[$i][1];
                                        $query = $tableData[$i][2];
                                    
                                    ?>
                                    <tr>
                                        <td><?=$url?></td>
                                        <td><?=$form?></td>
                                        <td><?=$query?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
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
