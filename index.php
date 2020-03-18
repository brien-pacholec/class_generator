<?php
require("/home/nginx/html/shared/mydaemen_auth.php");
include_once("config.php");
if($session_access_level !== "admin") {
	header("location: invalid_access.php?id=1");
} else {
    header("location: main.php");
}

?>