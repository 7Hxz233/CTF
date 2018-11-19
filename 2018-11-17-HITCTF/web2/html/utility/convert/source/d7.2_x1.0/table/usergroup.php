<?php

/**
 * DiscuzX Convert
 *
 * $Id: usergroup.php 15373 2010-08-24 01:19:22Z monkey $
 */

$curprg = basename(__FILE__);

$table_source = $db_source->tablepre.'usergroups';
$table_target = $db_target->tablepre.'common_usergroup';
$table_target_field = $table_target.'_field';

$limit = 100;
$nextid = 0;

$start = getgpc('start');
if($start == 0) {
	$db_target->query("TRUNCATE $table_target");
	$db_target->query("TRUNCATE $table_target_field");
}

$usergroup = array('groupid', 'radminid', 'type', 'system', 'grouptitle', 'creditshigher', 'creditslower', 'stars', 'color', 'allowvisit', 'allowsendpm', 'allowinvite', 'allowmailinvite', 'maxinvitenum', 'inviteprice', 'maxinviteday');
$usergroup_field = array('groupid', 'readaccess', 'allowpost', 'allowreply', 'allowpostpoll', 'allowpostreward', 'allowposttrade', 'allowpostactivity', 'allowdirectpost', 'allowgetattach', 'allowpostattach', 'allowvote', 'allowmultigroups', 'allowsearch', 'allowcstatus', 'allowinvisible', 'allowtransfer', 'allowsetreadperm', 'allowsetattachperm', 'allowhidecode', 'allowhtml', 'allowhidecode', 'allowhtml', 'allowanonymous', 'allowsigbbcode', 'allowsigimgcode', 'disableperiodctrl', 'reasonpm', 'maxprice', 'maxsigsize', 'maxattachsize', 'maxsizeperday', 'maxpostsperhour', 'attachextensions', 'raterange', 'mintradeprice', 'maxtradeprice', 'allowhidecode', 'allowhtml', 'allowanonymous', 'allowsigbbcode', 'allowsigimgcode', 'disableperiodctrl', 'reasonpm', 'maxprice', 'maxsigsize', 'maxattachsize', 'maxsizeperday', 'maxpostsperhour', 'attachextensions', 'raterange', 'mintradeprice', 'maxtradeprice', 'minrewardprice', 'maxrewardprice', 'magicsdiscount', 'maxmagicsweight', 'allowpostdebate', 'tradestick', 'exempt', 'maxattachnum', 'allowposturl', 'allowrecommend', 'edittimelimit', 'allowpostrushreply');

$userdata = $userfielddata = array();
$query = $db_source->query("SELECT * FROM $table_source WHERE groupid>'$start' ORDER BY groupid LIMIT $limit");
while ($data = $db_source->fetch_array($query)) {

	$nextid = $data['groupid'];

	$data  = daddslashes($data, 1);

	foreach($usergroup as $field) {
		$userdata[$field]= $data[$field];
	}

	foreach($usergroup_field as $field) {
		$userfielddata[$field]= $data[$field];
	}

	$userdatalist = implode_field_value($userdata, ',', db_table_fields($db_target, $table_target));
	$userfielddatalist = implode_field_value($userfielddata, ',', db_table_fields($db_target, $table_target_field));

	$db_target->query("INSERT INTO $table_target SET $userdatalist");
	$db_target->query("INSERT INTO $table_target_field SET $userfielddatalist");
}

if($nextid) {
	showmessage("继续转换数据表 ".$table_source." groupid > $nextid", "index.php?a=$action&source=$source&prg=$curprg&start=$nextid");
}

?>