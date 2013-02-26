<?php

$tab_options = TAB_OPTIONS;
$tab_content = TAB_CONTENT;
$tab_users = TAB_USERS;
$salt = FB_SECRET;

// greywolf
function gw_insert_content() {
	if(isset($_POST['content'])) {
		$array = array();
		foreach ($_POST['content'] as $key => $value) {
			$array[$key] = $value;
		}
		$type = $array['type'];
		$id = add_content($array);
	}
	if(isset($_POST['meta'])) {
		foreach($_POST['meta'] as $key => $value) {
			add_meta($id,$key,$value);
		}
	}
	if(isset($_FILES) && count($_FILES) >= 1) {
		add_file($id,null);
	}
	if(isset($id) && check_content($id)) {
		header('location: edit.php?action=insert&note=true&return=ok&msg=102&id='.$id);
	}
} // gw_insert_content
	
function gw_update_content() {
	$array = array();
	if(isset($_POST['content']['id'])) {
		foreach ($_POST['content'] as $key => $value) {
			$array[$key] = $value;
		}
		$type = $array['type'];
		$id = update_content($array);
	}
	if(isset($_POST['meta'])) {
		foreach($_POST['meta'] as $key => $value) {
			if(get_meta($id,$key)) {
			update_meta($id,$key,$value);
			} else {
			add_meta($id,$key,$value);
			}
		}
	}	
	if(isset($_FILES) && count($_FILES) >= 1) {
		add_file($id,null);
	}
	  
	if(isset($id) && check_content($id)) {
		header('location: edit.php?action=update&note=true&return=ok&msg=101&id='.$id);
	}
} // gw_update_content

function gw_updateoptions() {
	global $tab_options;
	if(isset($_POST['action']) && $_POST['action'] == 'update_options') {
		$db = new DataBase();
		foreach($_POST as $key => $value) {

				if(!is_array($value)) {
				// eseguo in sicurezza mysql_escape_string()
				$value = $db->Clean($value);
				}
				
				if(get_option($key) && $value != '' && !in_array($key,array('action','redirect'))) {
				
					if(is_array($value)) {
						$old = unserialize(get_option($key));
						$value = serialize(array_merge($old,$value));
					}
					$query ="UPDATE `$tab_options` SET `option_value` = '$value' WHERE `option_name` = '$key'";
					// ... la eseguo!
					$result = $db->Query($query);
				} elseif(!get_option($key) && !in_array($key,array('action','redirect'))) {
						if(is_array($value)) {
							$value = serialize($value);
						}
					$query = "INSERT INTO `$tab_options` (option_name,option_value) VALUES ('$key','$value');";
					// ... la eseguo!
					$result = $db->Query($query);
				}
		}
		// ok
		if($result == 1) {
			if(isset($_POST['redirect'])) { $redirect = '&tab='.$_POST['redirect']; }
			$url = gw_url_admin().'/setup.php?action=update&note=true&return=ok&msg=101'.$redirect;
			header('location:'.$url,301);
			exit;
		} else {
			echo 'Error';
			// do something
		}
	}
}

function gw_delete_media() {
	if(isset($_POST['delete_media']) && $_POST['delete_media'] == 1 && isset($_POST['media'])){
		delete_content($_POST['media'],TAB_CONTENT,false);
		header('location:media.php?action=delete&note=true&return=ok&return=error&msg=104');
	}
}

function gw_delete_users() {
	if(isset($_POST['delete_users']) && $_POST['delete_users'] == 1 && isset($_POST['user'])){
		if(count($_POST['user']) > 0) {
		$id = $_POST['user'];
		delete_user($id);
		header('location:allusers.php?action=delete&note=true&return=ok&return=error&msg=107');
		}
	}
}

function gw_updateuser() {
	global $tab_users,$salt;
	if(isset($_POST['action']) && $_POST['action'] == 'update_user' && isset($_POST['userID']) && is_numeric($_POST['userID'])) {
		$db = new DataBase();
		$id = $_POST['userID'];
		$error = null;
		foreach($_POST['user'] as $key => $value) {
		
				$value = $db->Clean($value);

				if($key == 'seckey') {
					$pass = md5($value.$salt);
					$query ="UPDATE `$tab_users` 
					SET $key = '$pass'
					WHERE `id` = '$id'";
					// ... la eseguo!
					$result = $db->Query($query);
					if($result == 0 || $result != 1) { $error = true; }
				} elseif($key != 'seckey' && $value != '') {
					// Scrivo la query e...
					//$query ="UPDATE `$tab_users` SET $key = '$value' WHERE id = '$id'";
					$query ="UPDATE `$tab_users` SET $key = '$value' WHERE `id` = '$id'";
					// ... la eseguo!
					$result = $db->Query($query);
					if($result == 0 || $result != 1) { $error = true; }
				}
		}
		// ok
		if(!$error) {
			$var = null;
			if(isset($_POST['redirect']) && $_POST['redirect'] == 'true') { $var = '&id='.$id; }
			$url = gw_url_admin().'/user.php?action=update&note=true&return=ok&msg=101'.$var;
			header('location:'.$url,301);
			exit;
		} else {
			header('location: user.php?action=userupdate&note=true&return=error&msg=103');
			exit;
		}
	}
}

