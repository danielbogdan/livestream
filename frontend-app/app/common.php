<?php
$host = "db";
$username = "live";
$password = "Magpass@99!";
$dbname = "live";
$usertablename = "accounts";
$rtmp = "172.26.0.4";
$domain = "localhost";

function genkey() {
	return bin2hex(openssl_random_pseudo_bytes(10));
}
?>
