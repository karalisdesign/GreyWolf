<?php 
require_once('../gw_core/gw_system.php');
include('header.php');
?>
<h3 class="admin-title">Media</h3>
<hr />
<?php

	gw_delete_media();
	$tab_content = TAB_CONTENT;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $tab_content WHERE type='attachment' ORDER BY id DESC";
	// ... la eseguo!
	$result = $db->Query($query); 
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0) {
	?>
	<form class="form-horizontal" action="" method="POST">
	
	<p class="pull-right"><button type="submit" class="btn btn-danger btn-small">Elimina</button></p>
	<div class="clearfix"></div>
	<hr />
	<div id="filters">
	<strong>Filtra: </strong>
		<div class="btn-group">
		<a href="" data-filter="*" class="btn btn-small btn-inverse">Tutti</a>
	<?php 
	$result = $db->Query($query); 
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0) {
		$lista = array();
	while($row = mysql_fetch_array($result)) { 
		$linkfull = get_image($row['ID']);
		$ext = end(explode('.',$linkfull));
		if(!in_array($ext,$lista)) {
		$lista[] = $ext;
		echo '<a href="" data-filter=".'.$ext .'" class="btn btn-small">'.ucfirst($ext).'</a>';
		}
	} 
	} ?>
		</div>
	</div>
	<hr />
	<div class="clearfix"></div>
	<?php 
	$result = $db->Query($query); 
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0) { ?>
	<ul id="media_content" class="pinterest_style">
	<?php 
	$num = 1;
	while($row = mysql_fetch_array($result)) { 
	$id = $row['ID'];
	$html = '';
	$linkfull = get_image($id);
	$linktitle = get_image_title($id);
	$images = array('gif','jpeg','png','jpg');
	$ext = end(explode('.',$linkfull));
	$date = $row['date'];
	$newDate = date("d F Y", strtotime($date));
	?>
		<li class="box <?php echo $ext; ?>">
			<div class="pin-thumb">
				<div class="close-del"><a href="" class="gw_del-content" rel="<?php echo $id; ?>" title="pinterest" data-content="content"></a></div>
				<?php
				$types = array('doc','docx','generale','html','pdf','ppt','pptx','pub','rar','rtf','txt','xls','xlsx','zip');
				if(in_array($ext,$images)) {
					$linkthumb = get_image($id,'thumb');
					$rel = 'rel="gal"';
				} else {
					$linkthumb = gw_url().'/gw_lib/img/ext/'.$ext.'.png';
					if(!in_array($ext,$types)) {
						$linkthumb = gw_url().'/gw_lib/img/ext/generale.png';
					}
				} 
				
				//$html .= '<a href="'.$linkfull.'" class="lightbox" '.$rel.'>';
				$html .= '<img src="'.$linkthumb.'" alt="'.$linktitle.'" />';
				//$html .= '</a>';
				echo $html;
				?>
				<div class="clearfix"></div>
				<input type="checkbox" class="check" name="media[]" value="<?php echo $row['ID']; ?>" style="display:none" /> 
				<span><?php echo $row['title']; ?></span>
				<div class="clearfix"></div>
			</div>
			<div class="pin-thumb-dett">
				<!-- <p>File: <strong><?php echo $ext; ?></strong></p> -->
				<p>Caricato il: <?php echo $newDate; ?></p>
				<p>Collegato a: <?php $post = get_meta_post($id); $post = get_content($post); if(check_content($post['ID'])) { echo '<a href="edit.php?id='.$post['ID'].'" target="_blank">'.$post['title'].'</a>'; } else { echo 'Nessun contenuto impostato'; } ?></p>
				<p style="text-align:center"><a href="#modal-dett" rel="<?php echo $id; ?>" data-toggle="modal" class="btn btn-info btn-mini media-dett">Dettagli &raquo;</a></p>
				<div class="clearfix"></div>
			</div>
		</li>
	<?php $num++; }

	} ?>
	</ul>
	<div class="clearfix"></div>
	<hr />
	<p class="pull-right"><button type="submit" class="btn btn-danger btn-small">Elimina</button></p>
	<div class="clearfix"></div>
	
	
	<input type="hidden" name="delete_media" value="1" />
	</form>

	<!-- Modal -->
		<div id="modal-dett" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-dett" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Dettagli media</h3>
		  </div>
		  <div class="modal-body">
			
			<div id="modal-dett-html"></div>
			
		  </div>
		  <div class="modal-footer">
			<div class="data"></div>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Chiudi</button>
		  </div>
		</div>
	<!-- /modal -->
<?php } 
	else { echo 'Non esistono media'; } 
	include('footer.php'); ?>