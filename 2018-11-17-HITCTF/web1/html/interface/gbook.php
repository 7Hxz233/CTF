<?php
session_start();
require_once("../duomiphp/common.php");
require_once(duomi_INC."/filter.inc.php");
require_once(duomi_INC.'/core.class.php');

if($cfg_feedbackstart=='0'){
	showMsg('对不起，留言暂时关闭','-1');
	exit();
}

if($cfg_feedbackcheck=='1') $needCheck = 0;
else $needCheck = 1;

if(empty($action)) $action = '';
if($action=='add')
{
	$ip = GetIP();
	$dtime = time();
	
	//检查验证码是否正确
	$validate = empty($validate) ? '' : strtolower(trim($validate));
	$svali = $_SESSION['duomi_ckstr'];
	if($validate=='' || $validate != $svali)
	{
		ResetVdValue();
		ShowMsg('验证码不正确!','-1');
		exit();
	}
	
	//检查留言间隔时间；
	if(!empty($cfg_feedback_times))
	{
		$row = $dsql->GetOne("SELECT dtime FROM `duomi_guestbook` WHERE `ip` = '$ip' ORDER BY `id` DESC ");
		if($dtime - $row['dtime'] < $cfg_feedback_times)
		{
			ShowMsg("留言过快，歇会再来留言吧","-1");
			exit();
		}
	}
	$userid = !empty($userid)?intval($userid):0;
	$uname = trimMsg($m_author);
	$uname =  _Replace_Badword($uname);
	$msg = trimMsg(cn_substrR($m_content, 1024), 1);
	
	if(!preg_match("/[".chr(0xa1)."-".chr(0xff)."]/",$msg)){
		showMsg('你必需输入中文才能发表!','-1');
		exit();
	}
	
	$reid = empty($reid) ? 0 : intval($reid);

	if(!empty($cfg_banwords))
	{
		$myarr = explode ('|',$cfg_banwords);
		for($i=0;$i<count($myarr);$i++)
		{
			$userisok = strpos($uname, $myarr[$i]);
			$msgisok = strpos($msg, $myarr[$i]);
			if(is_int($userisok)||is_int($msgisok))
			{
				showMsg('您发表的评论中有禁用词语!','-1');
				exit();
			}
		}
	}
	
	if($msg=='' || $uname=='') {
		showMsg('你的姓名和留言内容不能为空!','-1');
		exit();
	}
	$title = HtmlReplace( cn_substrR($title,60), 1 );
	if($title=='') $title = '无标题';
		$title = _Replace_Badword($title);

	if($reid != 0)
	{
		$row = $dsql->GetOne("Select msg From `duomi_guestbook` where id='$reid' ");
		$msg = "<div class=\\'rebox\\'>".addslashes($row['msg'])."</div>\n".$msg;
	}
	$msg = _Replace_Badword($msg);
	$query = "INSERT INTO `duomi_guestbook`(title,mid,uname,uid,msg,ip,dtime,ischeck)
                  VALUES ('$title','{$g_mid}','$uname','$userid','$msg','$ip','$dtime','$needCheck'); ";
	$dsql->ExecuteNoneQuery($query);
	if($needCheck==1)
	{
		ShowMsg('感谢您的留言，我们会尽快回复您！','../interface/gbook.php',0,3000);
		exit();	
	}
	else
	{
		ShowMsg('成功发送一则留言，但需审核后才能显示！','../interface/gbook.php',0,3000);
		exit();
	}
}
//显示所有留言
else
{
	if($key!=''){
	$key="您好，我想看".HtmlReplace($key).",多谢了";
	$title="求片";
	}else{
	$key='';
	$title='';
	}
	$page=empty($page) ? 1 : intval($page);
	if($page==0) $page=1;
	$tempfile = duomi_ROOT."/duomiui/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/gbook.html";
	$content=loadFile($tempfile);
	$t=$content;
	$t=$mainClassObj->parseTopAndFoot($t);
	$t=$mainClassObj->parseHistory($t);
	$t=$mainClassObj->parseSelf($t);
	$t=$mainClassObj->parseGlobal($t);
	$t=$mainClassObj->parduomireaList($t);
	$t=$mainClassObj->parseMenuList($t,"");
	$t=$mainClassObj->parseVideoList($t,-444);
	$t=$mainClassObj->parseNewsList($t,-444);
	$t=$mainClassObj->parseTopicList($t);
	$t=replaceCurrentTypeId($t,-444);
	$t=$mainClassObj->parseIf($t);
	$t=str_replace("{gbook:viewLeaveWord}",viewLeaveWord(),$t);
	$t=str_replace("{gbook:main}",viewMain(),$t);
	$t=str_replace("{duomicms:runinfo}",getRunTime($t1),$t);
	$t=str_replace("{duomicms:member}",front_member(),$t);
	echo $t;
	exit();
}

