<?php
define ( 'SCR', 'app' );
require_once ('global.php');
if (isset($_GET['ajax'])) {
	define('AJAX','1');
}
InitGP ( array ('q') );

if (in_array($q,array('ajax','article','diary','galbum','group','groups','hot','photos','sharelink','share','stopic','write'))) {
	#基础APP接口
	require_once R_P . 'require/app_route.php';
} elseif ($q == 'blooming') {

	InitGP(array('tid'),'G',2);
	if (!$db_appid) Showmsg('app_not_register');
	@include_once(D_P.'data/bbscache/info_class.php');

	$opened_class = @unserialize($db_threadconfig['opened_class']);
	!is_array($opened_class) && $opened_class = array();

	if (is_array($opened_class)) {
		foreach($opened_class as $v) {
			$openclass[$v] = $info_class[$v];
			unset($info_class[$v]);
		}
	}

	require_once PrintEot ( 'apps' );
	ajax_footer();

} elseif ($q == 'updata') {
	require_once(R_P.'require/posthost.php');
	include_once(D_P.'data/bbscache/level.php');
	InitGP(array('tid','cid'),'P',2);

	$pw_tmsgs = GetTtable($tid);
	$rt = $db->get_one("SELECT t.tid,subject,content FROM pw_threads t LEFT JOIN $pw_tmsgs tm ON t.tid=tm.tid WHERE t.tid=".pwEscape($tid));

	$systitle = $winddb['groupid'] == '-1' ? '' : $ltitle[$winddb['groupid']];
	$memtitle = $ltitle[$winddb['memberid']];
	$uptitle = $systitle ? $systitle : $memtitle;

	!$cid && Showmsg('Please select class');
	
	$partner	= md5($db_siteid.$db_siteownerid);
	$content	= pwConvert($rt['content'],'gbk',$db_charset);
	$subject	= pwConvert($rt['subject'],'gbk',$db_charset);
	$windid		= pwConvert($windid,'gbk',$db_charset);
	$uptitle	= pwConvert($uptitle,'gbk',$db_charset);

	$para = array(
		'tid'		=> $rt['tid'],
		'cid'		=> $cid,
		'upposter'	=> $windid,
		'uptitle'	=> $uptitle,
		'subject'	=> $subject,
		'rf'		=> $pwServer['HTTP_REFERER'],
		'sitehash'	=> $db_sitehash,
		'action'	=> 'updata',
	);

	ksort($para);
	reset($para);

	$arg = '';
	foreach ($para as $key => $value) {
		$arg .= "$key=".urlencode($value)."&";
	}

	$verify = md5(substr($arg,0,-1).$partner);

	if (strpos($content,'[attachment=') !== false) {
		preg_replace("/\[attachment=([0-9]+)\]/eis","upload('\\1')",$content,$db_cvtimes);
	}

	$data =  PostHost("http://app.phpwind.net/pw_app.php?","action=updata&tid=$rt[tid]&cid=$cid&upposter=$windid&uptitle=$uptitle&sitehash=$db_sitehash&subject=".urlencode($subject)."&content=".urlencode($content)."&verify=$verify&rf=".urlencode($pwServer['HTTP_REFERER']),"POST");

	$backdata = substr($data,strpos($data,'$backdata=')+10);
	$backdata = pwConvert($backdata,$db_charset,'gbk');

	Showmsg($backdata);

} elseif ($q == 'survey') {
	@include_once(D_P."data/bbscache/survey_cache.php");
	require_once(R_P.'require/header.php');
	InitGP(array('itemid'),'G',2);
	if (!$itemid) {
		foreach ($survey_cache as $itemdb) {
			$itemid = $itemdb['itemid'] > $itemid ? $itemdb['itemid'] : $itemid;
		}
		$survey = $survey_cache[$itemid];
	} else {
		$survey = $survey_cache[$itemid];
	}
	require_once PrintEot('apps');
	footer();
}  elseif ($q == 'appthread') {#新增app帖子交换的弹出框
	InitGP(array('do','forumid'));
	!$db_siteappkey && Showmsg ( 'app_siteappkey_notexist' );
    $appclient = L::loadClass('appclient');
	$app_url = $appclient->getThreadsUrl('index','main',$do,$forumid);
	require_once PrintEot('apps');
	ajax_footer();
} else {
	! $winduid && Showmsg ( 'not_login' );
	if (!$db_appbbs || !$db_appifopen) Showmsg ('app_close');

	$param = array ();

	$pw_query = base64_decode ( urldecode ( str_replace ( '&#61;', '=', $_GET ['pw_query'] ) ) );

	if ($pw_query) {
		$param ['pw_query'] = base64_encode ( $pw_query );
	}
	$param ['pw_appId'] = $_GET ['id'];
	$param ['pw_uid'] = $winduid;
	$param ['pw_siteurl'] = $db_bbsurl;
	$param ['pw_sitehash'] = $db_sitehash;
	$param ['pw_t'] = $timestamp;
	$param ['pw_bbsapp'] = 1;

	$server_url = 'http://apps.phpwind.net';
	$url = $server_url . '/apps.php?';

	foreach ( $param as $key => $value ) {
		$url .= "$key=" . urlencode ( $value ) . '&';
	}
	$hash = $param ['pw_appId'] . '|' . $param ['pw_uid'] . '|' . $param ['pw_siteurl'] . '|' . $param ['pw_sitehash'] . '|' . $param ['pw_t'];
	$url .= 'pw_sig=' . md5 ( $hash . $db_siteownerid );

	require_once (R_P . 'require/header.php');
	require_once PrintEot ( 'apps' );
	footer ();
}
function upload($aid) {
	global $db,$content,$db_bbsurl,$attachpath;
	$rt = $db->get_one("SELECT attachurl,type FROM pw_attachs WHERE aid='$aid'");
	if ($rt['attachurl']) {
		if ($rt['type'] == 'img') {
			$img = "[img]$db_bbsurl/$attachpath/".$rt['attachurl']."[/img]";
			$content = addslashes(str_replace("[attachment=$aid]",$img,$content));
		} else {
			$content = addslashes(str_replace("[attachment=$aid]",'',$content));
		}
	}
}
?>