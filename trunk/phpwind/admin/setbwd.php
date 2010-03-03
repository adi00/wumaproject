<?php
!function_exists('adminmsg') && exit('Forbidden');
$basename="$admin_file?adminjob=setbwd";

//引入短消息类
require_once(R_P.'require/msg.php');

InitGP(array('action','job', 'page'));
$optCates = '';
$db_perpage = 20;
$operate = array(
	0 => getLangInfo('cpmsg','filter_operate_not'),
	1 => getLangInfo('cpmsg','filter_operate_pass'),
	2 => getLangInfo('cpmsg','filter_operate_del'),
	3 => getLangInfo('cpmsg','filter_operate_replace'),
);

$scan_type = array(
	'thread' => getLangInfo('cpmsg','filter_scan_type_thread'),
	'post'   => getLangInfo('cpmsg','filter_scan_type_post'),
);

$level = array(
	1 => getLangInfo('cpmsg','filter_word_level_forbidden'),
	2 => getLangInfo('cpmsg','filter_word_level_checked'),
	3 => getLangInfo('cpmsg','filter_word_level_replace'),
);

$source = array(
	0 => getLangInfo('cpmsg','filter_word_center'),
	1 => getLangInfo('cpmsg','filter_word_custom'),
);

$score = array(1 => 1, 2 => 0.8, 3 => 0.6);
$center_level = array(10 => 1, 8 => 2, 6 => 3);//中心更新下来的词语等级
$num = '';
$update_show = 'display:none;';
$updateHost = 'http://app.phpwind.net/pwbbsapi.php?';

if ($admin_gid == 3) {
	!$action && $action = 'scan';
} else {
	!$action && $action = 'check';
}

