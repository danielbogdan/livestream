<?php
date_default_timezone_set("Europe/Bucharest");
include "common.php";
$pattern = "/\\?(.*$)/i";

$conn = mysqli_connect($host, $username, $password, $dbname);
$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE idhash="'.$_GET['key'].'" LIMIT 1');
$row = mysqli_fetch_assoc($result);

$url_components = parse_url($_GET['tcurl']);
parse_str($url_components['query'], $params);

if ($row['id'] > 0 && $row['appname'] == $_GET['name'] && $row['apphash'] == $params['key'] && $_GET['call'] == 'publish'){
		
	$command = "php /var/livestream/auth/publish_curl.php ".$row['appname']." >/dev/null 2>&1 & echo $!";
	$res = exec($command, $output);
	
	$result = mysqli_query($conn, 'INSERT INTO `streams` (appname) values ("'.$row['appname'].'")');
	$last_id = mysqli_insert_id($conn);
	$result = mysqli_query($conn, 'UPDATE accounts SET is_streaming="1", stream_id="'.$last_id.'", server="'.$_SERVER['REMOTE_ADDR'].'" WHERE appname="'.$row['appname'].'"');	
	$result = mysqli_query($conn, 'DELETE FROM viewers WHERE stream_id IN (SELECT id from streams WHERE appname="'.$row['appname'].'" AND id < "'.$last_id.'" )');	
	
	http_response_code(200);
} else {
    http_response_code(405);
}
?>
