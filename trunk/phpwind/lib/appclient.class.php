<?php
/**
 * APP相关
*/
class PW_AppClient{
	var $_db;

    function PW_Appclient() {
		global $db_siteappkey, $timestamp, $db_sitehash,$db_siteownerid,$db_siteid,$db_bbsurl,$db_charset,$db_appifopen,$db_appbbs,$db_appo,$pwServer;

		$db_bbsurl = Char_cv("http://".$pwServer['HTTP_HOST'].substr($pwServer['PHP_SELF'],0,strrpos($pwServer['PHP_SELF'],'/')));
		if (!file_exists(D_P."data/bbscache/forum_appinfo.php")){
			require_once(R_P."admin/cache.php");
			updatecache_f();
		}
		@include_once(D_P."data/bbscache/forum_appinfo.php");

		$this->_db = $GLOBALS['db'];
		$this->appkey = $db_siteappkey;
		$this->timestamp = time();
		$this->sitehash = $db_sitehash;
		$this->siteownerid = $db_siteownerid;
		$this->siteid = $db_siteid;
		$this->bbsurl = $db_bbsurl;
		$this->charset = $db_charset;
		$this->appifopen = $db_appifopen;
		$this->appbbs = $db_appbbs;
		$this->appo = $db_appo;
		$this->_appsdb = array();
		$this->_app_array = array();
		$this->_appslist = $this->getApplist();
		$this->appinfo = $forum_appinfo;
    }

	/**
     * 获取开启的APP列表
    */
    function getApplist() {
		@include_once(D_P."data/bbscache/apps_list_cache.php");
		
		!is_array($appsdb) && $appsdb = array();
		foreach ($appsdb as $value) {
			if ($value['appstatus'] == 1 && $value['status'] == 1) {
				$this->_appsdb[$value['appid']]['appid'] = $value['appid'];
				$this->_appsdb[$value['appid']]['name'] = $value['name'];
			}
		}
		if (!$this->_appsdb || !$this->appifopen) {
			$this->_appsdb = array();
		}
		return $this->_appsdb;
    }

    /**
     * 获取个人APP列表
    */
    function userApplist($uids,$appids = '',$arrt = 0) {

		if (!$uids) return false;

		if (is_numeric($uids)) {
			$sql_uid = ' uid=' . pwEscape($uids);
		} else {
			$sql_uid = ' uid IN(' . pwImplode(explode(',',$uids)) . ')';
		}

		if (is_numeric($appids)) {
			$sql_appid = ' AND appid=' . pwEscape($appids);
		} elseif ($appids) {
			$sql_appid = ' AND appid IN(' . pwImplode(explode(',',$appids)) . ')';
		}
		
		$query = $this->_db->query("SELECT uid,appid,appname FROM pw_userapp WHERE $sql_uid $sql_appid");
		while ($rt = $this->_db->fetch_array($query)) {
			if ($this->_appslist[$rt['appid']] && $this->_appslist[$rt['appid']]) {
				if ($arrt == 1) {
					$this->_app_array[$rt['appid']] = $rt['appname'];
				} elseif ($arrt == 2) {
					$this->_app_array[$rt['uid']][$rt['appid']] = $rt;
				} else {
					$this->_app_array[] = $rt;
				}
			}
		}

		if (!$this->_app_array || !$this->appifopen) {
			$this->_app_array = array();
		}

		return $this->_app_array;
    }

	/**
     * 获取版块APP信息
    */
	function showForumappinfo($fid,$position = 'forum_erect',$appid = '') {
		!$fid && Showmsg('undefined_action');

		$positiondb = explode(",",$position);
		$appinfodb = array();
		$foruminfo['appinfo'] = $this->appinfo[$fid];
		!$foruminfo['appinfo'] && $foruminfo['appinfo'] = array();
		foreach ($foruminfo['appinfo'] as $key => $value) {
			if ($appid && $appid != $key) {
				continue;
			}
			foreach ($positiondb as $val) {
				if ($value['position'][$val]) {
					if ($key == '17') {
						$appinfo = $value['c_text'].":".$value['mms_emailcode'].".".$fid.$value['mms_domain'];
					}
					$appinfodb[$val][] = $appinfo;
				}
			}
		}

		$newappinfodb = array();
		foreach ($appinfodb as $p => $info) {
			$appinfo = '';
			foreach ($info as $val) {
				$appinfo .= $val.' ';
			}
			$newappinfodb[$p] = $appinfo;
			
		}
		
		return $newappinfodb;
	}

