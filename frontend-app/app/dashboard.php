<?php

if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM streams WHERE appname="'.$_SESSION["appname"].'" ORDER BY id DESC LIMIT 1');
	$laststream = mysqli_fetch_assoc($result);
	if($laststream['id']) {
		$result = mysqli_query($conn, 'SELECT * FROM viewers WHERE stream_id="'.$laststream['id'].'"');
		while ($row = mysqli_fetch_assoc($result)) {
		  $viewers[] = $row;
		}
	}
}

if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM streams WHERE appname="'.$_SESSION["appname"].'"');
	while ($row = mysqli_fetch_assoc($result)) {
      $streams[] = $row;
    }
}

if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$_SESSION["appname"].'" ORDER BY id DESC LIMIT 1');
	$account = mysqli_fetch_assoc($result);
	$server = "";
	if($account['server'] == '172.26.0.2') $server = "rtmp";

}

$post = [
		'action' => 'get_media',
		'name' => $_SESSION["appname"],
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
$last_rec = json_decode($output);

?>

<!--
<script type="text/javascript" src="lib/javascript/jwplayer-7.11.3/jwplayer.js?v=<?=$APP_VER?>"></script>
<script>jwplayer.key="40IEcZFhBBH4kb84SEkG4vSU1q+mDiLdYxEIq85jRDo=";</script>
-->
<script type="text/javascript" src="https://cdn.jwplayer.com/libraries/4ETYDBDC.js"></script>
<script>
var rtmp = '<?=$server?>';
</script>

<div class="page-content">
	<div class="row">
		<div class="col-lg-6 left_section">
			<div class="left_section-container">
				<div class="live-preview-section">
					<div class="lp_title-box">
					<?php if($account['is_streaming']) { ?>
						<p class="lp_title">Your live feed preview</p>
					<?php } elseif($last_rec) { ?>
						<p class="lp_title">Your latest broadcast</p>
					<?php } else { ?>
						<p class="lp_title">You are not broadcasting now and you have no recorded broadcasts.</p>
					<?php } ?>
					</div>
					
					<div class="lp_video">
						<div class="style4" id="jwplayer">
							<div align="center" class="style5"></div>
						</div>
						<?php if($account['is_streaming'] || $last_rec) { ?>
						<script type="text/javascript">
							jwplayer("jwplayer").setup({
										hlslabels:{
											   "4500":"1080p",
											   "1300":"720p",
											   "700":"360p",
											   "350":"240p",
										},
									<?php if($account['is_streaming']) { ?>
										sources: [{
												file: "https://"+rtmp+"/transcoder_abr/<?php echo $_SESSION["appname"];?>/index.m3u8",
												label: "Auto",
												"default": "true"
											  }],
									<?php } elseif ($last_rec) { ?>
										sources: [{
												file: "https://localhost/video/<?php echo $_SESSION["appname"];?>/<?php echo $last_rec;?>",
												label: "Auto",
												"default": "true"
											  }],
									<?php } ?>
										rtmp: { bufferlength: 5  },
										hlshtml: true
										}).on('ready',function(){
										   jwplayer("jwplayer").play();
										});
						</script>
						<?php } ?>
					</div>
					
				</div>
				<div class="ls_stream-data" style="display: none;">
					<div class="ls_stream-data-top">
						<div class="ls_stream-data-top-left">
							<div class="uppercase_title-sm">
								<p>Stream Time</p>
							</div>
							<div class="ls_stream-data-time">
								<p><?php echo date("d M Y @ h:i");?></p>
							</div>
						</div>
						<div class="ls_stream-data-top-right">
							<p class="ls_data-uptime">uptime</p>
							<div class="ls_data-uptime-time-box">
								<p class="ls_data-uptime-time" id="uptime1"></p>
							</div>
						</div>
					</div>
					<div class="ls_stream-data-btm">
						<div class="ls_sdb-row row">
							<div class="ls_sdb-box col-md-4">
								<div class="uppercase_title-sm">
									<p>Publisher</p>
								</div>
								<p class="ls_sdb-ip"><a id="publisherip" href="#"></a></p>
							</div>
							<div class="ls_sdb-box col-md-4">
								<div class="uppercase_title-sm">
									<p>Video</p>
								</div>
								<p class="ls_sdb-resolution ls_sdb-info" id="resinfo"></p>
								<p class="ls_sdb-info ls_sdb-H" id="codecinfo"></p>
								<p class="ls_sdb-frames ls_sdb-info" id="framesinfo"></p>
							</div>
							<div class="ls_sdb-box col-md-4">
								<div class="uppercase_title-sm">
									<p>Audio</p>
								</div>
								<p class="ls_sdb-audio ls_sdb-info" id="audioinfo"></p>

							</div>
						</div>
					</div>
				</div>
				<div class="ls_stream-charts"  style="display: block;">
					<div class="ls_stream-charts-container">
						<div id="chart">
						  <div id="timeline-chart"></div>
						</div>
					</div>
				</div>
				<div class="ls_stream-maps"  style="display: block;">
					<div class="ls_stream-maps-container">
						<div id="chartdiv"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-6 right_section">
			<div class="rs_container">
				<div class="rs_title-main">
					<p>Stream Targets</p>
				</div>
				<div class="rs-stream-targets-list">
					<div id="ytStatusContainer" class="rs_stream-target" style="display: none;">
						<div class="stream_target-top">
							<div class="stt_info">
								<div class="stt-title">
									<img src="assets/images/youtube.svg">
									<p>Stream to YouTube</p>
								</div>	
								<!-- <p class="stt-info-details">Stream started 1 hour and 12 minutes ago</p> -->
							</div>
							<div class="stt_btn">
								<div class="custom-selects custom-select-yt OnBit">
								  <select id="ytstreamStatus" class="streamselect">
									<option value="1" class="OnBit" selected>OnBit</option>
								  </select>
								</div>
							</div>
						</div>
						<div class="stream_target-btm">
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title"><span id="yt_receive" class="<?php if($account['yt_pid']) { echo 'receive_biton'; } else { echo 'receive_offline'; } ?>"></span>Receive</p>
									<div class="stb_box-cont">
										<p class="stb_box-data bytesin">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Upload</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Viewers</p>
									<div class="stb_box-cont">
										<p class="stb_box-data ytviewers">0</p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Views</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="fbStatusContainer" class="rs_stream-target" style="display: none;">
						<div class="stream_target-top">
							<div class="stt_info">
								<div class="stt-title">
									<img src="assets/images/facebook.svg">
									<p>Stream to Facebook</p>
								</div>	
								<p class="stt-info-details">/<?php echo $account['fb_page_linked']; ?></p>
							</div>
							<div class="stt_btn">
								<div class="custom-selects custom-select-yt OnBit" >
								  <select id="fbstreamStatus" class="streamselect">
									<option value="1" class="OnBit" selected>OnBit</option>
								  </select>
								</div>
							</div>
						</div>
						<div class="stream_target-btm">
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title"><span id="fb_receive" class="<?php if($account['fb_pid']) { echo 'receive_biton'; } else { echo 'receive_offline'; } ?>"></span>Receive</p>
									<div class="stb_box-cont">
										<p class="stb_box-data bytesin">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Upload</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Viewers</p>
									<div class="stb_box-cont">
										<p class="stb_box-data fbviewers">0</p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Comments</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="igStatusContainer" class="rs_stream-target" style="display: none;">
						<div class="stream_target-top">
							<div class="stt_info">
								<div class="stt-title">
									<img src="assets/images/Instagram_logo-white.png">
									<p>Stream to Instagram</p>
								</div>	
								<!-- <p class="stt-info-details">Stream started 1 hour and 12 minutes ago</p> -->
							</div>
							<div class="stt_btn">
								<div class="custom-selects custom-select-yt OnBit">
								  <select id="igstreamStatus" class="streamselect">
									<option value="1" class="OnBit" selected>OnBit</option>
								  </select>
								</div>
							</div>
						</div>
						<div class="stream_target-btm">
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title"><span id="ig_receive" class="<?php if($account['ig_pid']) { echo 'receive_biton'; } else { echo 'receive_offline'; } ?>"></span>Receive</p>
									<div class="stb_box-cont">
										<p class="stb_box-data bytesin">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Upload</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Viewers</p>
									<div class="stb_box-cont">
										<p class="stb_box-data igviewers">0</p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Views</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="hlsStatusContainer" class="rs_stream-target" style="display: none;">
						<div class="stream_target-top">
							<div class="stt_info">
								<div class="stt-title">
									<p>Stream to website</p>
								</div>	
								<!-- <p class="stt-info-details">Stream started 1 hour and 12 minutes ago</p> -->
							</div>
							<div class="stt_btn">
								<div class="custom-selects custom-select-yt OnBit" >
								  <select id="hlsstreamStatus" class="streamselect">
									<option value="1" class="OnBit" selected>OnBit</option>
								  </select>
								</div>
							</div>
						</div>
						<div class="stream_target-btm">
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title"><span id="hls_receive" class="<?php if($account['is_streaming']) { echo 'receive_biton'; } else { echo 'receive_offline'; } ?>"></span>Receive</p>
									<div class="stb_box-cont">
										<p class="stb_box-data bytesin">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Upload</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0 <span>/mbs</span></p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Viewers</p>
									<div class="stb_box-cont">
										<p class="stb_box-data hlsviewers">0</p>
									</div>
								</div>
							</div>
							<div class="stream_target-btm-box">
								<div class="stb_box-content">
									<p class="stb_box-title">Views</p>
									<div class="stb_box-cont">
										<p class="stb_box-data">0</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form id="fb_form" action="" method="post">
	<input type="hidden" id="fb_start_stop" name="fb_start_stop" value=""/>
</form>

<form id="yt_form" action="" method="post">
	<input type="hidden" id="yt_start_stop" name="yt_start_stop" value=""/>
</form>

<form id="hls_form" action="" method="post">
	<input type="hidden" id="hls_start_stop" name="hls_start_stop" value=""/>
</form>

<script>
var uptime = "0";
<?php if($account['is_streaming']) { ?>
var pstate = "1";
<?php } else { ?>
var pstate = "0";
<?php } ?>

muser = "<?php echo $_SESSION["appname"];?>";

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
}, 2000);

