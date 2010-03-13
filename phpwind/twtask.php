<?php

/**
 * Teamwork任务模块
 * 新增及修改等等操作
 *
 * @author yumin
 * @package teamwork
 * @subpackage twtask
 */

define('SCR','twtask');
require_once('global.php');
include_once(D_P.'data/bbscache/teamwork_config.php');
require_once(R_P.'require/header.php');

// 基础数据
$action = GetGP('action'); // add,update
$actionTitle = ('add' == $action ? '发布新任务' : ('update' == $action ? '修改任务中' : '未知'));
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

			$twtask = $db->get_one('SELECT * FROM pw_teamtasks WHERE tid = '.pwEscape($tid)." AND publisher_id = {$winduid}");

			// 数据为空
			if (empty($twtask)) {

				header('Location: /twindex.php');
				exit('redirecting...');
			}

			// 字段处理
			$twtask['plan_start_time'] = date('Y-m-d', $twtask['plan_start_time']);
			$twtask['plan_end_time'] = date('Y-m-d', $twtask['plan_end_time']);
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

			// 默认字段
			$ret = $task['ret'];
			$ret['real_start_time'] = 0;
			$ret['real_end_time'] = 0;
			$ret['publisher_id'] = $winduid;
			$ret['create_time'] = time();
			$ret['modify_time'] = time();
			// 组装SQL
			$sql = 'INSERT INTO pw_teamtasks (pid,taskname,owner,owner_id,priority,plan_start_time,'.
			       'plan_end_time,remark,content,status,real_start_time,real_end_time,'.
			       'publisher_id,create_time,modify_time) VALUES'. pwSqlMulti(array($ret));
			//echo $sql;exit;
			// 写入数据
			$query = $db->update($sql);
			$tid = $db->insert_id();
			// 写入结果
			if (is_numeric($tid) && 0 < $tid) {

				header('Location: /twindex.php');
				exit('redirecting...');

			} else {

				$task['error']['all'] = '哎呀,数据写入失败了!请备份内容,然后重新再试.';
				$error = $task['error'];
			}

		} else {

			/* 验证失败 */

			$error = $task['error'];
		}

	} elseif ('update' == $action) {

		/* 修改提交 */
		$pid = GetGP('pid'); // project id
		$tid = GetGP('tid'); // task id
		if (0 < $pid && 0 < $tid) {

			$task = validator_submit_task($data);
			if (!isset($task['error']) && isset($task['ret'])) {

				/* 验证成功 */

				// 默认字段
				$ret = $task['ret'];
				$ret['modify_time'] = time();
				// 组装SQL
				$sql = 'UPDATE pw_teamtasks SET '.pwSqlSingle($ret).
					   ' WHERE tid = '.pwEscape($tid).' AND pid = '.pwEscape($pid)." AND publisher_id = {$winduid}";
				//echo $sql;exit;
				// 修改数据
				$query = $db->update($sql);
				$rows = $db->affected_rows();
				// 修改结果
				if (is_numeric($rows) && 0 < $rows) {

					header('Location: /twindex.php');
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
$query = $db->query('SELECT * FROM pw_members WHERE groupid = 3');
$twusers = array();
while($twuser = $db->fetch_array($query)){
	$twusers[] = $twuser;
}

// 载入项目(未结束)
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

	// 综合验证
	if (empty($data)) {
		$task['error']['all'] = '服务器只是个传说,啥都没填写的话,就别提交了嘛!';
		return $task;
	}

	// 所属项目
	$key = 'pid';
	if (isset($data[$key]) && 0 < $data[$key]) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['error'][$key] = '所属项目是必选项';
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

	// 负责人
	$key = 'owner_id';
	if (isset($data[$key]) && 0 < $data[$key]) {
		$task['ret'][$key] = $data[$key];
	} else {
		$task['error'][$key] = '负责人是必选项';
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
			$task['error'][$key] = '计划起始时间转换失败';		
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
			$task['error'][$key] = '计划截止时间转换失败';		
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