function get_results($colonna,$value,$where = 'ID') {

	global $tab_content;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT *
	FROM `$tab_content`
	WHERE `$where` = '$value'";
	// ... la eseguo!
	$result = $db->GetRow($query);
	return $result[$colonna];
}

function get_the_title($id) {
	$result = get_results('title',$id);
	return $result;
}

function get_the_type($id) {
	$result = get_results('type',$id);
	return $result;
}

function get_the_date($id,$type = 'standard',$edit = null) {
	if($edit == 'edit') { $colonna = 'date_modified'; }
	else { $colonna = 'date'; }

	$result = get_results($colonna,$id);
	return $result;

	if($type == 'year') { $result = substr($result,0,4);}
	if($type == 'month') { $result = substr($result,5,2);}
	if($type == 'day') { $result = substr($result,8,2);}
	if($type == 'standard') { $result = substr($result,0,10);}
	if($type == 'standard_IT') { $result = substr($result,8,2).'-'.substr($result,5,2).'-'.substr($result,0,4);}
	return $result;
}

function get_the_content($id) {
	$result = get_results('content',$id);
	return $result;
}

function get_the_slug($id) {
	$result = get_results('slug',$id);
	return $result;
}

function get_the_status($id) {
	$result = get_results('status',$id);
	return $result;
}

function get_the_parent($id) {
	$result = get_results('parent',$id);
	return $result;
}

function get_the_link($id) {
	$result = get_results('link',$id);
	return $result;
}

function get_the_mime_type($id) {
	$result = get_results('mime_type',$id);
	return $result;
}

function get_the_menu_order($id) {
	$result = get_results('menu_order',$id);
	return $result;
}

function get_the_lang($id) {
	$result = get_results('lang',$id);
	return $result;
}

function get_the_site($id) {
	$result = get_results('site',$id);
	return $result;
}

function get_the_author($id) {
	$result = get_results('author',$id);
	return $result;
}

function get_option($name) {
	global $tab_options;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT *
	FROM `$tab_options`
	WHERE `option_name` = '$name'";
	// ... la eseguo!
	$result = $db->GetRow($query);
	$result = $result['option_value'];
	return $result;
}

function gw_url() {
	$url = get_option('gw_url');
	return $url;
}

function gw_url_admin() {
	$url = gw_url().'/gw_admin';
	return $url;
}

//facebook connect
function mysqlc() {
	$db = new DataBase();
	$link = $db->OpenConnection();
	$select = $db->SelectDatabase(DBNAME, $link);
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
	$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	switch ($theType) {
		case "text":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
			break;
		case "long":
		case "int":
			$theValue = ($theValue != "") ? intval($theValue) : "''";
			break;
		case "double":
			$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "''";
			break;
		case "date":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
			break;
		case "defined":
			$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
	}
	return $theValue;
}

function login(){
	if(isset($_POST['action']) && $_POST['action'] == 'gwlog') {
	$mess = '<div class="evi">Ci sono stati errori nel login.</div>';
		if(isset($_POST['user']) && isset($_POST['seckey'])) {
			$usermail = $_POST['user'];
			$seckey = $_POST['seckey'];
        	$login = new login;
       	 	$check = $login->accedi($usermail,$seckey);
       		if(isset($check)) { echo $mess; }
    	} else {
    		echo $mess;
    	}
	}
}

function is_logged() {
	if(isset($_SESSION['user'])) { return true; } else { return false; }
}

function gw_randPass($length = 8){
  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@';

  $str = '';
  $max = strlen($chars) - 1;

  for ($i=0; $i < $length; $i++)
    $str .= $chars[rand(0, $max)];

  return $str;
}

