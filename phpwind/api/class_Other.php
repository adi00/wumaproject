<?php

!defined('P_W') && exit('Forbidden');
/*
*api mode 12 其他操作/信息
*/
class Other {
    var $base;
	var $db;
	var $appver;

    function Other($base) {        
        $this->base = $base;
        $this->db = $base->db;
		$this->appver = '2009/12/18';
    }
	
	function fetchAppver($appid = null,$appkey = null,$appidstate = 0) {//获取版本号
        require_once(R_P.'admin/cache.php');
        if ($appid) {
            setConfig('db_appid', $appid);
        }
        if ($appkey) {
            setConfig('db_siteappkey', $appkey);
        }
		setConfig('db_appidstate', $appidstate);

		updatecache_c();
		return new ApiResponse($this->appver);
	}

	function insertNav($title,$link,$method = '1') {//添加主导航信息
		if (!$title) {
			return new ApiResponse(false);
		}
		$nid = $this->db->get_value("SELECT nid FROM pw_nav WHERE type='main' AND title=" . pwEscape($title));
		$view = $this->db->get_value("SELECT view FROM pw_nav WHERE type='main' ORDER BY view DESC");
		
		if (!$nid && $method == '1') {
			$this->db->update("INSERT INTO pw_nav"
				. " SET " . pwSqlSingle(array(
					'type'	=> 'main',
					'pos'	=> '-1',
					'title'	=> $name,
					'style'	=> '|||',
					'link'	=> $link,
					'alt'	=> $name,
					'target'=> 1,
					'view'	=> $view+1,
					'upid'	=> 0,
					'isshow'=> 1
			)));
		} elseif ($nid) {
			$this->db->update("UPDATE pw_topictype SET isshow=" . pwEscape($method) . " WHERE type='main' AND nid=" . pwEscape($nid));
		}
		$navMenu = L::loadClass('navmenu');
		$navMenu->settype('bbs_navinfo');
		$navMenu->cache();
		
		return new ApiResponse(true);
	}

	function shareLinks($stype = 'getsharelinks',$sid,$name,$url = '',$logo = '',$descrip = '',$threadorder = 0,$ifcheck = 0) {//友情链接
		
		require_once(R_P.'admin/cache.php');

		if ($stype == 'getsharelinks') {
			$sharelinkdb = array();
			$query = $this->db->query("SELECT * FROM pw_sharelinks");
			while ($rt = $this->db->fetch_array($query)) {
				$sharelinkdb[$rt['sid']] = $rt;
			}
			return new ApiResponse($sharelinkdb);

		} elseif ($stype == 'upsharelinks') {
			
			$sid = $this->db->get_value('SELECT sid FROM pw_sharelinks WHERE sid='.pwEscape($sid));
			if ($sid) {
				$pwSQL = pwSqlSingle(array(
					'name'			=> $name,
					'url'			=> $url,
					'logo'			=> $logo,
					'descrip'		=> $descrip,
					'threadorder'	=> $threadorder,
					'ifcheck'		=> $ifcheck
				));
				$this->db->update("UPDATE pw_sharelinks SET $pwSQL WHERE sid=".pwEscape($sid));
				updatecache_i();
				
			} else {
				$pwSQL = pwSqlSingle(array(
					'threadorder'	=> $threadorder,
					'name'			=> $name,
					'url'			=> $url,
					'logo'			=> $logo,
					'descrip'		=> $descrip,
					'ifcheck'		=> $ifcheck
				));
				$this->db->update("INSERT INTO pw_sharelinks SET $pwSQL");
				$sid = $this->db->insert_id();
				updatecache_i();
			}
			return new ApiResponse($sid);

		} elseif ($stype == 'remove') {
			
			$this->db->update('DELETE FROM pw_sharelinks WHERE sid='.pwEscape($sid));
			updatecache_i();
			return new ApiResponse($sid);

		} elseif ($stype == 'state') {

			$sid = $this->db->get_value('SELECT sid FROM pw_sharelinks WHERE sid='.pwEscape($sid));

			return new ApiResponse($sid);
		}
	}

	function alertCnzz() {//CNZZ统计开启
		global $db_ystats_ifopen;
		require_once(R_P.'admin/cache.php');
		if ($db_ystats_ifopen == '0') {
			setConfig('db_ystats_ifopen', 1);
			updatecache_c();
		}
		return new ApiResponse(true);
	}
	
