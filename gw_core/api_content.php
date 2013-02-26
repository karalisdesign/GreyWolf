<?php

function get_content($id){
	$tab_content = TAB_CONTENT;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $tab_content WHERE ID='$id'";
	// ... la eseguo!
	$result = $db->Query($query);
	if(mysql_num_rows($result)) {
		while($row = mysql_fetch_array($result)) {
			$data = $row;
		}
		return $data;
	}
}


function check_content($id,$table = TAB_CONTENT){
	if($table == TAB_CONTENT) {$tabid = 'ID'; }
	elseif($table == TAB_META) {$tabid = 'meta_id'; }
	elseif($table == TAB_USERS) {$tabid = 'email'; }
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $table WHERE $tabid='$id'";
	// ... la eseguo!
	$result = $db->Query($query);
	$num_rows = mysql_num_rows($result);
	if($num_rows >= 1) { 
		return true;
	} else {
		return false;
	}
}

function add_content($array,$return = false){
	/*
	$array = array(
		'author' 		=>	'',
		'date'			=>	'';
		'title'			=>	'',
		'status'		=>	'',
		'site'			=> 	'',
		'lang'			=>	'',
		'slug'			=>	'',
		'date_modified'	=>	'',
		'parent'		=>	'',
		'link'			=>	'',
		'menu_order'	=>	'',
		'type'			=>	'',
		'mime_type'		=> 	''
	);
	*/

	$tab_content = TAB_CONTENT;

	if(count($array) >= 1) {
	
		$newdate = new DateTime();
		$newdate->setTimezone(new DateTimeZone('Europe/Rome'));
		$now = $newdate->format('Y-m-d H:i:s'); // same format as NOW()

		// IMPOSTO L'AUTORE, SE NON ESISTE, IMPOSTO L'AUTORE 1
		if(isset($array['author'])) { 
			$author = $array['author']; 
		} elseif(isset($_SESSION['user'])) { 
			$user = new user;
			$data = $user->data($_SESSION['user']);
			$author = $data['id']; 
		} else {
			$author = 1;
		}

		// IMPOSTO UNA DATA, SE NON ESISTE, IMPOSTO DATA DI OGGI
		if(isset($array['date'])) { 
			$date = $array['date']; 
		} else { 
			$date = $now; 
		}

		// IMPOSTO UN CONTENUTO, SE NON ESISTE, LASCIO VUOTO
		if(isset($array['content'])) { 
			$content = $array['content']; 
		} else { 
			$content = ''; 
		}

		// IMPOST UN TITLE, SE NON ESISTE, IMPOSTO "UNTITLE"
		if(isset($array['title'])) { 
			$title = $array['title']; 
		} else { 
			$title = 'Untitle'; 
		}

		// IMPOSTO UNO STATO, SE NON ESISTE, IMPOSTO "HIDDEN"
		if(isset($array['status'])) { 
			$status = $array['status']; 
		} else { 
			$status = 'hidden'; 
		}

		// IMPOSTO UN SITO, SE NON ESISTE, IMPOSTO "THIS"
		if(isset($array['site'])) { 
			$site = $array['site']; 
		} else { 
			$site = 'this'; 
		}

		// IMPOSTO UN SITO, SE NON ESISTE, IMPOSTO "THIS"
		if(isset($array['lang'])) { 
			$lang = $array['lang']; 
		} else { 
			$lang = default_lang(); 
		}

		// IMPOSTO UNO SLUG, SE NON ESISTE, NE CREO UNO FORMATTANDO IL TITOLO
		if(isset($array['slug'])) { 
			$slug = $array['slug']; 
		} elseif(isset($title)) { 
			$slug = str_replace(' ','-',preg_replace('/[^0-9a-zA-Z ]/m','',strtolower($title))); 
		} else {
			$slug = 'untitle';
		}

		// IMPOSTO UNA DATA DI MODIFICA, SE NON ESISTE, IMPOSTA QUELLA ATTUALE
		if(isset($array['date_modified'])) { 
			$date_modified = $array['date_modified']; 
		} else {
			$date_modified = $now; 
		}

		// IMPOSTO UNA PAGINA GENITORE, SE NON ESISTE, LASCIO VUOTO
		if(isset($array['parent'])) { 
			$parent = $array['parent']; 
		} else { 
			$parent = ''; 
		}

		// IMPOSTO UN LINK, SE NON ESISTE, LASCIO VUOTO
		if(isset($array['link'])) { 
			$link = $array['link']; 
		} else { 
			$link = ''; 
		}

		// IMPOSTO UN ORDINE PER IL MENU, SE NON ESISTE, IMPOSTO 0
		if(isset($array['menu_order'])) { 
			$menu_order = $array['menu_order']; 
		} else { 
			$menu_order = '0'; 
		}

		// IMPOSTO UN POST TYPE, SE NON ESISTE, IMPOSTO "PAGE"
		if(isset($array['type'])) { 
			$type = $array['type']; 
		} else { 
			$type = 'page'; 
		}

		// IMPOSTO UN MIME TYPE, SE NON ESISTE, LASCIO VUOTO
		if(isset($array['mime_type'])) { 
			$mime_type = $array['mime_type']; 
		} else { 
			$mime_type = ''; 
		}

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
		
		//print_r($newarray);

		$db = new DataBase();
		foreach($newarray as $key => $val){
			${$key} = $db->Clean(htmlentities($val));
		}
	
		if($return == true) {
			$newarray = array_merge($array,$newarray);
			return $newarray;
		} else {
			$query = "INSERT INTO $tab_content 
			(author,date,content,title,status,site,lang,slug,date_modified,parent,link,menu_order,type,mime_type) 
			VALUES ('$author','$now','$content','$title','$status','$site','$lang','$slug','$now','$parent','$link','$menu_order','$type','$mime_type');";
			// ... la eseguo!
			$result = $db->Query($query,true);

			if(!isset($link)) $link = gw_url().'/?'.$type.'='.$result;

			$query = "UPDATE $tab_content  
			SET link = '$link' 
			WHERE ID=$result";
			// ... la eseguo!
			$update = $db->Query($query);

			return $result;

		} // fine in return $array demo
	} // fine if $array
	else {
		echo 'error';
	}
} // fine add_content()

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
			$newdate = new DateTime();
			$newdate->setTimezone(new DateTimeZone('Europe/Rome'));
			$now = $newdate->format('Y-m-d H:i:s'); // same format as NOW()
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
			date_modified = '$now',
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
		echo 'error';
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

function delete_content($id,$table = TAB_CONTENT,$return=false){
	$db = new DataBase();
	// Scrivo la query e...
	if(is_array($id) && count($id)>0) {
		foreach($id as $single){
			$type = get_content($single);
			$type = $type['type'];
			if($type == 'attachment') {
				$meta_id = get_meta_attachment($single);
				delete_meta_by_id($meta_id);
				delete_media($single);
			}
			$query = "DELETE FROM $table WHERE ID=$single;";
			$result = $db->Query($query);	
		}
	} else {
		$query = "DELETE FROM $table WHERE ID=$id";
		$result = $db->Query($query);
	}
	// ... la eseguo!
	
	if(is_array($id) && $return == true) {
		//print_r($id);
		echo $query;
	} else {
		if($return == false) { return $id; }
		elseif($return == true) { echo $id; }
	}
}

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