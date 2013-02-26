<?php
$path = dirname(__FILE__);
$dbconfig = $path.'/gw_config-db.php';
if(file_exists($dbconfig)) {
	require_once($dbconfig);
} else {
	header('location: ../gw_admin/install.php');
	exit;
}

// definisco il prefisso
define('PREFIX','gw');

// definisco le tabelle
define('TAB_OPTIONS',PREFIX.'_'.'options');
define('TAB_CONTENT',PREFIX.'_'.'content');
define('TAB_META',PREFIX.'_'.'content_meta');
define('TAB_USERS',PREFIX.'_'.'users');
define('TAB_USER_META',PREFIX.'_'.'usermeta');

// definisco utente principale (temporaneo)
define('AUTHOR', '1');

// definisco dati per Facebook
define ('FB_ID', 	'355630754534084');
define ('FB_SECRET','dd858a36b254805a4fa3b1ab915fe760');
define ('FB_SITEURL','http://www.angelopili.it/gw_admin/');