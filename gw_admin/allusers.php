<?php
require_once('../gw_core/gw_system.php');
include('header.php');
gw_updateuser();
$id = null;
if(isset($_GET['id'])) { $id = $_GET['id']; }
$user = new user;
$user = $user->data($id);
?>
<h3 class="admin-title">Tutti gli utenti</h3>
<hr />
<form action="" method="POST" />
<div class="pull-left">
	<div class="btn-group">
		<a href="#newuser" class="btn btn-success btn-small" role="button" data-toggle="modal">Nuovo utente</a>
		<a href="user.php" class="btn btn-small">Il mio profilo</a>
	</div>
</div>
<div class="pull-right">
	<button type="submit" class="btn btn-small btn-danger">Elimina utenti</button>
</div>
<div class="clearfix"></div>
<hr />
	<?php
	gw_delete_users();
	$tab_users = TAB_USERS;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $tab_users ORDER BY id ASC";
	// ... la eseguo!
	$result = $db->Query($query); 
	
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0) { ?>
	<ul id="users_list" class="pinterest_style">
	<?php
	while($row = mysql_fetch_array($result)) { 
	$date = $row['firstlog'];
	$firstlog = date("d F Y", strtotime($date));
	?>

	<li class="box">
			<div class="pin-thumb">
				<div class="close-del"><a href="" class="gw_del-content" rel="<?php echo $row['id']; ?>" title="pinterest" data-content="users"></a></div>
				<?php if(!empty($row['face'])) { ?>
				<img src="https://graph.facebook.com/<?php echo $row['face']; ?>/picture?width=146&height=146" alt="picture" />
				<?php } else { ?>
				<img src="../gw_lib/img/unknow.jpg" alt="unknow profile" />
				<?php } ?>
				<input type="checkbox" class="check" name="user[]" value="<?php echo $row['id']; ?>" style="display:none" /> 
				<div class="clearfix"></div>
				<span><?php echo $row['name']; ?></span>
				<div class="clearfix"></div>
			</div>
			<div class="pin-thumb-dett" style="text-align:center">
				<?php
				switch($row['role']) {
					case '1':
						echo '<span class="label">Utente</span>';
					break;
					
					case '10':
						echo '<span class="label label-info">Amministratore</span>';
					break;
				} ?>
				<a href="user.php?id=<?php echo $row['id']; ?>" class="btn btn-mini">Modifica</a>
				<div class="clearfix"></div>
			</div>
		</li>
	
  	<?php
		}
	} ?>
	</ul>
<div class="clearfix"></div>
<input type="hidden" name="delete_users" value="1" />
</form>


<!-- Modal -->
<div id="newuser" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="newuser" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
<h3>Nuovo utente</h3>
</div>
<form class="form-horizontal" action="newuser.php" method="POST">
<div class="modal-body">

<div class="control-group">
<label class="control-label" for="username">Nome utente</label>
<div class="controls">
<input type="text" id="username" name="user[name]" placeholder="Nome utente">
</div>
</div>

<div class="control-group">
<label class="control-label" for="inputEmail">Email</label>
<div class="controls">
<input type="text" id="inputEmail" name="user[email]" placeholder="Email">
</div>
</div>

</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
<button class="btn btn-primary">Crea</button>
</div>
</form>
</div>
	
<?php include('footer.php'); ?>