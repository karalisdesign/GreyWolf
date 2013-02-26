<?php
function file_process($id,$files,$key,$echo = null,$unique){
	$handle = new upload($files);
	if($handle->uploaded) {
	$month = date('M');
	$year = date('Y');
	$dir = get_option('gw_media-path');
	
	$thumb_w = get_option('gw_media-thumb-width');
	$thumb_h = get_option('gw_media-thumb-height');
	
	$large_w = get_option('gw_media-large-width');
	$large_h = get_option('gw_media-large-height');
	
	$path = $_SERVER['DOCUMENT_ROOT'].'/'.$dir.'/'.$year.'/'.$month;
	$link = gw_url().'/'.$dir.'/'.$year.'/'.$month.'/';
	$mime_images = array('gif','jpeg','png','jpg');
	$originalwidth = $handle->image_src_x;
	if($originalwidth > 1200) { 
		$handle->image_resize		= true;
		$handle->image_x			= 1200;
		$handle->image_ratio_y		= true;
	}
	$handle->process($path);
		
		if($handle->processed) {
		$mime_type = $files['type'];
		$name = $handle->file_dst_name;
		$ext = $handle->file_dst_name_ext;
		if(in_array($ext,$mime_images)) {
			if($ext == 'jpg') {
				$mime_type = 'image/jpeg';
			} else {
				$mime_type = 'image/'.$ext;
			}
		}
		$data = array(
			'title'			=>	$name,
			'link'			=>	$link.$name,
			'type'			=>	'attachment',
			'mime_type'		=> 	$mime_type
		);
		
			$att = add_content($data); // aggiungo file nel DB come un contenuto attachment
			if(isset($id) && is_numeric($id)) {
				add_meta($id,$key,$att,$unique);  // aggiungo meta collegato
			}
			
			if(in_array($ext,$mime_images)) {
				$thumb = new upload($files);
				if($thumb->uploaded) {
				
				$new = str_replace('.'.$ext,'-thumb',$name);
				$thumb->file_new_name_body   = $new;
				$thumb->image_resize		= true;
				$thumb->image_x			= $thumb_w;
				$thumb->image_ratio = true;
				$thumb->image_ratio_crop = true;
				$thumb->process($path);
					
				}
				
				if($originalwidth > $large_w) {
					$large = new upload($files);
					if($large->uploaded) {
					
					$new = str_replace('.'.$ext,'-large',$name);
					$large->file_new_name_body   = $new;
					
					$large->image_resize		= true;
					$large->image_x			= $large_w;
					$large->image_y			= $large_h;
					$large->image_ratio = true;
					$large->image_ratio_crop = true;
					$large->process($path);
						
					}
				} // fine se l'immagine caricata è più grande della larghezza di "large"
	
			}
			
			
		$handle->clean();
		$thumb = get_image($att,'thumb');
		$original = get_image($att,null);
		$title = get_image_title($att);
		$result = array(
			'att'		=> $att,
			'thumb'		=> $thumb,
			'original'	=> $original,
			'title'		=> $title,
			'post'		=> $id
		);
		if($echo == true) { echo json_encode($result); }
		
		} else {
			echo 'error : ' . $handle->error;
		}
		
	}
}
function add_file($id,$echo,$unique){
	foreach($_FILES as $key => $val) {
		if(is_array($_FILES[$key]['error']) && count($_FILES[$key]['error']) >= 1) {
			$files = array();
			foreach ($_FILES[$key] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $files))
					$files[$i] = array();
					$files[$i][$k] = $v;
				}
			}
			foreach ($files as $file) {
				file_process($id,$file,$key,$echo,$unique);
			}
		} elseif($_FILES[$key]['error'] == 0) {
				file_process($id,$_FILES[$key],$key,$echo,$unique);
		}
	}
}