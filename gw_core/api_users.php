<?php

function check_user($email){
	$table = TAB_USERS;
	$check = check_content($email,$table);
	return $check;
}

function add_user($array,$return = false){
	/*
	$array = array(
		'name' 			=>	'',
		'first_name'	=>	'',
		'last_name'		=>	'',
		'email'			=>	'',
		'seckey'		=> 	'',
		'gender'		=>	'',
		'face'			=>	'',
		'lang'			=>	'',
		'role'			=>	'',
		'active'		=>	'',
		'firstlog'		=>	''
	);
	*/

	$table = TAB_USERS;

	if(count($array) >= 1) {

		$error = false;
		$message = array();

		// IMPOSTO USERNAME
		if(isset($array['name']) && !empty($array['name'])) { 
			$name = $array['name']; 
		} else {
			$name = null;
			$error = true;
			$message[] = 'Manca un nome!';
		}

		// IMPOSTO NOME
		if(isset($array['first_name'])) { 
			$first_name = $array['first_name']; 
		} else { 
			$first_name = null;
		}

		// IMPOSTO UN COGNOME
		if(isset($array['last_name'])) { 
			$last_name = $array['last_name']; 
		} else { 
			$last_name = null; 
		}

		// IMPOSTO UNA MAIL
		if(isset($array['email']) && check_user($array['email'])) { 
			$email = null;
			$error = true;
			$message[] = 'Questo indirizzo email è già utilizzato!'; 
		} elseif(isset($array['email']) && !empty($array['email']) && !check_user($array['email'])){
			$email = $array['email'];
		}else { 
			$email = null;
			$error = true;
			$message[] = 'Non è stata impostata una email!';
		}

		
		// IMPOSTO UNA SECKEY
		$user = new user; // richiamo la classe users
		if(isset($array['seckey'])) {
			$crypt = $user->crypt($array['seckey']);
			$seckey = $crypt; 
		} else { 
			$rand = gw_randPass();
			$crypt = $user->crypt($rand);
			$seckey = $crypt; 
		}

		// IMPOSTO IL SESSO
		if(isset($array['gender'])) { 
			$gender = $array['gender']; 
		} else { 
			$gender = null; 
		}

		// IMPOSTO ID FACEBOOK
		if(isset($array['face'])) { 
			$face = $array['face']; 
		} else { 
			$face = null;
		}

		// IMPOSTO LINGUA
		if(isset($array['lang'])) { 
			$lang = $array['lang']; 
		} else { 
			$lang = default_lang(); 
		}

		// IMPOSTO RUOLO
		if(isset($array['role'])) { 
			$role = $array['role']; 
		} else { 
			$role = 1; 
		}

		// IMPOSTO STATO
		if(isset($array['active'])) { 
			$active = $array['active']; 
		} else { 
			$active = false;
		}


		// IMPOSTO DATA DI REGISTRAZIONE
		if(isset($array['firstlog'])) { 
			$firstlog = $array['firstlog']; 
		} else { 

			$firstlog = date('Y-m-d H:i:s'); 
		}

		if($error == false) {

			$newarray = array(
				'name' 			=>	$name,
				'first_name'	=>	$first_name,
				'last_name'		=>	$last_name,
				'email'			=>	$email,
				'seckey'		=> 	$seckey,
				'gender'		=>	$gender,
				'face'			=>	$face,
				'lang'			=>	$lang,
				'role'			=>	$role,
				'active'		=>	$active,
				'firstlog'		=>	$firstlog
			);

			$db = new DataBase();
			foreach($newarray as $key => $val){
				${$key} = $db->Clean(htmlentities($val));
			}

			if($return == true) {
				$newarray = array_merge($array,$newarray);
				return $newarray;
			} else {
				$date = new DateTime();
				$date->setTimezone(new DateTimeZone('Europe/Rome'));
				$now = $date->format('Y-m-d H:i:s'); // same format as NOW()
				$query = "INSERT INTO $table 
				(name,first_name,last_name,email,seckey,gender,face,lang,role,active,firstlog) 
				VALUES ('$name','$first_name','$last_name','$email','$seckey','$gender','$face','$lang','$role','$active','$now');";
				// ... la eseguo!
				$result = $db->Query($query,true);


				return $result;

			} // fine in return $array demo
		} // se non ci sono errori, $error === false
		else {
			print_r($message);
		}
	} // fine if $array
	else {
		return false;
	}
} // fine add_user()

