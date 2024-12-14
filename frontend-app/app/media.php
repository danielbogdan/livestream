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

if (empty($_SESSION["account_id"])) {
        header("location: login.php");
}

try
{
        $sth = $dbh->prepare("SELECT * FROM ".$usertablename." WHERE id = :id");
        $sth->execute([':id' => $_SESSION["account_id"] ]);
        $row = $sth->fetch();

} catch(PDOException $e)
{
        http_response_code(401);
        trigger_error($e->getMessage());
        die("Database error!");
}


?>

<div class="page-content">
	<div class="row">
		<div class="col-lg-12 left_section">
			<?php if($row['has_archive']) { ?>
			<iframe id="iframe" src="https://arhiva.stream.maghost.ro/elim" style="height:2048px;width:100%;" allow="fullscreen"></iframe>
			<?php } else { ?>
			<div class="left_section-container">
			<p>Media archive is not enabled for this user. <a href="mailto:contact@maghost.ro">Contact us!</a></p>
			</div>
			<?php } ?>
		</div>
	</div>

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
		console.log('Logged out');
		$('#login_btn').show();
		$('#logout_btn').hide();
	});
	$.ajax({
	   type: "POST",
	   url: "/fb_logout.php",
	   data: "",
	   beforeSend: function(){  },
	   success: function(msg){
			location.reload();
	   }
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