function viewMain(){
	$main="<div class='leaveNavInfo'><h3><span id='adminleaveword'></span>".$GLOBALS['cfg_webname']."留言板</h3></div>";
	return $main;
}

function viewLeaveWord(){
	if(!empty($_SESSION['duomi_user_name']))
	{
		$uname=$_SESSION['duomi_user_name'];
		$userid =$_SESSION['duomi_user_id'];
	}
	
	$mystr="<div id=\"leaveword\">".
"<form id=\"f_leaveword\"   action=\"/".$GLOBALS['cfg_cmspath']."../interface/gbook.php?action=add\" method=\"post\">".
"<input type=\"hidden\" value=\"$userid\" name=\"userid\" />".
"<input type=\"hidden\" value=\"$uname\" name=\"m_author\" />".
"<table align=\"center\" style=\"margin:0 auto\">". 
"<tr>".
"<td height=\"25\" colspan=\"2\">".(isset($uname)?$uname:'<input type="input" value="游客" name="m_author" id="m_author" size="20" /><span class="red">*</span></td>').
"</tr>".
  "<tr>".
	 	"<td class=\"nb\" colspan=\"2\" valign=\"top\"><textarea cols=\"40\" name=\"m_content\" id=\"m_content\" rows=\"5\" ></textarea><span class=\"red\">*</span></td>".
  "</tr>".
  "<tr>".
	 	"<td height=\"25\" colspan=\"2\"><input type=\"submit\" click=\"leaveWord()\" value=\"提交留言\" class=\"btn\"/><input name=\"validate\" type=\"text\" id=\"vdcode\" style=\"width:50px;text-transform:uppercase;\" class=\"text\" tabindex=\"3\"/> <img id=\"vdimgck\" src=\"../duomiphp/vdimgck.php\" alt=\"看不清？点击更换\"  align=\"absmiddle\"  style=\"cursor:pointer\" onClick=\"this.src=this.src+'?get=' + new Date()\"/><span class=\"red\">*</span></td>".
  "</tr>".
"</table></form>".
"</div>".
"<div id=\"leavewordlist\"  align=\"center\">".leaveWordList($_GET['page'])."</div><br /><br /><script type=\"text/javascript\" src=\"js/base.js\"></script>";
	return $mystr;
}

function leaveWordList($currentPage){
	global $dsql;
	$vsize=10;
	if($currentPage<=1)
	{
		$currentPage=1;
	}
	$limitstart = ($currentPage-1) * $vsize;
	$sql="select * from `duomi_guestbook` where ischeck='1' ORDER BY id DESC limit $limitstart,$vsize";	
	$cquery = "Select count(*) as dd From `duomi_guestbook` where ischeck='1'";
	$row = $dsql->GetOne($cquery);
	if(is_array($row))
	{
		$TotalResult = $row['dd'];
	}
	else
	{
		$TotalResult = 0;
		$txt="<ul><li class=\"words\">当前没有留言</li></ul>";
	}
	$TotalPage = ceil($TotalResult/$vsize);
	$dsql->SetQuery($sql);
	$dsql->Execute('leaveWordList');
	$i=$TotalResult;
	while($row=$dsql->GetObject('leaveWordList')){
	$txt.="<ul><li class=\"topwords\"><span><strong>".$row->uname."</strong> ".MyDate('',$row->dtime)."</span>";
	$txt.="第".$row->id."楼</li>";
	$txt.="<li class=\"words\">".showFace($row->msg)."</li>";
	$txt.="</ul>";
	$i--;
	}
	unset($i);
	$txt.="<br /><br /><div class=\"page\"><span>共".$TotalPage."页</span>";
	if($currentPage==1)$txt.="<em class='nolink'>首页</em><em class='nolink'>&lt;</em>";
	else $txt.="<a title='首页' href=\"/".$GLOBALS['cfg_cmspath']."../interface/gbook.php?page=1\">首页</a><a title='前一页' href=\"/".$GLOBALS['cfg_cmspath']."../interface/gbook.php?page=".($currentPage-1)."\">&lt;</a>";
	if($TotalPage==1)
	{
		$txt.="<em>1</em>";
	}else{
	$x=$currentPage-4;
	$y=$currentPage+4;
	if($x<1)$x=1;
	if($y>$TotalPage)$y=$TotalPage;
	for($i=$x;$i<=$y;$i++)
	{
		$txt.="<a href=\"/".$GLOBALS['cfg_cmspath']."../interface/gbook.php?page=".$i."\">".$i."</a>";
	}	
	}
	if($currentPage==$TotalPage)$txt.="<em class='nolink'>&gt;</em><em class='nolink'>尾页</em>";
	else $txt.="<a href=\"/".$GLOBALS['cfg_cmspath']."../interface/gbook.php?page=".($currentPage+1)."\">&gt;</a> <a href=\"/".$GLOBALS['cfg_cmspath']."../interface/gbook.php?page=".$TotalPage."\">尾页</a>";
	return $txt."</div>";

}


?>