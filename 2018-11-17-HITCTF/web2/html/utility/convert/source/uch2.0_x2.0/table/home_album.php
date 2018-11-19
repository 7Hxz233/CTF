<?php

/**
 * DiscuzX Convert
 *
 * $Id: home_album.php 15720 2010-08-25 23:56:08Z monkey $
 */

$curprg = basename(__FILE__);

$table_source = $db_source->tablepre.'album';
$table_target = $db_target->tablepre.'home_album';

$limit = $setting['limit']['album'] ? $setting['limit']['album'] : 2000;
$nextid = 0;

$start = getgpc('start');
if($start == 0) {
	$db_target->query("TRUNCATE $table_target");
}

$query = $db_source->query("SELECT  * FROM $table_source WHERE albumid>'$start' ORDER BY albumid LIMIT $limit");
while ($album = $db_source->fetch_array($query)) {

	$nextid = $album['albumid'];

	$album  = daddslashes($album, 1);

	$data = implode_field_value($album, ',', db_table_fields($db_target, $table_target));

	$db_target->query("INSERT INTO $table_target SET $data");
}

if($nextid) {
	showmessage("继续转换数据表 ".$table_source." albumid> $nextid", "index.php?a=$action&source=$source&prg=$curprg&start=$nextid");
}

?>