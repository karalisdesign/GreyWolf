<?php
require_once('../gw_core/gw_system.php');
include('header.php');
gw_updateuser();
$id = null;
if(isset($_GET['id'])) { $id = $_GET['id']; }
$user = new user;
$user = $user->data($id);
?>
<h3 class="admin-title"><?php echo $user['name']; ?></h3>
<hr />
<div class="pull-left">
	<div class="btn-group">
		<a href="#newuser" class="btn btn-success btn-small" role="button" data-toggle="modal">Nuovo utente</a>
		<a href="allusers.php" class="btn btn-small">Tutti gli utenti</a>
		<a href="user.php" class="btn btn-small">Il mio profilo</a>
	</div>
</div>
<div class="pull-right">
	<a href="#" class="btn btn-small btn-danger gw_del-content" rel="<?php echo $user['id']; ?>" title="single-user" data-content="users">Elimina utente</a>
</div>
<div class="clearfix"></div>
<hr />
<?php
/*
if(isset($_GET['tab'])) { ?>
<script>
jQuery(document).ready(function(){
	jQuery('.tab-content').find('.tab-pane').each(function(el){
		jQuery(this).removeClass('active')
	})
	jQuery('ul.nav-pills').find('li').each(function(el){
		jQuery(this).removeClass('active')
	})
	jQuery('ul.nav-pills li[rel="<?php echo $_GET['tab']; ?>"]').addClass('active')
	jQuery('.tab-content .tab-pane#<?php echo $_GET['tab']; ?>').addClass('active')
})
</script>
<?php } else { ?>
<script>
jQuery(document).ready(function(){
	jQuery('.nav-pills').find('li:first').addClass('active')
	jQuery('.tab-content').find('.tab-pane:first').addClass('active')
})
</script>
<?php } ?>
<div class="tabbable">
    <ul id="setup" class="nav nav-pills">
		<li class="active" rel="profile"><a href="#profile" data-toggle="tab"><i class="icon-chevron-right icon-white"></i> Profilo</a></li>
		<li><a href="allusers.php"><i class="icon-chevron-right icon-white"></i> Tutti gli utenti</a></li>
		<li rel="newuser"><a href="#newuser" data-toggle="tab"><i class="icon-chevron-right icon-white"></i> Nuovo utente</a></li>
    </ul>
    <div class="tab-content">
	<div class="tab-pane fade in active" id="profile">
	
	*/ ?>
	
	<div class="row-fluid">
		
		<div class="span3 pull-right" style="padding-left:25px">
		<?php if(!empty($user['face'])) { ?>
		<img src="https://graph.facebook.com/<?php echo $user['face']; ?>/picture?width=300&height=300" alt="picture" class="img-circle" id="profilepic" />
		<?php } else { ?>
		<img src="../gw_lib/img/unknow.jpg" alt="unknow profile" class="img-circle" id="profilepic" />
		<?php } ?>
		</div>
		
		<div class="span9 pull-left" style="margin-left:0!important">
	
		<form class="form-horizontal" action="" method="POST">
		
		<div class="control-group">
			<label class="control-label" for="name"><strong>Nome</strong></label>
			<div class="controls">
				<input type="text" id="name" name="user[name]" class="input-block-level" value="<?php echo $user['name']; ?>" />    
				<p class="help-block">Il tuo nome utente.</p>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="email"><strong>Email</strong></label>
			<div class="controls">
				<input type="text" id="email" name="user[email]" class="input-block-level" value="<?php echo $user['email']; ?>" />    
				<p class="help-block">Il tuo indirizzo email.</p>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="lang"><strong>Lingua</strong></label>
			<div class="controls">
				<select name="user[lang]" class="input-block-level">
					<?php
					$langs = array('0'=>array('lang'=>'it_IT','name'=>'Italiano'),'1'=>array('lang'=>'en_EN','name'=>'English'));
					foreach($langs as $language) {
						$sel = '';
						$lang = $language['lang'];
						$name = $language['name'];
						if($user['lang'] == $lang) { $sel = 'selected'; }
						$string = '<option value="'.$lang.'" '.$sel.'>'.$name.'</option>';
						echo $string;
					}
					?>
				</select>
				<p class="help-block">Seleziona lingua utente.</p>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="role"><strong>Ruolo</strong></label>
			<div class="controls">
				<select name="user[role]" class="input-block-level">
					<?php
					$roles = array('0'=>array('value'=>'1','name'=>'Utente'),'1'=>array('value'=>'10','name'=>'Amministratore'));
					foreach($roles as $role) {
						$sel = '';
						$rolevalue = $role['value'];
						$rolename = $role['name'];
						if($user['role'] == $rolevalue) { $sel = 'selected'; }
						$string = '<option value="'.$rolevalue.'" '.$sel.'>'.$rolename.'</option>';
						echo $string;
					}
					?>
				</select>
				<p class="help-block">Determina il livello di permessi.</p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="seckey"><strong>Security-key</strong></label>
			<div class="controls">
				<div class="row-fluid">
					<div class="span6">
						<span class="label label-warning" style="padding:10px;display:block">Hai già impostato un chiave di sicurezza.</span>
					</div>
					<div class="span6">
						<input type="password" autocomplete="off" id="seckey" name="user[seckey]" class="input-block-level" value="" />    
						<p class="help-block">Riscrivi la nuova sec-key per aggiornarla.</p>
					</div>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="lastlog"><strong>Registrazione:</strong></label>
			<div class="controls">
				<span class="label label-info"><?php echo $user['firstlog']; ?></span>
				<p class="help-block">Data in cui questo utente si è registrato.</p>
			</div>
		</div>
				
		<!-- hiddens -->
		<input type="hidden" name="action" value="update_user" />
		<input type="hidden" name="redirect" value="true" />
		<input type="hidden" name="userID" value="<?php echo $user['id']; ?>" />
		<!-- fine hiddens -->
		<hr />
		<div class="control-group">
		<label class="control-label" for="gw_user-submit"></label>
			<div class="controls">
			<button type="submit" id="gw_user-submit" class="btn btn-success">Aggiorna</button> 
		</div>
		</div>
		
		</form>
		
		</div><!-- /.span8 -->
		
	</div><!-- /.row-fluid -->
<?php /*	
	</div><!-- fine #profile -->
	
	<div class="tab-pane fade in active" id="newuser">
	<form class="form-horizontal" action="" method="POST">
		<div class="control-group">
			<label class="control-label" for="email"><strong>Email</strong></label>
			<div class="controls">
				<input type="text" id="email" name="user[email]" class="input-block-level" value="<?php echo $user['email']; ?>" />    
				<p class="help-block">Il tuo indirizzo email.</p>
			</div>
		</div>
		
		<!-- hiddens -->
		<input type="hidden" name="action" value="new_user" />
		<input type="hidden" name="redirect" value="true" />
		<!-- fine hiddens -->
		
	</form>
	</div><!-- fine #newuser -->
 
</div><!-- fine tab-content -->
 */ ?>  
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