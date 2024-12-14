<?php
date_default_timezone_set("Europe/Bucharest");
session_start();
include "common.php";

if(empty($_SESSION["user_id"]))
{
	include("login.php");
	exit();
}

$conn = mysqli_connect($host, $username, $password, $dbname);
if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE id="'.$_SESSION["account_id"].'" LIMIT 1');
	$row = mysqli_fetch_assoc($result);
}

if($_POST['section']) {
	$_SESSION["section"] = $_POST['section'];
}


if($_POST['update_fb_api']){
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_api="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');
	exit;
}

if($_POST['update_record']){
	$result=mysqli_query($conn, 'UPDATE accounts SET record="'.$_POST['record'].'" WHERE id="'.$_SESSION["account_id"].'"');
	exit;
}

if($_POST['update_auto_fb']){
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_auto_start="'.$_POST['fb_auto_start'].'" WHERE id="'.$_SESSION["account_id"].'"');
	exit;
}

if($_POST['update_auto_yt']){
	$result=mysqli_query($conn, 'UPDATE accounts SET yt_auto_start="'.$_POST['yt_auto_start'].'" WHERE id="'.$_SESSION["account_id"].'"');
	exit;
}

if($_POST['update_auto_hls']){
	$result=mysqli_query($conn, 'UPDATE accounts SET hls_auto_start="'.$_POST['hls_auto_start'].'" WHERE id="'.$_SESSION["account_id"].'"');
	exit;
}

