<?php
session_start();
if($_POST){
	include "common.php";
	$conn = mysqli_connect($host, $username, $password, $dbname);

	if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"])) {
		echo "Empty required field";
		echo "<br><a href=$baseurl/register.php>Go back</a>";
		die();
	}

	$username = $_POST["username"];
	$email = $_POST["email"];
	$password = hash('sha256', $_POST["password"]);	

	if (strlen($username) > 64) {
		echo "Username too long";
		echo "<br><a href=$baseurl/register.html>Go back</a>";
		die();
	}
	if (strlen($email) > 64) {
		echo "Email too long";
		echo "<br><a href=$baseurl/register.html>Go back</a>";
		die();
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "Invalid email address";
		echo "<br><a href=$baseurl/register.html>Go back</a>";
		die();
	}
	
	
	$result = mysqli_query($conn, 'SELECT appname FROM accounts WHERE appname="'.$username.'" LIMIT 1');
	$nameresult = mysqli_fetch_assoc($result);
	
	$result = mysqli_query($conn, 'SELECT user_email FROM users WHERE user_email="'.$email.'" LIMIT 1');
	$emailresult = mysqli_fetch_assoc($result);
	
	if ( !empty($nameresult['appname']) )
	{
		echo "Application name already registered.";
		echo "<br><a href=$baseurl/register.html>Go back</a>";
		die();
	}

	if ( !empty($emailresult['user_email']) )
	{
		echo "Email already registered";
		echo "<br><a href=$baseurl/register.html>Go back</a>";
		die();
	}

	$apphash = genkey();
	$idhash = genkey();

	$result = mysqli_query($conn, "INSERT INTO ".$usertablename." (name, email, appname, apphash, idhash) VALUES ('".$username."', '".$email."', '".strtolower($username)."', '".$apphash."', '".$idhash."')");
	$last_id = mysqli_insert_id($conn);
	if($last_id) {
		$result = mysqli_query($conn, "INSERT INTO users (account_id, user_email, password, full_name, a_lvl) VALUES ('".$last_id."', '".$email."', '".$password."', '".$username."', 1)");
		$user_id = mysqli_insert_id($conn);
		
		
		$_SESSION["user_id"] = $user_id;
		$_SESSION["account_id"] = $last_id;
		$_SESSION["account_name"] = $username;
		$_SESSION["user_name"] = $username;
		$_SESSION["appname"] = strtolower($username);
		$_SESSION["apphash"] = $apphash;
		$_SESSION["idhash"] = $idhash;
		$_SESSION["a_lvl"] = 1;
		$_SESSION["section"] = 'dashboard';
		header("location: https://".$_SERVER['HTTP_HOST']);
	} else {
		echo "Something went wrong! Contact us!";
		echo "<br><a href=$baseurl/register.html>Go back</a>";
		die();
	}
	
} else {
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Maghost Stream Live</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>
                
				<form action="#" method="post" class="login100-form validate-form">
					<span class="login100-form-title">
						Register
					</span>
					<div class="wrap-input100 validate-input" data-validate = "Valid username is required: bp***">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate = "Valid username is required: bp***">
						<input class="input100" type="text" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required!">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Register
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>
<?php } ?>