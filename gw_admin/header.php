<?php
error_reporting(E_ALL); 
ini_set('display_errors', 'On'); 
if(!is_logged()) {
	header('location:'.gw_url().'/?error=noauth');
	exit;
} else {
$user = new user;
$user = $user->data();
$role = $user['role'];
}
if(isset($role) && $role == 10) {
	$userlogged = true;
} else {
	header('location:'.gw_url().'/?error=noauth');
	exit;
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo get_option('gw_name-site'); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
		<link rel="icon" type="image/gif" href="<?php echo gw_url(); ?>/favicon.gif" />
		<script src="<?php echo gw_url(); ?>/gw_lib/js/vendor/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="<?php echo gw_url(); ?>/gw_lib/js/vendor/jquery-ui-1.9.2.custom.min.js"></script>
		<link rel="stylesheet" href="<?php echo gw_url(); ?>/gw_lib/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo gw_url(); ?>/gw_lib/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="<?php echo gw_url(); ?>/gw_lib/css/stylesheet.css">
        <script type="text/javascript" src="../gw_lib/js/vendor/modernizr-2.6.2.min.js"></script>
        <script src="<?php echo gw_url(); ?>/gw_lib/js/vendor/bootstrap.min.js"></script>
</head>
    <body>
        <!--[if lt IE 7]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
		
		<?php echo gw_menu('main'); ?>
		
		<div id="wrap" class="container">
		<header>
		<!-- <h1><a href="<?php echo gw_url(); ?>">GreyWolf</a></h1> -->
		
		</header>
		<?php
        // restituisce un messaggio in base alle azione;
        gw_notifications();
        ?>