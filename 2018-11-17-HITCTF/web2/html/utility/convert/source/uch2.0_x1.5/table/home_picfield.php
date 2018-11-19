<?php

/**
 * DiscuzX Convert
 *
 * $Id: home_picfield.php 15720 2010-08-25 23:56:08Z monkey $
 */

$curprg = basename(__FILE__);

$table_source = $db_source->tablepre.'picfield';
$table_target = $db_target->tablepre.'home_picfield';

$limit = $setting['limit']['pic'] ? $setting['limit']['pic'] : 1000;
$nextid = 0;

$start = getgpc('start');
if($start == 0) {
	$db_target->query("TRUNCATE $table_target");
}

$query = $db_source->query("SELECT  * FROM $table_source WHERE picid>'$start' ORDER BY picid LIMIT $limit");
while ($pic = $db_source->fetch_array($query)) {

	$nextid = $pic['picid'];

	$pic  = daddslashes($pic, 1);

	$data = implode_field_value($pic, ',', db_table_fields($db_target, $table_target));

	$db_target->query("INSERT INTO $table_target SET $data");
}

if($nextid) {
	showmessage("继续转换数据表 ".$table_source." picid> $nextid", "index.php?a=$action&source=$source&prg=$curprg&start=$nextid");
}

?>