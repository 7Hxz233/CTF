<?php
require_once(dirname(__FILE__)."/../duomiphp/common.php");
require_once(duomi_INC."/core.class.php");
checkIP();
//站点状态
if($cfg_website==0)
{
	ShowMsg('站点已关闭!','-1');
	exit();
}
if($GLOBALS['cfg_runmode2']==2||$GLOBALS['cfg_newsparamset']==0){
	$paras=str_replace(getnewsfileSuffix(),'',$_SERVER['QUERY_STRING']);
	if(strpos($paras,"-")>0){
		$parasArray=explode("-",$paras);
		$tid=$parasArray[0];
		$page=$parasArray[1];
	}else{
		$tid=$paras;
		$page=1;
	}
	$tid = isset($tid) && is_numeric($tid) ? $tid : 0;
	$page = isset($page) && is_numeric($page) ? $page : 1;
}else{
	$tid=$$GLOBALS['cfg_newsparamid'];
	$page=$$GLOBALS['cfg_newsparampage'];
	$tid = isset($tid) && is_numeric($tid) ? $tid : 0;
	$page = isset($page) && is_numeric($page) ? $page : 1;
}
if($tid==0){
	showmsg('参数丢失，请返回！', -1);
	exit;
}
echoChannel($tid);

function echoChannel($typeId)
{
	global $dsql,$cfg_iscache,$mainClassObj,$page,$t1;
	$channelTmpName=getTypeTemplate($typeId,1);
	$channelTmpName=empty($channelTmpName) ? "newspage.html" : $channelTmpName;
	$channelTemplatePath = "/duomiui/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/".$channelTmpName;
	if (strpos(" ,".getHideTypeIDS().",",",".$typeId.",")>0) exit("<font color='red'>文章列表为空或被隐藏</font><br>");
	$pSize = getPageSizeOnCache($channelTemplatePath,"newspage",$channelTmpName);
	if (empty($pSize)) $pSize=12;
	$typeIds = getTypeId($typeId,1);
	$typename=getNewsTypeName($typeId);
	$sql="select count(*) as dd from duomi_news where tid in (".$typeIds.")";
	$row = $dsql->GetOne($sql);
	if(is_array($row))
	{
		$TotalResult = $row['dd'];
	}
	else
	{
		$TotalResult = 0;
	}
	$pCount = ceil($TotalResult/$pSize);
	$currentTypeId = $typeId;
	$cacheName = "parse_channel_".$currentTypeId;
	if($cfg_iscache){
		if(chkFileCache($cacheName)){
			$content = getFileCache($cacheName);
		}else{
			$content = parseChannelPart($channelTemplatePath,$currentTypeId);
			$content = str_replace("{newspagelist:typename}",$typename,$content);
			setFileCache($cacheName,$content);
		}
	}else{
			$content = parseChannelPart($channelTemplatePath,$currentTypeId);
			$content = str_replace("{newspagelist:typename}",$typename,$content);
	}
	$content = str_replace("{newspagelist:page}",$page,$content);
	$content=$mainClassObj->parseNewsPageList($content,$typeIds,$page,$pCount,'newspage',$currentTypeId);
	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{duomicms:member}",front_member(),$content);
	echo str_replace("{duomicms:runinfo}",getRunTime($t1),$content) ;
}

function parseChannelPart($templatePath,$currentTypeId)
{
	global $mainClassObj;
	$content=loadFile(duomi_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content = str_replace("{duomicms:currenttypeid}",$currentTypeId,$content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parduomireaList($content);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId);
	$content=$mainClassObj->parseNewsList($content,$currentTypeId);
	$content=$mainClassObj->parseTopicList($content);
	$content = str_replace("{newspagelist:typetext}",getTypeText($currentTypeId,1),$content);
	return $content;
}
?>