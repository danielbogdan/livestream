<?php
if(isset($_GET['cid']))		 $cid = $_GET['cid'];

if(isset($_GET['width']))		 $width = $_GET['width'];
if(isset($_GET['height'])) 		 $height = $_GET['height'];
if(isset($_GET['aspectratio']))  $aspectratio = $_GET['aspectratio'];

if(isset($_GET['key']))		 $key = $_GET['key'];

if(isset($_GET['f']))		 $f = $_GET['f'];

//if( !isset($key) || $key!='691413'){ exit; }
					

$clinetsList = array(
					   '8369'=>array(
											'user'=>'kilpatrick',
											'vod_url'=>'rtmp://s1.stream.maghost.ro:1942/hdfvr_play/mp4/'
										  )	
					);
										  
if(!isset($clinetsList[$cid])){ exit; }

require_once 'lib/Mobile-Detect-2.7.8/Mobile_Detect.php';
$_OS_Browser = new Mobile_Detect;

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript" src="lib/javascript/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="lib/javascript/jwplayer6/jwplayer.js"></script>
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
										{ file: "<?=$clinetsList[$cid]['vod_url'].$f?>"}
									], 
							rtmp: {
							     bufferlength: 5
								  },
							primary: "flash"
							});
		
			
			</script>  
	</div>
<script type="text/javascript">
	/*
	jwplayer("jwplayer").setup({
			  flashplayer: "jwplayer/player.swf",
				  skin:"nature01.zip",
			  //  file: "mentenanta.jpg",
				  file: "livestream1",
				  streamer:'rtmp://95.77.97.66/live',
				  height: 320,
				  width: 560
	  });
	*/	  
	
	$(document).ready(function () {
	  //do_stuff();
	});

	function do_stuff(){
		update_stats();
	}
	
	
</script>
</body>
</html>