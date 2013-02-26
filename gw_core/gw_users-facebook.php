<?php
if(!isset($_SESSION['user'])) {
	//Application Configurations
	$app_id		= FB_ID;
	$app_secret	= FB_SECRET;
	$salt	= FB_SECRET;
	$site_url	= FB_SITEURL;
	
	$dbtable = 'gw_users';

	try{
		include_once "src/facebook.php";
	}catch(Exception $e){
		error_log($e);
	}
	// Create our application instance
	$facebook = new Facebook(array(
		'appId'		=> $app_id,
		'secret'	=> $app_secret,
		));

	// Get User ID
	$user = $facebook->getUser();
	// We may or may not have this data based 
	// on whether the user is logged in.
	// If we have a $user id here, it means we know 
	// the user is logged into
	// Facebook, but we don’t know if the access token is valid. An access
	// token is invalid if the user logged out of Facebook.
	//print_r($user);
	if($user){
		// Get logout URL
		$logoutUrl = $facebook->getLogoutUrl();
	}else{
		// Get login URL
		$loginUrl = $facebook->getLoginUrl(array(
			'scope'			=> 'read_stream, publish_stream, email, user_about_me',
			'redirect_uri'	=> $site_url,
			));
	}

	if($user){

		try{
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');

		//Connecting to the database. You would need to make the required changes in the common.php file
		//In the common.php file you would need to add your Hostname, username, password and database name!
		mysqlc();
		mysql_query("SET time_zone = '+01:00'") or die(mysql_error());
		$name = GetSQLValueString($user_profile['name'], "text");
		$email = GetSQLValueString($user_profile['email'], "text");
		$gender = GetSQLValueString($user_profile['gender'], "text");
		$first_name = GetSQLValueString($user_profile['first_name'], "text");
		$last_name = GetSQLValueString($user_profile['last_name'], "text");
		$lang = GetSQLValueString($user_profile['locale'], "text");
		$uid = GetSQLValueString($user_profile['id'], "text");
		$seckey = gw_randPass();
		$skey = $seckey.$salt;
		$query = sprintf("SELECT * FROM $dbtable WHERE email = %s",$email);
		$res = mysql_query($query) or die('Query1 failed: ' . mysql_error() . "<br />\n$sql");
		if(mysql_num_rows($res) == 0) {
			
			$iquery = "INSERT INTO $dbtable (id,name,first_name,last_name,email,seckey,gender,face,lang,firstlog) values ('',$name,$first_name,$last_name,$email,md5('$skey'),$gender,$uid,$lang,NOW());";
			$ires = mysql_query($iquery) or die('Query2 failed: ' . mysql_error() . "<br />\n$sql");
			$_SESSION['user'] = $user_profile['email'];
			$_SESSION['id'] = $user_profile['id'];

			$to = str_replace("'","", $email);
			$subject = 'Registrazione su '.get_option('gw_name-site');
			$headers = "From: noreply@greywolf.com\r\n";
			$headers .= "Reply-To: noreply@greywolf.com\r\n";
			$headers .= "CC: ".get_option('gw_email-site')."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$message = '<html><body>';
			$message .= '<h1>Ciao '.str_replace("'","", $first_name).', benvenuto su '.get_option('gw_name-site').'!</h1>';
			$message .= '<p>Benvenuto <strong>'.str_replace("'","", $name).'</strong>, puoi accedere al nostro sito utilizzando semplicemente il tuo account facebook, o i seguenti dati:</p>';
			$message .= '<p style="background:#F9FF9A; border:1px solid #FFD760; padding:10px; display:block">';
			$message .= '<span><strong>Username:</strong> '.str_replace("'","", $email).'</span>';
			$message .= '<br /><br />';
			$message .= '<span><strong>Security-key:</strong> '.$seckey.'</span>';
			$message .= '</p>';
			$message .= '</body></html>';


			mail($to, $subject, $message, $headers);

		}
		else
		{
			$row = mysql_fetch_array($res);
			$_SESSION['user'] = $row['email'];
			$_SESSION['id'] = $user_profile['id'];
		}
		}catch(FacebookApiException $e){
				error_log($e);
				$user = NULL;
			}
		
	}
}
?>