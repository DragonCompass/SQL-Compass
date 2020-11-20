<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Search Page</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet" />
<style>
	#loading{
	background-color : white;
	height : 50vh;
	width : 50vw;
	z-index : 3;
	position : absolute;
	top : 25vh;
	left : 25vw;
	display : none;		
	}
	#mask {
		background-color : black;
		opacity : 0.5;
		display : none;
		z-index : 2;
		position : absolute;
		height : 100vh;
		width : 100vw;	
		top : 0;
		left : 0;
}	
</style>
</head>
    <body>
        <div class="s003">
        <form id="inject" >
            <div class="inner-form">
                <div class="input-field first-wrap">
                    <div class="input-select">
                    <select data-trigger="" id='option' name="choices-single-defaul">
                        <option placeholder="">현재 페이지 검사</option>
                        <option>하위 페이지까지 검사</option>
                    </select>
                    </div>
                </div>
                <div class="input-field second-wrap">
                    <input id="search" type="text" placeholder="검사할 페이지 주소 입력..." />
                </div>
                <div class="input-field third-wrap">
                     <button class="btn-search" type="button">
                    <svg class="svg-inline--fa fa-search fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                    </svg>
                    </button>
                </div>
            </div>
        </form>
        </div>
	<div id="loading">
		<a>SQL injection 취약점 검사중입니다.</a>
	</div>
	<div id="mask">
	</div>
        <script src="js/extention/choices.js"></script>
	<script src="vendor/jquery/jquery.min.js"></script>

        <script>
	$('.btn-search').click(function(){
                var newform = $('<form></form>');
		newform.attr('action','result.php');
		newform.attr('method','post');

		$('#loading').css('display','block');
                $('#mask').css('display','block');
		uval = $('#search').val()
		opt = $('#option').val()
		if (opt == "현재 페이지 검사")
			opt = 0; // one page
		else 
			opt = 1; // all page 
		newform.append($('<input/>',{type:'text',name:'url',value:uval}));
		newform.append($('<input/>',{type:'text',name:'option',value:opt}));

		newform.appendTo('body');
		newform.submit();
	});

        const choices = new Choices('[data-trigger]',
        {
            searchEnabled: false,
            itemSelectText: '',
        });

        </script>
    </body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
