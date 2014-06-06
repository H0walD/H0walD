<?php
#   Copyright by: Manuel
#   Support: www.ilch.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );


$tpl = new tpl ( 'info', 1);

$upload_path = "include/images/info/";

switch( $menu->get(1) ){
	case 'save':
	
		if ( isset($_FILES['img']) ) {
			if ( $_FILES['img']['size'] > 0 ) {
			
				$ext = pathinfo( $_FILES['img']['name'], PATHINFO_EXTENSION);
				
				if ( empty( $_POST['id'] ) ){
					$id = db_result(db_query("SELECT MAX(id) FROM prefix_info"), 0)+1;
					$img = $upload_path . $id .'.'. $ext;
				} else { 
					$img = $upload_path . escape($_POST['id'], 'integer') .'.'. $ext;
				}
						
				if ( in_array($ext, array('jpg', 'png', 'gif', 'jpeg') ) ) { // Fehler evt.
				
					if( move_uploaded_file($_FILES['img']['tmp_name'], $img ) ){
						$status = true;
						$uploadedInsert = $img;
						$uploadedUpdate = ', img = \''.$img.'\' ';
					} else {
						$uploadedInsert = $uploadedUpdate = NULL;
					}
				}
			}
		}
		
		$txt  = escape($_POST['txt'], 'textarea');
		$titel = escape($_POST['titel'], 'string');
				
		if ( $_POST['id'] ) {
			$id = escape($_POST['id'], 'integer');
			db_query('UPDATE `prefix_info` SET `txt` = "'.$txt.'", titel = "'.$titel.'" '.$uploadedUpdate.' WHERE id = "'.$id.'" LIMIT 1;');
			wd('admin.php?info', 'Update erfolgreich', 0);
		} else {
			db_query('INSERT INTO `prefix_info` (txt,titel,img) VALUES ( "'.$txt.'","'.$titel.'","'.$uploadedInsert.'" );' );
			wd('admin.php?info', 'Neuer Eintrag erfolgreich', 0);
		}

		exit();
	break;
	
	case 'delete':
		$delete = escape($menu->get(2), 'integer');
		$image = db_result(db_query("SELECT img FROM prefix_info WHERE id='".$delete."'"), 0);
		if ( db_query('DELETE FROM `prefix_info` WHERE id = "'.$delete.'" LIMIT 1') ){
			@unlink( $image );
			wd('admin.php?info', 'Erfolgreich', 0);
		} else {
			wd('admin.php?info', 'L&ouml;schen war nicht erfolgreich!', 3);
		}
		
		exit();
	break;
	
	case 'edit':
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		$design->header();
		
		$abf = 'SELECT txt, titel, id, img FROM `prefix_info` WHERE id = "'.escape($menu->get(2), 'integer').'"';
		$erg = db_query($abf);
		$row = db_fetch_assoc($erg);
		$row['sub'] = '&Auml;ndern';
		$row['img'] = ( file_exists( $row['img'] ) ? $row['img'] : 'include/images/info/0.png' );
		$row['image'] = '<img src="'.$row['img'].'" height="40" border="0" name="bild" valign="middle" style="margin-left:30px;border:1px solid;border-color:#4f8f7b;"/>';
		$tpl->set_ar_out($row, 0);
		
	break;
	
	default:
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		$design->header();
		
		$row = array();
		$row['sub'] = 'Eintragen';
		$row['titel'] = '';
		$row['txt'] = '';
		$row['image'] = '';
		$row['id'] = '';
		$tpl->set_ar_out($row, 0);	
	break;
}

$class = NULL;
$erg = db_query('SELECT * FROM `prefix_info` ORDER BY id');
while ($row = db_fetch_assoc($erg) ) {
	$class = ($class == 'Cmite' ? 'Cnorm' : 'Cmite' );
	$row['class'] = $class;
	$row['img'] = ( file_exists( $row['img'] ) ? $row['img'] : $upload_path . '0.png' );
	$tpl->set_ar_out($row, 1);
}

$tpl->out(2);

$design->footer();
?>