<?php
!defined('P_W') && exit('Forbidden');

InitGP(array("stopic_id"), null, 2);

$tpl_content = '';
if ($stopic_id) {
	$stopic_data = $stopic_service->getSTopicInfoById($stopic_id);
	if (null == $stopic_data) Showmsg('找不到专题数据，请您重试', $basename."&job=stman");
	$tpl_content =  $stopic_service->getStopicContent($stopic_id,1);
} else {
	$stopic_data = $stopic_service->getEmptySTopic();
	if ($stopic_data) $stopic_id = $stopic_data['stopic_id'];
}

$layout_list = $stopic_service->getLayoutList();
$category_list = $stopic_service->getCategorys();
$styles	= $stopic_service->getStyles();
$blocks	= $stopic_service->getBlocks();

$ajaxurl = EncodeUrl($stopic_admin_url);

include stopic_use_layout('admin');
?>