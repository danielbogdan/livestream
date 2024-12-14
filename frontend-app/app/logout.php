<?php
include "common.php";

session_start();
session_unset();
session_destroy();

header("location: https://".$_SERVER['HTTP_HOST']);
?>
