<?php
!function_exists('readover') && exit('Forbidden');
/*
 * 修改头像
 */
class JOB_DoSendGift{
	
	var $step = 1;
	
	/*
	 * 任务链接
	 */
	function getUrl($job){
		return "";
	}
	
	function finish($job,$jober,$factor){
		return 2;
	}
	
}