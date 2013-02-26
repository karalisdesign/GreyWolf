<?php 
require_once('../gw_core/gw_system.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Autenticazione - GreyWolf</title>
        <meta name="robots" content="noindex, nofollow">
		<meta name="robots" content="noarchive">
        <meta name="viewport" content="width=device-width">
		<link rel="icon" type="image/gif" href="../favicon.gif" />
		<script src="../gw_lib/js/vendor/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="../gw_lib/js/vendor/jquery-ui-1.9.2.custom.min.js"></script>
		<link rel="stylesheet" href="../gw_lib/css/bootstrap.min.css">
        <link rel="stylesheet" href="../gw_lib/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="../gw_lib/css/stylesheet.css">
        <script type="text/javascript" src="../gw_lib/js/vendor/modernizr-2.6.2.min.js"></script>
        <script src="../gw_lib/js/vendor/bootstrap.min.js"></script>
</head>
    <body>
	    <div class="modal">
		<div class="modal-header">
		<h3>Autenticazione</h3>
		</div>
		<form action="" method="POST" class="form-horizontal">
			<div class="modal-body">
				<?php 
					if(!empty($_POST['user']['email']) && !empty($_POST['user']['seckey'])) {
						$email = $_POST['user']['email'];
						$password = $_POST['user']['seckey'];
						$user = new user;
						$user->login($email,$password,'/gw_admin/');
					}
				?>
				<div class="control-group">
					<label class="control-label" for="email">Email</label>
					<div class="controls">
					<input type="text" id="email" name="user[email]">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="seckey">Password</label>
					<div class="controls">
					<input type="password" id="seckey" name="user[seckey]">
					</div>
				</div>
			</div>
			<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Accedi</button>
			</div>
		</form>
		</div>
	
	</body>
</html>