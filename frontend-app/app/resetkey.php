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
$apphash = $_SESSION["apphash"];
$idhash = $_SESSION["idhash"];
$newhash = genkey();

$graph_url= "http://".$ip.":5080/".$apphash."/rest/v2/broadcasts/".$idhash;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $graph_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$output = curl_exec($ch);
curl_close($ch);
$responseObj = json_decode($output);

if($responseObj) {
	$payload = json_encode( array( "streamId" => $newhash, "name" => $uname, "listenerHookURL" => "https://".$domain."/hook.php" ) );
	$graph_url= "http://".$ip.":5080/".$apphash."/rest/v2/broadcasts/create";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $graph_url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$output = curl_exec($ch);
	curl_close($ch);
	$responseObj = json_decode($output);
			
	if($responseObj->status == 'created') {
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
	}
}

echo "New Key: " . $newhash . "<br>";
echo "<br><a href=$baseurl/profile.php>Go back</a>";

?>