if ($action == 'setting') {
	if ($admin_gid != 3){
		adminmsg('illegal_request');
	}
	if (empty($job)) {
		InitGP(array('page','type','keyword','class','show', 'sort', 'importshow', 'success','fail'));
		$type = intval($type);
		$class = (int) $class;

		//是否显示需要更新
		$update_show = update();
		
		$style = array(
			1 => 'style=" background:#F00;color: #fff;display:inline;padding:1px;padding:0 5px;"',
			2 => 'style=" background:#ff6600;color: #fff;display:inline;padding:1px;padding:0 5px;"',
			3 => 'style=" background:#44aa00;color: #fff;display:inline;padding:1px;padding:0 5px;"',
		);

		(!is_numeric($page) || $page<1) && $page = 1;
		$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
		
		$sqladd = ' WHERE 1 ';
		if($keyword){
			$sqladd .= " AND word LIKE ".pwEscape("%$keyword%");
		}

		if($type){
			$sqladd .= " AND type = " . pwEscape($type);
		}
		
		switch ($sort) {
			case 'word':
				$ordersql = 'ORDER BY word ASC';
				break;
			case 'type':
				$ordersql = 'ORDER BY type DESC';
				break;
			case 'class':
				$ordersql = 'ORDER BY classid ASC';
				break;
			case 'source':
				$ordersql = 'ORDER BY custom DESC';
				break;
			default:
				$ordersql = 'ORDER BY id DESC';
				break;
		} 
		
		$show_all = $show_center = '';
		if(intval($class) > 0){
			$sqladd .= " AND classid = " . pwEscape($class);
		} elseif ($class == -1) {
			$show_center = 'class="current"';
			$sqladd .= " AND custom = 0";
		}
		
		if (!$show && !$class) {
			$show_all = 'class="current"';
		} elseif ($show == -1) {
			$sqladd .= " AND custom = 0";
			$show_center = 'class="current"';
		} elseif (intval($show)) {
			$sqladd .= " AND classid = " . pwEscape($show);
		}
		
		if ($show == '') {
			$show = 0;
			$classid = getCloseClass();
			$sqladd .= ' AND classid NOT IN ('.$classid.') ';
		}

		$sql = "SELECT COUNT(*) AS sum FROM pw_wordfb ".$sqladd;
		$count = $db->get_value($sql);
		$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=setting&type=$type&keyword=".rawurlencode($keyword)."&sort=".rawurlencode($sort)."&show=".rawurlencode($show)."&");

		if(!$keyword) $keyword = getLangInfo('cpmsg','filter_keyword');
		
		$classdb = array();
		$search_class = array();
		$classlist = array();
		$sql = "SELECT * FROM pw_filter_class";
		$query = $db->query($sql);
		while($rt = $db->fetch_array($query)){
			HtmlConvert($rt);
			if ($rt['state']) {
				$search_class[$rt['id']]=$rt;
			}
			$classdb[$rt['id']]=$rt;
			$classlist[$rt['id']]=$rt['title'];
			if ($show == $rt['id']) {
				if($rt['state'] > 0){
					$switch = getLangInfo('cpmsg','filter_class_close');
				} else {
					$switch = getLangInfo('cpmsg','filter_class_open');
				}
			}
		}
		$classlist[0] = getLangInfo('cpmsg','filter_class_nonentity');
		
		$replacedb = array();
		$sql = "SELECT * FROM pw_wordfb ".$sqladd." $ordersql $limit";

		$query = $db->query($sql);
		while($rt = $db->fetch_array($query)){
			HtmlConvert($rt);
			if (!$rt['type']) $rt['type'] = 3;
			$rt['class'] = $classlist[$rt['classid']];
			$rt['style'] = $style[$rt['type']];
			$rt['level'] = $level[$rt['type']];
			$rt['source'] = $source[$rt['custom']];
			$replacedb[$rt['id']]=$rt;
		}
		include_once PrintEot('filter');exit;
	} elseif ($job == 'add') {
		InitGP(array('step'));
		if ($step == 2) {
			InitGP(array('type','word','repword','class','newclass'));
			$word = $_POST['word'];
			$word = trim(str_replace("\r\n",",",$word));			
			$word = explode(',', $word);
			$strword = '';
			foreach ($word as $value) {
				$strword .= $strword ? ', '.pwEscape(trim($value)).'' : pwEscape(trim($value));
			}
			
			$type = intval($type);
			if(!$word) ajaxmsg('filtermsg_cannot', "$basename&action=setting");

			//查询是否有重复记录
			$sql = "SELECT * FROM pw_wordfb WHERE word IN ($strword)";
			$query = $db->query($sql);
			$wordfb = $db->affected_rows($query);
			
			//插入新分类
			if ($newclass) {
				$class = newClass($newclass);
			}
			
			if (!$wordfb) {
				$repword || $repword = '*****';
				
				//插入敏感词
				insertWord($word, $type, $class, $repword = '*****');
				
				//更新缓存
    			updatecache_w();
    		
				//设置字典文件
				setDictionary();

				require_once(R_P.'require/posthost.php');
				$appclient = L::loadClass('AppClient');

				$sitehash = $appclient->getApicode();

				$word = serialize(pwConvert($word,'UTF8',$db_charset));

				//提交到中心词库
				$data = PostHost($updateHost, "m=wordsfb&a=custom&sitehash=$sitehash&type=plus&word=$word", "POST");

				//重定向
				adminmsg('operate_success', "$basename&action=setting");
			}
		} else {
		    define('AJAX', 1);
		    
		    $classdb = array();
			$sql = "SELECT * FROM pw_filter_class WHERE state=1";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				HtmlConvert($rt);
				$classdb[$rt['id']]=$rt;
			}
			
		    $ajax_basename = EncodeUrl($basename."&action=setting&job=add");
		    $post_basename = EncodeUrl($basename."&action=setting&job=enforce");
		    include_once PrintEot('filterAjax');
		    ajax_footer();
		}
	} elseif ($job == 'enforce') {
		define('AJAX', 1);
	    InitGP(array('step'));
		if ($step == 2) {
    		InitGP(array('word'));
    		$word = $_POST['word'];
			$word = trim(str_replace("\r\n",",",$word));			
			$word = explode(',', $word);
			$strword = '';
			foreach ($word as $value) {
				$strword .= $strword ? ', '.pwEscape(trim($value)).'' : pwEscape(trim($value));
			}
			
			$type = intval($type);
			if(!$word) ajaxmsg('filtermsg_cannot', "$basename&action=setting");

			//查询是否有重复记录
			$sql = "SELECT id,word FROM pw_wordfb WHERE word IN ($strword)";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				HtmlConvert($rt);
				$wordfb[$rt['id']] =$rt['word'];
			}
			
			if ($wordfb) {
				$prompt = '';
				foreach ($wordfb as $value) {
					$prompt .= $prompt ? ", $value" : $value;
				}
	    		
				//提示信息
				$L = array(
					'prompt' => $prompt ,
				);
				
				echo getLangInfo('cpmsg','filter_word_repeat', $L);
			}
			
			ajax_footer();
		}
	} elseif ($job == 'edit') {
	    InitGP(array('step'));
		if ($step == 2) {
			InitGP(array('id', 'type','word','repword','class','newclass'));

			$type = intval($type);

			$sql = " SELECT id FROM pw_wordfb WHERE id != " . pwEscape($id) ." AND word = " . pwEscape($word);
			$num = $db->get_one($sql);

			if ($num) adminmsg('filter_repeat');
			
			//插入新分类
			if ($newclass) {
				$class = newClass($newclass);
			}

			$wordtime = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$repword || $repword = '*****';
			$value = array(
				'type'	 => $type,
				'wordreplace' => $repword,
				'wordtime' => $wordtime,
				'classid' => $class,
				'custom'   => 1
			);

			$sql = "UPDATE pw_wordfb"
				 . " SET " . pwSqlSingle($value)
				 . "WHERE id = " . pwEscape($id);
			$db->update($sql);

			//更新缓存
			updatecache_w();

			//设置字典文件
			setDictionary();

			//重定向
			adminmsg('operate_success', EncodeUrl($basename.'&action=setting'));
		} else {
		    define('AJAX', 1);
			InitGP(array('id'));

			$sql = " SELECT * FROM pw_wordfb WHERE id =".pwEscape($id);
			$word = $db->get_one($sql);

			$selected ='';
			if (!$word['type']) $word['type'] = 3;
			foreach ($level as $key => $value)
			{
				if ($word['type'] == $key) {
					$selected .= '<option value="'.$key.'" selected>'.$value.'</option>';
				} else {
					$selected .= '<option value="'.$key.'">'.$value.'</option>';
				}
			}
			
			$classdb = array();
			$classdb[0] = getLangInfo('cpmsg','filter_class_nonentity');
			$sql = "SELECT * FROM pw_filter_class";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				HtmlConvert($rt);
				$classdb[$rt['id']]=$rt['title'];
			}
			
			$class_selected ='';
			foreach ($classdb as $key => $value)
			{
				if ($word['classid'] == $key) {
					$class_selected .= '<option value="'.$key.'" selected>'.$value.'</option>';
				} else {
					$class_selected .= '<option value="'.$key.'">'.$value.'</option>';
				}
			}
			
			$ajax_basename = EncodeUrl($basename."&action=setting&job=edit");
			include_once PrintEot('filterAjax');
			ajax_footer();
		}
	} elseif ($job == 'batchedit') {
	    InitGP(array('step'));
		if ($step == 2) {
			InitGP(array('id', 'type','word','repword','class','newclass'));

			$type = intval($type);
			
			//插入新分类
			if ($newclass) {
				$class = newClass($newclass);
			}

			$wordtime = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$repword || $repword = '*****';
			$value = array(
				'type'	 => $type,
				'wordreplace' => $repword,
				'wordtime' => $wordtime,
				'classid' => $class,
				'custom'   => 1
			);

			$sql = "UPDATE pw_wordfb"
				 . " SET " . pwSqlSingle($value)
				 . "WHERE id IN (".$id.")";
			$db->update($sql);

			//更新缓存
			updatecache_w();

			//设置字典文件
			setDictionary();

			//重定向
			adminmsg('operate_success', "$basename" . "&action=setting");
		} else {
		    define('AJAX', 1);
			InitGP(array('id'));
			if (!$id) adminmsg('operate_error');
			$id = explode(',', $id);
			
			foreach ($id as $value){
				if($value==""){
					continue;
				}
				$str .= $str ? ', '.pwEscape($value).'' : pwEscape($value);
				$wid .= $wid ? ', '.$value : $value;
			}

			$sql = " SELECT * FROM pw_wordfb WHERE id IN (".$str.")";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				$word .= $word ? "\n".$rt['word'] : $rt['word'];
			}
			
			$classdb = array();
			$sql = "SELECT * FROM pw_filter_class WHERE state=1";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				HtmlConvert($rt);
				$classdb[$rt['id']]=$rt;
			}
			
			$ajax_basename = EncodeUrl($basename."&action=setting&job=batchedit");
			include_once PrintEot('filterAjax');
			ajax_footer();
		}
	}  elseif ($job == 'del') {
		InitGP(array('step'));
		if ($step == 2) {
			InitGP(array('id'));
			if (!$id) adminmsg('operate_error');
	
			$sql = "SELECT word, custom FROM pw_wordfb WHERE id IN (".$id.")";
			$query = $db->query($sql);
			$word = array();
			while ($rt = $db->fetch_array($query)) {
				if ($rt['custom'] == 1)  {
					$word[] = $rt['word'];
				}
			}
			$word = serialize($word);
	
			$sql = "DELETE FROM pw_wordfb WHERE id IN (".$id.")";
			$db->update($sql);
	
			//更新缓存
			updatecache_w();
	
			//设置字典文件
			setDictionary();
	
			require_once(R_P.'require/posthost.php');
			$appclient = L::loadClass('AppClient');
	
			$sitehash = $appclient->getApicode();
	
			$word = serialize(pwConvert(array($word),'UTF8',$db_charset));
	
			//提交到中心词库
			$data = PostHost($updateHost, "m=wordsfb&a=custom&sitehash=$sitehash&type=subtract&word=$word", "POST");
	
			//重定向
			adminmsg('operate_success', "$basename&action=setting");
		} else {
		    define('AJAX', 1);
			InitGP(array('id'));
			if (!$id) adminmsg('operate_error');
			$id = explode(',', $id);
						
			$count = 0;
			foreach ($id as $value){
				if($value==""){
					continue;
				}
				$str .= $str ? ', '.pwEscape($value).'' : pwEscape($value);
				$wid .= $wid ? ', '.$value : $value;
				$count++;
			}

			$sql = " SELECT * FROM pw_wordfb WHERE id IN (".$str.")";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				$list[] = $rt['word'];
			}
						
			$ajax_basename = EncodeUrl($basename."&action=setting&job=del");
			include_once PrintEot('filterAjax');
			ajax_footer();
		}
	}
} elseif ($action == 'class') {
	InitGP(array('step'));
	if (empty($job)) {
		InitGP(array('page'));

		//是否显示需要更新
		$update_show = update();

		(!is_numeric($page) || $page<1) && $page = 1;
		$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
		
		$sqladd = ' WHERE 1 ';

		//获取总分类数
		$sql = "SELECT COUNT(*) AS sum FROM pw_filter_class ".$sqladd;
		$count = $db->get_value($sql);
		$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=class");
		
		$word_count = array();		
		//获取各分类敏感词总数
		$sql = "SELECT classid,COUNT(id) AS count FROM pw_wordfb GROUP BY classid";
		$query = $db->query($sql);
		while($rt = $db->fetch_array($query)){
			HtmlConvert($rt);
			$word_count[$rt['classid']] = $rt['count'];
		}

		$classdb = array();
		//读取分类列表
		$sql = "SELECT * FROM pw_filter_class $sqladd $limit";
		$query = $db->query($sql);
		while($rt = $db->fetch_array($query)){
			HtmlConvert($rt);
			$rt['count'] = $word_count[$rt['id']];
			$rt['status'] = $rt['state'] ? getLangInfo('cpmsg','filter_class_show_open') : getLangInfo('cpmsg','filter_class_show_close');
			$rt['state_button'] = $rt['state'] ? getLangInfo('cpmsg','filter_class_show_close') : getLangInfo('cpmsg','filter_class_show_open');
			$classdb[] = $rt;				
		}
		include_once PrintEot('filter');exit;
	} elseif ($job == 'add') {//添加分类
		if ($step == 2) {
			InitGP(array('title'));
			
			//判断长度
			if (strlen($title) > 16) adminmsg('filter_class_len', EncodeUrl($basename.'&action=class'));
			
			if (!newClass($title)) adminmsg('filter_class_repeat', EncodeUrl($basename.'&action=class'));

			//重定向
			adminmsg('operate_success', EncodeUrl($basename.'&action=class'));
		} else {
			define('AJAX', 1);
	    	$ajax_basename = EncodeUrl($basename."&action=class&job=add");
	    	include_once PrintEot('filterAjax');
	    	ajax_footer();
		}
	} elseif ($job == 'edit') {//修改分类
		if ($step == 2) {
			InitGP(array('id','title'));
			
			//判断长度
			if (strlen($title) > 16) adminmsg('filter_class_len', EncodeUrl($basename.'&action=class'));
			
			//判断是否有重复分类
			$sql = " SELECT id FROM pw_filter_class WHERE title = " . pwEscape($title);
			$num = $db->get_one($sql);			
			if ($num) adminmsg('filter_class_repeat', EncodeUrl($basename.'&action=class'));
	
			//更新分类信息
			$sql = "UPDATE pw_filter_class SET title=".pwEscape($title)." WHERE id=".pwEscape($id);
			$db->update($sql);

			//重定向
			adminmsg('operate_success', EncodeUrl($basename.'&action=class'));
		} else {
			define('AJAX', 1);
			InitGP(array('id'));
			
			//获取分类信息
			$sql = "SELECT id,title FROM pw_filter_class WHERE id=".pwEscape($id);
			$class = $db->get_one($sql);
			
	    	$ajax_basename = EncodeUrl($basename."&action=class&job=edit");
	    	include_once PrintEot('filterAjax');
	    	ajax_footer();
		}
	} elseif ($job == 'del') {//删除分类
		if ($step == 2) {
			InitGP(array('class'));
			$class = (int) $class;

			delClass($class);
			
			//更新缓存
    		updatecache_w();
    		
			//设置字典文件
			setDictionary();
			
			//重定向
			adminmsg('operate_success', $basename.'&action=class');exit;
		} else {
			define('AJAX', 1);
			InitGP(array('class'));
			$class = (int) $class;
			
			if ($class > 0) {
				//获取分类名
				$sql = "SELECT title FROM pw_filter_class WHERE id=".pwEscape($class);
				$title = $db->get_value($sql);
				
				//获取该分类敏感词总数
				$sql = "SELECT COUNT(id) AS count FROM pw_wordfb WHERE classid=".pwEscape($class);
				$count = $db->get_value($sql);
			} elseif ($class == 0) {
				$title = getLangInfo('cpmsg', 'filter_all_word');
				
				//获取该分类敏感词总数
				$sql = "SELECT COUNT(id) AS count FROM pw_wordfb";
				$count = $db->get_value($sql);
			} else {
				adminmsg('filter_class_cannot_delete');
			}
			
	    	$ajax_basename = EncodeUrl($basename."&action=class&job=del");
	    	include_once PrintEot('filterAjax');
	    	ajax_footer();
		}
	} elseif ($job == 'import') {//导入词库
		if ($step == 2) {
			InitGP(array('class', 'newclass'),'P');
			$class = (int) $class;
			$upload = $_FILES['upload'];
			
			//插入新分类
			if ($newclass) {
				$class = newClass($newclass);
			}
	
			if (is_array($upload)) {
				$upload_name = $upload['name'];
				$upload_size = $upload['size'];
				$upload = $upload['tmp_name'];
			}

			$basename.="&type=$type";
	
			if($upload && $upload!='none'){
				require_once(R_P.'require/postfunc.php');
				$attach_ext = strtolower(substr(strrchr($upload_name,'.'),1));
				if(!if_uploaded_file($upload)){
					adminmsg('upload_error');
				} elseif($attach_ext!='txt'){
					adminmsg('upload_type_error');
				}
				$source = D_P."data/tmp/word.txt";
				if(postupload($upload,$source)){
					$content = explode("\n",readover($source));
					$wordtime = mktime(0,0,0,date("m"),date("d"),date("Y"));

					$success = 0;
					$fail    = 0;
					foreach($content as $key => $value){
						if($value){
							$word = substr($value, 0, strpos($value,'|'));
							$type = trim(substr(strrchr($value,'|'), 1));
							if (!intval($type)) {
								$fail++;
								continue;
							}
						
							$id = $db->get_value("SELECT id FROM pw_wordfb WHERE word=".pwEscape($word));
		
							if(empty($id)){
								$sql  ="INSERT INTO pw_wordfb (word,wordreplace,type,wordtime,classid,custom) VALUES (".pwEscape($word).", '*****', ".pwEscape($type).", ".pwEscape($wordtime).", ".pwEscape($class).", 1)";
								$db->update($sql);
								$success++;
							} else {
								$fail++;
							}
						}
					}
					
					//更新缓存
					updatecache_w();
			
					//设置字典文件
					setDictionary();
					
					//重定向
					$jumpurl = "$basename" . "&action=setting&importshow=1&success=".$success."&fail=".$fail;
					$show = <<<EOT
<script language="JavaScript">
	location.href = "$jumpurl";
</script>
EOT;
					echo $show;
				} else{
					adminmsg('upload_error');
				}
				P_unlink($source);
			}
			
		} else {
			define('AJAX', 1);
			InitGP(array('class'));
			$class = (int) $class;
			
			$classdb = array();
			$sql = "SELECT * FROM pw_filter_class WHERE state=1";
			$query = $db->query($sql);
			while($rt = $db->fetch_array($query)){
				HtmlConvert($rt);
				$classdb[$rt['id']]=$rt;
			}
			
	    	$ajax_basename = EncodeUrl($basename."&action=class&job=import");
	    	include_once PrintEot('filterAjax');
	    	ajax_footer();
		}
	} elseif ($job == 'importshow') {//显示导入结果
		define('AJAX', 1);
		InitGP(array('success', 'fail'));
	    include_once PrintEot('filterAjax');
	    ajax_footer();
	} elseif ($job == 'export') {//导出词库
		if ($step == 2) {
			InitGP(array('class', 'dict_name'));
			$class = (int) $class;
			
			if (intval($class) > 0) {				
				$sql = "SELECT word, type FROM pw_wordfb WHERE classid=".pwEscape($class);
				$query = $db->query($sql);
				while($rt = $db->fetch_array($query)){
					$words .= $rt['word']."|".$rt['type']."\r\n";
				}
			} else {
				$classid = getCloseClass();
			
				$sql = "SELECT word, type FROM pw_wordfb WHERE classid NOT IN ($classid) ";
				$query = $db->query($sql);
				while($rt = $db->fetch_array($query)){
					$words .= $rt['word']."|".$rt['type']."\r\n";
				}
			}
			
			$dict_name = $dict_name.'.txt';
			if(!strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"msie")) {
				$dict_name = pwConvert($dict_name,'UTF8',$db_charset);
			}
			
			header('Last-Modified: '.gmdate('D, d M Y H:i:s',$timestamp+86400).' GMT');
			header('Cache-control: no-cache');
			header('Content-Encoding: none');
			header('Content-Disposition: attachment; filename="'.$dict_name.'"');
			header('Content-type: txt');
			header('Content-Length: '.strlen($words));
			echo $words;exit;
		} else {
			define('AJAX', 1);
			InitGP(array('class'));
			$class = (int) $class;

			if ($class > 0) {
				//获取分类名
				$sql = "SELECT title FROM pw_filter_class WHERE id=".pwEscape($class);
				$title = $db->get_value($sql);

				//获取该分类敏感词总数
				$sql = "SELECT COUNT(id) AS count FROM pw_wordfb WHERE classid=".pwEscape($class);
				$count = $db->get_value($sql);
			} else {
				$title = getLangInfo('cpmsg','filter_all_word');
				
				//获取该分类敏感词总数
				$sql = "SELECT COUNT(id) AS count FROM pw_wordfb";
				$count = $db->get_value($sql);
				
				$job = 'exportall';
			}
			
	    	$ajax_basename = EncodeUrl($basename."&action=class&job=export");
	    	include_once PrintEot('filterAjax');
	    	ajax_footer();
		}
	} elseif ($job == "switch") {//开启/关闭分类
		if ($step == 2) {
			InitGP(array('class', 'state'));
			
			if ($class > 0) {
				$sql = "UPDATE pw_filter_class SET state=".pwEscape($state)." WHERE id=".pwEscape($class);;
				$db->update($sql);
								
				//更新缓存
	    		updatecache_w();
	    		
				//设置字典文件
				setDictionary();
				
				//重定向
				adminmsg('operate_success', "$basename" . "&action=class");
			}
		} else {
			define('AJAX', 1);
			InitGP(array('class'));
			$class = (int) $class;
			
			if ($class > 0) {
				//获取分类名
				$sql = "SELECT title,state FROM pw_filter_class WHERE id=".pwEscape($class);
				$filter_class = $db->get_one($sql);
				$title  = $filter_class['title'];
				$state  = $filter_class['state'];
				$state  = $state ? 0 : 1;
				$show   = $state ? getLangInfo('cpmsg','filter_class_show_open') : getLangInfo('cpmsg','filter_class_show_close');
				$prompt = $state ? getLangInfo('cpmsg','filter_switch_open') : getLangInfo('cpmsg','filter_switch_close');

				
				//获取该分类敏感词总数
				$sql = "SELECT COUNT(id) AS count FROM pw_wordfb WHERE classid=".pwEscape($class);
				$count = $db->get_value($sql);
			} else {
				ajaxmsg('filter_class_state');
			}
			
	    	$ajax_basename = EncodeUrl($basename."&action=class&job=switch");
	    	include_once PrintEot('filterAjax');
	    	ajax_footer();
		}
	}
} elseif ($action == 'scan') {
	if ($admin_gid != 3 && $admin_gid != 4){
		adminmsg('illegal_request');
	}

	if ($job == 'go') {
		define('AJAX', 1);
		InitGP(array('type', 'record_id', 'scan_count', 'remaintime', 'result_count', 'count', 'start_time', 'skip', 'convert'));
		$pagesize = 100;
		
		//如果没有敏感词,则不扫描
		$sql = "SELECT COUNT(id) AS count FROM pw_wordfb";
		$word_count = $db->get_value($sql);
		if(!$word_count) ajaxmsg('filter_setting_not_word', "$basename&action=scan");
		
		//如果没有词典,先更新辞典
		$sql = "SELECT COUNT(id) AS count FROM pw_filter_dictionary";
		$word_count = $db->get_value($sql);
		if(!$word_count){
			//更新缓存
			updatecache_w();

			//设置字典文件
			setDictionary();	
		}
		
		if(!$type) $type= 'thread';//扫描类型
		$scan_count		= intval($scan_count);//扫描数
		$result_count	= intval($result_count);//扫描结果数
		$remaintime		= intval($remaintime);//扫描剩余时间
		$count			= intval($count);//总扫描数
		$start_time		= intval($start_time);//扫描开始时间
		$skip			= intval($skip);//跳词数
		$convert		= intval($convert);//是否开启简繁转换

		if (!$record_id) {
			if ($type == 'thread') {
				//当前进度
				$sql = "SELECT tid FROM pw_filter_dictionary ORDER BY id DESC LIMIT 1";
				$progress = $db->get_value($sql);
				
				//扫描总数
				$sql = "SELECT COUNT(tid) AS count FROM pw_threads WHERE tid > ".pwEscape($progress);;
				$count = $db->get_value($sql);
			} else {
				//扫描总数
				$sql = "SELECT COUNT(pid) AS count FROM pw_posts";
				$count = $db->get_value($sql);

				if (!empty($db_ptable)) {
					$table_count = count(explode( ',', $db_ptable));
					$count = $count * $table_count;
				}

				//当前进度
				$sql = "SELECT pid FROM pw_filter_dictionary ORDER BY id DESC LIMIT 1";
				$progress = $db->get_value($sql);
				
				//当次需要扫描的总数
				$count = $count - $progress;
			}
			
			//没有需要扫描的帖子
			if(!$count) ajaxmsg('filter_increase_threads', "$basename&action=scan");
			
			//扫描开始时间
			$start_time = $timestamp;
			
			//插入用户扫描进度
			$value = array(
				'scantime' => $timestamp,
				'type'     => $type,
				'num'      => 0,
				'result'   => 0
			);
			$value = pwSqlSingle($value);;

			//插入扫描记录
			$sql = "INSERT pw_filter_record SET  $value";
			$db->update($sql);

			//用户扫描进度ID
			$record_id = $db->insert_id();
		}

		$scan = L::loadClass('WordScan');
		$result = $scan->scan($type, $pagesize, $skip, $convert);
		$scan_count = $scan_count + intval($result['scan_count']);//扫描数
		$result_count = $result_count + intval($result['result_count']);//扫描结果数
		
		//if ($scan_count > 500 && !$remaintime) {
		if ($scan_count) {
			$average = ($timestamp - $start_time) /  $scan_count;//扫描平均耗时
			$remaintime = (int)$start_time + $count * (($timestamp - $start_time) /  $scan_count);//剩余时间估算
		}
		if ($remaintime) {
			$remaining = (int) $remaintime - $timestamp;//剩余时间估算
			$remaining < 60 && $remaining = 60;
		}
		if(!$remaining) {
			$remaining = getLangInfo('cpmsg','filter_counting');
		} else {
			$remaining = $scan->getRemainingTime($remaining);
		}

		//用户显示的扫描进度
		$sql = "UPDATE pw_filter_record SET num=" . pwEscape($scan_count) . ",result=" . pwEscape($result_count)
			 . " WHERE id = " . pwEscape($record_id);
		$db->update($sql);

		//判断扫描是否结束
		if ($scan_count >= $count) {
			//重定向
			ajaxmsg('filtermsg_scanfinish', "$basename&action=scan");
		}

		include_once PrintEot('filterAjax');
		ajax_footer();
	} else {
		$update_show = update();

		$count = $db->get_value("SELECT COUNT(*) AS count FROM pw_filter_record");
		$page<1 && $page = 1;
		$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
		$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=scan&");

		//获取字典信息
		$sql = " SELECT * FROM pw_filter_record ORDER BY id DESC $limit";
		$query = $db->query($sql);
		while ($rt = $db->fetch_array($query)) {
			$rt['time'] = get_date($rt['scantime'],'Y-m-d H:m:s');
			$rt['type'] = $scan_type[$rt['type']];
			$dictionarys[$rt['id']] = $rt;
		}
		include_once PrintEot('filter');exit;
	}
} elseif ($action == 'check') {
	if (empty($job)) {
		$update_show = update();
		Clear();
		
		$count = $db->get_value("SELECT COUNT(*) FROM pw_filter WHERE state=0 AND tid>0 AND pid=0");
		$page<1 && $page = 1;
		$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
		$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=check&");

		$sql = "SELECT pf.id,pf.created_at,pf.filter,pf.tid,pf.pid,pt.subject,pt.author FROM pw_filter AS pf LEFT JOIN pw_threads AS pt ON pf.tid = pt.tid WHERE pf.state=0 AND pf.tid>0 AND pid=0 ORDER BY pf.id DESC $limit";
		$query = $db->query($sql);
		while ($rt = $db->fetch_array($query)) {
			$rt['subject'] = substrs($rt['subject'], 30);
			$rt['date'] = get_date($rt['created_at'],'Y-m-d H:m:s');
			$check_list[] = $rt;
		}
	} elseif ($job == 'post') {
		$update_show = update();

		$count = $db->get_value("SELECT COUNT(*) FROM pw_filter WHERE state=0 AND tid>0 AND pid>0");
		$page<1 && $page = 1;
		$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
		$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=check&job=post&");

		$sql = "SELECT pf.*,pt.subject,pt.ptable FROM pw_filter AS pf LEFT JOIN pw_threads AS pt ON pf.tid = pt.tid WHERE pf.state=0 AND pf.pid>0 AND pf.tid>0 ORDER BY pf.id DESC $limit";
		$query = $db->query($sql);
		while ($rt = $db->fetch_array($query)) {
			$rt['subject'] = substrs($rt['subject'], 40);
			$rt['date'] = get_date($rt['created_at'],'Y-m-d H:m:s');
			$check_list[] = $rt;
		}
	} elseif ($job == 'pass') {
		InitGP(array('pid', 'tid', 'type'));

		if (!$tid && !$pid) adminmsg('operate_error');

		if (!$type || $type == 'thread') {
			if (is_array($tid)) {
				$sTid = pwImplode($tid);
			} else {
				$sTid = (int)$tid;
			}
			$ttable = array();
			//查找帖子信息
			$sql = "SELECT pf.id,pt.tid,pt.subject,pt.author FROM pw_filter AS pf "
				 . "LEFT JOIN pw_threads AS pt ON pf.tid=pt.tid WHERE pt.tid IN (".$sTid.") AND pf.pid=0";
			$query = $db->query($sql);
			$objid = array();
			while ($rt = $db->fetch_array($query)) {
				$objid[]  = $rt['id'];
				$tt = GetTtable($rt['tid']);
				$ttable[$tt] = 1;

				//发消息通知
				$L = array(
					'subject' => $rt['subject'] ,
				);
				$title	 = getLangInfo('cpmsg','filtermsg_thread_pass_title');
				$content = getLangInfo('cpmsg','filtermsg_thread_pass_content', $L);

				$msg = array(
					'toUser'	=> $rt['author'],
					'toUid'		=> '',
					'fromUid'	=> '',
					'fromUser'	=> 'SYSTEM',
					'subject'	=> $title,
					'content'	=> $content,
					'other'		=> array()
				);
				pwSendMsg($msg);
			}

			//更改帖子状态
			$sql = "UPDATE pw_threads SET ifcheck=1 WHERE tid IN (".$sTid.")";
			$db->update($sql);
			foreach (array_keys($ttable) as $pw_tmsgs) {
				$sql = "UPDATE $pw_tmsgs SET ifwordsfb='$db_wordsfb' WHERE tid IN (".$sTid.")";
				$db->update($sql);
			}
			$filter_id = implode(',' , $objid);
			if ($filter_id) {
				//更改审核状态,更新审核人员信息
				$sql = "UPDATE pw_filter SET state=1,assessor=". pwEscape($admin_name) .",updated_at=".pwEscape($timestamp) ." WHERE id IN (".$filter_id.")";
				$db->update($sql);
			}

			//重定向
			adminmsg('operate_success', "$basename" . "&action=check");
		} else { # 回复
			if (empty($pid)) adminmsg('operate_error');

			if (is_array($pid)) {
				if (!$selid = checkselid($pid)) {
					$basename = "javascript:history.go(-1);";
					adminmsg('operate_error');
				}
				$objid = array_keys($pid);
			} else {
				$selid = (int)$pid;
				$objid[] = GetGP('id');
			}

			$ptable = GetGP('ptable');

			if (is_array($ptable)) {
				if ($db_plist && count($db_plist)>1) {
					foreach ($ptable as $key=>$value) {
						if (isset($db_plist[$value])) {
							$postslist[$value] = GetPtable($value);
						}
					}
				} else {
					$postslist[] = 'pw_posts';
				}
			} else {
				$postslist[] = GetPtable($ptable);
			}

			foreach ($postslist as $pw_posts) {
				$fids  = $tids = array();
				$query = $db->query("SELECT fid,tid FROM $pw_posts WHERE pid IN($selid)");
				while ($rt = $db->fetch_array($query)) {
					$tids[$rt['tid']] ++;
					$fids[$rt['fid']] ++;
				}
				foreach ($tids as $key => $value) {
					$rt = $db->get_one("SELECT postdate,author FROM $pw_posts WHERE tid=" . pwEscape($key) . " ORDER BY postdate DESC LIMIT 1");
					$db->update("UPDATE pw_threads SET replies=replies+".pwEscape($value) . ",lastpost=" . pwEscape($rt['postdate'],false) . ",lastposter =" . pwEscape($rt['author'],false) . "WHERE tid=" . pwEscape($key));
					# memcache refresh
					$threadList = L::loadClass("threadlist");
					$threadList->updateThreadIdsByForumId($fid,$tid);
				}
				foreach ($fids as $key => $value) {
					$db->update("UPDATE pw_forumdata SET article=article+".pwEscape($value).",tpost=tpost+".pwEscape($value,false)."WHERE fid=".pwEscape($key));
				}
				$db->update("UPDATE $pw_posts SET ifcheck='1',ifwordsfb='$db_wordsfb' WHERE pid IN($selid)");
			}

			$filter_id = implode(',' , $objid);
			if ($filter_id) {
				//更改审核状态,更新审核人员信息
				$sql = "UPDATE pw_filter SET state=1,assessor=". pwEscape($admin_name) .",updated_at=".pwEscape($timestamp) ." WHERE id IN (".$filter_id.")";
				$db->update($sql);
			}
//
//			require_once(R_P.'require/updateforum.php');
//			//重定向
//			if ($pid_count != $tid_count) adminmsg('operate_error');
//
//			$objid = array();
//			for ($i = 0; $i <$pid_count; $i++) {#TODO 批量操作
//				$sql = "SELECT pf.id,pt.tid,pt.fid,pt.subject,pt.ptable FROM pw_filter AS pf "
//					 . "LEFT JOIN pw_threads AS pt ON pt.tid=pf.tid "
//					 . "WHERE pf.pid = ".pwEscape($pid[$i])." AND pf.tid=".pwEscape($tid[$i]);
//				$pass_info = $db->get_one($sql);
//
//				$pw_posts = GetPtable($pass_info['ptable']);
//				//获取作者信息
//				$sql = "SELECT pp.pid,pp.author FROM $pw_posts AS pp WHERE pp.pid=" . pwEscape($pid[$i]);
//				$pt = $db->get_one($sql);
//
//				$objid[]  = $pass_info['id'];
//
//				//发消息通知
//				$L = array(
//					'subject' => $pass_info['subject'] ,
//				);
//
//				$title	 = getLangInfo('cpmsg','filtermsg_post_pass_title');
//				$content = getLangInfo('cpmsg','filtermsg_post_pass_content', $L);
//				$msg = array(
//					'toUser'	=> $pt['author'],
//					'toUid'		=> '',
//					'fromUid'	=> '',
//					'fromUser'	=> 'SYSTEM',
//					'subject'	=> $title,
//					'content'	=> $content,
//					'other'		=> array()
//				);
//				pwSendMsg($msg);
//				$rt = $db->get_one("SELECT postdate,author FROM $pw_posts WHERE tid=".pwEscape($pass_info['tid'])."ORDER BY postdate DESC LIMIT 1");
//				$db->update("UPDATE pw_threads SET replies=replies+1,lastpost=".pwEscape($rt['postdate'],false).",lastposter =".pwEscape($rt['author'],false)."WHERE tid=".pwEscape($pass_info['tid']));
//				# memcache refresh
//				$threadList = L::loadClass("threadlist");
//				$threadList->updateThreadIdsByForumId($pass_info['fid'],$pass_info['tid']);
//
//				$db->update("UPDATE $pw_posts SET ifcheck='1' WHERE pid='{$pt['pid']}' AND fid='{$pass_info['fid']}'");
//				updateforum($pass_info['fid']);
//
//				$sql = "UPDATE $pw_posts SET ifcheck=1 WHERE pid =".pwEscape($pt['pid']);
//				$db->update($sql);
//			}
//			$filter_id = implode(',' , $objid);
//			if ($filter_id) {
//				//更改审核状态,更新审核人员信息
//				$sql = "UPDATE pw_filter SET state=1,assessor=". pwEscape($admin_name) .",updated_at=".pwEscape($timestamp) ." WHERE id IN (".$filter_id.")";
//				$db->update($sql);
//			}

			//重定向
			adminmsg('operate_success', "$basename" . "&action=check&job=post");
		}
	} elseif ($job == 'del') {
		InitGP(array('pid', 'tid', 'type'));
		if (!$tid && !$pid) adminmsg('operate_error');

		$delarticle = L::loadClass('DelArticle');

		if (!$type || $type == 'thread') {
			if (is_array($tid)) {
				$sTid = pwImplode($tid);
			} else {
				$sTid = (int) $tid;
			}
			$sql = "SELECT pf.id,pf.tid,pt.subject,pt.author FROM pw_filter AS pf "
				 . "LEFT JOIN pw_threads AS pt ON pf.tid=pt.tid WHERE pf.tid IN (".$sTid.") AND pf.pid=0";
			$query = $db->query($sql);
			$objid = array();
			$threadsid = array();
			while ($rt = $db->fetch_array($query)) {
				$objid[]  = $rt['id'];
				$objid[] = $rt['id'];
				$threadsid[] = $rt['tid'];

				//发消息通知
				$L = array(
					'subject' => $rt['subject'] ,
				);

				$title	 = getLangInfo('cpmsg','filtermsg_thread_del_title');
				$content = getLangInfo('cpmsg','filtermsg_thread_del_content', $L);

				$msg = array(
					'toUser'	=> $rt['author'],
					'toUid'		=> '',
					'fromUid'	=> '',
					'fromUser'	=> 'SYSTEM',
					'subject'	=> $title,
					'content'	=> $content,
					'other'		=> array()
				);
				pwSendMsg($msg);
			}

			$filter_id = implode(',' , $objid);

			$delarticle->delTopicByTids($threadsid, $db_recycle);

			//更改审核状态,更新审核人员信息
			$sql = "UPDATE pw_filter SET state=2,assessor=". pwEscape($admin_name) .",updated_at=".pwEscape($timestamp) ." WHERE id IN (".$filter_id.")";
			$db->update($sql);

			//重定向
			adminmsg('operate_success', "$basename" . "&action=check");
		} else {
			if (is_array($pid)) {
				$pid_count = count($pid);
				$tid_count = count($tid);

				//重定向
				if ($pid_count != $tid_count) adminmsg('operate_error');

				$objid = array();
				for ($i = 0; $i <$pid_count; $i++) {
					$sql = "SELECT pf.id,pt.subject,pt.ptable FROM pw_filter AS pf "
						 . "LEFT JOIN pw_threads AS pt ON pt.tid=pf.tid "
						 . "WHERE pf.pid = ".pwEscape($pid[$i])." AND pf.tid=".pwEscape($tid[$i]);
					$del_info = $db->get_one($sql);

					$pw_posts = $del_info['ptable'] ? 'pw_posts' . $del_info['ptable'] : 'pw_posts';
					$objid[]  = $del_info['id'];

					//获取作者信息
					$sql = "SELECT author FROM $pw_posts WHERE pid=" . pwEscape($pid[$i]);
					$author = $db->get_value($sql);

					//发消息通知
					$L = array(
						'subject' => $del_info['subject'] ,
					);

					$title	 = getLangInfo('cpmsg','filtermsg_post_del_title');
					$content = getLangInfo('cpmsg','filtermsg_post_del_content', $L);
					$msg = array(
						'toUser'	=> $author,
						'toUid'		=> '',
						'fromUid'	=> '',
						'fromUser'	=> 'SYSTEM',
						'subject'	=> $title,
						'content'	=> $content,
						'other'		=> array()
					);
					pwSendMsg($msg);

					$sql = "SELECT pid,fid,tid,aid,author,authorid,postdate,subject,content,anonymous,ifcheck FROM $pw_posts WHERE pid = ".pwEscape($pid[$i]);
					$replydb[] = $db->get_one($sql);
				}
				$filter_id = implode(',' , $objid);
			} else {
				$sql = "SELECT pf.id,pt.subject,pt.ptable FROM pw_filter AS pf "
					 . "LEFT JOIN pw_threads AS pt ON pt.tid=pf.tid "
					 . "WHERE pf.pid = ".pwEscape($pid)." AND pf.tid=".pwEscape($tid);
				$del_info = $db->get_one($sql);

				$pw_posts = GetPtable($del_info['ptable']);

				//获取作者信息
				$sql = "SELECT author FROM $pw_posts WHERE pid=" . pwEscape($pid);
				$author = $db->get_value($sql);

				//发消息通知
				$L = array(
					'subject' => $del_info['subject'] ,
				);

				$title	 = getLangInfo('cpmsg','filtermsg_post_del_title');
				$content = getLangInfo('cpmsg','filtermsg_post_del_content', $L);
				$msg = array(
					'toUser'	=> $author,
					'toUid'		=> '',
					'fromUid'	=> '',
					'fromUser'	=> 'SYSTEM',
					'subject'	=> $title,
					'content'	=> $content,
					'other'		=> array()
				);
				pwSendMsg($msg);

				$sql = "SELECT pid,fid,tid,aid,author,authorid,postdate,subject,content,anonymous,ifcheck FROM $pw_posts WHERE pid = ".pwEscape($pid);
				$replydb[] = $db->get_one($sql);

				$filter_id = $del_info['id'];
			}

			$delarticle->delReply($replydb, true, $db_recycle);

			//更改审核状态,更新审核人员信息
			$sql = "UPDATE pw_filter SET state=2,assessor=". pwEscape($admin_name) .",updated_at=".pwEscape($timestamp) ." WHERE id IN (".$filter_id.")";
			$db->update($sql);

			//重定向
			adminmsg('operate_success', "$basename" . "&action=check&job=post");
		}
	}
	include_once PrintEot('filter');exit;
} elseif ($action == 'record') {
	if ($admin_gid == 3){
		InitGP(array('sort'));

		if(!$sort) $sort = 'pf.updated_at';
		if (empty($job)) {
			$update_show = update();

			$count = $db->get_value("SELECT COUNT(*) FROM pw_filter WHERE state>0 AND tid>0 AND pid=0");
			$page<1 && $page = 1;
			$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
			$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=record&");

			$sql = "SELECT pf.*,pt.subject, pt.author FROM pw_filter AS pf LEFT JOIN pw_threads AS pt ON pf.tid = pt.tid WHERE pf.state>0 AND pf.tid>0 AND pf.pid=0 ORDER BY $sort DESC $limit";
			$query = $db->query($sql);
			while ($rt = $db->fetch_array($query)) {
				$rt['date']  = get_date($rt['updated_at'],'Y-m-d H:m:s');
				$rt['operate'] = $operate[$rt['state']];
				$record_list[] = $rt;
			}
			include_once PrintEot('filter');exit;
		} elseif ($job == 'post') {
			$update_show = update();

			$count = $db->get_value("SELECT COUNT(*) FROM pw_filter WHERE state>0 AND pid>0");
			$page<1 && $page = 1;
			$limit = pwLimit(($page-1)*$db_perpage,$db_perpage);
			$pages = numofpage($count,$page,ceil($count/$db_perpage), "$basename&action=record&job=post&");

			$sql = "SELECT pf.*,pt.subject FROM pw_filter AS pf LEFT JOIN pw_threads AS pt ON pf.tid = pt.tid WHERE pf.state>0 AND pf.pid>0 ORDER BY $sort DESC  $limit";
			$query = $db->query($sql);
			while ($rt = $db->fetch_array($query)) {
				$rt['subject'] = substrs($rt['subject'], 33);
				$rt['date']  = get_date($rt['updated_at'],'Y-m-d H:m:s');
				$rt['operate'] = $operate[$rt['state']];
				$record_list[] = $rt;
			}
			include_once PrintEot('filter');exit;
		}
	} else {
		adminmsg('illegal_request');
	}
} elseif ($action == 'show') {
	define('AJAX', 1);
	InitGP(array('pid', 'tid', 'type'));

	if ($pid > 0 && $tid > 0){
		$job = 'post';
		# 回复
		$sql = "SELECT ptable FROM pw_threads WHERE tid = ". pwEscape($tid);
		$ptable = $db->get_value($sql);
		$pw_posts = GetPtable($ptable);
		$objid = $db->get_value("SELECT id FROM pw_filter WHERE pid=" . pwEscape($pid). " AND tid=" . pwEscape($tid));
		//获取回复帖信息
		$sql = "SELECT pt.tid, pt.subject, pp.pid, pp.author, pp.postdate, pp.content FROM $pw_posts AS pp LEFT JOIN pw_threads AS pt ON pp.tid = pt.tid WHERE pp.pid=" . pwEscape($pid). " AND pt.tid=" . pwEscape($tid);
		$content = $db->get_one($sql);
		if(!$content && $objid) {
			$sql = "DELETE FROM pw_filter WHERE pid=" . pwEscape($pid). " AND tid=" . pwEscape($tid);
			$db->update($sql);

			ajaxmsg('filtermsg_post_already_delete');exit;
		}
		$content['subject'] = showHightLight($content['subject']);
		$content['content'] = showHightLight($content['content']);
		$content['date'] = get_date($content['postdate']);
	} else {
		$job = 'thread';
		$pw_tmsgs = GetTtable($tid);

		//获取主题帖信息
		$sql = "SELECT pt.tid, pt.author, pt.subject, pt.postdate, pc.content FROM pw_threads AS pt LEFT JOIN $pw_tmsgs AS pc ON pt.tid = pc.tid WHERE pt.tid=" . pwEscape($tid);
		$content = $db->get_one($sql);
		$content['subject'] = showHightLight($content['subject']);
		$content['content'] = showHightLight($content['content']);
		$content['date'] = get_date($content['postdate']);
	}
	include_once PrintEot('filterAjax');
	ajax_footer();
} elseif ($action == 'synchronous') {
	if ($admin_gid == 3){
		$appclient = L::loadClass('AppClient');
		$sitehash = $appclient->getApicode();

		if ($job == 'confirm') {
			define('AJAX', 1);

			require_once(D_P.'data/bbscache/ft_config.php');

			include_once PrintEot('filterAjax');
			ajax_footer();
		} else {
			define('AJAX', 1);

			require_once(R_P.'require/posthost.php');

			//获取中心词库词语数量
			$app_num = $db->get_value("SELECT COUNT(id) AS count FROM pw_wordfb WHERE custom = 0");

			if (empty($app_num)) {
				//重新同步中心词库
				$data = PostHost($updateHost, "m=wordsfb&a=restart&sitehash=$sitehash", "POST");
			} else {
				//同步中心词库
				$data = PostHost($updateHost, "m=wordsfb&a=update&sitehash=$sitehash", "POST");
			}

			$content = pwConvert(unserialize($data),$db_charset,'UTF8');

			$list = array();
			if(is_array($content)){
				$i = 0;
				foreach($content as $key => $value){
					if($value['word']){
						$id = $db->get_value("SELECT id FROM pw_wordfb WHERE word=".pwEscape($value['word']));

						if(empty($id)){
							$sql  ="INSERT INTO pw_wordfb (word,wordreplace,type,wordtime) VALUES (".pwEscape($value['word']) .", '*****', ".pwEscape($center_level[$value['level']]) .", ".pwEscape($timestamp) ." )";
							$db->update($sql);

							$list[] = array('word' => $value['word'], 'level' => $center_level[$value['level']]);

							$i++;
						}
					}
				}

				//更新ft_update_num;
				$sql = "REPLACE INTO pw_hack SET hk_name='ft_update_num',hk_value=0";
				$db->update($sql);
	
				//更新缓存
				updatecache_w();
				updatecache_ft();
	
				//设置字典文件
				setDictionary();
				
				if (!$i) {
					ajaxmsg('没有需要同步的敏感词(本地已有相同的敏感词)', "$basename&action=setting");
				}
	
				include_once PrintEot('filterAjax');
				ajax_footer();
			} else {
				if ($content) {
					//重定向
					ajaxmsg($content, "$basename&action=setting");
				} else {
					//重定向
					ajaxmsg('filtermsg_not_update', "$basename&action=setting");
				}
			}
		}
	} else {
		adminmsg('illegal_request');
	}
} elseif ($action == 'test') {
	//获取主题帖信息
	$sql = "SELECT pt.tid, pt.author, pt.subject, pt.postdate, pc.content FROM pw_threads AS pt LEFT JOIN pw_tmsgs AS pc ON pt.tid = pc.tid WHERE pt.tid=91";
	$content = $db->get_one($sql);
	$content['content'] = showHightLight($content['content']);
} else {
	//重定向
	adminmsg('operate_success');
}

