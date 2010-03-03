<?php !defined('P_W') && exit('Forbidden');
/**
 * @desc 敏感词扫描类
 *
 */
class PW_WordScan {
	var $db;
	
	var $filter;
	
	var $pagesize = 100;
	
	function PW_WordScan() {
		global $db;
		$this->db = $db;
		$this->filter = L::loadClass('FilterUtil');
	}
	
	/**
	 * @desc 扫描并返回结果
	 *
	 * @param string $type -- 扫描类型: thread=>主题帖; post=>回复帖
	 * @param int $pagesize -- 单次扫描数据
	 * @param int $skip -- 跳词数
	 * @param int $convert -- 是否开启简繁转换: 0=>否; 1=>是
	 * @return array -- 扫描进度:返回当前扫描数和包含敏感词帖子数
	 */
	function scan($type = 'thread', $pagesize = 100, $skip = 0, $convert = false) {
		global $db_ptable;
		
		if ($type == 'thread') {
			$result = $this->scanThread($pagesize, $skip, $convert);
		} else {
			if (empty($db_ptable)) {
				$result = $this->scanPost($pagesize, $skip, $convert);
			} else {
				$result = $this->scanPosts($skip, $convert);
			}
		}
		
		return $result;
	}
	
	/**
	 * @desc 将扫描出的敏感词数组过滤成字符串
	 *
	 * @param unknown_type $strArray
	 * @return unknown
	 */
	function getWordString($strArray) {
		$array = array();
		
		foreach ($strArray as $value) {
			$array[] = $value[0];
		}
	
		$array = array_unique($array);
	
		$string='';
		foreach($array as $key=>$val) {
			if ($val) {
				$string .= $string ? ','.$val : $val;
			}
		}
		
		return $string;
	}
	
	/**
	 * @desc 扫描主题贴
	 *
	 * @param int $pagesize --  每次扫描记录条数
	 * @param int $skip --  跳词数
	 * @param int $convert -- 是否开启简繁转换
	 * @return array 扫描进度:返回当前扫描数和包含敏感词帖子数
	 */
	function scanThread($pagesize = 100, $skip = 0, $convert = false) {
		$result_count = 0;//扫描结果数

		//获取字典文件
		$sql = "SELECT id, tid, bin, source FROM pw_filter_dictionary ORDER BY id DESC;";
		$file_query = $this->db->query($sql);
		while ($file = $this->db->fetch_array($file_query)) {
			$scan_count   = 0;//扫描数
			$insertString = '';
			
			$file['dir'] = D_P.'data/bbscache/';
			$file['bin'] = D_P.'data/bbscache/' . $file['bin'];
			$file['source'] = D_P.'data/bbscache/' . $file['source'];

			$this->filter->setFiles($file);
	
			$start = intval($file['tid']);//起始位置

			//获取帖子列表,取出帖子的主题,内容和id,判断是否重复记录
			$sql = " SELECT t.tid, t.subject, c.content, t.ifcheck, t.postdate, t.author, f.id, f.state "
				 . " FROM pw_threads AS t LEFT JOIN pw_tmsgs AS c ON t.tid = c.tid LEFT JOIN pw_filter AS f ON t.tid = f.tid"
				 . " WHERE t.tid>{$start} ORDER BY t.tid ASC LIMIT " . $pagesize;
			$query = $this->db->query($sql);
			while ($rt = $this->db->fetch_array($query)) {
				$content = $rt['subject'] . $rt['content'];
				
				$result = $this->filter->paraseContent($content, $skip, $convert);

				//处理扫描结果
				if (is_array($result)) {	
					$filterStr = $this->getWordString($result[1]);	
					$score     = round($result[0], 2);
	
					if ($score >= 0.8) {
						if ($rt['ifcheck']) {
							//待审核
							$sql = "UPDATE pw_threads SET ifcheck=0 WHERE tid = " .pwEscape($rt['tid']);
							$this->db->update($sql);
						}
					}
	
					if (!$rt['id']) {
						//如果不重复,扫描到的结果+1
						$result_count++;
	
						$compart = $insertString ? ',' : '';
						//处理数据
						$insertString .= $compart . "( " . pwEscape($rt['tid']) . ", " . pwEscape($filterStr) . ", "
							. pwEscape($rt['postdate']) . ")";
					} else {
						if ($rt['state']) {
							//如果是已经审核通过的再次被扫到,扫描到的结果+1
							$result_count++;
	
							//处理数据
							$value = array(
								'state'  => 0,
								'filter' => $filterStr,
								'created_at' => $rt['postdate'],
							);
							$value = pwSqlSingle($value);
	
							//更新记录
							$sql = "UPDATE pw_filter SET {$value} WHERE pid=0 AND tid = " . pwEscape($rt['tid']);
							$this->db->update($sql);
						}
					}
				}
				$scan_count++;
				$last_id = $rt['tid'];
			}

			//插入记录
			if ($insertString) {
				$insertSql =  "INSERT INTO pw_filter (tid, filter, created_at) VALUES " . $insertString;
				$this->db->update($insertSql);
			}
	
			if ($last_id) {
				//更新扫描进度
				$sql = "UPDATE pw_filter_dictionary SET tid=".pwEscape($last_id)." WHERE id = ".pwEscape($file['id']);
				$this->db->update($sql);
			}
		}

		return array('scan_count' => $scan_count, 'result_count' => $result_count);
	}
	
