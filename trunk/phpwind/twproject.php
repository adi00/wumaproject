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
        
        /** render template */
        require_once PrintEot('tw_project_detail');
        break;
    
    /** for project list */
    default:
        
        /** get some parameters from request */
        InitGP(array('page', 'own', 'status', 'priority', 'start', 'end', 'orderby'));
        
        /** just make a bobo sql */
        $where = 'WHERE 1=1';
        
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
        $page_size = 20;
        if ($winddb['t_num']) {
			$page_size = $winddb['t_num'];
		}
        
        /** page to make */
        $page = 1;
        if (!empty($page)) {
            $page = max(intval($page), 1);
        }
        
        /** make query sql */
        $sql = 'SELECT * FROM pw_teamprojects ' . $where . $order . ' LIMIT ' 
            . $page_size . ' OFFSET ' . ($page - 1) * $page_size;
            
        /** get data */
        $projects = $db->fetch($sql, array());
        
        /** render template */
        require_once PrintEot('tw_project_list');
        break;
}

footer();
