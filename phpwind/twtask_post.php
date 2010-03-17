<?php

/**
 * Teamwork任务模块
 * 新增及修改等等操作
 *
 * @author yumin
 * @package teamwork
 * @subpackage twtask_post
 */

define('SCR','twtask_post');
require_once('global.php');
include_once(D_P.'data/bbscache/teamwork_config.php');
require_once(R_P.'require/header.php');

// 基础数据
$action = GetGP('action'); // add,update
$actionTitle = ('add' == $action ? '添加新任务' : ('update' == $action ? '修改任务中' : '未知'));
$data = $_POST;
$flag = (isset($data['flag']) ? $data['flag'] : '_nothing');

// 显示逻辑
if ('submit' != $flag) {

	if ('add' == $action) {

		/* 新增显示 */

	} elseif ('update' == $action) {

		/* 修改显示 */

		$tid = GetGP('tid'); // task id
		if (0 < $tid) {

			$twtask = $db->get_one('SELECT * FROM pw_teamtasks WHERE tid = '.pwEscape($tid)." AND (publisher_id = {$winduid} OR owner_id = {$winduid})");

			// 数据为空
			if (empty($twtask)) {

				header('Location: twindex.php');
				exit('redirecting...');
			}

			// 字段处理
			$twtask['plan_start_time'] = (0 < $twtask['plan_start_time'] ? date('Y-m-d', $twtask['plan_start_time']) : '');
			$twtask['plan_end_time'] = (0 < $twtask['plan_end_time'] ? date('Y-m-d', $twtask['plan_end_time']) : '');
			$twtask['real_start_time'] = (0 < $twtask['real_start_time'] ? date('Y-m-d', $twtask['real_start_time']) : '');
			$twtask['real_end_time'] = (0 < $twtask['real_end_time'] ? date('Y-m-d', $twtask['real_end_time']) : '');
			// 页面输出
			$data = $twtask;

		} else {

			// 非法操作
			Showmsg('undefined_action');
		}

	} else {

		// 非法操作
		Showmsg('undefined_action');
	}
}

// 提交逻辑
if ('submit' == $flag) {

	if ('add' == $action) {

		/* 新增提交 */

		$task = validator_submit_task($data);
		if (!isset($task['error']) && isset($task['ret'])) {

			/* 验证成功 */

			$ret = $task['ret'];
			if (is_array($ret['owners'])) {

				for ($i=0; $i<sizeof($ret['owners']); $i++) {

					// 默认字段
					$ret['publisher_id'] = $winduid;
					$ret['create_time'] = time();
					$ret['modify_time'] = time();
					$ret['owner_id'] = $ret['owners'][$i];
					$ret_temp = $ret;
					array_shift($ret_temp);
					$temp[] = pwSqlMulti(array($ret_temp));
				}

				$temp = implode(',', $temp);
				// 组装SQL
				$sql = 'INSERT INTO pw_teamtasks (pid,taskname,owner,priority,plan_start_time,plan_end_time,real_start_time,real_end_time,remark,content,status,publisher_id,create_time,modify_time,owner_id) VALUES'.$temp;

				// 写入数据
				$db->update($sql);
				$tid = $db->insert_id();

				// 写入结果
				if (!empty($tid) && is_numeric($tid) && 0 < $tid) {

					// 是否关联修改项目的实际开始时间
					if (0 < $ret['pid'] && 0 < $ret['real_start_time']) {

						$sql = 'SELECT COUNT(*) AS cnt FROM pw_teamtasks WHERE pid = '.$ret['pid'];
						$rs = $db->get_one($sql);
						if (1 == $rs['cnt']) {

							$sql = 'UPDATE pw_teamprojects SET real_start_time = '.$ret['real_start_time'].' WHERE pid = '.$ret['pid'];
							$db->update($sql);
						}
					}

					// 是否关联修改项目的实际结束时间
					if (0 < $ret['pid'] && 0 < $ret['real_end_time']) {

						$sql = 'UPDATE pw_teamprojects SET real_end_time = '.$ret['real_end_time'].' WHERE pid = '.$ret['pid'];
						$db->update($sql);
					}

					header('Location: twindex.php');
					exit('redirecting...');

				} else {

					$task['error']['all'] = '哎呀,数据写入失败了!请备份内容,然后重新再试.';
					$error = $task['error'];
				}

			} else {

				$task['error']['all'] = '哎呀,负责人字段格式出错了!';
				$error = $task['error'];
			}

		} else {

			/* 验证失败 */

			$error = $task['error'];
		}

	} elseif ('update' == $action) {

		/* 修改提交 */
		$tid = GetGP('tid'); // task id
		if (0 < $tid) {

			$task = validator_submit_task($data);
			if (!isset($task['error']) && isset($task['ret'])) {

				/* 验证成功 */

				// 默认字段
				$ret = $task['ret'];
				$ret['modify_time'] = time();
				// 组装SQL
				$sql = 'UPDATE pw_teamtasks SET '.pwSqlSingle($ret).' WHERE tid = '.pwEscape($tid)." AND (publisher_id = {$winduid} OR owner_id = {$winduid})";
				//echo $sql;exit;
				// 修改数据
				$db->update($sql);
				$rows = $db->affected_rows();
				// 修改结果
				if (is_numeric($rows) && 0 < $rows) {

					// 是否关联修改项目的实际开始时间
					if (0 < $ret['pid'] && 0 < $ret['real_start_time']) {

						$sql = 'SELECT COUNT(*) AS cnt FROM pw_teamtasks WHERE pid = '.$ret['pid'];
						$rs = $db->get_one($sql);
						if (1 == $rs['cnt']) {

							$sql = 'UPDATE pw_teamprojects SET real_start_time = '.$ret['real_start_time'].' WHERE pid = '.$ret['pid'];
							$db->update($sql);
						}
					}

					// 是否关联修改项目的实际结束时间
					if (0 < $ret['pid'] && 0 < $ret['real_end_time']) {

						$sql = 'UPDATE pw_teamprojects SET real_end_time = '.$ret['real_end_time'].' WHERE pid = '.$ret['pid'];
						$db->update($sql);
					}

					header('Location: twindex.php');
					exit('redirecting...');

				} else {

					$task['error']['all'] = '哎呀,数据修改失败了!请备份内容,然后重新再试.';
					$error = $task['error'];
				}

			} else {

				/* 验证失败 */

				$error = $task['error'];
			}

		} else {

			// 非法操作
			Showmsg('undefined_action');
		}

	} else {

		// 非法操作
		Showmsg('undefined_action');
	}
}

