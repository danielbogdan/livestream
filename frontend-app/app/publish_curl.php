<?php
date_default_timezone_set("Europe/Bucharest");
include "common.php";
$pattern = "/\\?(.*$)/i";

$conn = mysqli_connect($host, $username, $password, $dbname);
$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$argv[1].'" LIMIT 1');
$row = mysqli_fetch_assoc($result);

sleep(1);
		
	$post = [
		'action' => 'publish',
		'name' => $row['appname'],
	];
	
	if($row['fb_auto_start']) {
		if($row['fb_api'] && $row['fb_page_token']) {
			$title = "Transmisiune%20Live";
			$descr = "Transmisiune%20Live";
			
			if($row['fb_title']) {
				$data = date("d-m-Y");
				$ora = date("H:i");
				$title = $row['fb_title'];
				$title = str_replace("%DATE%", $data, $title);
				$title = str_replace("%TIME%", $ora, $title);
				$title = str_replace("%PAGE%", $row['fb_page_linked'], $title);
				$title = rawurlencode($title);
			}
			if($row['fb_descr']) {
				$descr = rawurlencode($row['fb_descr']);
			}
					
			$graph_url= "https://graph.facebook.com/".$row['fb_page_id']."/live_videos?status=LIVE_NOW&title=".$title."&description=".$descr."&access_token=".$row['fb_page_token'];
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $graph_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$output = curl_exec($ch);
			curl_close($ch);
			$fbObj = json_decode($output);
			$post['fb_url'] = $fbObj->stream_url;
			file_put_contents("/var/livestream/auth/logs/fb/".$row['appname'].time().".txt", print_r($output, true));
		}
		if($row['fb_api'] == 0 && $row['fb_manual_key']) {
			$post['fb_url'] = "rtmps://live-api-s.facebook.com:443/rtmp/".$row['fb_manual_key'];
		}
	}
	
	if($row['yt_key'] && $row['yt_auto_start']) $post['yt_key'] = $row['yt_key'];
	if($row['record']) $post['record'] = 1;
	if($row['hls_auto_start']) $post['hls'] = 1;

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
	
	file_put_contents("/var/livestream/auth/logs/stream/".$row['appname'].time().".txt", print_r($output, true));
	
	if($responseObj->hls_pid) $result = mysqli_query($conn, 'UPDATE accounts SET hls_pid="'.$responseObj->hls_pid.'"  WHERE appname="'.$row['appname'].'"');
	if($responseObj->fb_pid) $result = mysqli_query($conn, 'UPDATE accounts SET fb_pid="'.$responseObj->fb_pid.'", fb_stream_id="'.$fbObj->id.'" WHERE appname="'.$row['appname'].'"');
	if($responseObj->yt_pid) $result = mysqli_query($conn, 'UPDATE accounts SET yt_pid="'.$responseObj->yt_pid.'"  WHERE appname="'.$row['appname'].'"');
	if($responseObj->ig_pid) $result = mysqli_query($conn, 'UPDATE accounts SET ig_pid="'.$responseObj->ig_pid.'"  WHERE appname="'.$row['appname'].'"');
	if($responseObj->rec_pid) $result = mysqli_query($conn, 'UPDATE accounts SET rec_pid="'.$responseObj->rec_pid.'" WHERE appname="'.$row['appname'].'"');
	
	
?>
