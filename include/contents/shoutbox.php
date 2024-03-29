<?php
#   Copyright by: Manuel
#   Support: www.ilch.de

defined ('main') or die ( 'no direct access' );

  $title = $allgAr['title'].' :: Shoutbox '.$lang['archiv'];
  $hmenu = 'Shoutbox '.$lang['archiv'];
  $design = new design ( $title , $hmenu );
  $design->header();

if (is_siteadmin()) {
  # delete
  if ($menu->getA(1) == 'd' AND is_numeric($menu->getE(1))) {
    db_query("DELETE FROM prefix_shoutbox WHERE id = ".$menu->getE(1));
  }
  # delete all
  if ($menu->get(1) == 'delall') {
    if (is_numeric($menu->get(2))) {
      $anz = db_result(db_query("SELECT COUNT(*) FROM `prefix_shoutbox`"),0) - $menu->get(2);
      if ($anz > 0) {
        db_query("DELETE FROM `prefix_shoutbox` ORDER BY id LIMIT $anz");
        }
      }
    else { db_query("DELETE FROM `prefix_shoutbox`"); }
  }
}

echo '<script type="text/javascript">
  function del() {
    if (anz = prompt("Wieviele Eintr�ge sollen erhalten bleiben?\n(Es werden die zuletzt geschriebenen erhalten)", "0")) {
      if (anz >= 0) { window.location.href = "index.php?shoutbox-delall-"+anz; }
      else alert("Du musst eine Zahl gr��er gleich 0 eingeben");
    }
  }
</script>';

$class = 'Cnorm';
echo '<table width="100%" class="table table-bordered"><legend>Shoutbox '.$lang['archiv'].'</legend>';
$erg = db_query('SELECT * FROM `prefix_shoutbox` ORDER BY id DESC');
while ($row = db_fetch_assoc($erg) ) {
 $class = ( $class == 'Cmite' ? 'Cnorm' : 'Cmite' );
  echo '<tr><td>';
  if ( is_siteadmin() ) {
	  echo '<a href="index.php?shoutbox-d'.$row['id'].'" rel="tooltip" title="'.$lang['delete'].'"><i class="fa fa-trash-o"></i></a>&nbsp;';
	}
	echo '<b>'.$row['nickname'].' :</b> <small>'.preg_replace( '/([^\s]{'.$allgAr['sb_maxwordlength'].'})(?=[^\s])/', "$1\n", $row['textarea']).'</small></td></tr>';
}
echo '</table>';
if (is_siteadmin()) {
  echo '<a class="btn btn-primary" href="javascript:del();">'.$lang['clearshoutbox'].'</a>';
}
$design->footer();
?>