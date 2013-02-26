<?php require_once('../gw_system.php'); ?>
<?php
if(isset($_REQUEST['value'])) {
    $value = $_REQUEST['value'];
}
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'edit'){
	$data = unserialize(get_option('posttype'));
	$data = $data[$value];
}
?>

<script>
jQuery(document).ready(function(){
	var n = 0;
	var inputs = '<p><input type="text" class="sel_options" name="newfield[type][select][]" placeholder="Valore..." />';
	jQuery('#selectfield-<?php echo $value; ?>').click(function(){
		var selval = jQuery(this).val()
		if(selval == 'select'){
			jQuery('#generatedfields-<?php echo $value; ?>').addClass('well').html('<div class="row-fluid"><div class="span11"><a href="#" id="newoption" class="btn btn-small">Aggiungi</a></div><div class="span1"><span id="removefield" class="label label-important">x</span></div></div>').append(inputs)
		}
	})
	jQuery('#newoption').click(function(){
		jQuery('#generatedfields-<?php echo $value; ?>').append(inputs)
	})
	jQuery('#removefield').click(function(){
		jQuery('#generatedfields-<?php echo $value; ?>').html('').removeClass('well')
	})
	jQuery('#addfield-<?php echo $value; ?>').click(function(event){
		event.preventDefault();
		var string = jQuery('#formfields-<?php echo $value; ?>').serialize()
		var key = '<?php echo $value; ?>';
		var ele = jQuery('#newfields-<?php echo $value; ?> li').length;
		var n = ele;
		jQuery.ajax({ type: "POST",   
		     url: "../../gw_core/ajax/fields.php",  
		     data:'num='+ n++ +'&key='+key+'&'+string, 
		     async: false,
		     success : function(text) {
		         response = text;
		     }
		});
		jQuery('#fnome,#fslug,#fdesc').val('')
		jQuery('#generatedfields-<?php echo $value; ?>').html('').removeClass('well')
		jQuery('#newfields-<?php echo $value; ?>').append(jQuery(response).css({'backgroundColor' : '#B0E076'}).animate({backgroundColor:'#eee'},500));
	})

	jQuery('.btn-danger').live('click',function(){
		var ID = jQuery(this).attr('rel')
		jQuery('#'+ID).css('backgroundColor','#E06060').animate({backgroundColor:'#eee',height:'0'},{
                "complete" : function() {
                      jQuery(this).remove();
                }
            });
	})
	
	jQuery('.edit').live('click',function(){
		var ID = jQuery(this).attr('rel');
		jQuery('#'+ID+' .hide').show()
		
	})
	
	jQuery("#newfields-<?php echo $value; ?>").sortable({
		containment: "parent",
		axis: "y",
		handle: '.move',
    	cursor: 'move',
	});
})
</script>

<ul class="nav nav-tabs" id="itemmeta">
    <li class="active"><a data-toggle="tab" href="#posttype_generale-<?php echo $value; ?>">Generale</a></li>
    <li><a data-toggle="tab" href="#posttype_customfields-<?php echo $value; ?>">Campi personalizzati</a></li>
