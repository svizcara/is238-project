<?php session_unset();
include 'config.php';
include 'functions.php';
get_siteinfo();    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="IS238 Project Helpdesk">
        <meta name="author" content="IS238 2019 Group A">
        <title><?php echo $_SESSION['siteinfo']['site_title']; ?> | <?php echo $_SESSION['siteinfo']['site_desc']; ?> </title>
        
        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
    </head>
    
    <body class="bg-dark">
        
<!--Check if user is logged in-->
            
<?php 
    if(isset($_SESSION['success'])) { 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    }
    if(isset($_SESSION['msg'])) { 
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    if(isset($_SESSION['user'])) {
	if ( $_SESSION['user']['user_type'] == 'agent' ) {
		exit(header('location:agent/index.php'));			       }
	}
?>
<?php echo display_error(); ?>
