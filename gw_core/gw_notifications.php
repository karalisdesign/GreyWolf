<?php
// greywolf sistem
function gw_notifications() {
	if(isset($_GET['action']) && isset($_GET['note']) && $_GET['note'] == 'true' &&  isset($_GET['return']) ) {
		if($_GET['return'] == 'ok') {
				$return = 'alert-success';
			} elseif($_GET['return'] == 'error') {
				$return = 'alert-error';
			} elseif($_GET['return'] == 'notice') {
				$return = '';
		}
		if(isset($_GET['msg'])) {
			$msg = $_GET['msg'];
			if($msg == '101') $msg = 'Aggiornamento eseguito con successo!';
			if($msg == '102') $msg = 'Inserimento contenuto concluso con successo!';
			if($msg == '103') $msg = 'Ci sono stati degli errori!';
			if($msg == '104') $msg = 'Media eliminato definitivamente!';
			if($msg == '105') $msg = 'Contenuto eliminato definitivamente!';
			if($msg == '106') $msg = 'Utente eliminato definitivamente!';
			if($msg == '107') $msg = 'Utenti eliminati definitivamente!';
		}
		$box = '';
		$box .= '<div class="alert '.$return.'">';
		$box .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		$box .= $msg;
		$box .= '</div>';
		echo $box;
	}
}