/**
 * 清空无效记录
 *
 * @param unknown_type $type
 */
function Clear()
{
	global $db;
	$sql = "SELECT pf.id,pt.tid FROM pw_filter AS pf LEFT JOIN pw_threads AS pt ON pf.tid = pt.tid WHERE pf.state=0 AND pf.tid>0 AND pid=0 ORDER BY pf.id";
	$query = $db->query($sql);
	while ($rt = $db->fetch_array($query)) {
		if (!$rt['tid']) {
			$sql = "DELETE FROM pw_filter WHERE id=".pwEscape($rt['id']);
			$db->update($sql);
		}
	}
}

/**
 * @desc 更新缓存
 */
function updatecache_ft() {
	global $db;
	$query=$db->query("SELECT * FROM pw_hack WHERE hk_name LIKE 'ft_%'");
	$configdb="<?php\r\n";
	while (@extract($db->fetch_array($query))) {
		$hk_name = key_cv($hk_name);
		$configdb.="\$$hk_name=".pw_var_export($hk_value).";\r\n";
	}
	$configdb.="?>";
	writeover(D_P.'data/bbscache/ft_config.php',$configdb);
}

/**
* @desc 返回一个高亮的字符串
*/
function showHightLight($msg) {
	$wordsfb = L::loadClass('FilterUtil');
	$wordsfb->loadWords();
	
	if ($wordsfb->replace) {
		foreach ($wordsfb->replace as $key => $value) {
		    $keyword = $wordsfb->getTrueBanword($key);
		    $replace = '<span style="background:#0F3;font-weight:bold;display:inline;padding:1px">'.$keyword.'</span>';
		    $msg = preg_replace("/$key/i", $replace, $msg);
		}
	}

	if ($wordsfb->alarm) {
		foreach ($wordsfb->alarm as $key => $value) {
		    $keyword = $wordsfb->getTrueBanword($key);
		    $replace = '<span style="background:#FF0;font-weight:bold;display:inline;padding:1px">'.$keyword.'</span>';
		    $msg = preg_replace("/$key/i", $replace, $msg);
		}
	}

	if ($wordsfb->fbwords) {
		foreach ($wordsfb->fbwords as $key => $value) {
		    $keyword = $wordsfb->getTrueBanword($key);
		    $replace = '<span style="background:#F00;font-weight:bold;display:inline;padding:1px">'.$keyword.'</span>';
		    $msg = preg_replace("/$key/i", $replace, $msg);
		}
    }

	return $msg;
}

