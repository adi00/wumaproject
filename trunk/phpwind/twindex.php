<?php
define('SCR','twindex');
require_once('global.php');
include_once(D_P.'data/bbscache/teamwork_config.php');
require_once(R_P.'require/header.php');

//teamwork_projects
$query = $db->query("SELECT tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id = {$winduid} order by tp.priority asc limit 10");
$projectdb = array();
while($projects = $db->fetch_array($query)){
	$projects['priority'] = $teamwork_level[$projects['priority']];
	$projects['statusname'] = $teamwork_status[$projects['status']];
	$projectdb[] = $projects;	
}

//teamwork_tasks
$query = $db->query("SELECT tt.priority, tt.taskname, tt.plan_start_time, tt.real_start_time, tt.status, tt.create_time FROM pw_teamtasks AS tt WHERE owner_id = {$winduid} and tt.status in(0,1,2) order by tt.priority asc limit 10");
$teamtaskdb = array();
while($teamtasks = $db->fetch_array($query)){
	$teamtasks['priority'] = $teamwork_level[$teamtasks['priority']];
	$teamtasks['statusname'] = $teamwork_status[$teamtasks['status']];
	$teamtaskdb[] = $teamtasks;	
}

require_once(PrintEot('twindex'));footer();
?>
