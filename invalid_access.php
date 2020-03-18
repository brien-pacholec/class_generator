<?php
    require("/home/nginx/html/shared/mydaemen_auth.php");
    include_once("config.php");

    if($_GET['id'] == 1){
        $location="/home/nginx/html/administration/class_generator/index.php";
    } elseif($_GET['id'] == 2){
        $location="/home/nginx/html/administration/class_generator/main.php";
    } else {
        $location="not found.";
    }

    $subject = "INVALID ACCESS: Class Generator";

	// To send HTML mail, the Content-type header must be set
	$headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: web@daemen.edu';

    
    $message = "<strong>Location:</strong> ".$location."<br>";
    $message .= "<strong>User:</strong> ".$mydaemen_user->getUsername()."<br>";
    $message .= "<strong>Time:</strong> ".date("M j, Y, g:i a");
    
    mail("bpachole@daemen.edu", $subject, $message, $headers);
    $page_title = "Invalid Access";

?>

<?php require("/home/nginx/html/portal/resources/partials/system_wrapper.php"); ?>
<link rel="stylesheet" type="text/css" href = "<?php echo($css_path)?>">
<br>
<div id="small-container" class = "article centered-block">
    <div class = "panel-form-heading text-center">
        <h1 class = "form-title"> Invalid Access </h1>                
        <hr/>
    </div>
    <div class = "alert alert-danger text-center invalid-access-info my-auto">
        You do not have access to this system
    </div>	
</div>
<?php require("/home/nginx/html/portal/resources/partials/footer.php");?>