/**
* @desc 判断是否需要更新
*/
function update() {
	global $db, $updateHost;

	if (file_exists(D_P.'data/bbscache/ft_config.php')) {
		require_once(D_P.'data/bbscache/ft_config.php');

		if (empty($ft_update_num)) {
			//获取中心词库词语数量
			$app_num = $db->get_value("SELECT COUNT(id) AS count FROM pw_wordfb WHERE custom = 0");

			//获取上次更新时间
			$wordtime = $db->get_value("SELECT wordtime FROM pw_wordfb ORDER BY wordtime DESC LIMIT 1");
			$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

			if ($wordtime < $today || empty($app_num)) {
				require_once(R_P.'require/posthost.php');
				$appclient = L::loadClass('AppClient');

				$sitehash = $appclient->getApicode();

				//获取更新词数
				$data = PostHost($updateHost, "m=wordsfb&a=request&sitehash=$sitehash", "POST");

				$data = intval($data);

				if($data > 0){
					$sql = "REPLACE INTO pw_hack SET hk_name='ft_update_num',hk_value=".pwEscape($data);
					$db->update($sql);

					updatecache_ft();

					return '';
				} else {
					return 'display:none;';
				}
			} else {
				return 'display:none;';
			}
		} else {
			return '';
		}
	} else {
		writeover(D_P.'data/bbscache/ft_config.php');//写入文件

		return update();
	}
}

