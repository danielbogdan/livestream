<?php
error_reporting(0);
date_default_timezone_set("Europe/Bucharest");
include "common.php";
$conn = mysqli_connect($host, $username, $password, $dbname);
$run = true;
$fbcount = 0;
if($conn) {
	while ($run) {
		$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$argv[1].'" LIMIT 1');
		$row = mysqli_fetch_assoc($result);
		$server = $row['server'];
		$result = mysqli_query($conn, 'SELECT * FROM streams WHERE id="'.$row['stream_id'].'" LIMIT 1');
		$stream = mysqli_fetch_assoc($result);
		if($row['is_streaming']) {
			$ret = unserialize($row['retries']);
			if($row['fb_pid'] > 0) {
				if(check($row['fb_pid'], $server) != 'ok') {
					if($ret['fb'] < 3) {
						$ret['fb'] = $ret['fb'] + 1;
						restart_fb($row, $conn);
					} else {
						$result = mysqli_query($conn, 'UPDATE accounts SET fb_pid_status=0 WHERE appname="'.$row['appname'].'"');
					}
				} else {
					$result = mysqli_query($conn, 'UPDATE accounts SET fb_pid_status=1 WHERE appname="'.$row['appname'].'"');
					$ret['fb'] = 0;
				}
				
				$fbcount = $fbcount + 1;
				if($fbcount == 20 ) {
					$fbcount = 0;
					$row['viewers'] = fb_viewers($row, $conn, $stream);
				}
			}
			if($row['yt_pid'] > 0) {
				if(check($row['yt_pid'], $server) != 'ok') {
					if($ret['yt'] < 3) {
						$ret['yt'] = $ret['yt'] + 1;
						restart_yt($row, $conn);
					} else {
						$result = mysqli_query($conn, 'UPDATE accounts SET yt_pid_status=0  WHERE appname="'.$row['appname'].'"');
					}
				} else {
					$result = mysqli_query($conn, 'UPDATE accounts SET yt_pid_status=1  WHERE appname="'.$row['appname'].'"');
					$ret['yt'] = 0;
				}
				$row['viewers'] = yt_viewers($row, $conn, $stream);
			}
			if($row['hls_pid'] > 0) {
				if(check($row['hls_pid'], $server) != 'ok') {
					if($ret['hls'] < 3) {
						$ret['hls'] = $ret['hls'] + 1;
						restart_hls($row, $conn);
					} else {
						$result = mysqli_query($conn, 'UPDATE accounts SET hls_pid_status=0  WHERE appname="'.$row['appname'].'"');
					}
				} else {
					$result = mysqli_query($conn, 'UPDATE accounts SET hls_pid_status=1  WHERE appname="'.$row['appname'].'"');
					$ret['hls'] = 0;
				}
				$row['viewers'] = hls_viewers($row, $conn, $stream);
			}
			if($row['rec_pid'] > 0) {
				if(check($row['rec_pid'], $server) != 'ok') {
					if($ret['rec'] < 3) {
						$ret['rec'] = $ret['rec'] + 1;
						restart_rec($row, $conn);
					} else {
						$result = mysqli_query($conn, 'UPDATE accounts SET rec_pid_status=0 WHERE appname="'.$row['appname'].'"');
					}
				} else {
					$result = mysqli_query($conn, 'UPDATE accounts SET rec_pid_status=1 WHERE appname="'.$row['appname'].'"');
					$ret['rec'] = 0;
				}
			}
			$qry = mysqli_query($conn, "UPDATE accounts SET retries='".serialize($ret)."' WHERE appname='".$row['appname']."'");
			$qry = mysqli_query($conn, "INSERT INTO viewers (stream_id, viewers) values ('".$stream['id']."', '".$row['viewers']."')");
		} else {
			$run = false;
		}
		sleep(3);
	}
}

function check($p, $server) {
	$post = [
		'action' => 'check_pid',
		'pid' => $p,
	];

	$graph_url= "http://".$server."/api.php";
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
	return $responseObj->status;
}

