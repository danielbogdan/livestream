<?php
include "common.php";

session_start();
try
{
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}

if (empty($_SESSION["username"])) {
	header("location: login.html");
}

$uname = $_SESSION["username"];

$newhash = genkey();


try
{
        $sth = $dbh->prepare("SELECT * FROM ".$usertablename." WHERE username = :username");
        $sth->execute( array( 'username' => $uname ) );
        $res = $sth->fetch();
} catch(PDOException $e)
{
        trigger_error($e->getMessage());
        http_response_code(401);
        die("Query error");
}


$oldhash = $res["idhash"];



$file = "/etc/nginx/conf.d/rtmp/".$uname.".conf";

file_put_contents($file, str_replace($oldhash, $newhash, file_get_contents($file)));


try
{
	$sth = $dbh->prepare("UPDATE $usertablename SET idhash=:idhash WHERE username=:username");
	$sth->execute(array('username' => $uname, 'idhash' => $newhash));
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}


//shell_exec ("sh /var/www/html/test/nginx.service.sh");

echo "Server URL: " . $streamurl . $newhash . "<br>";
echo "<br><a href=$baseurl/profile.php>Go back</a>";
?>