</ul>
<div class="tab-content" id="tabmeta">
    <div id="posttype_generale-<?php echo $value; ?>" class="tab-pane fade active in">
    <div class="itemMeta">
    	<div class="control-group">
		<label class="control-label" for="name">Nome</label>
		<div class="controls">
		<input type="text" id="id" name="posttype[<?php echo $value; ?>][name]" placeholder="" class="input-block-level" value="<?php echo $data['name']; ?>" />
		<p class="help-block">Imposta un nome al singolare</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="plural">Nome al plurale</label>
		<div class="controls">
		<input type="text" id="plural" name="posttype[<?php echo $value; ?>][plural]" placeholder="" class="input-block-level" value="<?php echo $data['plural']; ?>" />
		<p class="help-block">Imposta un nome al plurale</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="new">Nuovo</label>
		<div class="controls">
		<input type="text" id="new" name="posttype[<?php echo $value; ?>][new]" placeholder="Nuovo ..." class="input-block-level" value="<?php echo $data['new']; ?>" />
		<p class="help-block">Frase per indicare un nuovo tipo di contenuto.</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="edit">Modifica</label>
		<div class="controls">
		<input type="text" id="edit" name="posttype[<?php echo $value; ?>][edit]" placeholder="Modifica ..." class="input-block-level" value="<?php echo $data['edit']; ?>" />
		<p class="help-block">Frase per indicare la modifica di un tipo di contenuto.</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="edit">Vedi tutti</label>
		<div class="controls">
		<input type="text" id="edit" name="posttype[<?php echo $value; ?>][view]" placeholder="Tutte le ..." class="input-block-level" value="<?php echo $data['view']; ?>" />
		<p class="help-block">Frase per indicare la lista di contenuti.</p>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="state">Attivo</label>
		<div class="controls">
		<input type="checkbox" id="state" name="posttype[<?php echo $value; ?>][state]" <?php if($data['state']=='on') { echo 'checked'; } ?>/>
		<p class="help-block">Check attivo / vuoto stand-by</p>
		</div>
		</div>

		<!-- hiddens -->
		<input type="hidden" name="posttype[<?php echo $value; ?>][id]" value="<?php echo $value; ?>" />
    </div><!-- /.itemMeta -->
    </div><!-- /#posttype_generale -->
	<div id="posttype_customfields-<?php echo $value; ?>" class="tab-pane fade">

	    <div class="itemMeta">
	    	<form action="" method="GET" id="formfields-<?php echo $value; ?>">
	    	<div class="row-fluid">
	    		<div class="span6"><p><input type="text" name="newfield[nome]" id="fnome" placeholder="Nome..." class="input-block-level" /></p></div>
	    		<div class="span6"><p><input type="text" name="newfield[slug]" id="fslug" placeholder="Slug..." class="input-block-level" /></p></div>
	    		<div class="clearfix"></div>
	    	</div>
	    	<div class="row-fluid">
	    		<div class="span6"><p><input type="text" name="newfield[desc]" id="fdesc" placeholder="Breve descrizione..." class="input-block-level" /></p></div>
	    		<div class="span6">
	        		<select name="newfield[type]" id="selectfield-<?php echo $value; ?>" class="input-block-level">
	        			<option value="inputtext">Campo di testo</option>
	        			<option value="inputhidden">Campo nascosto</option>
	        			<option value="textarea">Editor di testo</option>
	        			<option value="file">Carica file</option>
	        			<option value="select">Selezione</option>
	        		</select>
	        	</div>
	    	<div class="clearfix"></div>
	    	</div>
	    	<div id="generatedfields-<?php echo $value; ?>"></div>
	        <div class="row-fluid">
	        	<div class="span6"><p>&nbsp;</p></div>
	        	<div class="span2 offset4">
	        		<a href="#" class="btn btn-primary btn-block" id="addfield-<?php echo $value; ?>">Aggiungi</a>
	        	</div>
	        	<hr />
	        	<div class="clearfix"></div>
	        </div>
	    	</form>
	        <ul id="newfields-<?php echo $value; ?>">
			
	        	<?php
				
	        	if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'edit') {
	        		$values = get_option('posttype');
					$values = unserialize($values);
					if(isset($values[$value]['meta'])) {
						$meta = $values[$value]['meta'];
						if(isset($meta)) {
							$json = array();
							if(count($meta >= 1)) {
								$num = 0;
								foreach($meta as $number => $array){
									$json[$num]['num'] = $num;
									$json[$num]['key'] = $value;
									foreach($array as $key => $val) {
										$json[$num]['newfield'][$key] = $val;
									}
								$num++;
								}
							}
							$json = json_encode($json);
						}
					}
				}
				
	        	?>
	        </ul>
			<?php if(isset($json) && count(json_decode($json)) >= 1 ) { ?>
			<script>
			var obj = <?php echo $json; ?>;
			jQuery.each(obj, function(i, val) {
				jQuery.ajax({ 
					type: "POST",   
					url: "../../gw_core/ajax/fields.php",  
					data: val, 
					async: false,
					success : function(response) {
					jQuery('#newfields-<?php echo $value; ?>').append(jQuery(response).css({'backgroundColor' : '#B0E076'}).animate({backgroundColor:'#eee'},500));
					}
				});			   
			});
			</script>
			<?php } ?>
	    </div><!-- /.itemMeta -->
	</div><!-- /#posttype_customfields -->
</div><!-- /#tabmeta -->