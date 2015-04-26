<?php
session_start();
$tempPHPSELF = explode("\\",dirname(__FILE__));
$_SESSION['BASE_PATH'] = implode("/",$tempPHPSELF);
include_once("database.cls.php");
$objUser = new Database;
if(isset ($_POST['submit'])) {
	if(isset($_POST['user_name']) && trim($_POST['user_name']) != '' && trim($_POST['password']) != '') {
		$objUser->checkUser(mysql_real_escape_string($_POST['user_name']),mysql_real_escape_string($_POST['password']));
		unset($objUser);
	} else {
		header('location: index.php?error=1&msg=2');
	}
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Msys Technologies >> Portal Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	<div class="panel-body">
		<div class="row">	
    		<div class="col-md-4 col-md-offset-4">
    			<div><img src="images/logo.png" class="img-responsive center-block"></div>
				<h2 align="center">Msys Technologies</h2>
    		</div>
  		</div>
	</div>
    <form name="frmLogin" method="post" action="">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-4 col-md-offset-4">
	                <div class="login-panel panel panel-default">
	                    <div class="panel-heading">
	                        <h3 class="panel-title">Please Sign In</h3>
	                    </div>
	                    <div class="panel-body">
	                        <form role="form">
	                            <fieldset>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="User Name" id="user_name" name="user_name" type="text" autofocus>
	                                </div>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" value="">
	                                </div>
	                                <div class="checkbox">
	                                    <label>
	                                        <input name="remember" type="checkbox" id="remember" value="Remember Me">Remember Me
	                                    </label>
	                                </div>
	                                <!-- Change this to a button or input when using this as a form -->
	                                <input class="btn btn-lg btn-success btn-block" type="submit" id="login" name="submit" value="Login" />
	                            </fieldset>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</form>
	<div class="panel-fotter">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<?php 
					if(isset($_GET['error']) && $_GET['error']=='1'){
						if($_GET['msg']=='1')
							$message = 'Please Verify your Password';
						if($_GET['msg']=='2')
							$message = 'Please Verify your Username or Password is Null !!!';
						echo '<div id="login_error" align="center" style="font-size:14px;color:red;font-style:italize;font-weight:bold;">'.$message.'</div>';
					}
					if(isset($_SESSION['username']) && $_SESSION['username'] != ''){
						// header("location:loading.php");
					}
				?>
			</div>
		</div>
	</div>
    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>
<script type="text/javascript">
	
	function setCookie(cname,cvalue,exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires=" + d.toGMTString();
	    document.cookie = cname+"="+cvalue+"; "+expires;
	}

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	function checkCookie() {
	    var user = getCookie("p_username");
	    var pass = getCookie("p_username");
	    if (user != "" || pass != "") {
	        $("#user_name").val(user);
	        $("#password").val(pass);
	    } else {
	       var us = $("#user_name").val();
	       var pass = $("#password").val();
	       setCookie("p_username", us, 30);
	       setCookie("p_password", pass, 30);
	    }
	}
/*
	$("#login").click(function(){
		if($('#remember').is(':checked')){
			checkCookie();
		}
	});

	$(document).ready(function(){
		 var user = getCookie("p_username");
	    if (user != "") {
	        $("#user_name").val(user);
	        $("#password").val(getCookie("p_password"));
	    }
	});
*/
</script>
</html>