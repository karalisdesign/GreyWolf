<?php
require_once('../gw_core/gw_system.php');
include('header.php');
$id = $_GET['id'];
gw_update_content($id);
$values = get_option('posttype');
$values = unserialize($values);

//print_r($_FILES);

	$tab_content = TAB_CONTENT;
	$db = new DataBase();
	// Scrivo la query e...
	$query = "SELECT * FROM $tab_content WHERE id='".$_GET['id']."'";
	// ... la eseguo!
	$result = $db->Query($query);
	while($row = mysql_fetch_array($result)) { ?>
	<h3 class="admin-title"><?php echo $values[$row['type']]['name']; ?></h3>
	<hr />
	<form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
    <fieldset>
    <div class="control-group">
    <!-- Username -->
    <label class="control-label" for="gw_title">Titolo</label>
    <div class="controls">
    <input type="text" id="gw_title" name="content[title]" placeholder="" class="input-block-level" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'new') { echo ''; } else { echo $row['title']; } ?>">
    <p class="help-block">Scrivi un titolo per il contenuto che stai creando.</p>
    </div>
    </div>
	
    <div class="control-group">
    <label class="control-label" for="gw_content">Contenuto</label>
    <div class="controls">
    <textarea id="gw_content" name="content[content]" class="input-block-level" cols="120" rows="10"><?php echo $row['content']; ?></textarea>
    <p class="help-block">Aggiungi un contenuto.</p>
    </div>
    </div>
	
	<?php if($row['type'] == 'widget') {

	} else { ?>
	<div class="control-group" id="control-meta">
    <label class="control-label" for="gw_meta">&nbsp;</label>
    <div class="controls">

            <ul class="nav nav-tabs" id="itemmeta">
                <li class="active"><a data-toggle="tab" href="#tabcover">Copertina</a></li>
                <li><a data-toggle="tab" href="#tabgallery">Gallery</a></li>
                <?php if(isset($values[$row['type']]['meta'])) {
                echo '<li><a data-toggle="tab" href="#customfields">Campi personalizzati</a></li>';
                } ?>
            </ul>
            <div class="tab-content" id="tabmeta">
              	<div id="tabcover" class="tab-pane fade active in">
		
						<div class="itemMeta">
						
						<script type="text/javascript">
							<?php $timestamp = time();?>
							jQuery(function() {
								jQuery('.uploadify').each(function(){
									var thename = jQuery(this).attr('id');
									jQuery(this).uploadify({
										'auto':false,
										'formData'     : {
											'timestamp' : '<?php echo $timestamp;?>',
											'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
											'id' : '<?php echo $_GET['id']; ?>'
										},
										'queueID' : 'queue-'+thename,
										'fileObjName' : thename,
										'buttonClass' : 'btn btn-info',
										'buttonText' : 'Seleziona file',
										'swf'      : '<?php echo gw_url(); ?>/gw_lib/uploadify/uploadify.swf',
										'uploader' : '../gw_core/ajax/upload.php',
										
										'onUploadSuccess' : function(file, data, response) {
										
											var newdata = jQuery.parseJSON(data);
											jQuery.ajax({
												type: 'POST',
												url: '../gw_core/ajax/upload-html.php',
												data: { 
													timestamp: '<?php echo $timestamp;?>', 
													token: '<?php echo md5('unique_salt' . $timestamp);?>',
													name: thename,
													original: newdata.original,
													thumb: newdata.thumb,
													title: newdata.link,
													att: newdata.att,
													post: newdata.post
												}
											}).done(function(result) {
												jQuery('#thumb-'+thename).show().html(result)
											});
										} 
									});
								})
							});
						</script>
						
						<?php 
						
								$att = get_meta($row['ID'],'cover');	
								$mime_images = array('image/gif','image/jpeg','image/png');
								if(isset($att)) {
								echo '<div id="meta-cover" class="meta-box">
								<div class="thumb img-polaroid" id="thumb-cover">';
								echo get_meta_box($att,'cover',$row['ID']);
								echo '</div>';
								?>
								
								<div class="upload-command">
									<div id="upload-cover" class="upload-box">
										<input id="cover" name="cover" type="file" class="uploadify" />
									</div>
									<div id="start-cover" class="start-box">
									<a href="javascript:jQuery('#cover').uploadify('upload','*')">Carica!</a>
									</div>
								<div class="clearfix"></div>
								</div>
								
								<div id="queue-cover"></div>
								
								<?php echo '</div>';
								} else { ?>
								<div id="meta-cover" class="meta-box">
								<div class="thumb img-polaroid" id="thumb-cover">
								
								</div>
								
								<div class="upload-command">
									<div id="upload-cover" class="upload-box">
										<input id="cover" name="cover" type="file" class="uploadify" />
									</div>
									<div id="start-cover" class="start-box">
									<a href="javascript:jQuery('#cover').uploadify('upload','*')">Carica!</a>
									</div>
								<div class="clearfix"></div>
								</div>
								
								<div id="queue-cover"></div>
								</div>
								
								<?php 
								// echo '<input type="file" name="cover" class="input-block-level" />'; 
								}
							?>	
						<div class="clearfix"></div>
					</div>
              	</div>
               	<div id="tabgallery" class="tab-pane fade">
                	<div class="itemMeta">
					<?php
					$table = TAB_META;
					$db = new DataBase();
					// Scrivo la query e...
					$meta_query = "SELECT * FROM $table WHERE post_id='".$_GET['id']."' AND meta_key='gallery' ORDER BY meta_key ASC";
					// ... la eseguo!
					$meta_result = $db->Query($meta_query);
					$num = 1;
					while($meta_row = mysql_fetch_array($meta_result)) { 
					$att = $meta_row['meta_id'];
					?>
					<div class="meta-box" id="meta-gallery-<?php echo $num; ?>">
						<div id="thumb-meta-gallery-<?php echo $num; ?>" class="thumb img-polaroid">
						<a class="lightbox" href="<?php echo get_image('','',$att); ?>" rel="gallery">
							<img alt="<?php echo get_image_title('',$att); ?>" src="<?php echo get_image('','thumb',$att); ?>">
						</a>
						<a rel="delete=meta_id&id=<?php echo $att; ?>" class="btn btn-danger btn-mini del_meta-gal" href="javascript:void(0);">x</a>
						<div class="clearfix"></div>
						</div>
					</div>
					<?php $num++; }
					?>
					
					<?php $timestamp = time();?>
					<script type="text/javascript">
						jQuery(function() {
							jQuery('.uploadify_gal').uploadify({
								'auto':false,
								'formData': {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
										'id' : '<?php echo $_GET['id']; ?>'
									},
								'queueID' : 'queue-gallery',
								'fileObjName' : 'gallery',
								'buttonClass' : 'btn btn-info',
								'buttonText' : 'Seleziona file',
								'swf'      : '<?php echo gw_url(); ?>/gw_lib/uploadify/uploadify.swf',
								'uploader' : '../gw_core/ajax/upload.php',
								'onUploadStart' : function(file) {
									//alert(thename);
									//jQuery('#meta-'+thename+' .upload-box').hide()
								},
								'onUploadSuccess' : function(file, data, response) {
									var newdata = jQuery.parseJSON(data);
									jQuery.ajax({
										type: 'POST',
										url: '../gw_core/ajax/upload-html-gal.php',
										data: { 
											timestamp: '<?php echo $timestamp;?>', 
											token: '<?php echo md5('unique_salt' . $timestamp);?>',
											name: 'attachment',
											original: newdata.original,
											thumb: newdata.thumb,
											title: newdata.link,
											att: newdata.att
										}
									}).done(function(result) {
										jQuery('#tabgallery .meta-box').each(function(i,el){
											name = jQuery(this).attr('id');
											thename = name+'-'+(i+1);
										}) // fine EACH
										jQuery('#tabgallery .itemMeta').prepend('<div id="'+thename+'" class="meta-box"><div class="thumb img-polaroid" id="thumb-'+thename+'">'+result+'</div></div>')
									});
								} 
							});	
						})	
					</script>
					<div class="clearfix"></div>
					
					<div id="meta-gallery" class="meta-box">
					
						<div class="upload-command">
								<div id="upload-gallery" class="upload-box">
									<input id="gallery" name="gallery[]" type="file" class="uploadify_gal" />
								</div>
								<div id="start-gallery" class="start-box">
								<a href="javascript:jQuery('#gallery').uploadify('upload','*')">Carica!</a>
								</div>
							<div class="clearfix"></div>
						</div>
						
						<?php /*
						<div id="upload-gallery" class="upload-box">
							<input id="gallery" name="gallery[]" type="file" class="uploadify_gal" />	
							<div class="clearfix"></div>							
						</div>	
						*/ ?>
						<div id="queue-gallery"></div>
					</div>		
					<div class="clearfix"></div>
                	</div>
					<div class="clearfix"></div>
              	</div>
              	<div id="customfields" class="tab-pane fade">
                	<div class="itemMeta">
                	
                    <?php
                    if(isset($values[$row['type']]['meta'])) {
                    $meta = $values[$row['type']]['meta'];
                    if(isset($meta))
                    if(count($meta >= 1)) {
                        foreach($meta as $key => $val){ ?>
                        <div class="control-group">
                        <label class="control-label" for="gw_meta_facebook"><strong><?php echo $val['nome']; ?></strong></label>
                        <div class="controls">
                        <?php
                        switch($val['type']) {
                            case 'inputtext':
                                echo '<input type="text" name="meta['.$val['slug'].']" class="input-block-level" value="'.get_meta($row['ID'],$val['slug']).'" />'; 
                                break;
                            case 'file':
								$att = get_meta($row['ID'],$val['slug']);
								$mime_images = array('image/gif','image/jpeg','image/png');
								if(isset($att)) { 
								echo '<div id="meta-'.$val['slug'].'" class="meta-box">
								<div class="thumb img-polaroid" id="thumb-'.$val['slug'].'">';
								echo get_meta_box($att,$val['slug'],$row['ID']);
								echo '</div>';
								?>
								
								<div class="upload-command">
									<div id="upload-<?php echo $val['slug']; ?>" class="upload-box">
										<input id="<?php echo $val['slug']; ?>" name="<?php echo $val['slug']; ?>" type="file" class="uploadify" />
									</div>
									<div id="start-<?php echo $val['slug']; ?>" class="start-box">
									<a href="javascript:jQuery('#<?php echo $val['slug']; ?>').uploadify('upload','*')">Carica!</a>
									</div>
								<div class="clearfix"></div>
								<?php
								echo '</div>
								
								<div id="queue-'.$val['slug'].'"></div>
								
								</div>';
								} else { ?>
								
								<div id="meta-<?php echo $val['slug']; ?>" class="meta-box">
								<div class="thumb img-polaroid" id="thumb-<?php echo $val['slug']; ?>">
								
								</div>
								
								<div class="upload-command">
									<div id="upload-<?php echo $val['slug']; ?>" class="upload-box">
										<input id="<?php echo $val['slug']; ?>" name="<?php echo $val['slug']; ?>" type="file" class="uploadify" />
									</div>
									<div id="start-<?php echo $val['slug']; ?>" class="start-box">
									<a href="javascript:jQuery('#<?php echo $val['slug']; ?>').uploadify('upload','*')">Carica!</a>
									</div>
								<div class="clearfix"></div>
								</div>
								
								<div id="queue-<?php echo $val['slug']; ?>"></div>
								
								</div>
								<?php 
								// echo '<input type="file" name="cover" class="input-block-level" />'; 
								}
								
                                
                                break;
                            case 'hidden':
                                echo '<input type="hidden" name="meta['.$val['slug'].']" class="input-block-level" value="'.get_meta($row['ID'],$val['slug']).'"/>'; 
                                break;
                            case 'textarea':
                                echo '<textarea name="meta['.$val['slug'].']" class="input-block-level" cols="10" rows="5">'.get_meta($row['ID'],$val['slug']).'</textarea>'; 
                                break;
                            case is_array($val['type']):
                                
                                foreach($val['type'] as $keyoption => $valueoption) {
                                    switch($keyoption) {
                                        case 'select' :
                                            echo '<select name="meta['.$val['slug'].']">';
                                            echo '<option>---</option>';
                                            foreach($valueoption as $opt) {
                                            $selected = null;
                                            if(get_meta($row['ID'],$val['slug']) == $opt) { $selected = 'selected'; }
                                            echo '<option value="'.$opt.'" '.$selected.'>'.$opt.'</option>';
                                            }
                                            echo '</select>';
                                        break;
                                    }
                                }
                                
                                break;
                        } ?>
						<div class="clearfix"></div>
                        <p class="help-block"><?php echo $val['desc']; ?></p>
                        </div>
                        </div>
                        <?php
                        }
                    }
                }
                    ?>

                	</div>
              	</div>
            </div>


    </div>
    </div>

    <?php } ?>
	

	<?php if($user['role'] == 10)  { ?>
	<div class="control-group">
    <label class="control-label" for="gw_status"></label>
    <div class="controls">
		<div class="well">

    	<button id="button-opz-adv" rel="toggle" class="btn btn-small" type="button"><strong>Opzioni avanzate &raquo;</strong></button>
		<div class="clearfix"></div>
		<div id="opz-adv" class="hide form-adv">
		
			<div class="control-group">
				<label class="control-label" for="gw_menu"><strong>Menu</strong></label>
				<div class="controls">
				<input class="span2" size="16" type="text" name="content[menu_order]" value="<?php echo $row['menu_order']; ?>">
				<p class="help-block"><small>Ordine della pagina nel menù. Lascia valore 0 per non far visualizzare la pagina nel menù.</small></p>
				</div>
			</div>
		
		
			<div class="control-group">
				<label class="control-label" for="gw_date"><strong>Data di pubblicazione</strong></label>
				<div class="controls">
				    <div class="input-append date" id="dp3" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
    <input class="span2" size="16" type="text" name="content[date]" value="<?php echo $row['date']; ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
				<p class="help-block"><small>Puoi indicare una data di pubblicazione diversa. Il formato è in <span class="label">yyyy-mm-dd</span> (Anno-Mese-Giorno).</small></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="gw_status"><strong>Stato</strong></label>
				<div class="controls">
				<select name="content[status]">
					<option value="publish" <?php if($row['status'] == 'publish' || $_GET['action'] == 'new') echo 'selected'; ?>>Pubblicato</option>
					<option value="hidden" <?php if($row['status'] == 'hidden' && $_GET['action'] != 'new') echo 'selected'; ?>>Nascosto</option>
				</select>
				<p class="help-block"><small>Seleziona uno stato per questo contenuto.</small></p>
				</div>
				</div>
				
				<div class="control-group">
				<label class="control-label" for="gw_lang"><strong>Lingua</strong></label>
				<div class="controls">
				<?php gw_get_langs($row['ID']) ?>
				<p class="help-block"><small>Seleziona una lingua per questo contenuto.</small></p>
				</div>
				</div>
			
		</div>
	</div>
    <!-- <p class="help-block">Visualizza impostazioni avanzate.</p> -->
    </div>
    </div>
    <?php } ?>
	
	
    <div class="control-group">
    <!-- Button -->
    <div class="controls">
	
	<!-- start hiddens -->
	<input type="hidden" name="content[id]" value="<?php echo $row['ID']; ?>" />
	<input type="hidden" name="content[type]" value="<?php echo $row['type']; ?>" />
	<input type="hidden" name="content[site]" value="<?php echo $row['site']; ?>" />
	<input type="hidden" name="action" value="gw_edit" />
	<!-- end hiddens -->
	
		<div class="pull-left">
		<button type="submit" class="btn btn-success" style="font-weight:normal">Salva</button>
		</div>
		
		<div class="pull-right">
		<a href="javascript:void(0)" class="btn btn-danger btn-small gw_del-content" rel="<?php echo $row['ID']; ?>" data-content="content" title="<?php echo $row['type']; ?>">Elimina</a>
		</div>
		<div class="clearfix"></div>
	
	</div>
    </div>
    </fieldset>
    </form>
<?php } ?>
<?php include('footer.php'); ?>