<?php
include "common.php";

$login_username=isset($_POST["username"])?(string)$_POST["username"]:'';
$login_password=isset($_POST["pass"])?(string)$_POST["pass"]:'';

if ( empty($login_username) || empty($login_password) )
{
	echo "Empty required field";
	echo "<br><a href=$baseurl/index.html>Go back</a>";
	die();
}
$login_password = hash('sha256', $login_password);


$conn = mysqli_connect($host, $username, $password, $dbname);
if ($conn) {
	$result = mysqli_query($conn, 'SELECT accounts.*, users.* FROM accounts 
									LEFT JOIN users on accounts.id = users.account_id
									WHERE users.user_email="'.$login_username.'" AND users.password="'.$login_password.'" LIMIT 1');
	$res = mysqli_fetch_assoc($result);
}

if(!empty($res) )
{
	session_start();
	$_SESSION["user_id"] = $res["user_id"];
	$_SESSION["account_id"] = $res["id"];
	$_SESSION["account_name"] = $res["name"];
	$_SESSION["user_name"] = $res["full_name"];
	$_SESSION["appname"] = $res["appname"];
	$_SESSION["apphash"] = $res["apphash"];
	$_SESSION["idhash"] = $res["idhash"];
	$_SESSION["a_lvl"] = $res["a_lvl"];
	$_SESSION["section"] = 'dashboard';
	
	if($_SESSION["a_lvl"] == 2) {
		header("location: https://".$_SERVER['HTTP_HOST']."/admin");
	} else {
		header("location: https://".$_SERVER['HTTP_HOST']);
	}
}
else
{
	echo "Invalid username/password combination";
	echo "<br><a href=$baseurl/>Go back</a>";
	die();
}
?>
