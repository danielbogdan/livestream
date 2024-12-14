<?php
include "common.php";

$post = [
    'action' => 'publish',
    'user' => 'danielb',
];

$graph_url= "http://172.10.10.40/api.php";
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
print_r($responseObj);

?>