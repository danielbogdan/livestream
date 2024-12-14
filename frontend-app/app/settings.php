<?php

try
{
        $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
        http_response_code(401);
        trigger_error($e->getMessage());
        die("Database error!");
}

if (empty($_SESSION["username"])) {
        header("location: login.html");
}
$uname = $_SESSION["username"];

try
{
        $sth = $dbh->prepare("SELECT * FROM ".$usertablename." WHERE username = :username");
        $sth->execute([':username' => $uname ]);
        $row = $sth->fetch();

} catch(PDOException $e)
{
        http_response_code(401);
        trigger_error($e->getMessage());
        die("Database error!");
} 


?>
<div class="page-content  settings_page">
	<div class="row">
		<div class="col-lg-5 left_section">
			<div class="left_section-container">
				<div class="live-preview-section">
					<div class="lp_title-box">
						<p class="lp_title">Push to server & Encoder details</p>
					</div>
				
				</div>
				<div class="ls_server-data">
					<div class="ls_server-data-top">
						<div class="ls_information-container">
							<div class="uppercase_title-sm">
								<p>Server URL</p>
							</div>
							<div class="ls_information-input-container">
								<input type="text" class="input_code" value="<?php echo $streamurl ."transcoder?key=".$row["apphash"];?>" id="myInput1" disabled>
								<button onclick="copyCodeFunc()" class="btn_input"><span class="nav_icon icon-file-copy"></span></button>
							</div>
						</div>
						<div class="ls_information-container">
							<div class="uppercase_title-sm">
								<p>Stream key</p>
							</div>
							<div class="ls_information-input-container">
								<input type="text" class="input_code" value="<?php echo $uname."?key=".$row["idhash"];?>" id="myInput2" disabled>
								<button onclick="copyCodeFunc2()" class="btn_input"><span class="nav_icon icon-file-copy"></span></button>
							</div>
						</div>
					</div>
					<div class="ls_check-btn-container">
						<label class="toggle" for="uniqueID">
							<input type="checkbox" class="toggle__input" id="uniqueID" />
							<span class="toggle-track">
								<span class="toggle-indicator">
									<!-- 	This check mark is optional	 -->
									<span class="checkMark">
										<svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
											<path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
										</svg>
									</span>
								</span>
							</span>
							<p class="ls_check-btn-text">Record your stream automatically</p>
						</label>
					</div>
					<div class="ls_stream-data-details">
						<div class="information-container">
							<div class="uppercase_title-sm">
								<p>Title for live feed</p>
							</div>
							<div class="information-input-container">
								<input type="text" class="input_code" value="<page-title>" id="myInput">
							</div>
						</div>
						<div class="information-container">
							<div class="uppercase_title-sm">
								<p>Description for Live feed</p>
							</div>
							<div class="information-input-container">
								<input type="text" class="input_code" value="Transmisiune Live" id="myInput">
							</div>
						</div>
					</div>
					<div class="ls_stream_targets">
						<div class="lp_title-box">
						<p class="lp_title">Stream targets activation</p>
					</div>
						<div class="stream-targets-description">
							<p>Your videos blessed a huge number of people. Just for your records, since we collect this, info here is the total number of people who saw you.</p>
						</div>
						<div class="ls_stream-targets-container">
							<div class="ls_stream-targets-list row">
								<div class="ls_stream-target col-lg-4">
									<label class="stream_target">
										<input type="checkbox" name="" id="fb_check" <?php if($row['fb_enable'] == "1") echo "checked"; ?>>
										<div class="stream_target-box">	
											<p>Stream to</p>
											<img src="assets/images/stream-target-facebook.png">
										</div>
									</label>
								</div>
								<div class="ls_stream-target col-lg-4">
									<label class="stream_target">
										<input type="checkbox" name="" id="yt_check" <?php if($row['yt_enable'] == "1") echo "checked"; ?>>
										<div class="stream_target-box">	
											<p>Stream to</p>
											<img src="assets/images/stream_target-yt.png">
										</div>
									</label>
								</div>
								<div class="ls_stream-target available_soon col-lg-4">
									<label class="stream_target">
										<input type="checkbox" name="">
										<div class="stream_target-box">	
											<p>Stream to</p>
											<img src="assets/images/Instagram_logo.png">
										</div>
									</label>
									<span>*available soon</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="col-lg-6 right_section">
			<div class="rs_container">
				<div class="rs_title-main">
					<p>Stream Target settings</p>
				</div>
				<div class="rs-stream-targets-list">
					<div  class="rs_stream-target fb_content-stream-target <?php if($row['fb_enable'] == "1") echo "active"; ?>">
						<div class="stream_target-top">
							<div class="stt_info">
								<div class="stt-title stt-title-wimg">
									<p>Stream to <img class="img_stream-to" src="assets/images/stream-target-facebook.png"></p>
								</div>	
								<p class="stt-info-details">Connect your Facebook page by logging in using the button bellow. Once connected, approve all the required rights are you are ready to stream.</p>
							</div>
							<div class="stt_btn">
								<div class="custom-selects custom-select-yt Offline">
								  <select id="ytstreamStatus" class="streamselect">
									<option value="1" class="OnBit">OnBit</option>
									<option value="1" class="OnBit">OnBit</option>
									<option value="0" class="Offline" selected="">Offline</option>
								  </select>
								<div class="select-selected ytstreamStatus">Offline</div><div class="select-items select-hide"><div>OnBit</div><div>Offline</div></div></div>
							</div>
						</div>
						<div class="stream_target-btm">
							<div class="tabs_section">
				<!-- Tabs -->
					<section id="tabs">
						<div class="container_tabs rs_tabs">
							<div class="row-tabs" style="width:100%">
								<div class="" style="width:100%">
									<nav class="mynav">
										<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
											<a class="nav-item nav-link active" id="nav-linked-tab" data-toggle="tab" href="#nav-linked" role="tab" aria-controls="nav-linked" aria-selected="true">Linked. Automatic</a>
											<a class="nav-item nav-link" id="nav-manual-tab" data-toggle="tab" href="#nav-manual" role="tab" aria-controls="nav-manual" aria-selected="false">Manual. Human actions</a>
										</div>
									</nav>
									<span class="target"></span>
									<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
										<div class="tab-pane fade active show" id="nav-linked" role="tabpanel" aria-labelledby="nav-linked-tab">
											<div class="live_stats-list">
												<div class="uppercase_title-sm">
													<p>Connected with page</p>
												</div>

												<div class="tabs_section-content">
													<div class="rs__fb-page-info-section">
														<div class="rs__fb-page-info-container">
															<a href="#">
																<div class="rs_fb-page-avatar">
																	<img src="assets/images/elim-logi.png">
																	<p class="rs_fb-page-name">Biserica Penticostală Elim</p>
																</div>
																<button class="rs_page-logout-action"><span class="icon-cloud-error nav_icon"></span></button>
															</a>
														</div>
													</div>
													<div class="ls_check-btn-container">
														<label class="toggle" for="fbPublish">
															<input type="checkbox" class="toggle__input" id="fbPublish" />
															<span class="toggle-track">
																<span class="toggle-indicator">
																	<!-- 	This check mark is optional	 -->
																	<span class="checkMark">
																		<svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
																			<path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
																		</svg>
																	</span>
																</span>
															</span>
															<p class="ls_check-btn-text">Publish instantly once stream has started</p>
														</label>
													</div>
												</div>
												<div class="rs__social-details row">
													<div class="rs__social-details-title col-lg-6">
														<div class="uppercase_title-sm">
															<p>Title for facebook</p>
														</div>													
														<div class="information-input-container">
															<input type="text" class="input_code" value="<page-title>" >
														</div>
													</div>
													<div class="rs__social-details-title col-lg-6">
														<div class="uppercase_title-sm">
															<p>Description for Facebook</p>
														</div>													
														<div class="information-input-container">
															<input type="text" class="input_code" value="Transmisiune Live">
														</div>
													</div>
													
												</div>
											</div>
										</div>
										<div class="tab-pane fade " id="nav-manual" role="tabpanel" aria-labelledby="nav-manual-tab">
											<div class="live_stats-list manual_facebook-tab">
												<div class="stt_info">
													<p class="stt-info-details">Connect your Facebook page by logging in using the button bellow. Once connected, approve all the required rights are you are ready to stream.</p>
												</div>

												<div class="rs__social-details row">
													<div class="rs__social-details-title col-lg-6">
														<div class="uppercase_title-sm">
															<p>Server Url</p>
														</div>													
														<div class="information-input-container">
															<input type="text" class="input_code" value="<page-title>" >
														</div>
													</div>
													<div class="rs__social-details-title col-lg-6">
														<div class="uppercase_title-sm">
															<p>Stream Key</p>
														</div>													
														<div class="information-input-container">
															<input type="text" class="input_code" value="Transmisiune Live">
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								
								</div>
							</div>
						</div>
					</section>
					<!-- ./Tabs -->
				</div>
						</div>
					</div>

				<div class="rs-stream-targets-list yt_content-stream-target <?php if($row['yt_enable'] == "1") echo "active"; ?>">
					<div  class="rs_stream-target">
						<div class="stream_target-top">
							<div class="stt_info">
								<div class="stt-title stt-title-wimg">
									<p>Stream to <img src="assets/images/stream_target-yt2.png" class="img_stream-to"></p>
								</div>	
								<p class="stt-info-details">Fill all the requested information based on you YouTube channel. Pay attention when you copy and paste to have all thee information precisely without any space.</p>
							</div>
							<div class="stt_btn">
								<div class="custom-selects custom-select-yt OnBit">
								  <select id="ytstreamStatus" class="streamselect">
									<option value="1" class="OnBit">OnBit</option>
									<option value="1" class="OnBit">OnBit</option>
									<option value="0" class="Offline" selected="">Offline</option>
								  </select>
								<div class="select-selected ytstreamStatus">OnBit</div><div class="select-items select-hide"><div>OnBit</div><div>Offline</div></div></div>
							</div>
						</div>
						<div class="stream_target-btm">
							<div class="rs__social-details row">
								<div class="rs__social-details-title col-lg-6">
									<div class="uppercase_title-sm">
										<p>Stream key</p>
									</div>													
									<div class="information-input-container">
										<input type="text" class="input_code" value="w9mz-234b-mt08-xkej-eff3" >
									</div>
								</div>
								<div class="rs__social-details-title col-lg-6">
									<div class="uppercase_title-sm">
										<p>Default stream url</p>
									</div>													
									<div class="information-input-container">
										<input type="text" class="input_code" value="rtmp://a.rtmp.youtube.com/live2">
									</div>
								</div>
								
							</div>
							<div class="rs__social-details row">
								<div class="rs__social-details-title col-lg-12">
									<div class="uppercase_title-sm">
										<p>Channel ID</p>
									</div>													
									<div class="information-input-container">
										<input type="text" class="input_code" value="UCj_dFco9DygyrMMnEwTiuMQ" >
									</div>
								</div>
								
							</div>
							<div class="ls_check-btn-container">
								<label class="toggle" for="ytPublish">
									<input type="checkbox" class="toggle__input" id="ytPublish" />
									<span class="toggle-track">
										<span class="toggle-indicator">
											<!-- 	This check mark is optional	 -->
											<span class="checkMark">
												<svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
													<path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
												</svg>
											</span>
										</span>
									</span>
									<p class="ls_check-btn-text">Publish instantly once stream has started</p>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<!-- page content OLD -->
