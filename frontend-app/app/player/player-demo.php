<?php
$APP_VER = "10";

if(isset($_GET['cid'])) $cid = $_GET['cid'];
else exit;

if(isset($_GET['width']))		 $width = $_GET['width'];
if(isset($_GET['height'])) 		 $height = $_GET['height'];
if(isset($_GET['aspectratio']))  $aspectratio = $_GET['aspectratio'];

if(isset($_GET['skin']))  		  $skin = $_GET['skin'];
if(isset($_GET['active_color']))  $active_color = $_GET['active_color'];
if(isset($_GET['inactive_color']))  $inactive_color = $_GET['inactive_color'];
if(isset($_GET['background_color']))  $background_color = $_GET['background_color'];

$skins = array(
				"default"=>array(
							 "name" => "default",
							 "css_url" => "",
							 "active_color" => (isset($active_color)?'"'.$active_color.'"':''),
							 "inactive_color" => (isset($inactive_color)?'"'.$inactive_color.'"':''),
							 "background_color" => (isset($background_color)?'"'.$background_color.'"':''),
							),
				"tube"=>array(
							 "name" => "tube",
							 "css_url" => "//stream.maghost.ro/player/skins/jwplayer/tube/tube.min.css",
							 "active_color" => (isset($active_color)?'"'.$active_color.'"':''),
							 "inactive_color" => (isset($inactive_color)?'"'.$inactive_color.'"':''),
							 "background_color" => (isset($background_color)?'"'.$background_color.'"':''),
							)
			  );

if( isset($skin) || isset($active_color) || isset($inactive_color) ){
	
	if(!isset($skin)){ $skin = "default"; }
	
	$skin_option_string = ',skin : { '.  ( ($skins[$skin]["css_url"]!="")?'url:"'.$skins[$skin]["css_url"].'",  name:"'.$skins[$skin]["name"].'",':'' )  .'   '. (($skins[$skin]["active_color"]!="")?('active: '.$skins[$skin]["active_color"].','):'' ) .'   '. (($skins[$skin]["inactive_color"]!="")?'inactive: '.$skins[$skin]["inactive_color"].',':'' ) .' '. (($skins[$skin]["background_color"]!="")?'background: '.$skins[$skin]["background_color"].',':'' ) .'} ';	
	
}
//echo '<pre>'; print_r($skin_option_string);  print_r($skins);  exit;
							
$clinetsList = array(
						'918273644'=>array(
                                            'user'=>'elim',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1936/live/smil:streams.smil/jwplayer.smil',
                                            'liveurl_hls'=>'http://s1.stream.maghost.ro:1936/live/smil:streams.smil/playlist.m3u8',
                                           ),
					   	'918273645'=>array(
											'user'=>'betaniaj',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1937/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1937/live/smil:streams.smil/playlist.m3u8',
										  ),
						'918273646'=>array(
											'user'=>'bagape',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1938/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1938/live/smil:streams.smil/playlist.m3u8',
										  ),
						'918273647'=>array(
											'user'=>'bfilade',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1939/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1939/live/smil:streams.smil/playlist.m3u8',
										  ),
						'736489182'=>array(
											'user'=>'befrata',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1940/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1940/live/smil:streams.smil/playlist.m3u8',
										  ),				  
						'732648918'=>array(
											'user'=>'bmghiroda',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1941/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1941/live/smil:streams.smil/playlist.m3u8',
										  ),
						'648917831'=>array(
											'user'=>'bapelevii',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1943/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1943/live/smil:streams.smil/playlist.m3u8',
										  ),
						'864891742'=>array(
                                                                                        'user'=>'maghost',
                                                                                        'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1944/live/smil:streams.smil/jwplayer.smil',
                                                                                        'liveurl_hls'=>'http://s1.stream.maghost.ro:1944/live/smil:streams.smil/playlist.m3u8',
                                                                                  ),

						'864891731'=>array(
											'user'=>'bbetaniatm',
											'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1945/live/smil:streams.smil/jwplayer.smil',
											'liveurl_hls'=>'http://s1.stream.maghost.ro:1945/live/smil:streams.smil/playlist.m3u8',
										  ),
						'964891732'=>array(
                                                                                        'user'=>'dimovinter',
                                                                                        'liveurl_rtmp'=>'http://s1.stream.maghost.ro:1946/live/smil:streams.smil/jwplayer.smil',
                                                                                        'liveurl_hls'=>'http://s1.stream.maghost.ro:1946/live/smil:streams.smil/playlist.m3u8',
                                                                                  )						
					);

if(!isset($clinetsList[$cid])){ exit; }
					
require_once 'lib/Mobile-Detect-2.7.8/Mobile_Detect.php';
$_OS_Browser = new Mobile_Detect;

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript" src="lib/javascript/jquery/jquery-1.4.2.min.js?v=<?$APP_VER?>"></script>
	<script type="text/javascript" src="lib/javascript/jwplayer-7.11.3/jwplayer.js?v=<?$APP_VER?>"></script>
	<script>jwplayer.key="40IEcZFhBBH4kb84SEkG4vSU1q+mDiLdYxEIq85jRDo=";</script>

</head>
<body>
	<div style="width:100%">
			<div class="style4" id="jwplayer">
				<div align="center" class="style5">Loading the player ...</div>
			</div>
			<script type="text/javascript">
				jwplayer("jwplayer").setup({
							height: <?=$height?>,
							width: "<?=$width?>",
							<? if(isset($aspectratio)){ ?>aspectratio: "<?=$aspectratio?>", <? } ?>
							sources: [
										{ file: "<?=$clinetsList[$cid]['liveurl_hls']?>"},
										{ file: "<?=$clinetsList[$cid]['liveurl_rtmp']?>"}
									], 
							rtmp: { bufferlength: 5  },
							hlshtml: true
							<?=$skin_option_string?>
							});
		
			
			</script>  
	</div>
<script type="text/javascript">
	$(document).ready(function () {
	  //do_stuff();
	});

	function do_stuff(){
		update_stats();
	}
	
	function update_stats(){
		$.ajax({
			   type: "POST",
			   url: "getStreamStats.php",
			   data: "action=getStats",
			   success: function(msg){
				 nb_con = msg;
				 tmp = msg.split("#");
				 currentStatsObj = tmp[0].split("/");
				 maxStatsObj = tmp[1].split("/");
				 
				 $("#nbFlash").html(currentStatsObj[0]);  
				 $("#nbIos").html(currentStatsObj[1]);  
				 $("#nbTotal").html(currentStatsObj[2]);  
				 
				 $("#nbMaxFlash").html(maxStatsObj[0]);  
				 $("#nbMaxIos").html(maxStatsObj[1]);  
				 $("#nbMaxTotal").html(maxStatsObj[2]); 
				 
				 setTimeout("update_stats()",5000);
			   }
			});
		<?php if ($_OS_Browser->isAndroidOS() == false) {?>		
		$("#jw_bit_rate").html(jwplayer('jwplayer').getMeta().videodatarate);
		<?php } ?>
	}
</script>
</body>
</html>
