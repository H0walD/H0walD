<?php 
defined ('main') or die ( 'no direct access' );

$abf = 'SELECT
          a.news_kat as kate,
          DATE_FORMAT(a.news_time,"%d.%m.%Y") as datum,      
          a.news_title as title,
          a.news_kat as kate,
          a.news_id as id,      
          b.name as username,
          b.id as userid         
          FROM prefix_news as a
          LEFT JOIN prefix_user as b ON a.user_id = b.id
          WHERE news_recht >= '.$_SESSION['authright'].'
          ORDER BY a.news_time DESC
          LIMIT 0,5';
echo '<div class="panel panel-default"><div class="panel-body text-left">';        
$erg = db_query($abf);
while ($row = db_fetch_object($erg)) {

	echo '<div class="lastbox"><small>'.$row->kate.'</small><br><i class="fa fa-angle-double-right"></i> <a href="index.php?news-'.$row->id.'">'.$row->title.'</a><br><small>Autor : '.$row->username.' | '.$row->datum.'</small></div>';

}
echo '</div></div>';
?>