	/**
     * 获取数字签名
    */
	function getApicode() {
		$code = base64_encode(md5(md5($this->siteownerid.$this->appkey).$this->timestamp.$this->sitehash).$this->timestamp.'$sitehash='.$this->sitehash);
		return $code;
	}

	/**
     * 获取帖子交换上传列表
    */
    function getThreadsUrl($system = 'index', $mode = 'index', $action = 'index', $fid = 2) {
        global $winduid, $windid, $groupid;
        !$fid && $fid = 2; 
        $app_serverurl = 'http://app.phpwind.net/pwbbsapi.php?m=blooming&';
		$param = array(
			'pw_appId'		=> '8',
			'pw_charset'	=> $this->charset,
			'pw_uid'		=> $winduid,
			'pw_siteurl'	=> $this->bbsurl,
			'pw_t'			=> $this->timestamp,
			'pw_system'		=> $system,
			'pw_username'	=> $windid,
			'pw_fid'		=> $fid,
			'pw_mode'		=> $mode,
			'pw_action'		=> $action,
			'pw_groupid'	=> $groupid,
			'pw_query'		=> $this->getApicode(),
		);

        $url = $app_serverurl;
        ksort($param);
        foreach ( $param as $key => $value ) {
            $url .= "$key=" . urlencode ( $value ) . '&';
        }
        $hash = $param ['pw_system'] .'&'.$param ['pw_mode'].'&'.$param ['pw_action'] .'&'.$param ['pw_appId'] . '&' . $param ['pw_uid'] . '&' . $param ['pw_siteurl']  . '&' . $param ['pw_t'] . '&' . $param['pw_query'];
        $url .= 'pw_sig=' . md5 ( $hash . $this->siteownerid );
        return $url;
    }

	/**
     * 获取帖子交换权限
    */
    function getThreadRight() {
        global $db,$windid,$groupid, $db_threadconfig;
        $put = array();
        $t = $db_threadconfig;
        if (is_array($t)) {
            if ($t['ifopen'] == 1) {
                $isManage = ($groupid == 3) ? 1 : 0;//manage?
                //下载权限
                $put['down']['admin'] = ($isManage == 1 && $t['if_admin_down'] == 1) ? 1 : 0;
                $put['down']['other'] = array();
                if ($t['if_other_down'] == 1) {
                    foreach ($t['permissions'] as $v) {
                        if ($v['username'] == $windid) {
                            $fid_arr			= explode(',',$v['fid']);
                            $if_down_arr		= explode(',',$v['if_down']);
                            $if_subscribe_arr	= explode(',',$v['if_subscribe']);
                            for ($i = 0;$i < count($fid_arr);$i++) {
                                if ($if_down_arr[$i] == 1) {
                                    $put['down']['other'][] = $fid_arr[$i];
                                }
                                if ($if_post_arr[$i] == 1) {
                                    $put['order']['other'][] = $if_subscribe_arr[$i];
                                }
                            }
                            break;
                        }
                    }
                }
                //上传权限
                $put['post']['admin']	= ($isManage == 1 && $t['if_admin_post'] == 1) ? 1 : 0;
                $put['post']['group']	= $t['post_group'] ? explode(',',$t['post_group']) : array();
                $put['post']['other']	= $t['post_user'] ? explode(',',$t['post_user']) : array();
            }
        }
        return $put;
    }

	/**
     * 获取APP iframe
    */
    function getAppIframe($app_id) {
        global $admin_name;
     
        $app_serverurl = 'http://apps.phpwind.net/appsmanager.php';

		$param = array(
			'pw_sitehash'	=> $this->sitehash,
			'pw_fromurl'	=> $this->bbsurl . "/admin.php?adminjob=app",
			'pw_time'		=> $this->timestamp,
			'pw_user'		=> $admin_name,
			'pw_appid'		=> $app_id,
		);
        
		$url = $app_serverurl . '?';
		ksort($param);

		foreach ($param as $key => $value) {
			$url .= "$key=" . urlencode($value) . '&';
		}
		
		$arg = 'pw_appid='.$param['pw_appid'].'&pw_user='.$param['pw_user'].'&pw_time='.$param['pw_time'];

		$url .= 'pw_sig=' . md5($arg . $this->siteownerid);
		return $url;
    }

