<?php

/**
 * Teamwork项目模块
 * 新增及修改等等操作
 *
 * @author yumin
 * @package teamwork
 * @subpackage twproject_post
 */

define('SCR','twproject_post');
require_once('global.php');
include_once(D_P.'data/bbscache/teamwork_config.php');
require_once(R_P.'require/header.php');

// 基础数据
$action = GetGP('action'); // add,update
$actionTitle = ('add' == $action ? '发布新项目' : ('update' == $action ? '修改项目中' : '未知'));
$data = $_POST;
$flag = (isset($data['flag']) ? $data['flag'] : '_nothing');

// 显示逻辑
if ('submit' != $flag) {

	if ('add' == $action) {

		/* 新增显示 */

	} elseif ('update' == $action) {

		/* 修改显示 */

		$pid = GetGP('pid'); // project id
		if (0 < $pid) {

			$twproject = $db->get_one("SELECT * FROM pw_teamprojects WHERE pid = {$pid} AND (publisher_id = {$winduid} OR owner_id = {$winduid})");

			// 数据为空
			if (empty($twproject)) {

				header('Location: twindex.php');
				exit('redirecting...');
			}

			// 字段处理
			$twproject['plan_start_time'] = (0 < $twtask['plan_start_time'] ? date('Y-m-d', $twtask['plan_start_time']) : '');
			$twproject['plan_end_time'] = (0 < $twtask['plan_end_time'] ? date('Y-m-d', $twtask['plan_end_time']) : '');
			// 页面输出
			$data = $twproject;

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

		$project = validator_submit_project($data);
		if (!isset($project['error']) && isset($project['ret'])) {

			/* 验证成功 */

			// 默认字段
			$ret = $project['ret'];
			$ret['real_start_time'] = 0;
			$ret['real_end_time'] = 0;
			$ret['publisher_id'] = $winduid;
			$ret['create_time'] = time();
			$ret['modify_time'] = time();
			// 组装SQL
			$sql = 'INSERT INTO pw_teamprojects (projectname,owner,owner_id,priority,plan_start_time,'.
			       'plan_end_time,wiki,svn,remark,status,real_start_time,real_end_time,'.
			       'publisher_id,create_time,modify_time) VALUES'. pwSqlMulti(array($ret));
			//echo $sql;exit;
			// 写入数据
			$query = $db->update($sql);
			$pid = $db->insert_id();
			// 写入结果
			if (is_numeric($pid) && 0 < $pid) {

				header('Location: twindex.php');
				exit('redirecting...');

			} else {

				$project['error']['all'] = '哎呀,数据写入失败了!请备份内容,然后重新再试.';
				$error = $project['error'];
			}

		} else {

			/* 验证失败 */

			$error = $project['error'];
		}

	} elseif ('update' == $action) {

		/* 修改提交 */
		$pid = GetGP('pid'); // project id
		if (0 < $pid) {

			$project = validator_submit_project($data);
			if (!isset($project['error']) && isset($project['ret'])) {

				/* 验证成功 */

				// 默认字段
				$ret = $project['ret'];
				$ret['modify_time'] = time();
				// 组装SQL
				$sql = 'UPDATE pw_teamprojects SET '.pwSqlSingle($ret).' WHERE pid ='.pwEscape($pid)." AND (publisher_id = {$winduid} OR owner_id = {$winduid})";
				//echo $sql;exit;
				// 修改数据
				$query = $db->update($sql);
				$rows = $db->affected_rows();
				// 修改结果
				if (is_numeric($rows) && 0 < $rows) {

					header('Location: twindex.php');
					exit('redirecting...');

				} else {

					$project['error']['all'] = '哎呀,数据修改失败了!请备份内容,然后重新再试.';
					$error = $project['error'];
				}

			} else {

				/* 验证失败 */

				$error = $project['error'];
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
//$query = $db->query('SELECT * FROM pw_members WHERE groupid = 3 AND username != "admin"');
$query = $db->query('SELECT * FROM pw_members ');
$twusers = array();
while($twuser = $db->fetch_array($query)){
	$twusers[] = $twuser;
}

// 载入页面
require_once(PrintEot('twproject_post'));

// 公用页尾
footer();



// ==============================
// 函数区 Function Area
// ==============================



/**
 * 项目表单字段的验证
 *
 * @author yumin
 * @param $data 表单提交数组
 * @return $project 结果以及错误
 */
function validator_submit_project($data){

	// 综合验证
	if (empty($data)) {
		$project['error']['all'] = '服务器只是个传说,啥都没填写的话,就别提交了嘛!';
		return $project;
	}

	// 项目名称
	$key = 'projectname';
	if (isset($data[$key]) && !empty($data[$key])) {
		$project['ret'][$key] = get_safe_string($data[$key]);
	} else {
		$project['error'][$key] = '项目名称是必填项';
	}

	// owner
	$key = 'owner';
	if (isset($data[$key]) && !empty($data[$key])) {
		$project['ret'][$key] = get_safe_string($data[$key]);
	} else {
		$project['ret'][$key] = '';
	}

	// 负责人
	$key = 'owner_id';
	if (isset($data[$key]) && 0 < $data[$key]) {
		$project['ret'][$key] = $data[$key];
	} else {
		$project['error'][$key] = '负责人是必选项';
	}

	// 优先级
	$key = 'priority';
	if (isset($data[$key]) && 0 <= $data[$key]) {
		$project['ret'][$key] = $data[$key];
	} else {
		$project['error'][$key] = '优先级是必选项';
	}

	// 计划起始时间
	$key = 'plan_start_time';
	if (isset($data[$key]) && !empty($data[$key])) {

		$start = strtotime($data[$key]);
		if (0 < $start) {
			$project['ret'][$key] = $start;
		} else {
			$project['ret'][$key] = 0;
		}

	} else {
		$project['error'][$key] = '计划起始时间是必选项';
	}

	// 计划截止时间
	$key = 'plan_end_time';
	if (isset($data[$key]) && !empty($data[$key])) {

		$end = strtotime($data[$key]);
		if (0 < $end) {
			$project['ret'][$key] = $end;
		} else {
			$project['ret'][$key] = 0;
		}

	} else {
		$project['ret'][$key] = 0;
	}

	// Wiki地址
	$key = 'wiki';
	if (isset($data[$key]) && !empty($data[$key])) {
		$project['ret'][$key] = get_safe_string($data[$key]);
	} else {
		$project['ret'][$key] = '';
	}

	// SVN地址
	$key = 'svn';
	if (isset($data[$key]) && !empty($data[$key])) {
		$project['ret'][$key] = get_safe_string($data[$key]);
	} else {
		$project['ret'][$key] = '';
	}

	// 项目备注
	$key = 'remark';
	if (isset($data[$key]) && !empty($data[$key])) {
		$project['ret'][$key] = $data[$key];
	} else {
		$project['ret'][$key] = '';
	}

	// 项目当前状态
	$key = 'status';
	if (isset($data[$key]) && 0 <= $data[$key]) {
		$project['ret'][$key] = $data[$key];
	} else {
		$project['error'][$key] = '项目当前状态是必选项';
	}

	return $project;
}

/**
 * 对字串进行安全过滤
 *
 * @author yumin
 * @param $input 任意的字符串
 * @return $project 结果以及错误
 */
function get_safe_string($input) {

	return trim(strip_tags($input));
}
