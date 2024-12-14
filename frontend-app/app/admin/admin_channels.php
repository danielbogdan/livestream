<?php
if($conn){
	$channels = array();
	$result = mysqli_query($conn, 'SELECT * FROM accounts where name <> "admin"');
	while ($row = mysqli_fetch_assoc($result)) {
	  $qry = mysqli_query($conn, 'SELECT * FROM streams WHERE appname="'.$row['appname'].'" ORDER BY id DESC LIMIT 1');
	  $r = mysqli_fetch_assoc($qry);
	  $row['last_stream'] = $r['stream_date'];
      $channels[] = $row;
    }
}
?>


<div class="page-content admin_channels">
	<div class="row title-row">
		<div class="col-lg-2 col-md-12 light_background page_title-tab width_20">
			<p class="page_title-admin">Channels</p>
		</div>
	</div>
	<div class="table_head-row row">
		<div class="th_box light_background col-lg-2 col-md-12 channel_name_box width_20">
			<p class="th_title">Channel name</p>
		</div>
		<div class="th_box col-lg-2 col-md-12 active_targets_box width_13 text_center">
			<p class="th_title">active targets</p>
		</div>
		<div class="th_box col-lg-1 col-md-12 media_library_box text_center">
			<p class="th_title">media library</p>
		</div>
		<div class="th_box col-lg-2 col-md-12 last_login_box text_center">
			<p class="th_title">last login</p>
		</div>
		<div class="th_box col-lg-1 col-md-12 viewers_box text_center">
			<p class="th_title">viewers</p>
		</div>
		<div class="th_box col-lg-1 col-md-12 traffic_box text_center">
			<p class="th_title">traffic</p>
		</div>
		<div class="th_box col-lg-1 col-md-12 peak_box text_center">
			<p class="th_title">peak</p>
		</div>
		<div class="th_box col-lg-2 col-md-12 options_box text_center">
			<p class="th_title">options</p>
		</div>
	</div>
	
	<?php foreach($channels as $channel) { ?>
	<!-- client 1 start -->
	<div class="table_client-row row">
		<div class="light_background col-lg-2 col-md-12 channel_name_box-client live_label width_20">
			<div class="client_name-box">
				<a href="javascript:targets('<?php echo $channel['appname']?>')"><p>@<?php echo $channel['name']?></p></a>
			</div>
			<div class="client_resolution-labes">
				<div class="resolution-label">
					<div class="label_480p"><img src="/assets/images/480-sd-02.svg"></div>
					<div class="label_720p"><img src="/assets/images/720p-hd-03.svg"></div>
					<div class="label_1080p"><img src="/assets/images/1080p-full-04.svg"></div>
					<div class="label_2kp"><img src="/assets/images/2k-quad-05.svg"></div>
					<div class="label_4kp"><img src="/assets/images/4k-ultra-05.svg"></div>
				</div>
			</div>
			<div class="client_last-stream">
				<?php
				$lsf = "n/a";
				if($channel['last_stream']){
					$ls = strtotime($channel['last_stream']);
					$lsf = date( 'd M Y H:i', $ls );
				}
				?>
				<?php if($channel['stream_id']) { ?>
				<p id="<?php echo $channel['appname']?>_last"><span>Live NOW</span></p>
				<?php } else { ?>
				<p id="<?php echo $channel['appname']?>_last">Last stream on <span><?php echo $lsf; ?></span></p>
				<?php } ?>
			</div>
		</div>
		<div class="col-lg-2 col-md-12 active_targets_box width_13 pad_t5">
			<div id="<?php echo $channel['appname']?>" class="client_active-target-list">
			<?php if($channel['fb_enable']) { ?>
				<?php if($channel['is_streaming']) { 
					if($channel['fb_pid_status']) { ?>
						<div class="active_target-label target_live"><img src="/assets/images/facebook-icon-live.svg"></div>
					<?php } else { ?>
						<div class="active_target-label target_error"><img src="/assets/images/facebook-icon-nofeed.svg"></div>
					<?php } ?>
				<?php } else { ?>
						<div class="active_target-label"><img src="/assets/images/facebook-icon.svg"></div>
				<?php } ?>
			<?php } else { ?>
				<div class="active_target-label target_disable"><img src="/assets/images/facebook-icon.svg"></div>
			<?php } ?>
			
			<?php if($channel['yt_enable']) { ?>
				<?php if($channel['is_streaming']) {
					if($channel['yt_pid_status']) { ?>
						<div class="active_target-label target_live"><img src="/assets/images/youtube-icon-live.svg"></div>
					<?php } else { ?>
						<div class="active_target-label target_nofeed"><img src="/assets/images/youtube-icon-nofeed.svg"></div>
					<?php } ?>
				<?php } else { ?>
						<div class="active_target-label "><img src="/assets/images/youtube-icon.svg"></div>
				<?php } ?>
			<?php } else { ?>
				<div class="active_target-label target_disable"><img src="/assets/images/youtube-icon.svg"></div>
			<?php } ?>
			

			<?php if($channel['is_streaming']) { ?>
					<div class="active_target-label target_live"><img src="/assets/images/www-icon-live.svg"></div>
			<?php } else { ?>
				<div class="active_target-label"><img src="/assets/images/www-icon.svg"></div>
			<?php } ?>

			</div>
		</div>
		<div class="col-lg-1 col-md-12 media_library_box pad_t5">
		<?php if($channel['has_archive']) { ?>
			<div class="client_media_status"><img src="/assets/images/check-gren.png"></div>
		<?php } else { ?>
			<div class="client_media_status"><img src="/assets/images/invalid.png"></div>
		<?php } ?>
		</div>
		<div class="col-lg-2 col-md-12 last_login_box  pad_t5">
			<div class="last_login-client-user">
				<div class="ll_client-user-data">
					<div class="ll_client-user-name">
						<p id="<?php echo $channel['appname']?>_server">Offline</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-1 col-md-12 viewers_box pad_t5">
			<div class="client_data-box">
				<p id="<?php echo $channel['appname']?>_viewers" class="client-data-num">0</p>
				<img src="/assets/images/exchangeup.svg">
			</div>
			<p class="client_data-info">30 days avg</p>
		</div>
		<div class="col-lg-1 col-md-12 traffic_box pad_t5">
			<div class="client_data-box">
				<p class="client-data-num">0 <span>GB</span></p>
			</div>
			<p class="client_data-info">30 days avg</p>
		</div>
		<div class="col-lg-1 col-md-12 peak_box pad_t5">
			<div class="client_data-box">
				<p id="<?php echo $channel['appname']?>_peak" class="client-data-num">0</p>
			</div>
			<p class="client_data-info">all time</p>
		</div>
		<div class="col-lg-2 col-md-12 options_box pad_t5">
			<div class="option_client-btns">
				<button class="option_client-btn"><span class="nav_icon icon-reload"></span></button>
				<button class="option_client-btn"><span class="nav_icon icon-stop"></span></button>
				<button class="option_client-btn"><span class="nav_icon icon-settings"></span></button>
			</div>
		</div>
	</div>
	<!-- client 1 end -->
	<?php } ?>

	<div class="row footer-row">
		<div class="col-lg-2 col-md-12 light_background page_title-tab width_20">
		</div>
	</div>
	
	<form id="targets_form" action="" method="post">
		<input type="hidden" id="appname" name="appname" value=""/>
	</form>
	