/*
function update_content($array,$return = false){
	if(isset($array['id'])) {
		$id = $array['id'];
	
		$tab_content = TAB_CONTENT;
		$db = new DataBase();
		// Scrivo la query e...
		$query = "SELECT * FROM $tab_content WHERE ID='$id'";
		// ... la eseguo!
		$result = $db->Query($query);
		while($row = mysql_fetch_array($result)) {
			$data = $row;
		}

		foreach($data as $key => $val){
			if(isset($array[$key])) {
				${$key} = $array[$key];
			} else {
				${$key} = $val;
			}
		}

		// IMPOSTO UNO SLUG
		if(isset($array['slug'])) { 
			$slug = $array['slug']; 
		} elseif(isset($title)) { 
			$slug = str_replace(' ','-',preg_replace('/[^0-9a-zA-Z ]/m','',strtolower($title))); 
		}

		// IMPOSTO UNA DATA DI MODIFICA
		if(isset($array['date_modified'])) { 
			$date_modified = $array['date_modified']; 
		} elseif(isset($array['date_modified']) && strlen($array['date_modified']) == 10) { 
			$date_modified = $array['date_modified'].' '.date('H:i'); 
		} else {
			$date_modified = date('Y-m-d H:i:s'); ;
		}
		
		if(!isset($array['link'])) $link = gw_url().'/?'.$array['type'].'='.$id;

		$newarray = array(
			'author' 		=>	$author,
			'date'			=>	$date,
			'content'		=>	$content,
			'title'			=>	$title,
			'status'		=>	$status,
			'site'			=> 	$site,
			'lang'			=>	$lang,
			'slug'			=>	$slug,
			'date_modified'	=>	$date_modified,
			'parent'		=>	$parent,
			'link'			=>	$link,
			'menu_order'	=>	$menu_order,
			'type'			=>	$type,
			'mime_type'		=>  $mime_type
		);
		
		$db = new DataBase();
		foreach($newarray as $key => $val){
			${$key} = $db->Clean(htmlentities($val));
		}

		if($return == true) {
			$newarray = array_merge($array,$newarray);
			return $newarray;
		} else {
			//(author,date,title,status,site,lang,slug,date_modified,parent,link,menu_order,type,mime_type) 
			$query = "UPDATE $tab_content  
			SET author = '$author',
			date = '$date',
			content = '$content',
			title = '$title',
			status = '$status', 
			site = '$site', 
			lang = '$lang',
			slug = '$slug',
			date_modified = '$date_modified',
			parent = '$parent',
			link = '$link',
			menu_order = '$menu_order',
			type = '$type',
			mime_type = '$mime_type'
			WHERE ID=$id;";
			// ... la eseguo!
			$result = $db->Query($query,true);
			//return $result;

			if($result == 1) { $result = $id; }

			return $id;

		}
		
	} else {
		return false;
	}
} // fine update_content()

function delete_media($id) {
		$images = array('gif','jpeg','png','jpg');
		$files = array();
		$files[] = get_image($id);
		$ext = end(explode('.',$files[0]));
		if(in_array($ext,$images)) {
			$files[] = get_image($id,'thumb');
			$files[] = get_image($id,'large');
		}
		
		foreach($files as $file) {
			$file = end(explode('media/',$file));
			$path = dirname(__FILE__).'/../media/'.$file;
		
			if(file_exists($path)) {
				unlink($path);
			}
		}
	}
*/

function delete_user($id){
	$table = TAB_USERS;
	$db = new DataBase();
	// Scrivo la query e...
	if(is_array($id) && count($id)>0) {
		foreach($id as $single){
			$query = "DELETE FROM $table WHERE id=$single;";
			$result = $db->Query($query);	
		}
	} else {
		$query = "DELETE FROM $table WHERE id=$id";
		$result = $db->Query($query);
	}
	// ... la eseguo!
}
/*
function delete_post_meta_att($id){
	$table = TAB_META;
	$db = new DataBase();
	// Scrivo la query e...
	$metaquery = "SELECT * FROM $table WHERE post_id='$id'";
	$metavals = array();
	// ... la eseguo!
	$metaresult = $db->Query($metaquery);
	while($meta = mysql_fetch_array($metaresult)) {
		$meta_value = $meta['meta_value'];
		if(is_numeric($meta_value) && check_content($meta_value)) {
			$metavals['id'][] = $meta_value;
		} else {
			$metavals['string'][] = $meta['meta_id'];
		}
	}
	if(count($metavals['id']) > 0) {
		delete_content($metavals['id']);
	}
	if(count($metavals['string']) > 0) {
		foreach($metavals['string'] as $id){
			delete_meta_by_id($id);
		}
	}
}
*/