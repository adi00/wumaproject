<?php
!function_exists('adminmsg') && exit('Forbidden');

@include_once(D_P.'data/bbscache/o_config.php');

if (empty($action)) {
	if (empty($_POST['step'])) {

		require_once(R_P.'require/credit.php');
		!in_array($o_mkdir,array(1,2,3)) && $o_mkdir = 1;
		${'mkdir'.$o_mkdir} = 'checked';
		ifcheck($db_phopen,'phopen');
		ifcheck($o_photos_gdcheck,'photos_gdcheck');
		ifcheck($o_photos_qcheck,'photos_qcheck');

		$creategroup = ''; $num = 0;
		foreach ($ltitle as $key => $value) {
			if ($key != 1 && $key != 2 && $key !='6' && $key !='7' && $key !='3') {
				$num++;
				$htm_tr = $num % 4 == 0 ? '</tr><tr>' : '';
				$g_checked = strpos($o_photos_groups,",$key,") !== false ? 'checked' : '';
				$creategroup .= "<td><input type=\"checkbox\" name=\"groups[]\" value=\"$key\" $g_checked>$value</td>$htm_tr";
			}
		}
		$creategroup && $creategroup = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" align=\"center\"><tr>$creategroup</tr></table>";
		!is_array($creditset = unserialize($o_photos_creditset)) && $creditset = array();
		
		$creditlog = array();
		!is_array($photos_creditlog = unserialize($o_photos_creditlog)) && $photos_creditlog = array();
		foreach ($photos_creditlog as $key => $value) {
			foreach ($value as $k => $v) {
				$creditlog[$key][$k] = 'CHECKED';
			}
		}
		
		require_once PrintApp('admin');

	} else {
		InitGP(array('creditset','creditlog'),'GP');
		InitGP(array('config','phopen','groups'),'GP',2);
		
		require_once(R_P.'admin/cache.php');
		setConfig('db_phopen', $phopen);
		updatecache_c();
	
		$config['photos_groups'] = is_array($groups) ? ','.implode(',',$groups).',' : '';
		$updatecache = false;
		
		$config['photos_creditset'] = '';
		if (is_array($creditset) && !empty($creditset)) {
			foreach ($creditset as $key => $value) {
				foreach ($value as $k => $v) {
					$creditset[$key][$k] = round($v,($k=='rvrc' ? 1 : 0));
				}
			}
			$config['photos_creditset'] = addslashes(serialize($creditset));
		}
		is_array($creditlog) && !empty($creditlog) && $config['photos_creditlog'] = addslashes(serialize($creditlog));
		foreach ($config as $key => $value) {
			if (${'o_'.$key} != $value) {
				$db->pw_update(
					'SELECT hk_name FROM pw_hack WHERE hk_name=' . pwEscape("o_$key"),
					'UPDATE pw_hack SET ' . pwSqlSingle(array('hk_value' => $value, 'vtype' => 'string')) . ' WHERE hk_name=' . pwEscape("o_$key"),
					'INSERT INTO pw_hack SET ' . pwSqlSingle(array('hk_name' => "o_$key", 'vtype' => 'string', 'hk_value' => $value))
				);
				$updatecache = true;
			}
		}
		$updatecache && updatecache_conf('o',true);
		adminmsg('operate_success');
	}
} elseif ($action == 'albums') {
	if ($job == 'list') {
		InitGP(array('aname','owner','crtime_s','crtime_e','lasttime_s','lasttime_e','private','lines','orderway','ordertype','page'));
		if (empty($aname) && empty($owner) && empty($crtime_s) && empty($crtime_e) && empty($lasttime_s) && empty($lasttime_e) && $private == '-1') {
			adminmsg('noenough_condition',"$basename&action=albums");
		}
		$encode_aname = rawurlencode($aname);
		$encode_owner = rawurlencode($owner);
		$crtime_s && !is_numeric($crtime_s) && $crtime_s = PwStrtoTime($crtime_s);
		$crtime_e && !is_numeric($crtime_e) && $crtime_e = PwStrtoTime($crtime_e);
		$lasttime_s && !is_numeric($lasttime_s) && $lasttime_s = PwStrtoTime($lasttime_s);
		$lasttime_e && !is_numeric($lasttime_e) && $lasttime_e = PwStrtoTime($lasttime_e);
		$sql = "atype='0'";
		$urladd = '';
		if ($aname) {
			$aname = str_replace('*','%',$aname);
			$sql .= ' AND aname LIKE '.pwEscape($aname);
			$urladd .= '&aname='.rawurlencode($aname);
		}
		if ($owner) {
			$owner = str_replace('*','%',$owner);
			$sql .= ' AND owner LIKE '.pwEscape($owner);
			$urladd .= '&owner='.rawurlencode($owner);
		}
		if ($crtime_s) {
			$sql .= ' AND crtime>='.pwEscape($crtime_s);
			$urladd .= "&crtime_s=$crtime_s";
		}
		if ($crtime_e) {
			$sql .= ' AND crtime<='.pwEscape($crtime_e);
			$urladd .= "&crtime_e=$crtime_e";
		}
		if ($lasttime_s) {
			$sql .= ' AND lasttime>='.pwEscape($lasttime_s);
			$urladd .= "&lasttime_s=$lasttime_s";
		}
		if ($lasttime_e) {
			$sql .= ' AND lasttime<='.pwEscape($lasttime_e);
			$urladd .= "&lasttime_e=$lasttime_e";
		}
		$orderway = $orderway == 'crtime' ? 'crtime' : 'lasttime';
		$ordertype = $ordertype == 'asc' ? 'asc' : 'desc';
		$urladd .= "&orderway=$orderway&ordertype=$ordertype&lines=$lines";
		$count = $db->get_value("SELECT COUNT(*) AS count FROM pw_cnalbum WHERE $sql");
		empty($count) && adminmsg('album_not_exist',"$basename&action=albums");
		!is_numeric($lines) && $lines=30;
		$page < 1 && $page = 1;
		$numofpage = ceil($count/$lines);
		if ($numofpage && $page > $numofpage) {
			$page = $numofpage;
		}
		$pages=numofpage($count,$page,$numofpage,"$basename&action=$action&job=list$urladd&");
		$start = ($page-1)*$lines;
		$limit = pwLimit($start,$lines);
		$query = $db->query("SELECT aid,aname,private,ownerid,owner,photonum,lasttime,lastpid,crtime FROM pw_cnalbum WHERE $sql "."ORDER BY $orderway $ordertype ".$limit);
		while ($rt = $db->fetch_array($query)) {
			$rt['s_aname'] = substrs($rt['aname'],30);
			$rt['lasttime'] = $rt['lasttime'] ? get_date($rt['lasttime']) : '-';
			$rt['crtime'] 	= $rt['crtime'] ? get_date($rt['crtime']) : '-';
			$albumdb[] = $rt;
		}
		require_once PrintApp('admin');
	} elseif ($job == 'delete') { 
		InitGP(array('selid','aname','owner','crtime_s','crtime_e','lasttime_s','lasttime_e','private','lines','orderway','ordertype'));
		empty($selid) && adminmsg("no_album_selid");
		require_once(R_P . 'require/app_core.php');
		foreach ($selid as $key => $aid) {
			$query = $db->query("SELECT cn.pid,cn.path,cn.ifthumb,ca.ownerid FROM pw_cnphoto cn LEFT JOIN pw_cnalbum ca ON cn.aid=ca.aid WHERE cn.aid=" . pwEscape($aid));
			if (($num = $db->num_rows($query)) > 0) {
				$affected_rows = 0;
				while ($rt = $db->fetch_array($query)) {
					$uids[] = $rt['ownerid'];
					pwDelatt($rt['path'], $db_ifftp);
					if ($rt['ifthumb']) {
						$lastpos = strrpos($rt['path'],'/') + 1;
						pwDelatt(substr($rt['path'], 0, $lastpos) . 's_' . substr($rt['path'], $lastpos), $db_ifftp);
					}
					$affected_rows += delAppAction('photo',$rt['pid'])+1;//TODO 效率？
				}
				pwFtpClose($ftp);
				countPosts("-$affected_rows");
			}
			$db->update("DELETE FROM pw_cnphoto WHERE aid=" . pwEscape($aid));
			$db->update("DELETE FROM pw_cnalbum WHERE aid=" . pwEscape($aid));
		}
		$uids = array_unique($uids);
		updateUserAppNum($uids,'photo','recount');
		adminmsg('operate_success',"$basename&action=albums&job=list&aname=".rawurlencode($aname)."&owner=".rawurlencode($owner)."&crtime_s=$crtime_s&crtime_e=$crtime_e&lasttime_s=$lasttime_s&lasttime_e=$lasttime_e&private=$private&lines=$lines&orderway=$orderway&ordertype=$ordertype&page=$page&");
	} elseif ($job == 'edit') {
		InitGP(array('aid'));
		$album = $db->get_one("SELECT aid,aname,aintro,private FROM pw_cnalbum WHERE aid=".pwEscape($aid));
		empty($album) && Showmsg('album_not_exist',"$basename&action=albums");
		if (empty($_POST['step'])) {
			InitGP(array('aname','owner','crtime_s','crtime_e','lasttime_s','lasttime_e','private','lines','orderway','ordertype','page'));
			${'private_'.$album['private']} = 'selected';
			require_once PrintApp('admin');
		} else {
			InitGP(array('aname','aintro','private'));
			InitGP(array('url_aname','url_owner','url_crtime_s','url_crtime_e','url_lasttime_s','url_lasttime_e','url_private','url_lines','url_orderway','url_ordertype','url_page'));
			$db->update("UPDATE pw_cnalbum SET ".pwSqlSingle(array('aname' => $aname,'aintro' => $aintro, 'private' => $private))." WHERE aid=".pwEscape($aid));
			adminmsg('operate_success',"$basename&action=albums&job=list&aname=".rawurlencode($url_aname)."&owner=".rawurlencode($url_owner)."&crtime_s=$url_crtime_s&crtime_e=$url_crtime_e&lasttime_s=$url_lasttime_s&lasttime_e=$url_lasttime_e&private=$url_private&lines=$url_lines&orderway=$url_orderway&ordertype=$url_ordertype&page=$url_page&");
		}
	} else {
		require_once PrintApp('admin');
	}
} elseif ($action == 'photos') {
	if ($job == 'list') {
		require_once(R_P . 'require/app_core.php');
		InitGP(array('aid','aname','uploader','pintro','uptime_s','uptime_e','orderway','ordertype','lines','page'));
		if (empty($aid) && empty($aname) && empty($uploader) && empty($pintro) && empty($uptime_s) && empty($uptime_e)) {
			adminmsg('noenough_condition',"$basename&action=photos");
		}
		$uptime_s && $uptime_s = PwStrtoTime($uptime_s);
		$uptime_e && $uptime_e = PwStrtoTime($uptime_e);
		
		$urladd = '';
		$sql = "ca.atype='0'";
		if ($aid) {
			$sql .= ' AND ca.aid ='.pwEscape($aid);
			$urladd .= '&aid='.$aid;
		}
		if ($aname) {
			$aname = str_replace('*','%',$aname);
			$sql .= ' AND ca.aname LIKE '.pwEscape($aname);
			$urladd .= '&aname='.rawurlencode($aname);
		}
		if ($uploader) {
			$uploader = str_replace('*','%',$uploader);
			$sql .= ' AND cp.uploader LIKE '.pwEscape($uploader);
			$urladd .= '&uploader='.rawurlencode($uploader);
		}
		if ($pintro) {
			$pintro = str_replace('*','%',$pintro);
			$sql .= ' AND cp.pintro LIKE '.pwEscape($pintro);
			$urladd .= '&pintro='.rawurlencode($pintro);
		}
		if ($uptime_s) {
			$sql .= ' AND cp.uptime>='.pwEscape($uptime_s);
			$urladd .= "&uptime_s=$uptime_s";
		}
		if ($uptime_e) {
			$sql .= ' AND cp.uptime<='.pwEscape($uptime_e);
			$urladd .= "&uptime_e=$uptime_e";
		}
		switch ($orderway) {
			case 'uptime' :
				$orderway = 'cp.uptime';break;
			case 'hits' :
				$orderway = 'cp.hits';break;
			case 'c_num' :
				$orderway = 'cp.c_num';break;
			default:
				$orderway = '';break;
		}
		
		$ordertype = $ordertype == 'asc' ? 'asc' : 'desc';
		$sqladd = $orderway ? "ORDER BY $orderway $ordertype" : '';
		$urladd .= $orderway ? "&orderway=$orderway&ordertype=$ordertype" : '';
		$count = $db->get_value("SELECT COUNT(*) AS count FROM pw_cnphoto cp LEFT JOIN pw_cnalbum ca ON cp.aid=ca.aid WHERE $sql");
		empty($count) && adminmsg('no_photos',"$basename&action=photos&job=list");
		!is_numeric($lines) && $lines=30;
		$page < 1 && $page = 1;
		$numofpage = ceil($count/$lines);
		if ($numofpage && $page > $numofpage) {
			$page = $numofpage;
		}
		$pages=numofpage($count,$page,$numofpage,"$basename&action=$action&job=list&lines=$lines$urladd&");
		$start = ($page-1)*$lines;
		$limit = pwLimit($start,$lines);
		$query = $db->query("SELECT cp.pid,cp.aid,cp.path,cp.uploader,cp.uptime,cp.ifthumb,cp.hits,cp.c_num,ca.aname FROM pw_cnphoto cp LEFT JOIN pw_cnalbum ca ON cp.aid=ca.aid WHERE ".$sql." ".$sqladd." ".$limit);
		$cnpho = array();
		while ($rt = $db->fetch_array($query)) {
			$rt['s_aname']	= substrs($rt['aname'],10);
			$rt['path']	= getphotourl($rt['path'], $rt['ifthumb']);
			$rt['uptime']	= get_date($rt['uptime']);
			$cnpho[] = $rt;
		}
		require_once PrintApp('admin');
	} elseif ($job == 'delete') {
		InitGP(array('aid','aname','uploader','pintro','uptime_s','uptime_e','orderway','ordertype','lines','page','selid'));
		require_once(R_P . 'require/app_core.php');
		foreach ($selid as $key => $pid) {
			$photo = $db->get_one("SELECT cp.path,ca.aid,ca.lastphoto,ca.lastpid,ca.ownerid FROM pw_cnphoto cp LEFT JOIN pw_cnalbum ca ON cp.aid=ca.aid WHERE cp.pid=" . pwEscape($pid) . " AND ca.atype='0'");
			if (empty($photo)) {
				adminmsg('data_error',"$basename&action=photos");
			}
			$uids[] = $photo['ownerid'];
			$db->update("DELETE FROM pw_cnphoto WHERE pid=" . pwEscape($pid));
		
			$pwSQL = array();
			if ($photo['path'] == $photo['lastphoto']) {
				$pwSQL['lastphoto'] = $db->get_value("SELECT path FROM pw_cnphoto WHERE aid=" . pwEscape($photo['aid']) . " ORDER BY pid DESC LIMIT 1");
			}
			if (strpos(",$photo[lastpid],",",$pid,") !== false) {
				$pwSQL['lastpid'] = implode(',',getLastPid($photo['aid']));
			}
			$upsql = $pwSQL ? ',' . pwSqlSingle($pwSQL) : '';
			$db->update("UPDATE pw_cnalbum SET photonum=photonum-1{$upsql} WHERE aid=" . pwEscape($photo['aid']));
		
			pwDelatt($photo['path'], $db_ifftp);
			$lastpos = strrpos($photo['path'],'/') + 1;
			pwDelatt(substr($photo['path'], 0, $lastpos) . 's_' . substr($photo['path'], $lastpos), $db_ifftp);
			pwFtpClose($ftp);
		
			$affected_rows = delAppAction('photo',$pid) + 1;
			countPosts("-$affected_rows");
		}
		$uids = array_unique($uids);
		updateUserAppNum($uids,'photo','recount');
		adminmsg('operate_success',"$basename&action=photos&job=list&aid=$aid&aname=".rawurlencode($aname)."&uploader=".rawurlencode($uploader)."&pintro=".rawurlencode($pintro)."&uptime_s=$uptime_s&uptime_e=$uptime_e&orderway=$orderway&ordertype=$ordertype&lines=$lines&page=$page&");
	} else {
		require_once PrintApp('admin');
	}
}
?>