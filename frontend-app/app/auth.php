<?php
date_default_timezone_set("Europe/Bucharest");
include "common.php";
$pattern = "/\\?(.*$)/i";

file_put_contents('rec_pid.txt', print_r($_GET, true));
exit;

try
{
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}

	$username = $argv[1];
try
{
        $sth = $dbh->prepare("SELECT * FROM ".$usertablename." WHERE username = :username");
        $sth->execute( array( 'username' => $username ) );
        $res = $sth->fetch();
} catch(PDOException $e)
{
        trigger_error($e->getMessage());
        http_response_code(401);
        die("Query error");
}

$row = $res;
echo $row['idhash'];
?>
