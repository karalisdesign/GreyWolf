<?php
require_once('gw_core/gw_system.php');
unset($_SESSION);
header("Location:".gw_url());
exit;
?>