var yt_pid = 0;
var fb_pid = 0;
var ig_pid = 0;
var hls_pid = 0;
var stream_id = 0;
var is_streaming = 0;
<?php if($account['yt_pid']) echo 'yt_pid = 1;'; ?>
<?php if($account['fb_pid']) echo 'fb_pid = 1;'; ?>
<?php if($account['ig_pid']) echo 'ig_pid = 1;'; ?>
<?php if($account['hls_pid']) echo 'hls_pid = 1;'; ?>
<?php if($account['is_streaming']) echo 'is_streaming = 1;'; ?>

function getStats(){
	$.ajax({
	   type: "GET",
	   url: "/api.php",
	   data: "action=xml&u=" +muser+"&q="+uvid,
	   beforeSend: function(){  },
	   success: function(msg){
			res = JSON.parse(msg);
			if(res.streaming == "1") {
				if(res.bw_in) {
					uptime = res.seconds;
					$("#uptime1").html(res.time);
					$("#publisherip").html(res.address);
					$("#resinfo").html(res.width+"x"+res.height+" @"+res.frame_rate);
					$("#codecinfo").html(res.vcodec);
					$("#framesinfo").html(res.frame_rate+" frames");
					$("#audioinfo").html(res.acodec+" "+res.sample_rate);
					$(".bytesin").html(res.bw_in+" <span>/mbs</span>");
					$(".ls_stream-data").show();
				}
				if(res.yt_pid > 0) {
					yt_pid = 1;
					$("#yt_receive").removeClass().addClass('receive_biton');
					$(".ytstreamStatus").html('OnBit');
					$("#ytstreamStatus").val('1');
					$("#ytStatusContainer").show();
					$(".ytviewers").html(res.yt_viewers);
				}
				if(res.fb_pid > 0) {
					fb_pid = 1;
					$("#fb_receive").removeClass().addClass('receive_biton');
					$(".fbstreamStatus").html('OnBit');
					$("#fbstreamStatus").val('1');
					$("#fbStatusContainer").show();
					$(".fbviewers").html(res.fb_viewers);
				}
				if(res.ig_pid > 0) {
					yt_pid = 1;
					$("#ig_receive").removeClass().addClass('receive_biton');
					$(".igstreamStatus").html('OnBit');
					$("#igstreamStatus").val('1');
					$("#igStatusContainer").show();
					$(".igviewers").html(res.yt_viewers);
				}
				/*
				if(res.hls_pid > 0) {
					hls_pid = 1;
					$("#hls_receive").removeClass().addClass('receive_biton');
					$(".hlsstreamStatus").html('OnBit');
					$("#hlsstreamStatus").val('1');
					$("#hlsStatusContainer").show();
					$(".hlsviewers").html(res.hls_viewers);
				}
				*/
				if(res.streaming > 0) {
					hls_pid = 1;
					$("#hls_receive").removeClass().addClass('receive_biton');
					$(".hlsstreamStatus").html('OnBit');
					$("#hlsstreamStatus").val('1');
					$("#hlsStatusContainer").show();
					$(".hlsviewers").html(res.hls_viewers);
					if(pstate == 0) {
						pstate = 1;
						yt_max = [];
						hls_max = [];
						fb_max = [];
						$(".lp_video").html('<div class="style4" id="jwplayer">\
														<div align="center" class="style5"><b>Streaming is starting ...</b></div>\
													</div>');
							check_hls();
					}
				}
				
				rtmp = res.server;
				is_streaming = 1;
				stream_id = res.stream_id;
			}
			if(res.streaming == "0") {
				$("#uptime1").html("");
				$("#publisherip").html("");
				$("#resinfo").html("");
				$("#codecinfo").html("");
				$("#framesinfo").html("");
				$("#audioinfo").html("");
				$(".bytesin").html("0 <span>/mbs</span>");
				$(".ls_stream-data").hide();
				$("#yt_receive").removeClass().addClass('receive_offline');
				$("#fb_receive").removeClass().addClass('receive_offline');
				$("#ig_receive").removeClass().addClass('receive_offline');
				$("#hls_receive").removeClass().addClass('receive_offline');
				
				$(".ytstreamStatus").html('Offline');
				$("#ytstreamStatus").val('0');
				
				$(".fbstreamStatus").html('Offline');
				$("#fbstreamStatus").val('0');
				
				$(".igstreamStatus").html('Offline');
				$("#igstreamStatus").val('0');
				
				$(".hlsstreamStatus").html('Offline');
				$("#hlsstreamStatus").val('0');
				
				is_streaming = 0;
				
				if(pstate == 1) {
					pstate = 0;
					$(".lp_video").html('<div class="style4" id="jwplayer">\
													<div align="center" class="style5"><b>Streaming is stopping</b></div>\
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

var dotsi = 1;
var dots = "";
function check_hls(){
	$.ajax({
	   type: "GET",
	   url: "/api.php",
	   data: "action=chls&u="+muser,
	   beforeSend: function(){  },
	   success: function(msg){
			res = JSON.parse(msg);
			if(res.status === 'ok') {
				jwplayer("jwplayer").setup({
							hlslabels:{
								   "4500":"1080p",
								   "1300":"720p",
								   "700":"360p",
								   "350":"240p",
							},
							sources: [{
								file: "https://"+rtmp+"/transcoder_abr/<?php echo $_SESSION["appname"];?>/index.m3u8",
								label: "Auto",
								"default": "true"
							}],
							rtmp: { bufferlength: 5  },
							hlshtml: true,
							}).on('ready',function(){
							   jwplayer("jwplayer").play();
							});
			}
			if(res.status === 'nok') {
				if(dotsi < 4) {
					dotsi++;
					dots = dots + ".";
					$(".lp_video").html('<div class="style4" id="jwplayer">\
													<div align="center" class="style5"><b>Streaming is starting ' + dots +'</b></div>\
												</div>');
				} else {
					dotsi = 1;
					dots = ".";
					$(".lp_video").html('<div class="style4" id="jwplayer">\
													<div align="center" class="style5"><b>Streaming is starting ' + dots +'</b></div>\
												</div>');
				}
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


<script>
	var x, i, j, l, ll, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select-yt");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected " + selElmnt.id );
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);

  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h, sl, yl;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        sl = s.length;
        h = this.parentNode.previousSibling;
        for (i = 0; i < sl; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            yl = y.length;
            for (k = 0; k < yl; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
		s.dispatchEvent(new Event('change'));
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}


function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);


var yt_max = [];
var hls_max = [];
var fb_max = [];

var yt_tmp = [];
var hls_tmp = [];
var fb_tmp = [];

var last_timestamp = "";

<?php
foreach($viewers as $view) {
	$ret = unserialize($view['viewers']);
	$timestamp = strtotime($view['timestamp']);
	if(!$ret['yt']) $ret['yt'] = 0;
	if(!$ret['hls']) $ret['hls'] = 0;
	if(!$ret['fb']) $ret['fb'] = 0;
?>
yt_max.push([<?php echo $timestamp.'000, '.$ret['yt']?>]);
hls_max.push([<?php echo $timestamp.'000, '.$ret['hls']?>]);
fb_max.push([<?php echo $timestamp.'000, '.$ret['fb']?>]);
<?php } ?>

var options = {
      chart: {
        type: "area",
        height: 300,
        foreColor: "#000a13",
        stacked: false,
        dropShadow: {
          enabled: false,
          enabledSeries: [0],
          top: -2,
          left: 2,
          blur: 5,
          opacity: 0.06
        }
      },
      colors: ['#CE0005', '#1561AC', '#09274C'],
      stroke: {
        curve: "smooth",
		lineCap: 'round',
        width: 0.5
      },
      dataLabels: {
        enabled: false
      },
      series: [],
	  noData: {
		text: 'Loading...'
	  },
      markers: {
        size: 0,
        strokeColor: "#fff",
        strokeWidth: 1,
        strokeOpacity: 1,
        fillOpacity: 1,
        hover: {
          size: 6
        }
      },
      xaxis: {
        type: "datetime",
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        labels: {
          offsetX: 14,
          offsetY: -5
        },
        tooltip: {
          enabled: true
        }
      },
      grid: {
        padding: {
          left: -5,
          right: 5
        }
      },
      tooltip: {
        x: {
          format: "dd MMM yyyy"
        },
      },
      legend: {
        position: 'top',
        horizontalAlign: 'left'
      },
      fill: {
        type: "solid",
        fillOpacity: 1
      },
       toolbar: {
        show: false,
        offsetX: 0,
        offsetY: 0,
        tools: {
          download: false,
          selection: false,
          zoom: false,
          zoomin: false,
          zoomout: false,
          pan: false,
          reset: false | '<img src="/static/icons/reset.png" width="20">',
          customIcons: []
        }
      }
    };

    var chart = new ApexCharts(document.querySelector("#timeline-chart"), options);
    chart.render();
	if(is_streaming < 1) drawGraph();
	
var maxvals = 1000;
function getGraphs(){
	$.ajax({
	    type: "GET",
	    url: "/api.php",
	    data: "action=graphs&u=" +muser+"&t="+last_timestamp,
	    beforeSend: function(){  },
	    success: function(msg){
			res = JSON.parse(msg);
			if(res.timestamp) {
				if(last_timestamp < res.date) {
					last_timestamp = res.date;
					var ts = res.timestamp+'000';
					if(res.yt > 0) {
						yt_max.push([ +ts, +res.yt]);
						//if(yt_max.length > maxvals) yt_max.shift();
					} else {
						yt_max.push([ +ts, 0]);
					}
					if(res.hls > 0) {
						hls_max.push([ +ts, +res.hls]);
						//if(hls_max.length > maxvals) hls_max.shift();
					} else {
						hls_max.push([ +ts, 0]);
					}
					if(res.fb > 0) {
						fb_max.push([ +ts, +res.fb]);
						//if(fb_max.length > maxvals) fb_max.shift();
					} else {
						fb_max.push([ +ts, 0]);
					}

					drawGraph();
				}
			}
	    }
	});
}		


function drawGraph(){
	var k;
	/*
	if(is_streaming > 0) {
		k = Math.ceil(uptime / 360);
	} else {
		k = Math.ceil(yt_max.length / 100);
	}
	*/
	
	k = Math.ceil(uptime / 360);
	if(k < 1) k = 1;
						
	var i = 0;
	yt_tmp = [];
	while( i < yt_max.length) {
		yt_tmp.push(yt_max[i]);
		i = i + k;
	}
	
	var i = 0;
	hls_tmp = [];
	while( i < hls_max.length) {
		hls_tmp.push(hls_max[i]);
		i = i + k;
	}
	
	var i = 0;
	fb_tmp = [];
	while( i < fb_max.length) {
		fb_tmp.push(fb_max[i]);
		i = i + k;
	}
		
	chart.updateOptions({
	   series: [{
		name: 'YouTube',
		data: yt_tmp
	  }, {
		name: 'Facebook',
		data: fb_tmp
	  }, {
		name: 'Website',
		data: hls_tmp
	  }],
	});
}

/*
const graphint = setInterval(function() {
	if(is_streaming) {
		getGraphs();
	}
}, 1000);
*/

</script>

<script>
am5.ready(function() {
	
	
<?php
$data = unserialize($laststream['countries']);
echo 'var data = [';
foreach($data as $key => $value) {
	echo '{ id: "'.$key.'", name: "'.$value['name'].'", value: '.$value['count'].'},';
}
echo '];';
?>

var root = am5.Root.new("chartdiv");
root.setThemes([am5themes_Animated.new(root)]);

var chart = root.container.children.push(am5map.MapChart.new(root, {}));

var polygonSeries = chart.series.push(
  am5map.MapPolygonSeries.new(root, {
    geoJSON: am5geodata_worldLow,
    exclude: ["AQ"],
    fill: am5.color(0x1561AC),
  })
);

var bubbleSeries = chart.series.push(
  am5map.MapPointSeries.new(root, {
    valueField: "value",
    calculateAggregates: true,
    polygonIdField: "id"
  })
);

var circleTemplate = am5.Template.new({});

bubbleSeries.bullets.push(function(root, series, dataItem) {
  var container = am5.Container.new(root, {});

  var circle = container.children.push(
    am5.Circle.new(root, {
      radius: 20,
      fillOpacity: 0.7,
      fill: am5.color(0xCE0005),
      cursorOverStyle: "pointer",
      tooltipText: `{name}: [bold]{value}[/]`
    }, circleTemplate)
  );

  var countryLabel = container.children.push(
    am5.Label.new(root, {
      text: "{name}",
      paddingLeft: 5,
      populateText: true,
      fontWeight: "bold",
      fontSize: 13,
      centerY: am5.p50
    })
  );

  circle.on("radius", function(radius) {
    countryLabel.set("x", radius);
  })

  return am5.Bullet.new(root, {
    sprite: container,
    dynamic: true
  });
});

bubbleSeries.bullets.push(function(root, series, dataItem) {
  return am5.Bullet.new(root, {
    sprite: am5.Label.new(root, {
      text: "{value.formatNumber('#.')}",
      fill: am5.color(0xffffff),
      populateText: true,
      centerX: am5.p50,
      centerY: am5.p50,
      textAlign: "center"
    }),
    dynamic: true
  });
});



// minValue and maxValue must be set for the animations to work
bubbleSeries.set("heatRules", [
  {
    target: circleTemplate,
    dataField: "value",
    min: 10,
    max: 50,
    minValue: 0,
    maxValue: 100,
    key: "radius"
  }
]);

bubbleSeries.data.setAll(data);

/*
updateData();
setInterval(function() {
  updateData();
}, 2000)

function updateData() {
  for (var i = 0; i < bubbleSeries.dataItems.length; i++) {
    bubbleSeries.data.setIndex(i, { value: Math.round(Math.random() * 100), id: data[i].id, name: data[i].name })
  }
}
*/


}); // end am5.ready()
</script>
