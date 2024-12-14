<?php
include "common.php";
session_start();

//removed unneeded mysql database connection

if (empty($_SESSION["username"]))
{
	header("location: login.html");
	exit(); // exit or browser may see the rest of the page
}
?>
<html>
<head>
<title> <?php
echo "Profile ". $_SESSION["username"];
?> Live</title>
</head>
<body>
Hi, <?php echo $_SESSION["username"];?><br>
<br><a href="<?php echo $baseurl;?>/showhash.php">Show your stream RTMP URL</a><br>
<!-- <br><a href="<?php echo $baseurl;?>/resethash.php">Reset your stream RTMP URL</a><br> -->
<br><a href="<?php echo $baseurl;?>/resetkey.php">Reset your stream Key</a><br>

<br><a href="<?php echo $baseurl;?>/player.php">Show your Player</a><br>

<br><a href="<?php echo $baseurl;?>/resetpasswd.html">Reset Password</a><br>

<br><a href="<?php echo $baseurl;?>/social.php">Stream to Social</a><br>

<br>

<a href="<?php echo $baseurl;?>/logout.php">Logout</a><br>
</body>
</html>