if($_POST['update_yt_key']){
	$result=mysqli_query($conn, 'UPDATE accounts SET yt_key="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['update_yt_ch_id']){
	$result=mysqli_query($conn, 'UPDATE accounts SET yt_ch_id="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');
}
			
if($_POST['fb_page_id'] && $_POST['fb_page_linked'] && $_POST['fb_page_token']) {
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_page_id="'.$_POST['fb_page_id'].'", fb_page_linked="'.$_POST['fb_page_linked'].'", fb_page_token="'.$_POST['fb_page_token'].'" WHERE id="'.$_SESSION["account_id"].'"');	
}
		
if($_POST['fb_page_unlink'] == 1) {
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_page_id="", fb_page_linked="", fb_page_token="" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['fb_logout'] == 1) {
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_user_token="", fb_page_id="", fb_page_linked="", fb_page_token="" WHERE id="'.$_SESSION["account_id"].'"');
}
	
if($_POST['update_fb_title']) {
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_title="'.$_POST['val'].'"  WHERE id="'.$_SESSION["account_id"].'"');	
}

if($_POST['update_fb_description']) {
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_descr="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');	
}

if($_POST['update_fb_manual_key']){
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_manual_key="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['fb_enable'] == 'activ'){
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_enable="1" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['fb_enable'] == 'inactiv'){
	$result=mysqli_query($conn, 'UPDATE accounts SET fb_enable="0" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['yt_enable'] == 'activ'){
	$result=mysqli_query($conn, 'UPDATE accounts SET yt_enable="1" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['yt_enable'] == 'inactiv'){
	$result=mysqli_query($conn, 'UPDATE accounts SET yt_enable="0" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['ig_enable'] == 'activ'){
	$result=mysqli_query($conn, 'UPDATE accounts SET ig_enable="1" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['ig_enable'] == 'inactiv'){
	$result=mysqli_query($conn, 'UPDATE accounts SET ig_enable="0" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['update_ig_url']){
	$result=mysqli_query($conn, 'UPDATE accounts SET ig_url="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['update_ig_key']){
	$result=mysqli_query($conn, 'UPDATE accounts SET ig_key="'.base64_decode($_POST['val']).'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['update_hls_title']) {
	$result=mysqli_query($conn, 'UPDATE accounts SET hls_title="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');	
}

if($_POST['update_hls_description']) {
	$result=mysqli_query($conn, 'UPDATE accounts SET hls_description="'.$_POST['val'].'" WHERE id="'.$_SESSION["account_id"].'"');	
}
	
if($_POST['fb_start_stop'] == 'start' && $row['is_streaming']) {
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
		file_put_contents("/var/livestream/auth/logs/fb/".$row['appname'].time().".txt", print_r($output, true));
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
	if($responseObj->fb_pid) $result = mysqli_query($conn, 'UPDATE accounts SET fb_pid="'.$responseObj->fb_pid.'", fb_stream_id="'.$fbObj->id.'" WHERE id="'.$_SESSION["account_id"].'"');
}
	
if($_POST['fb_start_stop'] == 'stop') {
	if($row['fb_stream_id'] && $row['fb_api']){
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
	
	$post = [
		'action' => 'done_fb',
		'name' => $row['appname'],
		'fb_pid' => $row['fb_pid']
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
	if($responseObj->status == "ok") $result = mysqli_query($conn, 'UPDATE accounts SET fb_pid=0, fb_pid_status=0, fb_stream_id="" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['yt_start_stop'] == 'start' && $row['yt_key'] && $row['is_streaming']) {
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
	if($responseObj->yt_pid) $result = mysqli_query($conn, 'UPDATE accounts SET yt_pid="'.$responseObj->yt_pid.'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['yt_start_stop'] == 'stop' && $row['yt_pid']) {
	$post = [
		'action' => 'done_yt',
		'name' => $row['appname'],
		'yt_pid' => $row['yt_pid']
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
	
	if($responseObj->status == "ok") $result = mysqli_query($conn, 'UPDATE accounts SET yt_pid=0, yt_pid_status=0 WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['ig_start_stop'] == 'start' && $row['ig_key'] && $row['is_streaming']) {
	$post = [
		'action' => 'publish_ig',
		'name' => $row['appname'],
		'ig_url' => $row['ig_url'],
		'ig_key' => $row['ig_key']
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
	if($responseObj->ig_pid) $result = mysqli_query($conn, 'UPDATE accounts SET ig_pid="'.$responseObj->ig_pid.'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['ig_start_stop'] == 'stop' && $row['ig_pid']) {
	$post = [
		'action' => 'done_ig',
		'name' => $row['appname'],
		'ig_pid' => $row['ig_pid']
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
	
	if($responseObj->status == "ok") $result = mysqli_query($conn, 'UPDATE accounts SET ig_pid=0, ig_pid_status=0 WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['hls_start_stop'] == 'start' && $row['is_streaming']) {
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
	if($responseObj->hls_pid) $result = mysqli_query($conn, 'UPDATE accounts SET hls_pid="'.$responseObj->hls_pid.'" WHERE id="'.$_SESSION["account_id"].'"');
}

if($_POST['hls_start_stop'] == 'stop' && $row['hls_pid']) {
	$post = [
		'action' => 'done_hls',
		'name' => $row['appname'],
		'hls_pid' => $row['hls_pid']
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
	if($responseObj->status == "ok") $result = mysqli_query($conn, 'UPDATE accounts SET hls_pid=0 WHERE id="'.$_SESSION["account_id"].'"');
}


if($_POST['profile_title']){
	$result=mysqli_query($conn, 'UPDATE users SET full_name="'.$_POST['profile_title'].'" WHERE user_id="'.$_SESSION["user_id"].'"');
}

if(isset($_FILES['profile_icon']) && $_FILES['profile_icon']['error'] === UPLOAD_ERR_OK){
	$fileTmpPath = $_FILES['profile_icon']['tmp_name'];
	$fileName = $_FILES['profile_icon']['name'];
	$fileSize = $_FILES['profile_icon']['size'];
	$fileType = $_FILES['profile_icon']['type'];
	$fileNameCmps = explode(".", $fileName);
	$fileExtension = strtolower(end($fileNameCmps));
	$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
		
	$uploadFileDir = '/var/livestream/auth/images/icons/';
	$dest_path = $uploadFileDir . $newFileName;
	
	$allowedfileExtensions = array('jpg', 'gif', 'png');

	if (in_array($fileExtension, $allowedfileExtensions)) {
		if(move_uploaded_file($fileTmpPath, $dest_path)){
		  $result=mysqli_query($conn, 'UPDATE users SET user_icon="'.$newFileName.'" WHERE user_id="'.$_SESSION["user_id"].'"');
		  header("Refresh:0");
		}
	}
}


if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM users WHERE user_id="'.$_SESSION["user_id"].'" LIMIT 1');
	$user = mysqli_fetch_assoc($result);
}

if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE id="'.$_SESSION["account_id"].'" LIMIT 1');
	$row = mysqli_fetch_assoc($result);
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Martian+Mono:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fonts/pixelins-style.css">
<!-- CDN chats views -->
	<script src="/js/am/apexcharts.js"></script>
	<!-- CDN map -->
	<script src="/js/am/index.js"></script>
<script src="/js/am/map.js"></script>
<script src="/js/am/worldLow.js"></script>
<script src="/js/am/Animated.js"></script>



    <title>LIVE Maghost!</title>
    <link rel="stylesheet" href="https://use.typekit.net/cmt1jlz.css">
  </head>
  <body>
  	<div class="wrapper">

		<!-- Sidebar -->
		<nav id="sidebar_container" class="col-lg-2">
			<div class="wrapper">
				<!-- Sidebar -->
				<nav id="sidebar">
					<div class="sidebar-header">
						<div class="side-logo">
							<a href="#"><img src="assets/images/logo-app.png" alt=""></a>
						</div>
					</div>
					<div class="channel_name">
						<p>@<?=$row["name"]?></p>
					</div>
					<div class="sidebar_menus">
						<div class="sidenar_megamenu">
							<ul class="list-unstyled components">
								<li>
									<a href="javascript:menu('dashboard');"><span class="nav_icon icon-template"></span>Dashboard</a>
								</li>
								<li>
									<a href="javascript:menu('targets');"><span class="nav_icon icon-live"></span>Targets</a>
								</li>
								<li>
									<a href="javascript:menu('media');"><span class="nav_icon icon-folder-cloud"></span>Media Content</a>
								</li>
								<li>
									<a href="javascript:menu('codes');"><span class="nav_icon icon-coding"></span>Codes</a>
								</li>
								<!--
								<li>
									<a href="javascript:menu('analytics');"><span class="nav_icon icon-analytic-display"></span>Analytics</a>
								</li>
								-->
							</ul>
							<?php if($_SESSION['admin_section']) { ?>
							<ul class="sidebar_settings list-unstyled components">
								<li>
									<a href="/admin"><span class="nav_icon icon-settings"></span>Admin</a>
								</li>
							</ul>
							<?php } ?>
							
						</div>
						<ul class="sidebar_settings list-unstyled components">
							<li>
								<a href="<?php echo $baseurl;?>/logout.php"><span class="nav_icon icon-turn-off	"></span>Log out</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</nav>
		
		<form id="menu" action="" method="post">
			<input type="hidden" id="section" name="section" value=""/>
		</form>
		<script>
		function menu(page){
			$("#section").val(page);
			$("#menu").submit();
		}
		</script>

		<!-- Page Content -->
		<div id="content" class="col-lg-9">
			<!-- <div id="content">
				<nav class="navbar navbar-expand-lg navbar-light bg-light">
					<div class="container-fluid">

						<button type="button" id="sidebarCollapse" class="btn btn-info">
							<i class="fas fa-align-left"></i>
							<span>Toggle Sidebar</span>
						</button>

					</div>
				</nav> 
			</div> -->
			
			<?php
			include($_SESSION["section"].".php");
			?>
		</div>
		<div class="right_sidebar col-lg-1">
			<div class="right_sidebar-content">
				<div class="rsidebar-user_pic">
					<div class="rsidebar_user-img">
						<?php if($user['user_icon']) { ?>
						<img src="images/icons/<?php echo $user['user_icon'];?>">
						<?php } else { ?>
						<img src="assets/images/icon-user.png">
						<?php } ?>
						<div class="user-online"></div>
					</div>
					<p class="rsidebar_user-name"><?=$_SESSION["user_name"]?></p>
				</div>
				<div class="right_sidebar-menu">
					<div class="rsidebar_edit">
						<a href="#edit_field" data-toggle="modal" data-target="#edit_field"><span class="nav_icon icon-to-do-edit2"></span></a>
					</div>
					<div class="rsidebar_add">
						<a href="#modal_field" data-toggle="modal" data-target="#modal_field"><img src="assets/images/icon-add.png"></a>
					</div>
				</div>
			</div>
		</div>
	</div>       
	
	<div id="modal_field" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">Users for <?php echo $row[name];?></h5>
				</div>
				
				<form id="modal_form" action="" method="post" enctype="multipart/form-data">
					<div class="modal-body">
					
						<div class="row">
							
						</div>
												
						<input type="hidden" id="c_id" name="c_id" value=""/>
						<input type="hidden" id="ref" name="ref" value="contacts"/>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
						<button id="submit" type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="edit_field" class="modal fade edit_user-modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<div class="modal-header">
					<h5 class="modal-title">Change your profile</h5>
					
					
				</div>
				
				<form id="edit_form" action="" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="modal_content">
							<div class="modal_user-img-container">
								<div class="modal_user-img-title"><p>Profile image</p></div>
								<div class="modal_user-img-content">
									<div class="modal_user-img-box">
										<div class="modal_user-img">
											<label for="" class="upload-button">
												<?php if($user['user_icon']) { ?>
												<img class="profile-pic" src="images/icons/<?php echo $user['user_icon'];?>">
												<?php } else { ?>
												<img class="profile-pic" src="assets/images/deafault-user.jpg">
												<?php } ?>
											</label>
											<input type="file" id="icon" name="profile_icon" class="file-upload"></input>
										</div>
									</div>
								</div>
							</div>
							<div class="modal_user-name-container">
								<div class="modal_user-name-txt">
									<p>Your new profile name</p>
								</div>
								<div class="modal_user-input-name">
									<span class="modal_user-input-symbol">@</span><input type="text" name="profile_title" value="<?php echo $user[full_name];?>">
								</div>
							</div>
						</div>

					<div class="modal-footer">
						
						<div class="modal_buttons">
							<div class="modal_buttons-container">
								<div class="modal_buttons-cancel modal_button">
										<button type="button" class="close" data-dismiss="modal">Cancel</button> 
								</div>
								<div class="modal_buttons-save modal_button">
									<button type="submit" >Save</button>
								</div>
							</div>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
<script>
	$(document).ready(function() {

    
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    

    $(".file-upload").on('change', function(){
        readURL(this);
    });
    
    $(".upload-button").on('click', function() {
       $(".file-upload").click();
    });
});
</script>
  </body>
</html>
