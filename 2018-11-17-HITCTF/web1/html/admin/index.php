<?php
/**
 * �����ļ�
 *
 * @version        2015��7��12��Z ������ $
 * @package        DuomiCms.Administrator
 * @copyright      Copyright (c) 2015, SamFea, Inc.
 * @link           http://www.duomicms.net
 */
require_once(dirname(__FILE__)."/config.php");
require_once(duomi_ADMIN.'/inc_menu.php');
$defaultIcoFile = duomi_ROOT.'/data/admin/quickmenu.txt';
$myIcoFile = duomi_ROOT.'/data/admin/quickmenu-'.$cuserLogin->getUserID().'.txt';
if(!file_exists($myIcoFile)) {
	$myIcoFile = $defaultIcoFile;
}
include(duomi_ADMIN.'/html/index.htm');
exit();
?>