	function showSurvey($itemdb = array()) {//调查问卷
		global $db_charset;
		
		$survey_cache = "<?php\r\n";
		if (!empty($itemdb) && is_array($itemdb)) {
			$survey_cache .= "\$db_survey='1';\r\n";
		} else {
			$survey_cache .= "\$db_survey='0';\r\n";
		}
		
		foreach ($itemdb as $key => $item) {
			$item['url'] = rawurldecode($item['url']);
			$itemd[$key] = $item;
		}
		if (is_array($itemd)) {
			$survey_cache .= "\$survey_cache=".pw_var_export($itemd);
			$survey_cache .= ';';
		}
		$survey_cache .= "\r\n?>";
		$survey_cache = pwConvert($survey_cache,$db_charset,'gbk');
		writeover(D_P."data/bbscache/survey_cache.php",$survey_cache);
		return new ApiResponse(true);
	}

    function configThreads($params) {//生成帖子交换缓存
        if ($params && is_array($params)) {
            require_once(R_P.'admin/cache.php');
            setConfig('db_threadconfig', $params);
            updatecache_c();
            return new ApiResponse(true);
        }
        return new ApiResponse(false);
    }

	function threadscateGory($classdb) {//生成帖子交换分类
    
        $classcache = "<?php\r\n\$info_class=array(\r\n";

        foreach ($classdb as $key => $class) {

            !$class['ifshow'] && $class['ifshow'] = '0';
            $flag && $info_class[$class['cid']]['ifshow'] && $class['ifshow'] = '1';

            $class['name'] = str_replace(array('"',"'"),array("&quot;","&#39;"),$class['name']);
            $classcache .= "'$class[cid]'=>".pw_var_export($class).",\r\n\r\n";
        }
        $classcache .= ");\r\n?>";
        writeover(D_P."data/bbscache/info_class.php",$classcache);
    }

	function insertHelp($hup = 0,$subject,$content,$url = '',$hid = 0,$action = 'add') {//添加帮助信息

		@include_once(D_P.'data/bbscache/help_cache.php');
		require_once(R_P.'admin/cache.php');
		$hup = (int)$hup;
		$hid = (int)$hid;
			
		if ($action == 'add' || $action == 'edit') {
			$title	= trim($title);
			$url	= trim($url);
			$content = str_replace(
				array("\t","\r",'  '),
				array('&nbsp; &nbsp; ','','&nbsp; '),
				trim($content)
			);
			if (empty($title)) {
				return new ApiResponse('help_title_empty');
			}
			$lv = 0;
			$fathers = '';
			if ($action == 'add') {
				foreach ($_HELP as $key => $value) {
					if (strtolower($title) == strtolower($value['title'])) {
						return new ApiResponse('help_title_exist');
					}
					if ($key == $hup) {
						$lv = $value['lv']+1;
						$fathers = ($value['fathers'] ? "$value[fathers]," : '').$hup;
						!$value['ifchild'] && $this->db->update("UPDATE pw_help SET ifchild='1' WHERE hid=".pwEscape($hup));
					}
				}
				$this->db->update("INSERT INTO pw_help"
					. " SET " . pwSqlSingle(array(
						'hup'		=> $hup,
						'lv'		=> $lv,
						'fathers'	=> $fathers,
						'title'		=> $title,
						'url'		=> $url,
						'content'	=> $content,
						'vieworder'	=> 0
				)));
				$hid = $this->db->insert_id();
			} elseif ($action == 'edit') {
				if ($hid == $hup) {
					return new ApiResponse('hup_error1');
				}
				if ($_HELP[$hid]['hup'] != $hup && strpos(",{$_HELP[$hup][fathers]},",",$hid,") !== false) {
					return new ApiResponse('hup_error2');
				}

				foreach ($_HELP as $key => $value) {
					if ($key != $hid && strtolower($title) == strtolower($value['title'])) {
						return new ApiResponse('help_title_exist');
					}
				}
				$this->db->update("UPDATE pw_help"
					. " SET " . pwSqlSingle(array(
							'hup'		=> $hup,
							'title'		=> $title,
							'url'		=> $url,
							'content'	=> $content,
							'vieworder'	=> 0
						))
					. " WHERE hid=".pwEscape($hid)
				);
			}
			
			updatecache_help();
			return new ApiResponse($hid);
		} elseif ($action == 'delete') {
			$this->db->update("DELETE FROM pw_help WHERE hid=".pwEscape($hid).'OR hup='.pwEscape($hid));
			updatecache_help();
			return new ApiResponse(true);
		}
	}
}
