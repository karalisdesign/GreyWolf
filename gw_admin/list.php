<?php 
require_once('../gw_core/gw_system.php');
gw_check_posttype();
include('header.php');
$values = get_option('posttype');
$values = unserialize($values);
?>
<script type="text/javascript">
	/*
	jQuery(document).ready(function(){
		var color = jQuery('#row-<?php echo $_GET['id']; ?> td').css('backgroundColor');
		jQuery('#row-<?php echo $_GET['id']; ?> td').each(function(index){
				jQuery(this).css({backgroundColor:'#C4FF66'}).animate({backgroundColor:color},1000)
		})
	})
	*/
</script>
		<div class="row-fluid">
			<div class="span6">
				<h3 class="admin-title"><?php echo $values[$_GET['gw_type']]['view']; ?></h3>
			</div>
				<div class="span6">
					<div class="pull-right">
					<a href="new.php?gw_type=<?php echo $_GET['gw_type']; ?>" class="btn btn-small btn-success">
						<i class="icon-pencil icon-white"></i>
						<strong><?php echo $values[$_GET['gw_type']]['new']; ?></strong>
						</a>
					</div>
				</div>
		<div class="clearfix"></div>
			<hr />
		</div>
<?php
	$tab_content = TAB_CONTENT;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $tab_content WHERE type='".$_GET['gw_type']."' AND status='publish' ORDER BY id DESC";
	// ... la eseguo!
	$result = $db->Query($query);
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0) {
	?>
		<form class="form-horizontal" action="" method="POST">
		<table class="table table-striped table-hover" id="list">
		<thead>
			<tr>
				<th>Titolo</th>
				<th></th>
			</tr>
		</thead>
    <tbody>
<?php while($row = mysql_fetch_array($result)) { ?>
	<tr id="row-<?php echo $row['ID']; ?>" class="<?php if(isset($_GET['ID']) && $_GET['ID']  == $row['ID']) { echo 'seleziona';} ?>">
		
	<td class="span9">
		<a href="edit.php?id=<?php echo $row['ID']; ?>"><?php echo $row['title']; ?></a> 
		<span class="list-options"> &raquo; <a href="<?php echo $row['link']; ?>" target="_blank"><span class="label label-info">Anteprima</span></a></span>
			<div class="clearfix"></div>
			<div id="dett-<?php echo $row['ID']; ?>" class="gw_list-dett">
				<?php
				$date = $row['date'];
				$newDate = date("d F Y", strtotime($date));
				$ora = date("H:i", strtotime($date));
				$published = 'alle ore '.$ora.' il '.$newDate;
				?>
				<img src="<?php echo gw_url(); ?>/gw_lib/img/lang/<?php echo $row['lang'];  ?>.png" alt="<?php echo $row['lang'];  ?>"/>
				<small><?php if($row['status'] == 'publish') { echo 'Pubblicato';} elseif($row['status'] == 'hidden') { echo 'Nascosto';} ?> <?php echo $published; ?></small> 
				<div class="clearfix"></div>
			</div><!-- fine /gw_list-dett -->
	</td>
		
	<td class="span3">
		<div class="list-options" style="text-align:right;display:block">
		<a href="#" rel="<?php echo $row['ID']; ?>" class="gw_list-view-dett ">Dettagli</a> | 
		<a href="edit.php?id=<?php echo $row['ID']; ?>"> Modifica</a> |
		<a href="#del-<?php echo $row['ID']; ?>" role="button" data-toggle="modal" class="bt-red">Elimina</a>
		</div>
	
		<!-- Modal -->
		<div id="del-<?php echo $row['ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="del-<?php echo $row['id']; ?>" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Cancella</h3>
		  </div>
		  <div class="modal-body">
			<p>Sei sicuro di voler cancellare questo contenuto?</p>
			
			<div class="control-group">
			<label class="control-label" for="gw_content">ID</label>
			<div class="controls">
			<input type="text" value="<?php echo $row['ID']; ?>" readonly class="input-block-level uneditable-input"  />
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="gw_content">Titolo</label>
			<div class="controls">
			<input type="text" value="<?php echo $row['title']; ?>" readonly class="input-block-level uneditable-input" />
			</div>
			</div>
			
		  </div>
		  <div class="modal-footer">
			<div class="data"></div>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
			<button class="btn btn-primary delete-content" rel="content" title="<?php echo $row['ID']; ?>">Ok</button>
		  </div>
		</div>
		<!-- /modal -->
	</td>
	
    </tr>
  <?php } ?>
  	</tbody>
    </table>
</form>
<?php } else {
		echo 'Non esistono contenuti!';
	}
	include('footer.php'); ?>