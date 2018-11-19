<?php

/**
 * DiscuzX Convert
 *
 * $Id: imagetypes.php 8815 2010-04-23 02:05:15Z monkey $
 */

$curprg = basename(__FILE__);

$table_source = $db_source->tablepre.'imagetypes';
$table_target = $db_target->tablepre.'forum_imagetype';

$limit = 1000;
$nextid = 0;

$start = getgpc('start');
if($start == 0) {
	$db_target->query("TRUNCATE $table_target");
}

$query = $db_source->query("SELECT  * FROM $table_source WHERE typeid>'$start' ORDER BY typeid LIMIT $limit");
while ($row = $db_source->fetch_array($query)) {

	$nextid = $row['typeid'];

	$row  = daddslashes($row, 1);

	$data = implode_field_value($row, ',', db_table_fields($db_target, $table_target));

	$db_target->query("INSERT INTO $table_target SET $data");
}

if($nextid) {
	showmessage("继续转换数据表 ".$table_source." typeid > $nextid", "index.php?a=$action&source=$source&prg=$curprg&start=$nextid");
}

?>