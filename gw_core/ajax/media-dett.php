<?php
require_once('../gw_system.php');
/*
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
} */

if(isset($_POST['media'])) { 
$id = $_POST['media'];
$media = get_content($id);
$linkfull = get_image($id);
$ext = end(explode('.',$linkfull));
$images = array('gif','jpeg','png','jpg');
$types = array('doc','docx','generale','html','pdf','ppt','pptx','pub','rar','rtf','txt','xls','xlsx','zip');
if(in_array($ext,$images)) {
		$linkthumb = get_image($id,'thumb');
		$linklarge = get_image($id,'large');
		if(isset($linkthumb)) echo '<p style="text-align:center"><a href="'.$linkfull.'" class="lightbox"><img src="'.$linkthumb.'" alt="icona" class="img-polaroid"/></a></p>';
	}
else {
		
		if(!in_array($ext,$types)) {
			$icona = gw_url().'/gw_lib/img/ext/generale.png';
		} else {
			$icona = gw_url().'/gw_lib/img/ext/'.$ext.'.png';
		}
		echo '<p style="text-align:center"><img src="'.$icona.'" alt="icona" /></p>';
	} 
?>
<p><strong>Nome</strong>: <?php echo $media['title']; ?></p>
<p><strong>Estensione</strong>: <?php echo $ext; ?></p>
<?php
	
	if(in_array($ext,$images)) {
		if(isset($linkthumb)) echo '<p><strong>Thumb</strong>: '.$linkthumb.'</p>';
		if(isset($linklarge)) echo '<p><strong>Large</strong>: '.$linklarge.'</p>';
	}
?>
<?php }
?>