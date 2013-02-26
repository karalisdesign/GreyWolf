<?php

function get_meta_id($att){
	$table = TAB_META;
	$db = new DataBase();
	$meta_query = "SELECT * FROM $table WHERE meta_id='$att'";
	$meta_results = $db->Query($meta_query);
	while($row = mysql_fetch_array($meta_results)) {
		$id = $row['meta_value'];
	}
	return $id;
}
function get_image($id,$size=null,$meta=null){
	if(isset($meta)) {
		$id = get_meta_id($meta);
	}
	$data = get_content($id);
	$link = $data['link'];
	$thumbsize = array('thumb','large');
	if(isset($size) && in_array($size,$thumbsize)) {
		$thumb = substr($link,0,-4);
		$ext = substr($link,-4);
		$result = $thumb.'-'.$size.$ext;
	} else {
	$result = $link;
	}
	return $result;
	
}

function get_image_title($id,$meta = null){
	if(isset($meta)) {
		$id = get_meta_id($meta);
	}
	$data = get_content($id);
	$data = $data['title'];
	$result = $data;
	
	return $result;
	
}

function get_image_mime($id,$meta = null){
	if(isset($meta)) {
		$id = get_meta_id($meta);
	}
	$data = get_content($id);
	$data = $data['mime_type'];
	$result = $data;
	
	return $result;

}


