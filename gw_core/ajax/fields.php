<?php 
require_once('../gw_system.php');
$num = $_POST['num'];
$key = $_POST['key'];
$nome = $_POST['newfield']['nome'];
if($_POST['newfield']['slug'] != '') { 
	$slug = $_POST['newfield']['slug'];
} elseif($nome != '') {
	$slug = $nome;
} else {
	$slug = 'field'.$num;
}
$slug = str_replace(' ','',preg_replace('/[^0-9a-zA-Z ]/m','',strtolower($slug)));
$desc = $_POST['newfield']['desc'];
$type = $_POST['newfield']['type'];
?>
<li class="row-fluid newfield" id="field-<?php echo $key; ?>-<?php echo $num; ?>">
	<div class="newfieldwrap">
		<div class="span1 move"><i class="icon icon-move"></i></div>
		<div class="span8"><span class="label label-info"><?php echo $nome; ?></span><br /><small><?php echo $desc; ?></small></div>
		<div class="span2" style="text-align:right"><a href="javascript:void(0);" class="btn btn-mini btn-warning edit" rel="field-<?php echo $key; ?>-<?php echo $num; ?>">Modifica</a></div>
		<div class="span1 pull-right" style="text-align:right"><a href="javascript:void(0);" class="btn btn-mini btn-danger del" rel="field-<?php echo $key; ?>-<?php echo $num; ?>">x</a></div>
		<!-- hiddens -->
		<div class="clearfix"></div>
		<p class="hide"><label><strong>Nome</strong></label>
		<input type="text" name="posttype[<?php echo $key; ?>][meta][<?php echo $num; ?>][nome]" value="<?php echo $nome; ?>" placeholder="nome..." />
		</p>
		<p class="hide"><label><strong>Slug</strong></label>
		<input type="text" name="posttype[<?php echo $key; ?>][meta][<?php echo $num; ?>][slug]" value="<?php echo $slug; ?>" placeholder="slug..." />
		</p>
		<p class="hide"><label><strong>Descrizione</strong></label>
		<input type="text" name="posttype[<?php echo $key; ?>][meta][<?php echo $num; ?>][desc]" value="<?php echo $desc; ?>" placeholder="descrizione..." />
		</p>
		<?php if(is_array($type) && count($type) >= 1) {
			foreach($type as $nukey => $nuvalue){ 
				foreach($nuvalue as $nuval){
					echo '<input type="text" class="hide" name="posttype['.$key.'][meta]['.$num.'][type]['.$nukey.'][]" value="'.$nuval.'" placeholder="valore..." />';
				}
			}
		} else { ?>
		<p class="hide"><label><strong>Campo</strong></label>
		<select name="posttype[<?php echo $key; ?>][meta][<?php echo $num; ?>][type]" class="input-block-level">
			<?php
				$option = '';
				$array = array(
					0 => array('value' => 'inputtext','nome'=>'Campo di testo'),
					1 => array('value' => 'inputhidden','nome'=>'Campo nascosto'),
					2 => array('value' => 'textarea','nome'=>'Editor di testo'),
					3 => array('value' => 'file','nome'=>'Carica file'),
					4 => array('value' => 'select','nome'=>'Selezione')
				);
				foreach($array as $key => $value) {
					$selected = null;
					if($type == $value['value']) { $selected = 'selected'; }
					$option .= '<option value="'.$value['value'].'" '.$selected.'>'.$value['nome'].'</option>';
				}
				echo $option;
			?>
	    </select>
		</p>
		<?php } ?>
		<!-- /hiddens -->
	<div class="clearfix"></div>
	</div>
</li>