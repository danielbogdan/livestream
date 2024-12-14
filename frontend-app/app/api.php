<?php

include "common.php";

if($_GET['action'] == 'xml' && $_GET['u']){
	
	$res = array();
	
	$conn = mysqli_connect($host, $username, $password, $dbname);
	if ($conn) {
		$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$_GET['u'].'" LIMIT 1');
		$row = mysqli_fetch_assoc($result);
		$res['fb_pid'] = $row['fb_pid_status'];
		$res['yt_pid'] = $row['yt_pid_status'];
		$res['ig_pid'] = $row['ig_pid_status'];
		$res['hls_pid'] = $row['hls_pid_status'];
		$result = mysqli_query($conn, 'UPDATE hls_viewers SET updated="'.date("y-m-d H:i:s").'" WHERE uvid="'.$_GET['q'].'"');
	}
	
	$server = "";
	if($row['server'] == '172.26.0.2') $server = "rtmp";

	
	
	/*
	$xml = simplexml_load_file("https://".$server."/stats");
	$streams = $xml->server->application[0]->live->stream;
	
	foreach($streams as $stream) {
		if($stream->name->__toString() == $_GET['u']){
			$seconds = round($stream->time/1000);
			$res['time'] = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);
			$res['bw_in'] = number_format($stream->bw_in/1000000,2,".",".");
			$res['width'] = $stream->meta->video->width->__toString();
			$res['height'] = $stream->meta->video->height->__toString();
			$res['frame_rate'] = $stream->meta->video->frame_rate->__toString();
			$res['vcodec'] = $stream->meta->video->codec->__toString();
			$res['acodec'] = $stream->meta->audio->codec->__toString();
			$res['channels'] = $stream->meta->audio->channels->__toString();
			$res['sample_rate'] = $stream->meta->audio->sample_rate->__toString();
			$res['seconds'] = $seconds;
			
			
			foreach($stream->client as $client) {
				if($client->publishing) $res['address'] = $client->address->__toString();
			}
		}
	}
	*/
	
	$stats = file_get_contents("https://".$server."/stats");
	$stats = json_decode($stats);
	$objarr = (array)$stats;
	$streams = $objarr['http-flv']->servers[0]->applications[0]->live->streams;
	
	foreach($streams as $stream) {
		if($stream->name == $_GET['u']){
			$seconds = round($stream->time/1000);
			$res['time'] = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);
			$res['bw_in'] = number_format($stream->bw_in/1000000,2,".",".");
			$res['width'] = $stream->meta->video->width;
			$res['height'] = $stream->meta->video->height;
			$res['frame_rate'] = $stream->meta->video->frame_rate;
			$res['vcodec'] = $stream->meta->video->codec;
			$res['acodec'] = $stream->meta->audio->codec;
			$res['channels'] = $stream->meta->audio->channels;
			$res['sample_rate'] = $stream->meta->audio->sample_rate;
			$res['seconds'] = $seconds;
			
			foreach($stream->clients as $client) {
				if($client->publishing) $res['address'] = $client->address;
			}
		}
	}
	
	
	if($row['is_streaming']) {
		$res['streaming'] = 1;
	} else {
		$res['streaming'] = 0;
	}
	
	if($row['yt_pid'] > 0){
		$res['yt_viewers'] = unserialize($row['viewers'])['yt'];
	}
	
	if($row['fb_pid'] > 0){
		$res['fb_viewers'] = 0;
		$r = unserialize($row['viewers']);
		if($r['fb'] > 0) $res['fb_viewers'] = $r['fb'];
	}
	
	//if($row['hls_pid'] > 0){
		$res['hls_viewers'] = unserialize($row['viewers'])['hls'];
	//}
	$res['server'] = $server;
	
	echo json_encode($res); exit;
}

if($_GET['action'] == 'uvid' && $_GET['u']){
	$conn = mysqli_connect($host, $username, $password, $dbname);
	if ($conn) {
		$req = file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']);
		$uns = unserialize($req);
		$result = mysqli_query($conn, 'INSERT INTO `hls_viewers` (appname, uvid, country) values ("'.$_GET['u'].'","'.$_GET['q'].'","'.$uns['geoplugin_countryName'].'")');
		$result = mysqli_query($conn, 'SELECT * FROM streams WHERE appname="'.$_GET['u'].'" ORDER BY id DESC LIMIT 1');
		$row = mysqli_fetch_assoc($result);
		$countries = unserialize($row['countries']);
		$countries[$uns['geoplugin_countryCode']]['name'] = $uns['geoplugin_countryName'];
		$countries[$uns['geoplugin_countryCode']]['count'] = $countries[$uns['geoplugin_countryCode']]['count'] + 1;
		$result = mysqli_query($conn, "UPDATE streams SET countries='".serialize($countries)."' WHERE id='".$row['id']."'");
	}
	$res["html"] = "ok";
	echo json_encode($res); exit;
}

if($_GET['action'] == 'chls' && $_GET['u']){
	$post = [
		'action' => 'check_hls',
		'name' => $_GET['u'],
	];
	
	$conn = mysqli_connect($host, $username, $password, $dbname);
	if ($conn) {
		$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$_GET['u'].'" LIMIT 1');
		$row = mysqli_fetch_assoc($result);
		
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
		if($responseObj->status == 'ok') {
			sleep(1);
			$res['status'] = "ok";
			echo json_encode($res);
		} else {
			sleep(1);
			$res['status'] = "nok";
			echo json_encode($res);
		}
	}
	exit;
}


if($_GET['action'] == 'graphs' && $_GET['u']){
	$res = "";
	$conn = mysqli_connect($host, $username, $password, $dbname);
	if ($conn) {
		$result = mysqli_query($conn, 'SELECT * FROM streams WHERE appname="'.$_GET['u'].'" ORDER BY id DESC LIMIT 1');
		$laststream = mysqli_fetch_assoc($result);
		if($laststream['id']) {
			$result = mysqli_query($conn, 'SELECT * FROM viewers WHERE stream_id="'.$laststream['id'].'" ORDER BY timestamp DESC LIMIT 1');
			if($result) {
				$last = mysqli_fetch_assoc($result);
				$res = unserialize($last['viewers']);
				if(!$res['yt']) $res['yt'] = 0;
				if(!$res['fb']) $res['fb'] = 0;
				if(!$res['hls']) $res['hls'] = 0;
				$res['timestamp'] = strtotime($last['timestamp']);
				$res['date'] = $last['timestamp'];
			}
			echo json_encode($res);
		}
	}
}


?>