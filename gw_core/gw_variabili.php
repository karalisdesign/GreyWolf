<?php

	class GreyWolf {
		public $ID;
		public $vars;

        var $tab_content = TAB_CONTENT;
       
        function __construct( $ID ) {
            $this->ID = $ID;
        }

        function getSql(){
            global $tab_content;
            $db = new DataBase();
            // Scrivo la query e...
            $query = "SELECT *
            FROM `$tab_content`
            WHERE `ID` = '$this->ID'";
            // ... la eseguo!
            $result = $db->GetRow($query);
            return $result;
        }

        function getVars(){
        	$title = get_the_title($this->ID);
        	$author = get_the_author($this->ID);

        	$date = get_the_date($this->ID);
        	$year = get_the_date($this->ID,'year');
        	$month = get_the_date($this->ID,'month');
        	$day = get_the_date($this->ID,'day');
        	$standard_IT = get_the_date($this->ID,'standard_IT');

        	$date_mod = get_the_date($this->ID,'standard','edit');
        	$year_mod = get_the_date($this->ID,'year','edit');
        	$month_mod = get_the_date($this->ID,'month','edit');
        	$day_mod = get_the_date($this->ID,'day','edit');
        	$standard_IT_mod = get_the_date($this->ID,'standard_IT','edit');

        	$content = get_the_content($this->ID);
        	$type = get_the_type($this->ID);
        	$status = get_the_status($this->ID);
        	$lang = get_the_lang($this->ID);
        	$slug = get_the_slug($this->ID);
        	$site = get_the_site($this->ID);
        	$parent = get_the_parent($this->ID);
        	$link = get_the_link($this->ID);
        	$menu_order = get_the_menu_order($this->ID);
        	$mime_type = get_the_mime_type($this->ID);
        	

        	$vars['ID'] = $this->ID;
        	$vars['author'] = $author;

        	$vars['date']['standard'] = $date;
        	$vars['date']['standard_IT'] = $standard_IT;
        	$vars['date']['year'] = $year;
        	$vars['date']['month'] = $month;
        	$vars['date']['day'] = $day;

        	$vars['date_mod']['standard'] = $date_mod;
        	$vars['date_mod']['standard_IT'] = $standard_IT_mod;
        	$vars['date_mod']['year'] = $year_mod;
        	$vars['date_mod']['month'] = $month_mod;
        	$vars['date_mod']['day'] = $day_mod;

        	$vars['title'] = $title;
        	$vars['type'] = $type;
        	$vars['status'] = $status;
        	$vars['site'] = $site;
        	$vars['lang'] = $lang;
        	$vars['slug'] = $slug;
        	$vars['parent'] = $parent;
        	$vars['link'] = $link;
        	$vars['menu_order'] = $menu_order;
        	$vars['mime_type'] = $mime_type;
        	$vars['content'] = $content;

        	return $vars;
        }

        function getQuery() {
            return $this->getVars();
        }
	}
?>