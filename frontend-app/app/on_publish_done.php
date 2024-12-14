<?php
date_default_timezone_set("Europe/Bucharest");
include "common.php";
$pattern = "/\\?(.*$)/i";

$conn = mysqli_connect($host, $username, $password, $dbname);
$result = mysqli_query($conn, 'UPDATE accounts SET is_streaming="0", stream_id="0" WHERE idhash="'.$_GET['key'].'"');
$result = mysqli_query($conn, 'DELETE FROM hls_viewers WHERE appname="'.$_GET['name'].'"');
$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE idhash="'.$_GET['key'].'" LIMIT 1');
$row = mysqli_fetch_assoc($result);

$post = [
		'action' => 'publish_done',
		'name' => $row['appname'],
	];


if ($row['appname'] == $_GET['name'] && $_GET['call'] == 'publish_done' && $row['fb_stream_id'] && $row['fb_api']){
	
	$graph_url= "https://graph.facebook.com/".$row['fb_stream_id']."?end_live_video=true&access_token=".$row['fb_page_token'];
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $graph_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$output = curl_exec($ch);
	curl_close($ch);
}

$graph_url= "http://".$row['server']."/api.php";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $graph_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$output = curl_exec($ch);
curl_close($ch);
$responseObj = json_decode($output);
	
if($responseObj->status == "ok") {
	$result = mysqli_query($conn, 'UPDATE accounts SET fb_pid=0, fb_pid_status=0, yt_pid=0, yt_pid_status=0, ig_pid=0, ig_pid_status=0, fb_stream_id="", rec_pid=0, rec_pid_status=0, hls_pid=0, hls_pid_status=0, viewers="", retries="", server="" WHERE idhash="'.$_GET['key'].'"');
}

http_response_code(200);
?>