<div class="page-content" style="margin-top:40px">
	<div class="row">
		<div class="col-lg-12 left_section">
			<div class="left_section-container">
				
				<div>
					<?php 
					echo "<br>Server URL: " . $streamurl ."" . $row["apphash"]. "transcoder<br>";
					echo "Stream Key: " . $uname."?key=".$row["idhash"];
					?>
				</div>
				<form id="rec_form" action="" method="post">
					<div>
						<input type="hidden" name="record" value="0"/>
						<input type="checkbox" id="record" name="record" value="1" <?php if($row['record'] == "1") echo "checked"; ?>/>
						<input type="hidden" name="update_record" value="1"/>
						<label for="record">Record stream</label>
					</div>
				</form>
				<form id="facebook_title" action="" method="post">
					<label for="fb_title">Live title:</label><br>
					<input type="text" id="fb_title" name="fb_title" value="<?php echo $row['fb_title']?>"><br>
					<label for="fb_descr">Live Description:</label><br>
					<input type="text" id="fb_descr" name="fb_descr" value="<?php echo $row['fb_descr']?>"><br>
					<input type="hidden" name="up_settings" value="1">
					<input type="submit" value="Save"/>
				</form>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-lg-12 left_section">
			<div class="left_section-container">
				
				<div>
					<b>FACEBOOK</b>
				</div>
				<?php if($row['fb_page_token'] == "") { ?>
				  <div id="link_list" style="display:none;">
					<label for="page">Select Facebook Page:</label>
					<select name="link_page" id="link_page">
						<option></option>
					</select>
					<br>
					<a href="javascript:link();">Link selected page</a>
					<br><br><br>
				  </div>
				  <div id="login_btn">
					Log In to Facebook and choose page:
					 <fb:login-button 
					  scope="public_profile,email,pages_read_engagement,pages_manage_posts"
					  onlogin="checkLoginState();">
					</fb:login-button>
				  </div>
				  <div id="logout_btn" style="display:none;">
					<a href="javascript:logout();">Log Out</a>
				  </div>

				<?php } else { ?>
					<div style="color:green;">Current linked page:</div>
					<span><b><?=$row['fb_page_linked']?></b></span>
				<?php } ?>
				<br>
				<form id="auto_fb_form" action="" method="post">
					<div>
						<input type="hidden" name="fb_auto_start" value="0"/>
						<input type="checkbox" id="fb_auto_start" name="fb_auto_start" value="1" <?php if($row['fb_auto_start'] == "1") echo "checked"; ?>/>
						<input type="hidden" name="update_auto_fb" value="1"/>
						<label for="fb_auto_start">Auto publish when streaming starts</label>
					</div>
				</form>
				
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-lg-12 left_section">
			<div class="left_section-container">
				
				<div>
					<b>YOUTUBE</b>
				</div>
				<form id="yt_key_form" action="" method="post">
					<label for="yt_key">Stream key:</label><br>
					<input type="text" id="yt_key" name="yt_key" value="<?php echo $row['yt_key']?>"><br>
					<input type="hidden" name="update_yt_key" value="1">
					<input type="submit" value="Save"/>
				</form>
				<form id="auto_yt_form" action="" method="post">
					<div>
						<input type="hidden" name="yt_auto_start" value="0"/>
						<input type="checkbox" id="yt_auto_start" name="yt_auto_start" value="1" <?php if($row['yt_auto_start'] == "1") echo "checked"; ?>/>
						<label for="yt_auto_start">Auto publish when streaming starts</label>
						<input type="hidden" name="update_auto_yt" value="1"/>
					</div>
				</form>
				
			</div>
		</div>
	</div>
	<form id="facebook_form" action="" method="post">
	    <input type="hidden" id="fb_page_id" name="fb_page_id" value="">
		<input type="hidden" id="fb_page_linked" name="fb_page_linked" value="">
		<input type="hidden" id="fb_page_token" name="fb_page_token" value="">
		<input type="hidden" id="fb_page_unlink" name="fb_page_unlink" value="">
		<input type="hidden" id="fb_logout" name="fb_logout" value="">
	</form>