/**
 * @desc AJAX跳转
 *
 * @param String $msg -- 提示
 * @param String $jumpurl -- 跳转到URL
 */
function ajaxmsg($msg, $jumpurl='')
{
	define('AJAX', 1);

	$msg = getLangInfo('cpmsg',$msg);
	$show = <<<EOT
<div style="padding:1.5em 3em">
	$msg
</div>
<script language="JavaScript">
function skip(){
	location.href = "$jumpurl";
}
setTimeout("skip()",2000);
</script>
EOT;
	echo $show;
	ajax_footer();
}

/**
 * 设置字典文件
 *
 * @param unknown_type $type
 */
function setDictionary()
{
	//更新总字典文件
	setAllDictionary();
	
	//检测字典文件
	checkDictionary();
}

/**
 * @desc 生成总字典文件
 */
function setAllDictionary()
{
	global $db, $score;

	require_once(R_P."lib/filterutil.class.php");

	$bin_file = D_P.'data/bbscache/dict_all.php';
	$source_file = D_P.'data/bbscache/dict_all.txt';

	if(!file_exists($bin_file) && !file_exists($source_file)) {
		writeover($source_file, '');//文本形式字典
		writeover($bin_file,'');//二进制字典
	}

	$classid = getCloseClass();
	
	$querys = $db->query("SELECT word, type FROM pw_wordfb WHERE classid NOT IN ($classid)");
	$content = "";
	while ($value = $db->fetch_array($querys)) {
		$weighing = $score[$value['type']];
	  	$content.="".$value['word']."|".$weighing."\r\n";
	}

	writeover($source_file, $content);//文本形式字典
	writeover($bin_file,'');//二进制字典

	//更新二进制字典
	$trie = new Trie();
	$trie->build($source_file, $bin_file);
}

