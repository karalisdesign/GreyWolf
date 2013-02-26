<?php
function gw_menu($name) {
	$url = gw_url();
	$url_admin = gw_url_admin();
	$user = new user;
	$user = $user->data();
	global $loginUrl;
	$menu = '';
	$item_admin = array('widget');
	if(isset($name) && $name === 'main') { 
	$menu .= '<div class="navbar navbar-inverse navbar-fixed-top" id="mainMenu">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="'.$url.'">'.get_option('gw_name-site').'</a>
		  <div class="nav-collapse collapse">
		  <ul class="nav">';
	$active = null;
	$url = $_SERVER['SCRIPT_NAME']; 
	if(strpos($url,'index.php')) {
		$active = 'active';
	}
	$menu .= '<li class="'.$active.'"><a href="'.gw_url_admin().'"><i class="icon-home icon-white"></i> Bacheca</a></li>';
	$active = null;
	$url = $_SERVER['SCRIPT_NAME']; 
	if(strpos($url,'add.php')) {
		$active = 'active';
	}
	$menu .= '<ul class="nav">
				<li class="dropdown '.$active.'"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-file icon-white"></i> Nuovo<b class="caret"></b></a>
					<ul class="dropdown-menu">';
						$values = get_option('posttype');
						if(isset($values) && count(unserialize($values)) >= 1) {
							$values = unserialize($values);
							foreach($values as $value) { 
								if(isset($value['state']) && $value['state'] == 'on') {
									if(!in_array($value['id'],$item_admin)) {
									$menu .= '<li><a href="'.$url_admin.'/new.php?gw_type='.$value['id'].'">'.$value['name'].'</a></li>';
									} elseif(in_array($value['id'],$item_admin) && $user['role'] == 10) {
									$menu .= '<li><a href="'.$url_admin.'/new.php?gw_type='.$value['id'].'">'.$value['name'].'</a></li>';
									}
								}
							} // end foreach
						} // end if post type exist
	$menu .= '</ul>
				</li>
			</ul>';
	$active = null;
	$url = $_SERVER['SCRIPT_NAME']; 
	if(strpos($url,'list.php')) {
		$active = 'active';
	}
    $menu .= '<ul class="nav">
				<li class="dropdown '.$active.'"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list icon-white"></i> Visualizza <b class="caret"></b></a>
					<ul class="dropdown-menu">';

	$values = get_option('posttype');
	if(isset($values) && count(unserialize($values)) >= 1) {
		$values = unserialize($values);
		foreach($values as $value) { 
			if(isset($value['state']) && $value['state'] == 'on') {
				$menu .= '<li><a href="'.$url_admin.'/list.php?gw_type='.$value['id'].'">'.$value['plural'].'</a></li>';
			}
		} // end foreach
	} // end if post type exist
	$menu .= '<li class="divider"></li>';
	$menu .= '<li><a href="'.$url_admin.'/media.php">Media</a></li>';			
	$menu .= '</ul>
				</li>
			</ul>';
	$active = null;
	$url = $_SERVER['SCRIPT_NAME']; 
	if(strpos($url,'setup.php')) {
		$active = 'active';
	}
     $menu .= '<ul class="nav">
				<li class="dropdown '.$active.'"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i> Impostazioni <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="'.$url_admin.'/setup.php?tab=generale">Generale</a></li>
						<li><a href="'.$url_admin.'/setup.php?tab=seo">SEO</a></li>
						<li><a href="'.$url_admin.'/setup.php?tab=lingue">Lingue</a></li>
						<li><a href="'.$url_admin.'/setup.php?tab=posttype">Tipi di contenuto</a></li>
						<li><a href="'.$url_admin.'/setup.php?tab=themes">Temi</a></li>
						<li><a href="'.$url_admin.'/setup.php?tab=media">Media</a></li>
					</ul>
				</li>
			</ul>';
    $menu .= '<li class=""><a href="#support"><i class="icon-envelope icon-white"></i> Supporto tecnico</a></li>';
    $menu .= '</ul>'; // END NAV WRAP
		
	$menu .= '<ul class="nav pull-right">';
	
	if(!isset($_SESSION['user'])) {
	$menu .= '<li><a href="'.$loginUrl.'">Accedi con Facebook</a></li>';
	} else {
    $menu .= '<li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#"><strong><i class="icon-user icon-white"></i> '.$user['name'].'</strong> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="'.$url_admin.'/user.php">Profilo</a></li>
					<li><a href="'.$url_admin.'/allusers.php">Utenti</a></li>
					<li><a href="'.gw_url().'/logout.php">Logout</a></li>
                </ul>
            </li>';
    $menu .= '</ul>';
	}
	
    $menu .= '</div>
        </div>
      </div>
    </div>';
			return $menu;
	}
} // fine gw_menu