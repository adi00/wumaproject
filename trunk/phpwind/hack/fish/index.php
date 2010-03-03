<?php
!function_exists('readover') && exit('Forbidden');
@include_once(D_P."data/bbscache/fish/config.php");
include_once(R_P.'require/credit.php');
$basename="hack.php?H_name=fish";

if(!file_exists(D_P.'data/bbscache/fish/config.php')){

Showmsg("未设置 config.php 文件,请至后台插件中心设置");

}

!$cheatopen && Showmsg($cheatopenmsg);

if(!$guestview){ //如果禁止游客访问
	$groupid == 'guest' && showmsg('not_login');
}

InitGP(array('action'));

if (!$action){
	require(H_P."require/default.php");
}elseif(in_array($action,array('click','job'))){
	require(H_P."require/$action.php");
}else{
	Showmsg('undefined_action');
}

?>