// 载入用户
$query = $db->query('SELECT * FROM pw_members ');
$twusers = array();
while($twuser = $db->fetch_array($query)){
	$twusers[] = $twuser;
}

// 载入项目(进行中)
$query = $db->query('SELECT * FROM pw_teamprojects WHERE status IN (0,1,2)');
$twprojects = array();
while($twproject = $db->fetch_array($query)){
	$twprojects[] = $twproject;
}

// 载入页面
require_once(PrintEot('twtask_post'));

// 公用页尾
footer();



// ==============================
// 函数区 Function Area
// ==============================



/**
 * 任务表单字段的验证
 *
 * @author yumin
 * @param $data 表单提交数组
 * @return $task 结果以及错误
 */
function validator_submit_task($data){

	global $action;

	// 综合验证
	if (empty($data)) {
		$task['error']['all'] = '服务器只是个传说,啥都没填写的话,就别提交了嘛!';
		return $task;
	}

	// 负责人
	$key = 'owner_id';
	if (isset($data[$key]) && 0 < $data[$key]) {
		if ('add' == $action) {
			$task['ret']['owners'] = (!is_array($data[$key]) ? array($data[$key]) : $data[$key]);
		} elseif ('update' == $action) {
			$task['ret']['owner_id'] = $data[$key];
		}
	} else {
		$task['error'][$key] = '负责人是必选项';
	}
	//print_r($task['ret']['owner_id']);exit;

	// 所属项目
	$key = 'pid';
	if (isset($data[$key]) && 0 < $data[$key]) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['ret'][$key] = 0;
	}

	// 任务名称
	$key = 'taskname';
	if (isset($data[$key]) && !empty($data[$key])) {
		$task['ret'][$key] = get_safe_string($data[$key]);
	} else {
		$task['error'][$key] = '任务名称是必填项';
	}

	// owner
	$key = 'owner';
	if (isset($data[$key]) && !empty($data[$key])) {
		$task['ret'][$key] = get_safe_string($data[$key]);
	} else {
		$task['ret'][$key] = '';
	}

	// 优先级
	$key = 'priority';
	if (isset($data[$key]) && 0 <= $data[$key]) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['error'][$key] = '优先级是必选项';
	}

	// 计划起始时间
	$key = 'plan_start_time';
	if (isset($data[$key]) && !empty($data[$key])) {

		$start = strtotime($data[$key]);
		if (0 < $start) {
			$task['ret'][$key] = $start;
		} else {
			$task['ret'][$key] = 0;
		}

	} else {
		$task['ret'][$key] = 0;
	}

	// 计划截止时间
	$key = 'plan_end_time';
	if (isset($data[$key]) && !empty($data[$key])) {

		$end = strtotime($data[$key]);
		if (0 < $end) {
			$task['ret'][$key] = $end;
		} else {
			$task['ret'][$key] = 0;
		}

	} else {
		$task['ret'][$key] = 0;
	}

	// 实际开始时间
	$key = 'real_start_time';
	if (isset($data[$key]) && !empty($data[$key])) {

		$end = strtotime($data[$key]);
		if (0 < $end) {
			$task['ret'][$key] = $end;
		} else {
			$task['ret'][$key] = 0;
		}

	} else {
		$task['ret'][$key] = 0;
	}

	// 实际截止时间
	$key = 'real_end_time';
	if (isset($data[$key]) && !empty($data[$key])) {

		$end = strtotime($data[$key]);
		if (0 < $end) {
			$task['ret'][$key] = $end;
		} else {
			$task['ret'][$key] = 0;
		}

	} else {
		$task['ret'][$key] = 0;
	}

	// 任务备注
	$key = 'remark';
	if (isset($data[$key]) && !empty($data[$key])) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['ret'][$key] = '';
	}

	// 任务内容
	$key = 'content';
	if (isset($data[$key]) && !empty($data[$key])) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['ret'][$key] = '';
	}

	// 任务当前状态
	$key = 'status';
	if (isset($data[$key]) && 0 <= $data[$key]) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['error'][$key] = '任务当前状态是必选项';
	}

	return $task;
}

/**
 * 对字串进行安全过滤
 *
 * @author yumin
 * @param $input 任意的字符串
 * @return $task 结果以及错误
 */
function get_safe_string($input) {

	return trim(strip_tags($input));
}
