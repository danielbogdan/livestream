<?php
include "common.php";
session_start();
$conn = mysqli_connect($host, $username, $password, $dbname);
if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$_GET["app"].'" LIMIT 1');
	$row = mysqli_fetch_assoc($result);
	$server = "";
	if($row['server'] == '172.26.0.2') $server = "rtmp";

}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Live Streaming</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdn.jwplayer.com/libraries/4ETYDBDC.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
		<link rel="stylesheet" href="assets/css/style.css">
		<style>
		.jw-display-icon-container {
		  pointer-events: inherit !important;
		}
		</style>
 	<script>
    function sendHeightToParent() {
      var height = document.body.scrollHeight;
      parent.postMessage({ type: 'resizeIframe', height: height }, '*');
    }

    window.onload = sendHeightToParent;
  </script> 
    </head>
    <body style="margin: 0; overflow: hidden;">
		<div class="lp_video_site">
			<div class="style4" id="jwplayer">
				<div align="center" class="style5 no_stream-messaje" style="height: 500px !important;display: flex;justify-content: center;align-items: center;color: #fff;background-color: #000;font-size: 20px;  padding: 20px;"><p>În curând aici va începe o nouă transmisiune live care îţi poate transforma viaţa. <br>Urmăreşte-ne!</p></div>
			</div>
		</div>
		<?php if($row['is_streaming']) { ?>
			<script type="text/javascript">
				jwplayer("jwplayer").setup({
					hlslabels:{
						   "4500":"1080p",
						   "1300":"720p",
					},
					sources: [{
							file: "https://<?php echo $server;?>/transcoder_abr/<?php echo $row["appname"];?>/index.m3u8",
							label: "Auto",
							"default": "true"
						  }],
					rtmp: { bufferlength: 5  },
					hlshtml: true
				}).on('ready',function(){
					var overlays = document.getElementsByClassName("jw-overlays")[0];
					var div = document.createElement("div");
					div.id="interceptor";
					overlays.appendChild(div);
					document.getElementById('interceptor').innerHTML = "<div style=\"width:100%\"><p id=\"globviews\" style=\"margin: 0;  padding: 5px 20px;  background-color: #0000007d;  text-align: end;  color: #fff;  margin: 15px 20px;  width: fit-content;  line-height: 16px;  font-size: 16px;  border-radius: 7px;  margin-left: auto;  padding-top: 8px;\"></p></div>";
				   jwplayer("jwplayer").play();
				});
			</script>
		<?php } ?>
	</body>

<script>
<?php if($row['is_streaming']) { ?>
var pstate = 1;
<?php } else { ?>
var pstate = 0;
<?php } ?>
var myInterval;
muser = "<?php echo $row["appname"];?>";
var uvid = makeid(10);
$.ajax({
   type: "GET",
   url: "/api.php",
   data: "action=uvid&u=" +muser+"&q="+uvid,
   beforeSend: function(){  },
   success: function(msg){
		res = JSON.parse(msg);
   }
});

const interval = setInterval(function() {
   getStats();
}, 1000);

function getStats(){
	$.ajax({
	   type: "GET",
	   url: "/api.php",
	   data: "action=xml&u=" +muser+"&q="+uvid,
	   beforeSend: function(){  },
	   success: function(msg){
			res = JSON.parse(msg);
			if(res.streaming == "1") {
					if(pstate == 0) {
						pstate = 1;
						$(".lp_video_site").html('<div class="style4" id="jwplayer">\
													<div align="center" class="style5"><b>Streaming is starting ...</b></div>\
												</div>');
						check_hls();
					} else {
						var vt = +res.yt_viewers + res.fb_viewers + res.hls_viewers;
						$("#globviews").html(vt);
					}
			}
			if(res.streaming == "0") {
				if(pstate == 1) {
					pstate = 0;
					$(".lp_video_site").html('<div class="style4" id="jwplayer">\
													<div align="center" class="style5"><b>Streaming is stopping ...</b></div>\
												</div>');
					setTimeout(
						  function(){ 
						     location.reload();
						  },
						  2000
						);
				}
			}
			
	   }
	});
}

function check_hls(){
	$.ajax({
	   type: "GET",
	   url: "/api.php",
	   data: "action=chls&u="+muser,
	   beforeSend: function(){  },
	   success: function(msg){
			res = JSON.parse(msg);
			if(res.status === 'ok') {
				clearInterval(myInterval);
				jwplayer("jwplayer").setup({
							hlslabels:{
								   "4500":"1080p",
								   "1300":"720p",
							},
							sources: [{
								file: "https://<?php echo $row["server"];?>/transcoder_abr/"+muser+".m3u8",
								label: "Auto",
								"default": "true"
							}],
							rtmp: { bufferlength: 5  },
							hlshtml: true
							}).on('ready',function(){
								var overlays = document.getElementsByClassName("jw-overlays")[0];
								var div = document.createElement("div");
								div.id="interceptor";
								overlays.appendChild(div);
								document.getElementById('interceptor').innerHTML = "<div style=\"width:100%\"><p id=\"globviews\" style=\"margin: 0;  padding: 5px 20px;  background-color: #0000007d;  text-align: end;  color: #fff;  margin: 15px 20px;  width: fit-content;  line-height: 16px;  font-size: 16px;  border-radius: 7px;  margin-left: auto;  padding-top: 8px;\"></p></div>";
							    jwplayer("jwplayer").play();
							});
			}
			if(res.status === 'nok') {
				check_hls();
			}
		}
	});
}

function makeid(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
      counter += 1;
    }
    return result;
}
</script>
</html>
