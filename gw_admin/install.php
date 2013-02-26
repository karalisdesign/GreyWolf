<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>INSTALLAZIONE - GreyWolf</title>
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
	<div class="well">
		<h1>Installazione di GreyWolf</h1>
		<hr />
<?php
	$site = str_replace($_SERVER['SCRIPT_URL'],'',$_SERVER['SCRIPT_URI']);
	
	$path = dirname(__FILE__);
	$dbconfig = $path.'/../gw_core/gw_config-db.php';
	
	if(isset($_GET['status']) && $_GET['status'] == 'ready') {
	echo '<div class="alert">';
		echo 'GreyWolf &egrave; gi&agrave; stato configurato!';
		echo '</div>';
		echo '<hr />';
		echo '<a href="../index.php" class="btn btn-small">Esci</a>';
	} elseif(!isset($_GET['step']) && !isset($_GET['status'])) {
	echo '<a href="?step=1" class="btn btn-info btn-small">Procedi</a>';
	} else {
	if(!file_exists($dbconfig)) {
	
		switch($_GET['step']) {
		case '1':
		echo '<form action="install.php?step=2" method="POST" class="form-horizontal">
		
		<div class="control-group">
		<label class="control-label" for="dbname">Nome database</label>
		<div class="controls">
		<input type="text" id="dbname" name="db[dbname]" placeholder="database">
		</div>
		</div>
		
		<div class="control-group">
		<label class="control-label" for="user">Utente</label>
		<div class="controls">
		<input type="text" id="user" name="db[user]" placeholder="utente">
		</div>
		</div>
		
		<div class="control-group">
		<label class="control-label" for="password">Password</label>
		<div class="controls">
		<input type="text" id="password" name="db[password]" placeholder="password">
		</div>
		</div>
		
		<div class="control-group">
		<label class="control-label" for="host">Host</label>
		<div class="controls">
		<input type="text" id="host" name="db[host]" placeholder="host">
		</div>
		</div>
		
		<hr />
		<div class="control-group">
		<div class="controls">
		<button type="submit" class="btn btn-small btn-info">Continua</button>
		</div>
		</div>
		</form>';
		break;
		case '2':
		$error = false;
		if(isset($_POST['db'])) {
			foreach($_POST['db'] as $value) {
				if(empty($value)) {
					$error = true;
				}
			}
		}
		if($error == false) {
		
			$con = mysql_connect($_POST['db']['host'],$_POST['db']['user'],$_POST['db']['password']);
			$db_selected = mysql_select_db($_POST['db']['dbname'],$con);
			if(!$db_selected || !$con) {
				die ('<div class="alert">Cannot use foo : ' . mysql_error() .'</div><hr /><a href="install.php?step=1" class="btn btn-small btn-danger">Indietro</a>');
			}

				if(!file_exists($dbconfig)) {
				
				// OK FIN QUI, ALLORA CREO IL FILE DI CONFIGURAZIONE DEL DATABASE
				$filename = '../gw_core/gw_config-db.php';
				$newdate = new DateTime();
				$newdate->setTimezone(new DateTimeZone('Europe/Rome'));
				$now = $newdate->format('Y-m-d H:i:s'); // same format as NOW()
$Content = "<?php 
// site: ".$site."
// Installato in data: ".$now."
// definisco dati per connessione database
define('HOST', '".$_POST['db']['host']."');
define('USER', '".$_POST['db']['user']."');
define('PASSWORD', '".$_POST['db']['password']."');
define('DBNAME', '".$_POST['db']['dbname']."');";
				
				$handle = fopen($filename, 'x+');
				fwrite($handle, $Content);
				fclose($handle);
				

				}
			mysql_close($con);
		}
			
			echo '<div class="alert alert-success">';
			echo '<h4>Ok, &egrave; stato possibile stabilire e definire una connessione</h4>';
			echo '</div>';
			echo '<hr />';
			echo '<a href="install.php?step=3" class="btn btn-small btn-info">Continua</a>';
	
			break;
		
			}
		} elseif(file_exists($dbconfig)) {
		
			switch($_GET['step']) {
			case '1':
			case '2':
			header('location: install.php?step=3');
			exit;
			break;
			case '3':
			
			require_once('../gw_core/gw_config.php');
			require_once('../gw_core/gw_db-class.php');
			
			$db = new DataBase();
			$link = $db->OpenConnection();
			$connection = $db->SelectDatabase(DBNAME,$link);
			
			if(!$connection) {
				header('location: install.php?note=error');
				exit;
			}
			
			
			$check = "SHOW TABLES LIKE `".TAB_OPTIONS."`";
			$check = $db->Query($check);
			if(!$check) {

				$query = "CREATE TABLE IF NOT EXISTS `gw_content` (
				  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `author` bigint(20) unsigned NOT NULL DEFAULT '0',
				  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `content` longtext NOT NULL,
				  `title` text NOT NULL,
				  `status` varchar(20) NOT NULL DEFAULT 'hidden',
				  `site` varchar(20) NOT NULL DEFAULT 'this',
				  `lang` varchar(20) NOT NULL DEFAULT 'it_IT',
				  `slug` varchar(200) NOT NULL DEFAULT '',
				  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `parent` bigint(20) NOT NULL DEFAULT '0',
				  `link` varchar(255) NOT NULL DEFAULT '',
				  `menu_order` int(11) NOT NULL DEFAULT '0',
				  `type` varchar(20) NOT NULL DEFAULT 'page',
				  `mime_type` varchar(100) NOT NULL DEFAULT '',
				  PRIMARY KEY (`ID`),
				  KEY `slug` (`slug`),
				  KEY `parent` (`parent`),
				  KEY `author` (`author`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
				$result = $db->Query($query);

				$query = "CREATE TABLE IF NOT EXISTS `gw_content_meta` (
				  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
				  `meta_key` varchar(255) DEFAULT NULL,
				  `meta_value` longtext,
				  PRIMARY KEY (`meta_id`),
				  KEY `post_id` (`post_id`),
				  KEY `meta_key` (`meta_key`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
				$result = $db->Query($query);

				$query = "CREATE TABLE IF NOT EXISTS `gw_options` (
				  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `option_name` varchar(64) NOT NULL DEFAULT '',
				  `option_value` longtext NOT NULL,
				  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
				  PRIMARY KEY (`option_id`),
				  UNIQUE KEY `option_name` (`option_name`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
				$result = $db->Query($query);

				$query ="CREATE TABLE IF NOT EXISTS `gw_usermeta` (
				  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
				  `meta_key` varchar(255) DEFAULT NULL,
				  `meta_value` longtext,
				  PRIMARY KEY (`umeta_id`),
				  KEY `user_id` (`user_id`),
				  KEY `meta_key` (`meta_key`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				$result = $db->Query($query);

				$query = "CREATE TABLE IF NOT EXISTS `gw_users` (
				  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
				  `first_name` varchar(255) CHARACTER SET latin1 NOT NULL,
				  `last_name` varchar(255) CHARACTER SET latin1 NOT NULL,
				  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
				  `seckey` char(32) CHARACTER SET latin1 NOT NULL,
				  `gender` varchar(10) CHARACTER SET latin1 NOT NULL,
				  `face` varchar(10) CHARACTER SET latin1 NOT NULL,
				  `lang` varchar(5) CHARACTER SET latin1 NOT NULL,
				  `role` int(2) NOT NULL DEFAULT '1',
				  `active` varchar(10) NOT NULL DEFAULT 'false',
				  `firstlog` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
				$result = $db->Query($query);
			
			} else {
				$status = "SELECT *
				FROM `".TAB_OPTIONS."`
				WHERE `option_name` = 'active'";
				$result = $db->GetRow($status);
				$result = $result['option_value'];
				if($result == 1){
					header('location: install.php?status=ready');
					exit;
				}
			}
			
			$status = "SELECT *
				FROM `".TAB_OPTIONS."`
				WHERE `option_name` = 'active'";
				$result = $db->GetRow($status);
				$result = $result['option_value'];
				if($result == 1){
					header('location: install.php?status=ready');
					exit;
				}
			
			echo '<form action="install.php?step=4" method="POST" class="form-horizontal">

			<input type="hidden" id="gw_url" name="option[gw_url]" class="input-block-level" value="'.$site.'" />
			
			<div class="control-group">
			<label class="control-label" for="gw_name-site">Nome del sito</label>
			<div class="controls">
			<input type="text" id="gw_name-site" name="option[gw_name-site]">
			</div>
			</div>
			
			<hr />
			
			<div class="control-group">
			<label class="control-label" for="first_name">Il tuo nome</label>
			<div class="controls">
			<input type="text" id="first_name" name="user[first_name]">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="last_name">Il tuo cognome</label>
			<div class="controls">
			<input type="text" id="last_name" name="user[last_name]">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="name">Scegli un nickname</label>
			<div class="controls">
			<input type="text" id="name" name="user[name]">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="seckey">Security-key</label>
			<div class="controls">
			<input type="password" id="seckey" name="user[seckey]">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="email">Email</label>
			<div class="controls">
			<input type="text" id="email" name="user[email]">
			</div>
			</div>
			
			<hr />
			<div class="control-group">
			<div class="controls">
			<button type="submit" class="btn btn-small btn-info">Continua</button>
			</div>
			</div>
			</form>';
			
			break;
			
			case '4':
			require_once('../gw_core/gw_system.php');
			
			$db = new DataBase();
			
			if(get_option('active') == 1) {
				header('location: install.php?status=ready');
				exit;
			}
			
			$error = false;
			$msg = array();
			foreach($_POST['option'] as $key => $val) {
					if(empty($val)){ 
						$error = true; 
						$msg[] = $key;
					} else {
						$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('$key','$val');";
						$result = $db->Query($query);
					}
					
				}
			foreach($_POST['user'] as $key => $val) {
					if(empty($val)){ 
						$error = true; 
						$msg[] = $key;
					}
				}
				
			if($error == true){
				$string = '&error='.implode(',',$msg);
				header('location: install.php?step=3'.$string);
				exit;
			}
			
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('active','1');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_seo-title','".$_POST['option']['gw_name-site']."');";
			$result = $db->Query($query);
			
			$desc = "Flexibility, easy and mobility";
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_seo-desc','".$desc."');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_email-site','".$_POST['user']['email']."');";
			$result = $db->Query($query);
			
			$lingue = 'a:1:{s:5:"it_IT";a:4:{s:2:"id";s:5:"it_IT";s:4:"nome";s:8:"Italiano";s:7:"default";s:2:"on";s:5:"stato";s:2:"on";}}';
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('lingue','".$lingue."');";
			$result = $db->Query($query);
			
			$posttype = 'a:2:{s:4:"page";a:7:{s:4:"name";s:6:"Pagina";s:6:"plural";s:6:"Pagine";s:3:"new";s:12:"Nuova pagina";s:4:"edit";s:15:"Modifica pagina";s:4:"view";s:15:"Tutte le pagine";s:5:"state";s:2:"on";s:2:"id";s:4:"page";}s:4:"news";a:7:{s:4:"name";s:7:"Notizia";s:6:"plural";s:7:"Notizie";s:3:"new";s:13:"Nuova notizia";s:4:"edit";s:16:"Modifica notizia";s:4:"view";s:16:"Tutte le notizie";s:5:"state";s:2:"on";s:2:"id";s:4:"news";}}';
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('posttype','".$posttype."');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_theme','default');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_media-path','media');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_media-thumb-width','150');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_media-thumb-height','150');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_media-large-width','940');";
			$result = $db->Query($query);
			
			$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('gw_media-large-height','280');";
			$result = $db->Query($query);
			
			$_POST['user']['role'] = 10;
			$_POST['user']['active'] = true;
			add_user($_POST['user']);
			
			add_content(array('title' => 'Hello GreyWolf!','content'=>'Welcome :)','status'=>'publish'));
			
			echo '<div class="alert alert-success">';
			echo '<h4>GreyWolf &egrave; stato installato con successo! :)</h4>';
			echo '</div>';
			echo '<hr />';
			echo '<a href="login.php" class="btn btn-small btn-success">Continua</a>';
			
			break;
			
			}
		}
	
	}

	

?>
	</div>
	</body>
</html>