/**
 * @desc 检测字典文件
 */
function checkDictionary()
{
	global $db, $score;

	require_once(R_P."lib/filterutil.class.php");

	$classid = getCloseClass();
	
	$db_dictlist = array();
	$dict_folder = D_P.'data/bbscache/';
	
	//读取所有敏感词时间戳
	$sql = "SELECT wordtime FROM pw_wordfb WHERE classid NOT IN ($classid) GROUP BY wordtime";
	$query = $db->query($sql);
	while($rt = $db->fetch_array($query)){
		//字典名称
		$title = $rt['wordtime'];
		$db_dictlist[] = $title;
		$bin_path	= 'dict_' . $title . '.php';
		$source_path =  'dict_' . $title . '.txt';
		$bin_file	= $dict_folder . $bin_path;
		$source_file = $dict_folder . $source_path;
		
		//查询该时间戳是否有字典,如果没有就插入记录
		$sql = "SELECT id FROM pw_filter_dictionary WHERE title =" . $title;
		$dictionary = $db->get_value($sql);
		
		if(!$dictionary) {			
			//处理数据
			$value = array(
				'title'  => $title,
				'bin'	 => $bin_path,
				'source' => $source_path,
			);
			$value = pwSqlSingle($value);
	
			//插入记录
			$sql = "INSERT INTO pw_filter_dictionary SET  $value ";
			$db->update($sql);
		}
		
		//查找字典所包含的敏感词
		$querys = $db->query("SELECT word, type FROM pw_wordfb WHERE classid NOT IN ($classid) AND wordtime =" . pwEscape($title));
		$content = "";
		while ($value = $db->fetch_array($querys)) {
			$weighing = $score[$value['type']];
			$content.="".$value['word']."|".$weighing."\r\n";
		}

		//更新字典
		if ($content) {
			writeover($source_file, $content);//文本形式字典
			writeover($bin_file,'');//二进制字典

			//更新二进制字典
			$trie = new Trie();
			$trie->build($source_file, $bin_file);
		}
	}

	foreach ($db_dictlist AS $key => $value) {
		$dict_str .= $dict_str ? ", ".pwEscape($value) : pwEscape($value);
	}

	if (!$dict_str) $dict_str = -1;
	
	//删除多余字典记录
	$sql = "DELETE FROM pw_filter_dictionary WHERE title NOT IN ($dict_str)";
	$db->update($sql);
	
	$files = glob($dict_folder . 'dict_*.php');
	$db_dictlist[] = 'all';
	$db_dictlist = array_flip($db_dictlist);
	
	foreach ($files as $value){
		$title = substr($value, strpos($value,'_') +1);
		$title = substr($title, 0, strpos($title,'.'));
		
		if(!array_key_exists($title, $db_dictlist)){
			$bin_path	 = 'dict_' . $title . '.php';
			$source_path =  'dict_' . $title . '.txt';
			$bin_file	 = $dict_folder . $bin_path;
			$source_file = $dict_folder . $source_path;

			//删除多余文件
			deldir($bin_file);
			deldir($source_file);
		}
	}
}

