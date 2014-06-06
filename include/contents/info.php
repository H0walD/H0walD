<?php 
#   Copyright by: Manuel
#   Support: www.ilch.de


defined ('main') or die ( 'no direct access' );

$title = $allgAr['title'].' ::Info';
$hmenu = 'Info';
$design = new design ( $title , $hmenu );
$design->header();

//-----------------------------------------------------------|
echo '<div>
	<div class="Cdark"><h1>Info</h1></div>';

  $erg = db_query('SELECT img,titel,txt FROM `prefix_info` ORDER BY id');
	while ($row = db_fetch_assoc($erg)) {
            echo '<table width="100%" border="0" cellpadding="5" cellspacing="1" class="border">';
            echo '<thead><tr class="Cmite"><td colspan="2"><b style="font-size:20px">'.$row['titel'].'</b></td></tr></thead>';
            echo '<tbody><tr class="Cnorm"><td width="20%"><img width="200" src="'.( file_exists( $row['img'] ) ? $row['img'] : 'include/images/info/0.png').'"/></td><td width="80%"><b>'.bbcode($row['txt']).'</b></td></tr></tbody>';
            echo '</table><br /><br />';
  } 
echo '</div>';


$design->footer();

?>

