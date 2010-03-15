<?php
define('SCR','index');
define('TW_POSITION','task');
require_once('global.php');
@include_once(D_P.'data/bbscache/teamwork_config.php');
/**
* 用户组权限判断
*/
$_G['allowmember']==0 && Showmsg('member_right');
require_once(R_P.'require/header.php');

InitGP(array('action'));
switch ($action) {
case 'detail':
	InitGP(array('tid'));
	if(!is_numeric($tid)){
		 Showmsg('data_error');
	}
	$sql		= "SELECT tt.*,tp.projectname FROM pw_teamtasks AS tt LEFT JOIN pw_teamprojects as tp on tt.pid = tp.pid WHERE tid={$tid}";
	$query 		= $db->query($sql);
	$teamtask 	= $db->fetch_array($query);
	if(empty($teamtask)){
		Showmsg('data_error');
	}
	$teamtask['priority'] = $teamwork_level[$teamtask['priority']];
	$teamtask['statusname'] = $teamwork_status[$teamtask['status']];
	require_once PrintEot('tw_task_detail');
	break;
case 'srst':
	InitGP(array('tid'));
	if(is_numeric($tid)){
		$sql		= "UPDATE pw_teamtasks SET real_start_time=".time()." WHERE tid={$tid} AND owner_id={$winduid}";
		$query 		= $db->query($sql);
	}
	break;
case 'sret':
	InitGP(array('tid'));
	if(is_numeric($tid)){
		$sql		= "UPDATE pw_teamtasks SET real_end_time=".time()." WHERE tid={$tid} AND owner_id={$winduid}";
		$query 		= $db->query($sql);
	}
	break;
case 'ss':
	InitGP(array('tid','status'));
	if(is_numeric($tid) && is_numeric($status) && array_key_exists($status, $teamwork_status)){
		$sql		= "UPDATE pw_teamtasks SET status=".$status." WHERE tid={$tid} AND owner_id={$winduid}";
		$query 		= $db->query($sql);
	}
	break;
case 'add':
case 'update':
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
	$query = $db->query('SELECT * FROM pw_members WHERE groupid = 3 AND username != "admin"');
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
	
	break;
default:
	//任务列表
	InitGP(array('page','own','status','project','plan_start_time','real_start_time','plan_end_time','real_end_time','orderby'));
	
	if ($winddb['t_num']) {
		$db_perpage = $winddb['t_num'];
	}else{
		$db_perpage = 20;
	}
	
	$con		= array();
	if($own==1){
		$con[]	= "tt.owner_id = {$winduid}";
	}
	if($status!='' && is_numeric($status) && $status>=0){
		$con[]	= "tt.status = {$status}";
	}else{
		//$con[]	= "tt.status in(0,1,2)";
	}
	if($plan_start_time!=''){
		$con[]	= "tt.plan_start_time>=".strtotime($plan_start_time." 00:00:00");
	}
	if($real_start_time!=''){
		$con[]	= "tt.real_start_time>=".strtotime($real_start_time." 00:00:00");
	}
	if($real_end_time!=''){
		$con[]	= "tt.real_end_time>0 AND tt.real_end_time<=".strtotime($real_end_time." 00:00:00");
	}
	if($plan_end_time!=''){
		$con[]	= "tt.plan_end_time>0 AND tt.plan_end_time<=".strtotime($plan_end_time." 00:00:00");
	}
	if($project!=''){
		$con[]		= 'tp.projectname like "%'.$project.'%"';
	}
	$conSql		= "";
	if(count($con)>0){
		$conSql	= " WHERE ".implode(' AND ', $con);
	}
	
	$orderSql	= "";
	if($orderby=='create'){
		$orderSql	= "tt.create_time DESC";
	}else if($orderby=='start'){
		$orderSql	= "tt.plan_start_time DESC";
	}else{
		$orderSql	= "tt.priority ASC";
	}
	$orderSql		= " ORDER BY ".$orderSql;
	
	
	$sql		= "SELECT COUNT(*) AS cnt FROM pw_teamtasks AS tt";
	if($project!=''){
		$sql	.= " LEFT JOIN pw_teamprojects as tp on tt.pid = tp.pid";
	}
	$sql		.= $conSql;
	
	$rs 		= $db->get_one($sql);
	$count 		= $rs['cnt'];
	
	(int)$page<1 && $page = 1;
	$numofpage = ceil($count/$db_perpage);
	$numofpage < 1 && $numofpage = 1;
	if ($page > $numofpage) {
		$page  = $numofpage;
	}
	$start_limit = intval(($page-1) * $db_perpage);
	$totalpage	= min($numofpage,$db_maxpage);
	
	$limitSql	= " LIMIT {$start_limit},{$db_perpage}";
	
	$pages		= numofpage($count,$page,$numofpage,"twtask.php?{$urladd}&",$db_maxpage);
	
	$sql		= "SELECT tt.tid, tt.priority, tt.taskname, tt.plan_start_time, tt.real_start_time, tt.status, tt.create_time FROM pw_teamtasks AS tt";
	if($project!=''){
		$sql	.= " LEFT JOIN pw_teamprojects as tp on tt.pid = tp.pid";
	}
	$sql		.= $conSql.$orderSql.$limitSql;
	$query 		= $db->query($sql);
	$teamtaskdb = array();
	while($teamtasks = $db->fetch_array($query)){
		$teamtasks['priority'] = $teamwork_level[$teamtasks['priority']];
		$teamtasks['statusname'] = $teamwork_status[$teamtasks['status']];
		$teamtaskdb[] = $teamtasks;	
	}
	require_once PrintEot('tw_task_list');
	break;
}

footer();

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
?>