	/**
     * 获取在线APP列表
    */
	function getOnlineApp() {
		global $admin_name;
		$app_list = 'http://apps.phpwind.net/adminlist.php';
		$param = array(
			'pw_sitehash'	=> $this->sitehash,
			'pw_fromurl'	=> $this->bbsurl . "/admin.php?adminjob=app",
			'pw_time'		=> $this->timestamp,
			'pw_user'		=> $admin_name,
		);
		$arg = implode('|',$param);
		
		ksort($param);
		$url = $app_list . '?';
		foreach ($param as $key => $value) {
			$url .= "$key=" . urlencode($value) . '&';
		}
		$url .= 'pw_sig=' . md5($arg . $this->siteownerid);
		return $url;
	}

	/**
     * APP论坛状态
    */
	function alertAppState($admintype) {
		global $admin_name,$db_bbsname,$db_timedf;
		
		$param = array(
			'pw_sitehash'	=> $this->sitehash,
			'pw_fromurl'	=> $this->bbsurl . "/admin.php?adminjob=app",
			'pw_time'		=> $this->timestamp,
			'pw_user'		=> $admin_name,
		);
		if ($admintype == 'open') {
			$param = array_merge($param, array(
				'action'	=> 'open',
				'sitename'	=> $db_bbsname,
				'siteurl'	=> $this->bbsurl,
				'charset'	=> $this->charset,
				'timedf'	=> $db_timedf
			));
		} elseif ($admintype == 'close') {
			$param['action'] = 'close';
		}

		ksort($param);

		$str = $arg = '';
		foreach ($param as $key => $value) {
			if ($value) {
				$str .= "$key=" . urlencode($value) . '&';
				$arg .= "$key=$value&";
			}
		}
		$str .= 'pw_sig=' . md5($arg . $this->siteownerid);

		return $str;
	}

	/**
     * 判断是否为本地
    */
	function isLocalhost($host) {
		if ($host && strpos($host,'localhost') === false && strpos($host,'127.0') === false && strpos($host,'127.1') === false && !preg_match('/^192.168.*/',$host) && !preg_match('/^10.*/',$host)) {
			$islocalhost = false;
		} else {
			$islocalhost = true;
		}
		return $islocalhost;
	}

	/**
     * 自动注册APP帐号
    */
	function RegisterApp($host,$type = 'new',$siteid,$siteownerid,$sitehash) {
		global $admin_name;
		require_once(R_P.'require/posthost.php');
		
		//if (!$this->isLocalhost($host) && (!$this->appkey || $type == 'modify')) {
		if (!$this->isLocalhost($host)) {
			
			if ($type == 'modify') {
				$a = 'modify';
				$pw_query = '&pw_query='.urlencode($this->getApicode());
			} else {
				$a = 'register';
				$pw_query = '';
				$siteid = $this->siteid;
				$siteownerid = $this->siteownerid;
				$sitehash = $this->sitehash;
			}
			
			$reginfo = array(
				'pw_siteid'       => $siteid,
				'pw_siteownerid'  => $siteownerid,
				'pw_sitehash'     => $sitehash,
				'pw_pt'           => $this->timestamp,
				'pw_siteurl'      => $this->bbsurl,
				'pw_charset'	  => $this->charset,
				'pw_username'     => $admin_name
			);

			ksort($reginfo);
			$str = '';
			foreach($reginfo as $key=> $val) {
				$str .= $key.'='.urlencode($val).'&';
			}
			$sig = md5($str.$siteownerid);
			$str .= 'pw_sig='.$sig;
			
			$data = PostHost('http://app.phpwind.net/pwbbsapi.php?',"m=register&a=$a&".$str.$pw_query,'POST');
			
			$backdata = substr($data,strpos($data,'$backdata=')+10);
			return $backdata;

			if (strpos($data,'$back=next') !== false) {
				return 'next';
			} else {
				return 'end';
			}
	   }
	   return false;
	}
}
?>