function getCloseClass()
{
	global $db;

	$sql = "SELECT id FROM pw_filter_class WHERE state=0";
	$query = $db->query($sql);
	$id = '';
	while ($value = $db->fetch_array($query)) {
		$id .= $id ? ", ".$value['id'] : $value['id'];
	}

	if (!$id) $id=-1;
	
	return $id;
}

function newClass($title)
{
	global $db;
	
	//判断是否有重复分类
	$sql = " SELECT id FROM pw_filter_class WHERE title = " . pwEscape($title);
	$num = $db->get_one($sql);

	if ($num) {
		return 0;
	} else {
		//插入分类
		$sql = "INSERT pw_filter_class SET state=1,title = " . pwEscape($title);
		$db->update($sql);
		
		return $db->insert_id();
	}
}

function delClass($class)
{
	global $db;
	
	if ($class > 0) {
		//删除分类
		$sql = "DELETE FROM pw_filter_class WHERE id=".pwEscape($class);
		$query = $db->update($sql);
				
		//删除分类下的敏感词
		$sql = "DELETE FROM pw_wordfb WHERE classid=".pwEscape($class);
		$query = $db->update($sql);
	} elseif ($class == 0) {				
		//清空敏感词
		$sql = "DELETE FROM pw_wordfb";
		$query = $db->update($sql);
	}
}

function insertWord($word, $type, $class, $repword = '*****')
{
	global $db;
	$wordtime = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$insertValue = ','.pwEscape($type).','.pwEscape($class).','.pwEscape($repword).','.pwEscape($wordtime).', 1';
		
	if (is_array($word)) {
		$sql = "INSERT INTO pw_wordfb (word, type, classid, wordreplace, wordtime, custom) VALUES";
		
		$sqlStr = '';
		foreach ($word as $value) {
			$sqlStr .= $sqlStr ? ", (".pwEscape($value).$insertValue.")" : "(".pwEscape($value).$insertValue.")";
		}
		
		$sql = $sql.$sqlStr;
		$db->update($sql);
	} else {
		$value = array(
			'word'	 => $word,
			'wordreplace' => $repword,
			'type'	 => $type,
			'wordtime' => $wordtime,
			'custom'   => 1
		);

		$sql = "INSERT INTO pw_wordfb"
			 . " SET " . pwSqlSingle($value);
		$db->update($sql);
	}
}
?>