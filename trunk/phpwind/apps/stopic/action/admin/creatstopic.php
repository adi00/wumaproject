<?php
!defined('P_W') && exit('Forbidden');
define('AJAX',1);
InitGP(array('stopic_id','block_config','category_id','stopic_title'));

$stopic_id = (int) $stopic_id;
$category_id = (int) $category_id;
if (!$stopic_id || !$category_id) showmsg('undefined_error');
$stopic_data = $stopic_service->getSTopicInfoById($stopic_id);
if (empty($stopic_data)) showmsg('undefined_error');

$new_config = array();
if (isset($block_config['container'])) {
	foreach ($block_config['container'] as $layout_id) {
		foreach ($block_config as $block_container => $blocks) {
			if (false !== strpos($block_container, $layout_id)) {
				$new_config[$layout_id][substr($block_container, strlen($layout_id)+1)] = $block_config[$block_container];
				unset($block_config[$block_container]);
			}
		}
	}
}

$update_fields = array('category_id'=>$category_id, 'title'=>$stopic_title, 'block_config'=>$new_config);
if ('' == $stopic_data['layout_config']) $update_fields['layout_config'] = $stopic_service->getLayoutDefaultSet();
$stopic_service->updateSTopicById($stopic_id, $update_fields);

$stopic_service->creatStopicHtml($stopic_id);

$stopicUrl	= $stopic_service->getStopicUrl($stopic_id);

include stopic_use_layout ('ajax');
?>