<?php

$graph_url= "http://86.104.220.7:5080/danielb/rest/v2/broadcasts/danielb/rtmp-endpoint?endpointServiceId=danielb";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $graph_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$output = curl_exec($ch);
curl_close($ch);

$responseObj = json_decode($output);

file_put_contents('curl3.txt', print_r($responseObj, true));



?>