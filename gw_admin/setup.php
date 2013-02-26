<?php
require_once('../gw_core/gw_system.php');
include('header.php'); ?>
<?php gw_updateoptions();?>

<?php if(isset($_GET['tab'])) { ?>
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
<h3 class="admin-title">Impostazioni</h3>
<hr />
<div class="tabbable">
    <ul id="setup" class="nav nav-pills">
		<li rel="generale"><a href="#generale" data-toggle="tab"><i class="icon-cog icon-white"></i> Generale</a></li>
		<li rel="seo"><a href="#seo" data-toggle="tab"><i class="icon-eye-open icon-white"></i> SEO</a></li>
   		<li rel="lingue"><a href="#lingue" data-toggle="tab"><i class="icon-globe icon-white"></i> Lingue</a></li>
   		<li rel="posttype"><a href="#posttype" data-toggle="tab"><i class="icon-file icon-white"></i> Post type</a></li>
   		<li rel="themes"><a href="#themes" data-toggle="tab"><i class="icon-leaf icon-white"></i> Themes</a></li>
   		<li rel="media"><a href="#media" data-toggle="tab"><i class="icon-picture icon-white"></i> Media</a></li>
    </ul>
	<hr />
    <div class="tab-content" id="tabbs">
	<div class="tab-pane fade in" id="generale">

		<form class="form-horizontal" action="" method="POST">

		<div class="control-group">
		<label class="control-label" for="gw_name-site"><strong>Nome del sito</strong></label>
			<div class="controls">
			<input type="text" id="gw_name-site" name="gw_name-site" class="input-block-level" value="<?php echo get_option('gw_name-site'); ?>" />    
			<p class="help-block">Imposta un nome per questo sito.</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="gw_url"><strong>Indirizzo web:</strong></label>
			<div class="controls">
			<input type="text" id="gw_url" name="gw_url" class="input-block-level" value="<?php echo get_option('gw_url'); ?>" />    
			<p class="help-block">Imposta indirizzo(URL) del sito.</p>
		</div>
		</div>
		
		<div class="control-group">
		<label class="control-label" for="gw_email-site"><strong>Email sito</strong></label>
			<div class="controls">
			<input type="text" id="gw_email-site" name="gw_email-site" class="input-block-level" value="<?php echo get_option('gw_email-site'); ?>" /> 
			<p class="help-block">Imposta un indirizzo di posta elettronica per quelle email che verranno generate dal sito.</p>
		</div>
		</div>
		
		<!-- hiddens -->
		<input type="hidden" name="action" value="update_options" />
		<input type="hidden" name="redirect" value="generale" />
		<!-- fine hiddens -->
	
		<div class="form-actions">
			<button type="submit" class="btn btn-success">Salva impostazioni</button> 
		</div>
		
		</form>
	
	</div><!-- fine #generale -->
	
	<div class="tab-pane fade in " id="seo">
		<form class="form-horizontal" action="" method="POST">

		<div class="control-group">
		<label class="control-label" for="gw_seo-title"><strong>Titolo sito:</strong></label>
			<div class="controls">
			<input type="text" id="gw_seo-title" name="gw_seo-title" class="input-block-level" value="<?php echo get_option('gw_seo-title'); ?>" />    
			<p class="help-block">Questo potrà comparire sulla barra della finestra e sui risultati di Google per la Home e altre pagine senza un titolo definito.</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="gw_seo-desc"><strong>Descrizione sito:</strong></label>
			<div class="controls">
			<textarea id="gw_seo-desc" name="gw_seo-desc" class="input-block-level"><?php echo get_option('gw_seo-desc'); ?></textarea>    
			<p class="help-block">Questo potrà comparire come descrizione sui risultati di Google per la Home e altre pagine senza una descrizione definita.</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="gw_seo-key"><strong>Keywords:</strong></label>
			<div class="controls">
			<textarea id="gw_seo-key" name="gw_seo-key" class="input-block-level"><?php echo get_option('gw_seo-key'); ?></textarea>    
			<p class="help-block">Utilizza delle singole parole chiave separate da una virgola per descrivere i contenuti di questo sito.</p>
		</div>
		</div>

		<!-- hiddens -->
		<input type="hidden" name="action" value="update_options" />
		<input type="hidden" name="redirect" value="seo" />
		<!-- fine hiddens -->
	
		<div class="form-actions">
			<button type="submit" class="btn btn-success">Salva impostazioni</button> 
		</div>
		
		</form>
	</div><!-- #ID SEO -->

	<div class="tab-pane fade in" id="lingue">
	
	
		<div style="text-align:right"><a class="btn btn-primary btn-small" data-toggle="modal" href="#new-lingua">Nuovo</a></div>
	<div class="modal hide" id="new-lingua">
	<form class="form-horizontal" action='' method="POST">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3>Lingua</h3>
	</div>
	<div class="modal-body">
	
    <div class="control-group key_temp" id="lingue">
    <label class="control-label" for="lang_id">ID Lingua</label>
    <div class="controls">
	<div class="input-append">
		<input type="text" id="lingue-val" name="lingue[id]" placeholder="" class="span2">
		<button class="btn btn-primary go_load" rel="lingue" type="button">Continua</button>
	</div>
    <p class="help-block">ex: per l'Italiano it_IT, per l'inglese en_EN</p>
    </div>
    </div>
	
	<div id="load-lingue"></div>
	
	<!-- hiddens -->
	<input type="hidden" name="action" value="update_options" />
	<input type="hidden" name="redirect" value="lingue" />
	<!-- fine hiddens -->
    
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
	<button type="submit" id="lingue" class="oksubmit btn btn-success" style="display:none">Inserisci</button>
	</div>
	</form>

	</div>
	<?php
	$values = get_option('lingue');
	if(isset($values) && count(unserialize($values)) >= 1) {
	?>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
    <tbody>
	<?php
	$values = unserialize($values);
	foreach($values as $value) { ?>
	<tr id="row-<?php echo $value['id']; ?>" class="<?php if(isset($value['default']) && $value['default'] == 'on') { echo 'success'; } ?>">
		<td class="span1"><span class="label label-warning"><?php echo $value['id']; ?></span></td>
		<td class="span9"><?php echo $value['nome']; ?></td>
		<td class="span1"><?php if(isset($value['default']) && $value['default'] == 'on') { echo '<span class="label label-success">Lingua di default</span>'; } ?></td>
		<td class="span1">
			<div class="btn-group">
			<a href="#edit-<?php echo $value['id']; ?>" data-toggle="modal" class="btn btn-small">Modifica</a>
			<a href="#del-<?php echo $value['id']; ?>" data-toggle="modal" class="btn btn-danger btn-small">Elimina</a>
			</div>

			<!-- Modal -->
		<div id="del-<?php echo $value['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="del-<?php echo $value['id']; ?>" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Cancella</h3>
		  </div>
		  <div class="modal-body">
			<p>Sei sicuro di voler cancellare questa lingua?</p>
			
			<div class="control-group">
			<label class="control-label" for="gw_content">ID</label>
			<div class="controls">
			<input type="text" value="<?php echo $value['id']; ?>" readonly class="input-block-level uneditable-input"  />
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="gw_content">Titolo</label>
			<div class="controls">
			<input type="text" value="<?php echo $value['nome']; ?>" readonly class="input-block-level uneditable-input" />
			</div>
			</div>
			
		  </div>
		  <div class="modal-footer">
			<div class="data"></div>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
			<button class="btn btn-danger delete-content" rel="lingue" title="<?php echo $value['id']; ?>">Elimina</button>
		  </div>
		</div>
		<!-- /modal -->

		<!-- Modal -->
		<div id="edit-<?php echo $value['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="del-<?php echo $value['id']; ?>" aria-hidden="true">
		  <form class="form-horizontal" action='' method="POST">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Modifica</h3>
		  </div>
		  <div class="modal-body">
				
				<input type="hidden" name="lingue[<?php echo $value['id']; ?>][id]" value="<?php echo $value['id']; ?>" />
				
				<div class="control-group">
			    <label class="control-label" for="nome">Nome:</label>
			    <div class="controls">
			    <input type="text" id="nome" name="lingue[<?php echo $value['id']; ?>][nome]" placeholder="" class="input-block-level" value="<?php echo $value['nome']; ?>" />
			    <p class="help-block">Inserisci il nome della lingua.</p>
			    </div>
			    </div>

			    <div class="control-group">
			    <label class="control-label" for="default">Default:</label>
			    <div class="controls">
			    <input type="checkbox" name="lingue[<?php echo $value['id']; ?>][default]" id="default" <?php if(isset($value['default']) && $value['default'] == 'on') { echo 'checked'; } ?> />
			    <p class="help-block">Imposta come lingua di default per questo sito.</p>
			    </div>
			    </div>
				
				<div class="control-group">
			    <label class="control-label" for="stato">Attivo:</label>
			    <div class="controls">
			    <input type="checkbox" class="on_off" name="lingue[<?php echo $value['id']; ?>][stato]" id="stato" <?php if(isset($value['stato']) && $value['stato'] == 'on') { echo 'checked'; } ?> />
			    <p class="help-block">Check attiva/ vuoto stand-by.</p>
			    </div>
			    </div>
			
		  <!-- hiddens -->
	<input type="hidden" name="action" value="update_options" />
	<input type="hidden" name="redirect" value="lingue" />
	<!-- fine hiddens -->
    
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
	<button type="submit" class="btn btn-success">Aggiorna</button>
	</div>
	</form>

	</div>
		<!-- /modal -->

		</td>
	</tr>
	<?php } // end foreach
	?>
	</tbody>
    </table>

    <?php }// end if exist lingue; 
    else { ?>
	<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attenzione!</strong> Non esistono valori per questo contenuto.
    </div>
	<?php } ?>
	
	</div><!-- #ID lingue -->


	<div class="tab-pane fade in" id="posttype">
	
	<div style="text-align:right"><a class="btn btn-primary btn-small" data-toggle="modal" href="#new-posttype">Nuovo</a></div>
	<div class="modal hide" id="new-posttype">
	<form class="form-horizontal" action='' method="POST">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4>Post type</h4>
	</div>
	<div class="modal-body">
	
    <div class="control-group key_temp" id="posttype">
    <label class="control-label" for="lang_id">ID</label>
    <div class="controls">
	<div class="input-append">
		<input type="text" id="posttype-val" name="posttype[id]" placeholder="" class="span2">
		<button class="btn btn-primary go_load" rel="posttype" type="button">Continua</button>
	</div>
    <p class="help-block">Utilizza una slug per identificare il tipo di contenuto.</p>
    </div>
    </div>
	
	<div id="load-posttype" class="loading"></div>
	
	<!-- hiddens -->
	<input type="hidden" name="action" value="update_options" />
	<input type="hidden" name="redirect" value="posttype" />
	<!-- fine hiddens -->
    
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
	<button type="submit" id="posttype" class="oksubmit btn btn-success" style="display:none">Inserisci</button>
	</div>
	</form>

	</div>
	<?php
	$values = get_option('posttype');
	//print_r(unserialize($values));
	if(isset($values) && count(unserialize($values)) >= 1) {
	?>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th>Stato</th>
				<th></th>
			</tr>
		</thead>
    <tbody>
	<?php
	$values = unserialize($values);
	foreach($values as $value) { ?>
	<tr id="row-<?php echo $value['id']; ?>" class="<?php if(isset($value['default']) && $value['default'] == 'on') { echo 'success'; } ?>">
		<td class="span1"><span class="label label-warning"><?php echo $value['id']; ?></span></td>
		<td class="span9"><?php echo $value['name']; ?></td>
		<td class="span1"><?php if(isset($value['state']) && $value['state'] == 'on') { echo '<span class="label label-success">Attivo</span>'; } ?></td>
		<td class="span1">
			<div class="btn-group">
			<a href="#edit-<?php echo $value['id']; ?>" rel="posttype" data-toggle="modal" class="btn btn-small edit_content">Modifica</a>
			<a href="#del-<?php echo $value['id']; ?>" data-toggle="modal" class="btn btn-danger btn-small">Elimina</a>
			</div>

			<!-- Modal -->
		<div id="del-<?php echo $value['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="del-<?php echo $value['id']; ?>" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4>Cancella</h4>
		  </div>
		  <div class="modal-body">
			<p>Sei sicuro di voler cancellare questo tipo di contenuto?</p>
			
			<div class="control-group">
			<label class="control-label" for="gw_content">ID</label>
			<div class="controls">
			<input type="text" value="<?php echo $value['id']; ?>" readonly class="input-block-level uneditable-input"  />
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="gw_content">Titolo</label>
			<div class="controls">
			<input type="text" value="<?php echo $value['name']; ?>" readonly class="input-block-level uneditable-input" />
			</div>
			</div>
			
		  </div>
		  <div class="modal-footer">
			<div class="data"></div>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
			<button class="btn btn-danger delete-content" rel="posttype" title="<?php echo $value['id']; ?>">Elimina</button>
		  </div>
		</div>
		<!-- /modal -->

		<!-- Modal -->
		<div id="edit-<?php echo $value['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="edit-<?php echo $value['id']; ?>" aria-hidden="true">
		  <form class="form-horizontal" action='' method="POST">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4>Modifica</h4>
		  </div>
		  	<div class="modal-body">
		  	<div id="load-posttype-<?php echo $value['id']; ?>" class="loading"></div>
		  	<!-- hiddens -->
			<input type="hidden" name="action" value="update_options" />
			<input type="hidden" name="redirect" value="posttype" />
			<!-- fine hiddens -->

	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Annulla</button>
	<button type="submit" class="btn btn-success">Aggiorna</button>
	</div>
	</form>

	</div>
		<!-- /modal -->

		</td>
	</tr>
	<?php } // end foreach
	?>
	</tbody>
    </table>

    <?php }// end if exist posttype; 
    else { ?>
	<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attenzione!</strong> Non esistono valori per questo contenuto.
    </div>
	<?php } ?>
	
	</div><!-- #ID posttype -->
	
	<div class="tab-pane fade in" id="themes">
	<?php get_themes(); ?>
	</div><!-- // fine # theme -->
	
	<div class="tab-pane fade in " id="media">
		<form class="form-horizontal" action="" method="POST">
			<div class="control-group">
			<label class="control-label" for="gw_media-path">Cartella</label>
			<div class="controls">
			<input type="text" value="<?php echo get_option('gw_media-path'); ?>" id="gw_media-path" name="gw_media-path" />
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="gw_media-thumb-width"><span class="label label-info">Miniatura</span></label>
			<div class="controls">
			<input type="text" value="<?php echo get_option('gw_media-thumb-width'); ?>" id="gw_media-thumb-width" name="gw_media-thumb-width"  class="input-mini" placeholder="width"/>
			<span class="label">&nbsp;x&nbsp;</span>
			<input type="text" value="<?php echo get_option('gw_media-thumb-height'); ?>" id="gw_media-thumb-height" name="gw_media-thumb-height"  class="input-mini" placeholder="height" />
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="gw_media-large-width"><span class="label label-info">Larga</span></label>
			<div class="controls">
			<input type="text" value="<?php echo get_option('gw_media-large-width'); ?>" id="gw_media-large-width" name="gw_media-large-width"  class="input-mini" placeholder="width"/>
			<span class="label">&nbsp;x&nbsp;</span>
			<input type="text" value="<?php echo get_option('gw_media-large-height'); ?>" id="gw_media-large-height" name="gw_media-large-height"  class="input-mini" placeholder="height" />
			</div>
			</div>
			
			<!-- hiddens -->
			<input type="hidden" name="action" value="update_options" />
			<input type="hidden" name="redirect" value="media" />
			<!-- fine hiddens -->
			
			<div class="form-actions">
			<button type="submit" class="btn btn-success">Salva impostazioni</button> 
			</div>
		</form>
	</div><!-- // fine # media -->
    
</div><!-- fine tab-content -->
</div>
<?php include('footer.php'); ?>