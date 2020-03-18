<?php
$system_code = "classgenerator";
$system_menu = new \Daemen\SystemMenu("code",$system_code);
$mydaemen_user_system_access = \Daemen\SystemMenuAccess::getUserAccessLevelForSystem($system_menu,$mydaemen_user);
$session_access_level = $mydaemen_user_system_access->getAccessLevel();
$mydaemen_user_OU = $mydaemen_user->getOu();

$system_title = "Class Generator";
$css_path = "./css/styles.css?ver=".filemtime('/home/nginx/html/administration/class_generator/css/styles.css');
?>