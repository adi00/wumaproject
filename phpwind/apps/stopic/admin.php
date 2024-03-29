<?php
!function_exists('readover') && exit('Forbidden');

$admin_jobs = array(
	'make',
	'advance',
	'initstopic',
	'editblock',

	'default',
	'draw',
	'generate',
	'cgman',
	'bgman',
	'stman',
	"ajax",
	'editcontent',
	'gettidcontent',
	'editstopic',
	'creatstopic',
	'preview',
	'changestyle',
	'createcategory',
);
$job = in_array($job, $admin_jobs) ? $job : 'stman';

$stopic_admin_url = $basename;

$stopic_service	= L::loadClass('stopicservice','stopic');
include A_P."/action/admin/$job.php";


function stopic_use_layout($layout) {
	return A_P."/template/layout/$layout.php";
}
function stopic_load_view($action, $module='admin') {
	return A_P."/template/$module/$action.htm";
}



?>