</div>

<script>
$(document).ready(function() {
	$('#record').change(function() {
		$('#rec_form').submit();
	});
	$('#fb_auto_start').change(function() {
		$('#auto_fb_form').submit();
	});
	$('#yt_auto_start').change(function() {
		$('#auto_yt_form').submit();
	});	
});

var tokens = [];
var acc_token = "";

window.fbAsyncInit = function() {
FB.init({
  appId      : '677514669868733',
  cookie     : true,
  xfbml      : true,
  version    : 'v13.0'
});
  
FB.getLoginStatus(function(response) {
	statusChangeCallback(response);
});
  
};
  
function statusChangeCallback(response){
	if(response.status === 'connected' ) {
		console.log('Logged in');
		$('#login_btn').hide();
		$('#logout_btn').show();
		get_token(response.authResponse.accessToken, response.authResponse.userID);
	} else {
		console.log('Not logged in');
		$('#login_btn').show();
		$('#logout_btn').hide();
	}
};
  
function checkLoginState() {
  FB.getLoginStatus(function(response) {
	statusChangeCallback(response);
  });
};

function get_token(token, user) {
	
	$.ajax({
	   type: "POST",
	   url: "/api_get_token.php",
	   data: "token="+token+"&user="+user,
	   beforeSend: function(){  },
	   success: function(msg){
			res = JSON.parse(msg);
			$("#link_page").html(res.html);
			$("#link_list").show();
	   }
	});
}


	  
function logout() {
	FB.logout(function(response) {
		$('#fb_logout').val(1);
		$("#facebook_form").submit();
	});
};
	
