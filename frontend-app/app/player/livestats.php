<?php

	if($_GET['n']) {
		
		
		//file_put_contents('ip.txt', $_SERVER['REMOTE_ADDR']."\n", FILE_APPEND);
		
		
		if(time() - filemtime('data.xml') > 10){

			$username = "admin";
			$password = "h38X0zOM16S404Et";
			$URL = "http://86.104.220.235:8086/connectioncounts";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$URL);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			$result=curl_exec($ch);
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			file_put_contents ('data.xml', $result);

		}
		
		if($_GET['y']) {
			$timp = 100 + rand(20,60);
			if(time() - filemtime($_GET['y'].'.txt') > $timp){
				
			   /*
			   $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=".$_GET['y']."&type=video&eventType=live&key=AIzaSyBEh-0Dmbsj5HNslpXjYIDhCSz7a37BYQ0";
			   $curl = curl_init($url);

			   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
			   $json_response = curl_exec($curl);
			   curl_close($curl);
			   $responseObj = json_decode($json_response);
			   $items = $responseObj->items;
			   if(is_array($items)) { 
					$vid = $items[0]->id->videoId;
					file_put_contents ($_GET['y'].'.txt', $vid);
			   } else {
				   file_put_contents ($_GET['y'].'.txt', "none");
			   }
			   */
			   
			   $doc = new DOMDocument();
			   $doc->loadHTMLFile("https://www.youtube.com/channel/".$_GET['y']."/videos?view=2&flow=grid&live_view=501");
			   
			    foreach( $doc->getElementsByTagName('img') as $item){
					$src =  $item->getAttribute('src');
					if (strpos($src, 'hqdefault_live') !== false) {
						$pieces = explode("/", $src);
						file_put_contents ($_GET['y'].'.txt', $pieces[4]);
						break;
					} else {
						file_put_contents ($_GET['y'].'.txt', "none");
					}
			    }
			}
			
			if(time() - filemtime($_GET['y'].'_v.txt') > 50){
				$stream = fopen($_GET['y'].'.txt',"r");
				$vid = stream_get_contents($stream);
				fclose($stream);
				if($vid != "none") {
					$url = "https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id=".$vid."&key=AIzaSyBEh-0Dmbsj5HNslpXjYIDhCSz7a37BYQ0";
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
					$json_response = curl_exec($curl);
					curl_close($curl);
					$responseObj = json_decode($json_response);
					$items = $responseObj->items;
					$viws = $items[0]->liveStreamingDetails->concurrentViewers;
					if($viws){
						file_put_contents ($_GET['y'].'_v.txt', print_r($viws, true));
						$res['yt'] = $viws;
					} else {
						file_put_contents ($_GET['y'].'_v.txt', "0");
					}
				}
			} else {
				$stream = fopen($_GET['y'].'_v.txt',"r");
				$viewers = stream_get_contents($stream);
				fclose($stream);
				if($viewers > 0) {
					$res['yt'] = $viewers;
				}
			}
			
		}
		

		$xml = simplexml_load_file('data.xml');
		
		$res['html'] = "";
		
		foreach ($xml->VHost as $vhost) {
			if($vhost->Name == $_GET['n']) {
				$res['html'] = (string) $vhost->ConnectionsCurrent;
			}
		}
		
		echo json_encode($res); exit;
		
	}

?>