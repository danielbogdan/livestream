<?php


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript" src="lib/javascript/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="lib/javascript/jwplayer-7.11.3/jwplayer.js?v=<?=$APP_VER?>"></script>
	<script>jwplayer.key="40IEcZFhBBH4kb84SEkG4vSU1q+mDiLdYxEIq85jRDo=";</script>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
</head>
<body>
	<div style="width:100%">
			<div class="style4" id="jwplayer">
				<div align="center" class="style5">Loading the player ...</div>
			</div>
			<script type="text/javascript">
				jwplayer("jwplayer").setup({
							sources: [{
									file: "https://livestreamrtmp.maghost.ro/transcoder_abr/danielb.m3u8",
									label: "Auto",
									"default": "true"
								  },
								  {
									file: "https://livestreamrtmp.maghost.ro/transcoder_abr/danielb_720p/index.m3u8",
									label: "720p HD"
								  },
								  {
									file: "https://livestreamrtmp.maghost.ro/transcoder_abr/danielb_360p/index.m3u8",
									label: "360p HD"
								  },
								  {
									file: "https://livestreamrtmp.maghost.ro/transcoder_abr/danielb_240p/index.m3u8",
									label: "240p"
								  }],
							rtmp: { bufferlength: 5  },
							hlshtml: true
							}).on('ready',function(){
							    var overlays = document.getElementsByClassName("jw-overlays")[0];
								var div = document.createElement("div");
								div.id="interceptor";
								overlays.appendChild(div);
							    addOverlay();
							}).on('resize',function(e){
								var divHeight = e.height - 30;
								var style = "pointer-events:none; height:"+divHeight+"px; width:"+e.width+"px; color: white; padding:20px; text-align:right;";
								document.getElementById("interceptor").setAttribute("style", style);
							});

			</script>  
	</div>
<script type="text/javascript">

</script>
</body>
</html>
