<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Sora:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet"><!-- 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fonts/pixelins-style.css">

    <script src="assets/js/script.js"></script>
    <title>Login | LIVE Maghost!</title>
    <link rel="stylesheet" href="https://use.typekit.net/cmt1jlz.css">
  </head>
  <body class="login-page">
  	<div class="wrapper">

  		<div class="container">
  			<div class="login_container">
  				<div class="logo">
  					<a href="#"><img src="assets/images/logo-app.png" alt=""></a>
  				</div>
  				<div class="login_box">

  						<div id="slide"  class="login_slide" style="margin-left: 0px;">
		<div class="top login_top"  style="margin-left: 100%;">
			<div class="left">
				<div class="content">
						<div class="login_form">
  						<div class="login_title-txt">
  							<p class="login_title">Welcome back, media lover</p>
  							<p class="login_description">Please enter your details bellow</p>
  						</div>
	  					<form action="login_action.php" method="post" class="login100-form validate-form">
						  	<div class="form-group">
						    	<input type="text" class="form-control" id="InputEmail1" aria-describedby="emailHelp" name="username" placeholder="User">
						  	</div>
						  	<div class="form-group">
						  	  <input type="password" class="form-control" id="InputPassword1" placeholder="Password" name="pass">
						  	</div>
						  	<div class="login_settings-section">
							  <!-- <div class="form-check">
							    <input type="checkbox" class="form-check-input" id="exampleCheck1">
							    <label class="form-check-label" for="exampleCheck1">Remember me</label>
							  </div> -->
							  	<label class="container-checkbox">
								  	<input type="checkbox">
								  	<span class="checkmark"></span>
								  	Remember me
									</label>
							  	<a href="#" class="forgot_password">Forgot password?</a>
						  	</div>
						  	<button type="submit" class="btn btn-primary login_btn">Log In</button>
							</form>
							<div class="singup_box">
								<p>Don't have an account? <span id="LeftToRight">Create one now</span></p>
							</div>
  					</div>

					
				</div>
			</div>

			<div class="right">
				<div class="content">
					
						<div class="login_form">
  						<div class="login_title-txt">
  							<p class="login_title">New to media? Enroll now</p>
  							<p class="login_description">Please enter your details bellow</p>
  						</div>
	  					<form id="regForm" action="register.php" method="post" class="login100-form validate-form" autocomplete="off">
						  	<div class="form-group">
						    	<input type="text" class="form-control" id="InputUser" aria-describedby="userHelp" name="username" placeholder="User" autocomplete="off">
						  	</div>
							<div class="form-group">
						    	<input type="text" class="form-control" id="InputEmail1" aria-describedby="emailHelp" name="email" placeholder="Email" autocomplete="off">
						  	</div>
						  	<div class="form-group">
						  	  <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password">
						  	</div>
						  	<div class="form-group">
						  	  <input type="password" class="form-control" id="confirmpass" name="confirmpass" placeholder="Confirm password" autocomplete="new-password">
						  	</div>
						  	<div class="login_settings-section">
							  <!-- <div class="form-check">
							    <input type="checkbox" class="form-check-input" id="exampleCheck1">
							    <label class="form-check-label" for="exampleCheck1">Remember me</label>
							  </div> -->
						  	</div>
						  	<button type="button" onclick="javascript:docheck();" class="btn btn-primary login_btn">Create</button>
						</form>
							<div class="singup_box">
								<p><span id="RightToLeft">Back to Login</span></p>
							</div>
  					</div>

				</div>
			</div>
		</div>
	</div>




  				
  					<div class="login_video-container">
  						<div class="login_video-box">
  							<video src="assets/video/live__videobg.webm" autoplay muted loop></video>
  						</div>
  					</div>	
  				</div>
  			</div>
  		</div>

	</div>       

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
$(document).ready(function () {
    $("#RightToLeft").on("click", function () {
        $("#slide").animate({
            marginLeft: "0",
        });
        $("#slide").addClass("login_slide");
        $("#slide").removeClass("signup_slide");
        $(".top").animate({
            marginLeft: "100%",
        });
        $(".top").addClass("login_top");
        $(".top").removeClass("signup_top");
    });
    $("#LeftToRight").on("click", function () {
        $("#slide").animate({
            marginLeft: "50%",
        });        
        $("#slide").addClass("signup_slide");
        $("#slide").removeClass("login_slide");
        $(".top").animate({
            marginLeft: "0",
        });
        $(".top").addClass("signup_top");
        $(".top").removeClass("login_top");
    });
});

function docheck(){
	
	if($("#password").val() == "") {
		alert("Password empty");
		return;
	}
	
	if($("#password").val() == $("#confirmpass").val()) {
		$("#regForm").submit();
	} else {
		alert("Passwords do not match");
	}
}
</script>
   
  </body>
</html>