function gw_get_langs($id = null) {
	$lingue = get_option('lingue');
		if(isset($lingue) && count(unserialize($lingue)) >= 1) {
			if(isset($id)) {
				$db = new DataBase();
				$tab_content = TAB_CONTENT;
				// Scrivo la query e...
				$query = "SELECT * FROM $tab_content WHERE id='".$_GET['id']."'";
				// ... la eseguo!
				$result = $db->Query($query);
				while($row = mysql_fetch_array($result)) {
					$current_lang = $row['lang'];
				}
			}
			$options = '<select class="input-block-level" name="content[lang]">';
			$lingue = unserialize($lingue);
			foreach($lingue as $lingua) {
					$default = null;
					if(isset($lingua['stato']) && $lingua['stato'] == 'on') {
					if(isset($current_lang) && $current_lang == $lingua['id']) {
						$default = 'selected';
					}
					$options .='<option value="'.$lingua['id'].'" '.$default.'>'.$lingua['nome'].'</option>';
				}
			} 
		$options .= '</select>';
		echo $options;
	}
}

function default_lang() {
	$lingue = get_option('lingue');
		if(isset($lingue) && count(unserialize($lingue)) >= 1) {
			$lingue = unserialize($lingue);
			foreach($lingue as $key => $val) {
				if(isset($val['default']) && $val['default'] == 'on') {
					$lang = $key;
				}
			} 
		return $lang;
	}
}

function gw_check_posttype(){
	if(isset($_GET['gw_type'])) {
	$values = get_option('posttype');
	if(isset($values) && count(unserialize($values)) >= 1) {
		$posttypes = array();
		$values = unserialize($values);
			foreach($values as $value) {
				if(isset($value['state']) && $value['state'] == 'on') {
					$posttypes[] = $value['id'];
				}
			} // end foreach
			if(!in_array($_GET['gw_type'],$posttypes)) {
				header('location:'.gw_url_admin().'/?action=post_no-exist');
				exit;
			}
		} // end if have posttype
		else {
			header('location:'.gw_url_admin().'/?action=post_no-exist');
			exit;
		}
	} else {
		header('location:'.gw_url_admin().'/?action=post_no-exist');
		exit;
	}
}

function get_themes() {
	$theme = get_option('gw_theme');
	$dirs = glob('../gw_themes/*', GLOB_ONLYDIR);
	if(count($dirs >= 1)) {
		$table = '<form action="" method="post" class="form-horizontal">';
		
		$table .= '<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th width="150">Screenshot</th>
				<th>Tema</th>
				<th>Attiva</th>
			</tr>
		</thead>
		<tbody>
		';
	
		foreach($dirs as $dir) {
		
			$dirname = str_replace('../gw_themes/','',$dir);
			$file = $dir.'/style.css';
			$thumb = $dir.'/screenshot.jpg';
			if(file_exists($file)) {
			$select = $checked = null;
			if(isset($theme) && $theme == $dirname) { $select = 'success'; $checked = 'checked'; }
			$table .= '<tr class="'.$select.'">';
			$table .='<td>'; if(file_exists($thumb)) { $table .= '<img src="'. $thumb .'" alt="screenshot" />'; } echo '</td>';
			$table .='<td>';
			if(isset($theme) && $theme == $dirname) { $table .= '<span class="label label-success">'; }
			$table .= ucfirst($dirname);
			if(isset($theme) && $theme == $dirname) { $table .= '</span>'; }
			$table .= '</td>
					<td><input type="radio" name="gw_theme" value="'.$dirname.'"'.$checked.' /></td>
				</tr>';
			}
		}
		$table .= '</tbody></table>';
	
		$table .= '<!-- hiddens -->
		<input type="hidden" name="action" value="update_options" />
		<input type="hidden" name="redirect" value="themes" />
		<!-- fine hiddens -->
	
		<div class="form-actions">
			<button type="submit" class="btn btn-success">Salva impostazioni</button> 
			</div>
	</form>';
	}
	echo $table;
}

function get_meta_box($att,$nome,$id){
	$linkfull = get_image($att);
	$linktitle = get_image_title($att);
	$images = array('gif','jpeg','png','jpg');
	$ext = end(explode('.',$linkfull));
	$types = array('doc','docx','generale','html','pdf','ppt','pptx','pub','rar','rtf','txt','xls','xlsx','zip');
	if(in_array($ext,$images)) {
		$linkthumb = get_image($att,'thumb');
	} else {
		$linkthumb = gw_url().'/gw_lib/img/ext/'.$ext.'.png';
		if(!in_array($ext,$types)) {
			$linkthumb = gw_url().'/gw_lib/img/ext/generale.png';
		}
	} 
	
	
	$html = '<a href="'.$linkfull.'" class="lightbox">
		<img src="'.$linkthumb.'" alt="'.$linktitle.'" rel="img['.$nome.']" />
		</a>
		<a href="#meta-'.$nome.'" class="btn btn-danger btn-mini del_att" rel="delete=meta&id='.$id.'&key='.$nome.'">x</a>
		<div class="clearfix"></div>';
	return $html;
}