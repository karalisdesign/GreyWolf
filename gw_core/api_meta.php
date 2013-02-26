<?php
function add_meta($postid,$key,$value,$unique = null){

	if(isset($postid,$key,$value) && $value != '' && check_content($postid)){
		$tab_meta = TAB_META;
		$db = new DataBase();

		$key = $db->Clean(htmlentities($key));
		$value = $db->Clean(htmlentities($value));

		if($unique == true) {

			// Scrivo la query e...
			$query = "SELECT * FROM $tab_meta WHERE post_id='$postid' AND meta_key='$key'";
			// ... la eseguo!
			$result = $db->Query($query);
			$num_rows = mysql_num_rows($result);
			if($num_rows > 0) {
				while($row = mysql_fetch_array($result)) {
					$meta_id[] = $row['meta_id'];
				}
				$delete = "DELETE FROM $tab_meta WHERE meta_id IN (".implode(",", $meta_id).")";
				// ... la eseguo!
				$result = $db->Query($delete);
			}

		}
		
		$query = "INSERT INTO $tab_meta 
		(post_id,meta_key,meta_value) 
		VALUES ('$postid','$key','$value');";
		// ... la eseguo!
		$result = $db->Query($query,true);

	} else {
		return 'Errore';
	}
}

function update_meta($postid,$key,$value){
	
	if(isset($postid,$key,$value) && $value != '' && check_content($postid)){
	$tab_meta = TAB_META;
	$db = new DataBase();

	$key = $db->Clean(htmlentities($key));
	$value = $db->Clean(htmlentities($value));

		$query = "UPDATE $tab_meta  
			SET meta_value = '$value'
			WHERE post_id=$postid
			AND meta_key ='$key'";
		// ... la eseguo!
		$result = $db->Query($query);

	} else {
		return false;
	}
}

function delete_meta($postid,$key,$value=null){
	if(isset($postid,$key) && check_content($postid)){
		$tab_meta = TAB_META;
		if(isset($value)) {
			$query = "DELETE FROM $tab_meta WHERE post_id='$postid' AND meta_key='$key' AND meta_value='$value'";
		} else {
			$query = "DELETE FROM $tab_meta WHERE post_id='$postid' AND meta_key='$key'";
		}
		$db = new DataBase();
		$result = $db->Query($query);
	} else {
		return false;
	}
}

function delete_meta_by_id($meta_id){
	if(isset($meta_id)){
		$tab_meta = TAB_META;
		$query = "DELETE FROM $tab_meta WHERE meta_id='$meta_id'";
		$db = new DataBase();
		$result = $db->Query($query);
	} else {
		return false;
	}
}

function get_meta($postid,$key){
	$tab_meta = TAB_META;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $tab_meta WHERE post_id='$postid' AND meta_key='$key'";
	// ... la eseguo!
	$result = $db->Query($query);
	while($row = mysql_fetch_array($result)) {
		$value = $row['meta_value'];
	}
	if(isset($value))
	return $value;
}

function get_meta_attachment($id){
	$tab_meta = TAB_META;
	$db = new DataBase();
	// Scrivo la query e...
	$metaquery = "SELECT * FROM $tab_meta WHERE meta_value='$id';";
	$metaresult = $db->Query($metaquery);
	while($meta = mysql_fetch_array($metaresult)) {
		$meta_id = $meta['meta_id'];
		}
	return $meta_id;
}

function get_meta_post($id){
	$tab_meta = TAB_META;
	$db = new DataBase();
	// Scrivo la query e...
	$metaquery = "SELECT * FROM $tab_meta WHERE meta_value='$id';";
	$metaresult = $db->Query($metaquery);
	$num_rows = mysql_num_rows($metaresult);
		if($num_rows > 0) {
			while($meta = mysql_fetch_array($metaresult)) {
				$post_id = $meta['post_id'];
				}
			return $post_id;
		}
	
}