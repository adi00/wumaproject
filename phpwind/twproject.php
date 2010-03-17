<?php

/**
 * teamwork项目detail页
 * 
 * @author qining (xutuo@taobao.com)
 * @category teamwork
 * @package teamwork
 * @copyright Copyright (c) KOUBEI (koubei.com)
 * @version $Id$
 */

define('SCR','twproject');
require_once('global.php');
include_once(D_P.'data/bbscache/teamwork_config.php');
require_once(R_P.'require/header.php');

InitGP(array('action'));

// 载入用户
$query = $db->query('SELECT * FROM pw_members ');
$twusers = array();
while($twuser = $db->fetch_array($query)){
	$twusers[$twuser['uid']] = $twuser['username'];
}

switch ($action) {

    /** for detail page show */
    case 'detail':
        
        /** what the fuck function */
        InitGP(array('pid'));
        $pid = intval($pid);
        
        /** mysql is just so stupid, we must add "LIMIT 1" to stop searching */
        $project = $db->get_one("SELECT * FROM pw_teamprojects WHERE pid = {$pid} LIMIT 1");
        
        /** throw an error */
        if (empty($project)) {
            ShowMsg('No Project Found');
        }
        
        $project['priority'] = $teamwork_level[$project['priority']];
		$project['statusname'] = $teamwork_status[$project['status']];
		$project['owner'] = $twusers[$project['owner_id']];
        
        /** render template */
        require_once PrintEot('tw_project_detail');
        break;
    
    /** for project list */
    default:
        
        /** get some parameters from request */
        InitGP(array('page', 'own', 'status', 'priority', 'start', 'end', 'orderby', 'project'));
        
        /** just make a bobo sql */
        $where = 'WHERE 1=1';
        
        /** search by name */
        if (!empty($project)) {
            $where .= " AND projectname LIKE '%" 
                . str_replace(array('"', "'", '%', '?', '*'), '', $project) . "%'";
        }
        
        /** fetch by owner */
        if (!empty($own)) {
            $own = intval($own);
            $where .= " AND owner_id = {$own}";
        }
        
        /** fetch by status */
        if (!empty($status)) {
            $status = intval($status);
            if ($status >= 0 && $status <= 5) {
                $where .= " AND status = {$status}";
            }
        }
        
        /** fetch by status */
        if (!empty($priority)) {
            $priority = intval($priority);
            if ($priority >= 1 && $priority <= 6) {
                $where .= " AND status = {$priority}";
            }
        }
        
        /** fetch by time */
        /** start time */
        if (!empty($start)) {
            $start_stamp = strtotime($start . ' 00:00:00');
            if ($start_stamp < time() && $start_stamp > 0) {
                $where = " AND (plan_start_time >= {$start_stamp} OR real_start_time >= {$start_stamp})";
            }
        }
        
        /** end time */
        if (!empty($end)) {
            $end_stamp = strtotime($end . ' 00:00:00');
            
            // end timestamp must be bigger than start timestamp
            if ($end_stamp > 0 && (!isset($start_stamp) || $end_stamp > $start_stamp)) {
                $where = " AND (plan_end_time <= {$end_stamp} OR real_end_time <= {$end_stamp})";
            }
        }
        
        /** project order */
        $order = ' ORDER BY priority ASC';
        if (!empty($orderby)) {
            switch (!$orderby) {

                case 'create':
                    $order = ' ORDER BY create_time DESC';
                    break;
                case 'start':
                    $order = ' ORDER BY plan_start_time DESC';
                    break;
                case 'priority':
                default:
                    $order = ' ORDER BY priority ASC';
                    break;

            }
        }
        
        /** project per size */
        $db_perpage = 20;
        if ($winddb['t_num']) {
			$db_perpage = $winddb['t_num'];
		}
        
        /** page to make */
        $page = 1;
        if (!empty($page)) {
            $page = max(intval($page), 1);
        }
        
        /** make page nav */
        $count_sql = 'SELECT COUNT(pid) AS num FROM pw_teamprojects ' . $where;
        $count_result = $db->get_one($count_sql);
        $count = $count_result['num'];
        $total_page = ceil($count / $page_size);
        
        $pages		= numofpage($count, $page, $total_page, "twproject.php?{$urladd}&", $db_maxpage);
        
        /** make query sql */
        $sql = 'SELECT * FROM pw_teamprojects ' . $where . $order . ' LIMIT ' 
            . $db_perpage . ' OFFSET ' . ($page - 1) * $db_perpage;
            
        /** get data */
        $query = $db->query($sql);
		$projects = array();
        
		while ($project = $db->fetch_array($query)) {
			$project['priority'] = $teamwork_level[$project['priority']];
			$project['statusname'] = $teamwork_status[$project['status']];
			$projects[] = $project;
		}
        
        /** render template */
        require_once PrintEot('tw_project_list');
        break;
}

footer();
