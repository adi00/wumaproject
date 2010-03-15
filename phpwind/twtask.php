<?php
define('SCR','index');
define('TW_POSITION','task');
require_once('global.php');
@include_once(D_P.'data/bbscache/teamwork_config.php');;
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
			$sql		= "UPDATE pw_teamtasks SET real_start_time=".time().",modify_time=".time()." WHERE tid={$tid} AND owner_id={$winduid}";
			$query 		= $db->query($sql);
		}
		break;
	case 'sret':
		InitGP(array('tid'));
		if(is_numeric($tid)){
			$sql		= "UPDATE pw_teamtasks SET real_end_time=".time().",modify_time=".time()." WHERE tid={$tid} AND owner_id={$winduid}";
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