function link(){
	var id = $('#link_page').val();
	var name = $( "#link_page option:selected" ).text();
	var token = $( "#link_page option:selected" ).data('token');

	$('#fb_page_id').val(id);
	$('#fb_page_linked').val(name);
	$('#fb_page_token').val(token);
	
	$("#facebook_form").submit();
}

function unlink(){
	console.log("unlink");
	$('#fb_page_unlink').val(1);
	$("#facebook_form").submit();
}


(function(d, s, id){
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement(s); js.id = id;
 js.src = "https://connect.facebook.net/en_US/sdk.js";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>


<script>
	function copyCodeFunc() {
  // Get the text field
  var copyText = document.getElementById("myInput1");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert("Copied the text: " + copyText.value);
}
	function copyCodeFunc2() {
  // Get the text field
  var copyText = document.getElementById("myInput2");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert("Copied the text: " + copyText.value);
}
</script>
<script>
$('#fb_check').change(function(){
    if($(this).is(":checked")) {
        $('.fb_content-stream-target').addClass("active");
		$.ajax({
		   type: "POST",
		   url: "/index.php",
		   data: "fb_enable=activ",
		   beforeSend: function(){  },
		   success: function(msg){
		   }
		});
    } else {
        $('.fb_content-stream-target').removeClass("active");
		$.ajax({
		   type: "POST",
		   url: "/index.php",
		   data: "fb_enable=inactiv",
		   beforeSend: function(){  },
		   success: function(msg){
		   }
		});
    }
});

$('#yt_check').change(function(){
    if($(this).is(":checked")) {
        $('.yt_content-stream-target').addClass("active");
		$.ajax({
		   type: "POST",
		   url: "/index.php",
		   data: "yt_enable=activ",
		   beforeSend: function(){  },
		   success: function(msg){
		   }
		});
    } else {
        $('.yt_content-stream-target').removeClass("active");
		$.ajax({
		   type: "POST",
		   url: "/index.php",
		   data: "yt_enable=inactiv",
		   beforeSend: function(){  },
		   success: function(msg){
		   }
		});
    }
});

</script>