function restart_fb($row, $conn) {
	
	if ($row['fb_stream_id'] && $row['fb_api']){
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
		$fb_url = $fbObj->stream_url;
	}
	
	if($row['fb_api'] == 0 && $row['fb_manual_key']) {
		$fb_url = "rtmps://live-api-s.facebook.com:443/rtmp/".$row['fb_manual_key'];
	}
	
	$post = [
		'action' => 'publish_fb',
		'name' => $row['appname'],
		'fb_url' => $fb_url
	];
	
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
	if($responseObj->fb_pid) $result = mysqli_query($conn, 'UPDATE accounts SET fb_pid="'.$responseObj->fb_pid.'", fb_stream_id="'.$fbObj->id.'" WHERE appname="'.$row['appname'].'"');
}

function restart_yt($row, $conn) {
	$post = [
		'action' => 'publish_yt',
		'name' => $row['appname'],
		'yt_key' => $row['yt_key']
	];
	
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
	if($responseObj->yt_pid) $result = mysqli_query($conn, 'UPDATE accounts SET yt_pid="'.$responseObj->yt_pid.'"  WHERE appname="'.$row['appname'].'"');
}

function restart_hls($row, $conn) {
	$post = [
		'action' => 'publish_hls',
		'name' => $row['appname']
	];
	
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
	if($responseObj->hls_pid) $result = mysqli_query($conn, 'UPDATE accounts SET hls_pid="'.$responseObj->hls_pid.'"  WHERE appname="'.$row['appname'].'"');
}

function restart_rec($row, $conn) {
	$post = [
		'action' => 'publish_rec',
		'name' => $row['appname']
	];
	
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
	if($responseObj->rec_pid) $result = mysqli_query($conn, 'UPDATE accounts SET rec_pid="'.$responseObj->rec_pid.'" WHERE appname="'.$row['appname'].'"');
}

function yt_viewers($row, $conn, $stream){
    if($row['yt_ch_id']) {
	   $res = unserialize($row['viewers']);
	   $v = 0;
	   $doc = new DOMDocument();
	   $doc->loadHTMLFile("https://www.youtube.com/channel/".$row['yt_ch_id']);

	   $video_id = get_string_between($doc->textContent, 'https://i.ytimg.com/vi/', '/hqdefault_live');	   
			   
	   $docu = new DOMDocument();
	   $docu->loadHTMLFile("https://www.youtube.com/watch?v=".$video_id);
	   
	   $viewers = get_string_between($docu->textContent, '"views":{"runs":[{"text":"', '"},');
	   
	   if($viewers != "") {
		   if(is_numeric($viewers)){
			 $v = $viewers;
		   }  
		   if($viewers[0] == "O") {
			 $v = 1;
		   }
	   }
	   $res['yt'] = $v;
	   $result = mysqli_query($conn, "UPDATE accounts SET viewers='".serialize($res)."' WHERE appname='".$row['appname']."'");
	   if($v > $stream['yt_max']) $result = mysqli_query($conn, 'UPDATE streams SET yt_max="'.$v.'" WHERE id="'.$stream['id'].'"');
	   return serialize($res);
    }
	return $row['viewers'];
}

function hls_viewers($row, $conn, $stream){
	$res = unserialize($row['viewers']);
	$greater = date("y-m-d H:i:s", time() - 5);
	$result = mysqli_query($conn, 'SELECT * FROM `hls_viewers` WHERE appname="'.$row['appname'].'" AND `updated` > "'.$greater.'"');
	$res['hls'] = mysqli_num_rows($result);
	$result = mysqli_query($conn, "UPDATE accounts SET viewers='".serialize($res)."' WHERE appname='".$row['appname']."'");
	if($res['hls'] > $stream['hls_max']) $result = mysqli_query($conn, 'UPDATE streams SET hls_max="'.$res['hls'].'" WHERE id="'.$stream['id'].'"');	
	return serialize($res);
}

function fb_viewers($row, $conn, $stream){
	
	$res = unserialize($row['viewers']);
	
	$graph_url= "https://graph.facebook.com/v17.0/".$row['fb_stream_id']."?fields=live_views&access_token=".$row['fb_page_token'];
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $graph_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$output = curl_exec($ch);
	curl_close($ch);
	$fbObj = json_decode($output);
	$v = $fbObj->live_views;
	$res['fb'] = $v;
	
	$result = mysqli_query($conn, "UPDATE accounts SET viewers='".serialize($res)."' WHERE appname='".$row['appname']."'");
	if($v > $stream['fb_max']) $result = mysqli_query($conn, 'UPDATE streams SET fb_max="'.$v.'" WHERE id="'.$stream['id'].'"');
	
	return serialize($res);

}


function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


?>
	