	/**
	 * @desc 扫描回复贴
	 *
	 * @param int $pagesize --  每次扫描记录条数
	 * @param int $skip --  跳词数
	 * @param int $convert -- 是否开启简繁转换
	 * @return array 扫描进度:返回当前扫描数和包含敏感词帖子数
	 */
	function scanPost($pagesize = 100, $skip = 0, $convert = false) {
		$result_count = 0;//扫描结果数
	
		//获取字典文件
		$sql = "SELECT id, pid, bin, source FROM pw_filter_dictionary ORDER BY id DESC;";
		$file_query = $this->db->query($sql);
	
		while ($file = $this->db->fetch_array($file_query)) {
			$scan_count   = 0;//扫描数
	
			$file['dir'] = D_P.'data/bbscache/';
			$file['bin'] = D_P.'data/bbscache/' . $file['bin'];
			$file['source'] = D_P.'data/bbscache/' . $file['source'];
	
			$this->filter->setFiles($file);
	
			$start = intval($file['pid']);
			
			$insertString = '';
	
			//获取帖子信息,判断是否重复记录
			$sql = "SELECT p.pid,p.content,t.tid,t.subject,p.author,p.postdate,p.ifcheck , f.id, f.state FROM pw_posts AS p "
				 . "LEFT JOIN pw_threads AS t ON p.tid=t.tid LEFT JOIN pw_filter AS f ON p.pid = f.pid WHERE p.tid > 0 AND p.pid>".pwEscape($start)
				 . " ORDER BY p.pid ASC LIMIT ".$pagesize;
			$query = $this->db->query($sql);
			while ($rt = $this->db->fetch_array($query)) {
				$result = $this->filter->paraseContent($rt['content'], $skip, $convert);
	
				//处理扫描结果
				if(is_array($result)) {	
					$filterStr = $this->getWordString($result[1]);	
					$score     = round($result[0], 2);
	
					if ($score >= 0.8) {
						if ($rt['ifcheck']) {
							//待审核
							$sql = "UPDATE pw_posts SET ifcheck=0 WHERE pid = " .pwEscape($rt['pid']);
							$this->db->update($sql);
						}
					}
	
					if (!$rt['id']) {
						//如果不是重复记录,扫描到的结果+1
						$result_count++;
	
						$compart = $insertString ? ',' : '';
						//处理数据
						$insertString .= $compart . "( " . pwEscape($rt['tid']) . ", " . pwEscape($rt['pid']) . ", " . pwEscape($filterStr)
						  . ", " . pwEscape($rt['postdate']) . ")";
					} else {
						if ($rt['state']) {
							//如果是已经审核通过的再次被扫到,扫描到的结果+1
							$result_count++;
	
							//处理数据
							$value = array(
								'state'  => 0,
								'filter' => $filterStr,
								'created_at' => $rt['postdate'],
							);
							$value = pwSqlSingle($value);
	
							//更新记录
							$sql = "UPDATE pw_filter SET {$value} WHERE tid=".pwEscape($rt['tid'])." AND pid=" . pwEscape($rt['pid']);;
							$this->db->update($sql);
						}
					}
				}
				$scan_count++;
				$last_id = $rt['pid'];
			}
	
			//插入记录
			if ($insertString) {
				$insertSql =  "INSERT INTO pw_filter (tid, pid, filter, created_at) VALUES " . $insertString;
				$this->db->update($insertSql);
			}
	
			//更新扫描进度
			if ($last_id) {
				$sql = "UPDATE pw_filter_dictionary SET pid=".pwEscape($last_id)." WHERE id = ".pwEscape($file['id']);
				$this->db->update($sql);
			}
		}
	
		return array('scan_count' => $scan_count, 'result_count' => $result_count);
	}
	
