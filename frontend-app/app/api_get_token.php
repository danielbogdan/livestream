<?php
include "common.php";
session_start();

$conn = mysqli_connect($host, $username, $password, $dbname);
if(!$conn) {
	$err['msg'] = 'db error';
	echo json_encode($err);
	exit;
}

$result = mysqli_query($conn, 'SELECT * FROM accounts WHERE id="'.$_SESSION["account_id"].'" LIMIT 1');
$row = mysqli_fetch_assoc($result);

if($row['fb_user_token']) {
	$access_token = $row['fb_user_token'];
} else {

	$graph_url= "https://graph.facebook.com/v20.0/oauth/access_token?grant_type=fb_exchange_token&client_id=677514669868733&client_secret=5398ac824d7d68e407b6612a584c8066&fb_exchange_token=".$_POST['token'];
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $graph_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$output = curl_exec($ch);
	curl_close($ch);
	$responseObj = json_decode($output);

	if($responseObj->access_token) {
		file_put_contents("/var/livestream/auth/logs/token.txt", $responseObj->access_token, FILE_APPEND);
		$result = mysqli_query($conn, 'UPDATE accounts SET fb_user_token="'.$responseObj->access_token.'" WHERE id="'.$_SESSION["account_id"].'"');
	}
	$access_token = $responseObj->access_token;
}


$graph_url= "https://graph.facebook.com/v20.0/".$_POST['user']."/accounts?access_token=".$access_token;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $graph_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$output = curl_exec($ch);

if (curl_errno($ch)) {
	$res['error'] = curl_error($ch);
}

curl_close($ch);
$responseObj = json_decode($output);

$html = "";

foreach ($responseObj->data as $obj) {
	$html .= '<option value="'.$obj->id.'" data-token="'.$obj->access_token.'">'.$obj->name.'</option>';
}

$res['html'] = $html;

echo json_encode($res);
exit;






















?>