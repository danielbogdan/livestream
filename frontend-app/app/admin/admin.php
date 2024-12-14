<?php
session_start();
include "../common.php";

if (empty($_SESSION["user_id"]))
{
	include("login.php");
	exit();
}

$conn = mysqli_connect($host, $username, $password, $dbname);


if(isset($_POST['appname'])) {
	if ($conn) {
		$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE appname="'.$_POST['appname'].'" LIMIT 1');
		$res = mysqli_fetch_assoc($result);
		
		$_SESSION["account_id"] = $res["id"];
		$_SESSION["account_name"] = $res["name"];
		$_SESSION["appname"] = $res["appname"];
		$_SESSION["apphash"] = $res["apphash"];
		$_SESSION["idhash"] = $res["idhash"];
		$_SESSION["section"] = 'dashboard';
				
		header("location: https://".$_SERVER['HTTP_HOST']);
	}
}


if(isset($_GET['action']) && $_GET['action'] == 'stats'){
	if($conn){
		$channels = array();
		$result = mysqli_query($conn, 'SELECT * FROM accounts where name <> "admin"');
		while ($row = mysqli_fetch_assoc($result)) {
		  $row['viewers'] = json_encode(unserialize($row['viewers']));
		  $stream = mysqli_query($conn, 'SELECT * FROM streams WHERE appname="'.$row['appname'].'" ORDER BY id DESC LIMIT 1');
		  $row2 = mysqli_fetch_assoc($stream);
		  $row['peak'] = $row2['hls_max'] + $row2['yt_max'];
		  $ls = strtotime($row2['stream_date']);
		  $row['last_stream'] = date( 'd M Y H:i', $ls );
		  $channels[] = $row;
		}
	}
	$res['channels'] = $channels;
	echo json_encode($res);
	exit;
}


if ($conn) {
	$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE id="'.$_SESSION["account_id"].'" LIMIT 1');
	$row = mysqli_fetch_assoc($result);
}

if(isset($_POST['section'])) {
	$_SESSION["admin_section"] = $_POST['section'];
}


if ($conn) {
	$result = mysqli_query($conn, 'SELECT accounts.*, users.* FROM accounts
								LEFT JOIN users on accounts.id = users.account_id
								WHERE users.user_id="'.$_SESSION["user_id"].'" LIMIT 1');
	$row = mysqli_fetch_assoc($result);
}

if ($_SESSION["a_lvl"] < 2) header("location: https://".$_SERVER['HTTP_HOST']);
if (empty($_SESSION["admin_section"])) $_SESSION["admin_section"] = 'admin_channels';
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
	<link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/fonts/pixelins-style.css">

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
							<a href="#"><img src="/assets/images/logo-app.png" alt=""></a>
						</div>
					</div>
					<div class="channel_name">
						<p>Admin Channel</p>
					</div>
					<div class="sidebar_menus">
						<div class="sidenar_megamenu">
							<ul class="list-unstyled components">
								<li>
									<a href="javascript:menu('admin_channels');"><span class="nav_icon icon-template"></span>Channels</a>
								</li>
								<li>
									<a href="javascript:menu('admin_users');"><span class="nav_icon icon-live"></span>User Management</a>
								</li>
								<li>
									<a href="javascript:menu('admin_media');"><span class="nav_icon icon-folder-cloud"></span>Media Content</a>
								</li>
								<li>
									<a href="javascript:menu('admin_codes');"><span class="nav_icon icon-coding"></span>Codes</a>
								</li>
								<!--
								<li>
									<a href="javascript:menu('analytics');"><span class="nav_icon icon-analytic-display"></span>Analytics</a>
								</li>
								-->
							</ul>
							<!--
							<ul class="sidebar_settings list-unstyled components">
								<li>
									<a href="javascript:menu('settings');"><span class="nav_icon icon-settings"></span>Settings</a>
								</li>
								<li>
									<a href="javascript:menu('help');"><span class="nav_icon icon-question"></span>Help</a>
								</li>
							</ul>
							-->
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
			include($_SESSION["admin_section"].".php");
			?>
		</div>
		<div class="right_sidebar col-lg-1">
			<div class="right_sidebar-content">
				<div class="rsidebar-user_pic">
					<div class="rsidebar_user-img">
						<?php if($row['user_icon']) { ?>
						<img src="images/icons/<?php echo $row['user_icon'];?>">
						<?php } else { ?>
						<img src="/assets/images/icon-user.png">
						<?php } ?>
						<div class="user-online"></div>
					</div>
					<p class="rsidebar_user-name"><?=$row["user_name"]?></p>
				</div>
				<div class="right_sidebar-menu">
					<div class="rsidebar_edit">
						<a href="#edit_field" data-toggle="modal" data-target="#edit_field"><span class="nav_icon icon-to-do-edit2"></span></a>
					</div>
					<div class="rsidebar_add">
						<a href="#modal_field" data-toggle="modal" data-target="#modal_field"><img src="/assets/images/icon-add.png"></a>
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
												<?php if($row['user_icon']) { ?>
												<img class="profile-pic" src="images/icons/<?php echo $row['user_icon'];?>">
												<?php } else { ?>
												<img class="profile-pic" src="/assets/images/deafault-user.jpg">
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
									<span class="modal_user-input-symbol">@</span><input type="text" name="profile_title" value="<?php echo $row[full_name];?>">
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


});
</script>
  </body>
</html>