	/**
	 * @desc 扫描分表后的回复贴
	 *
	 * @param int $skip --  跳词数
	 * @param int $convert -- 是否开启简繁转换
	 * @return array 扫描进度:返回当前扫描数和包含敏感词帖子数
	 */
	function scanPosts($skip = 0, $convert = 0)
	{
		$result_count = 0;//扫描结果数
	
		//获取字典文件
		$sql = "SELECT id, pid, bin, source FROM pw_filter_dictionary ORDER BY id DESC;";
		$file_query = $this->db->query($sql);
		while ($file = $this->db->fetch_array($file_query)) {
			$scan_count   = 0;//扫描数
			$insertString = '';
			$start = intval($file['pid']);//开始ID
	
			$file['dir'] = D_P.'data/bbscache/';
			$file['bin'] = D_P.'data/bbscache/' . $file['bin'];
			$file['source'] = D_P.'data/bbscache/' . $file['source'];

			$this->filter->setFiles($file);
	
			//获取回复主题信息
			$sql = " SELECT tid, subject, ptable FROM pw_threads WHERE tid>{$start} ORDER BY tid ASC LIMIT 1";
			$thread = $this->db->get_one($sql);
			$pw_posts = $thread['ptable'] ? 'pw_posts' . $thread['ptable'] : 'pw_posts';
	
			//获取回复信息
			$sql = "SELECT pid,subject,content,author,postdate,ifcheck FROM $pw_posts "
				 . " WHERE tid = ".pwEscape($thread['tid']). " ORDER BY pid ASC ";
			$query = $this->db->query($sql);
			while ($rt = $this->db->fetch_array($query)) {
				$content = $rt['subject'] . $rt['content'];
				$result = $this->filter->paraseContent($content, $skip, $convert);
	
				//处理扫描结果
				if(is_array($result)) {
					//判断是否重复记录
					$sql = "SELECT id, state FROM pw_filter WHERE tid = "
						 . pwEscape($thread['tid'])." AND pid = " . pwEscape($rt['pid']);
					$record = $this->db->get_one($sql);
	
					$filterStr = $this->getWordString($result[1]);	
					$score     = round($result[0], 2);
	
					if($score >= 0.8){
						if($rt['ifcheck']){
							//待审核
							$sql = "UPDATE pw_posts SET ifcheck=0 WHERE pid = " .pwEscape($rt['pid']);
							$this->db->update($sql);
						}
					}
	
					if (!$record) {
						//如果不是重复记录,扫描到的结果+1
						$result_count++;
	
						$compart = $insertString ? ',' : '';
						//处理数据
						$insertString .= $compart . "( " . pwEscape($thread['tid']) . ", " . pwEscape($rt['pid']) . ", " . pwEscape($filterStr)
						  . ", " . pwEscape($rt['postdate']) . ")";
					} else {
						if ($record['state']) {
							//如果是已经审核通过的再次被扫到,扫描到的结果+1
							$result_count++;
	
							//处理数据
							$value = array(
								'state'  => 0,
								'filter' => $filterStr,
								'created_at' => $rt['postdate'],
							);
							$value = pwSqlSingle($value);
	
							//更新记录
							$sql = "UPDATE pw_filter SET {$value} WHERE tid=".pwEscape($rt['tid'])." AND pid=" . pwEscape($rt['pid']);
							$this->db->update($sql);
						}
					}
				}
				$scan_count++;
				$last_id = $rt['tid'];
			}
			//插入记录
			if ($insertString) {
				$insertSql =  "INSERT INTO pw_filter (tid, pid, filter, created_at) VALUES " . $insertString;
				$this->db->update($insertSql);
			}
	
			if(empty($last_id)) $last_id = $thread['tid'];
	
			//更新扫描进度
			if ($last_id) {
				$sql = "UPDATE pw_filter_dictionary SET pid=".pwEscape($last_id)." WHERE id = ".pwEscape($file['id']);
				$this->db->update($sql);
			}
		}
		return array('scan_count' => $scan_count, 'result_count' => $result_count);
	}
	
	/**
	 * @desc 获取剩余时间
	 *
	 * @param int $date
	 * @return string
	 */
	function getRemainingTime($date) {
		$days = (int) ($date/60/60/24);
		$hours = (int) ($date/60/60 - $days*24);
		$minutes = (int) ($date/60 - $days*24*60 - $hours*60);
		$seconds = (int) ($date - $days*24*60*60 - $hours*60*60 - $minutes*60);
		$result = $days . "天" . $hours . "小时" . $minutes . "分" . $seconds . "秒";
		return $result;
	}
}