</div>

<script>
const interval = setInterval(function() {
   getStats();
}, 1000);

function getStats(){
	$.ajax({
	   type: "GET",
	   url: "/admin/admin.php",
	   data: "action=stats",
	   beforeSend: function(){  },
	   success: function(msg){
			res = JSON.parse(msg);
			var arrayLength = res.channels.length;
			for (var i = 0; i < arrayLength; i++) {
				var channel = res.channels[i];
				var shtml = "";
				if(channel.fb_enable > 0) {
					if(channel.is_streaming > 0) {
						if(channel.fb_pid_status > 0) {
							shtml += '<div class="active_target-label target_live"><img src="/assets/images/facebook-icon-live.svg"></div>';
						} else {
							shtml += '<div class="active_target-label target_error"><img src="/assets/images/facebook-icon-nofeed.svg"></div>';
						}
					} else {
						shtml += '<div class="active_target-label"><img src="/assets/images/facebook-icon.svg"></div>';
					}
				} else {
					shtml += '<div class="active_target-label target_disable"><img src="/assets/images/facebook-icon.svg"></div>';
				}
				
				if(channel.yt_enable > 0) {
					if(channel.is_streaming > 0) {
						if(channel.yt_pid_status > 0) {
							shtml += '<div class="active_target-label target_live"><img src="/assets/images/youtube-icon-live.svg"></div>';
						} else {
							shtml += '<div class="active_target-label target_nofeed"><img src="/assets/images/youtube-icon-nofeed.svg"></div>';
						}
					} else {
						shtml += '<div class="active_target-label "><img src="/assets/images/youtube-icon.svg"></div>';
					}
				} else {
					shtml += '<div class="active_target-label target_disable"><img src="/assets/images/youtube-icon.svg"></div>';
				}
				

				if(channel.is_streaming > 0) {	
						shtml += '<div class="active_target-label target_live"><img src="/assets/images/www-icon-live.svg"></div>';
						var sr = "";
						if(channel.server == "172.26.0.2") sr = "rtmp";

						$("#"+channel.appname+"_server").html(sr);
				} else {
					var sr = "Offline";
					$("#"+channel.appname+"_server").html(sr);
					shtml += '<div class="active_target-label"><img src="/assets/images/www-icon.svg"></div>';
				}

				$("#"+channel.appname).html(shtml);
				if(channel.is_streaming > 0) {
					$("#"+channel.appname+"_last").html("<span>Live NOW</span>");
				} else {
					$("#"+channel.appname+"_last").html("Last stream on <span>"+channel.last_stream+"</span>");
				}				
				
				var arrviewers = JSON.parse( channel.viewers );
				var vtotal = +arrviewers.yt + arrviewers.fb + arrviewers.hls;
				$("#"+channel.appname+"_viewers").html(vtotal);
				$("#"+channel.appname+"_peak").html(channel.peak);
			}
	   }
	});
}

function targets(u){
	$("#appname").val(u);	
	$("#targets_form").submit();
}

$(document).ready(function() {

});
</script>