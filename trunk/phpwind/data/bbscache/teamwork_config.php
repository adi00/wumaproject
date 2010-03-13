<?php

// set error_reporting level
//error_reporting('ALL');

// 统一的权限判断
$_G['allowmember']==0 && Showmsg('teamwork_denied');

// set time zone
date_default_timezone_set('Asia/Shanghai');

// 项目任务优先级
$teamwork_level=array(
	'1' => 'P0',
	'2' => 'P1',
	'3' => 'P2',
	'4' => 'P3',
	'5' => 'P4',
	'6' => 'P5',
);

// 项目任务的状态
$teamwork_status=array(
	'0' => '未开始',
	'1' => '正常执行中',
	'2' => '延期',
	'3' => '正常结束',
	'4' => '延期结束',
	'5' => '意外中止',
);
