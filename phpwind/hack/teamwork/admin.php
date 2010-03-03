<?php
!function_exists('adminmsg') && exit('Forbidden');

if( empty( $job ) ){
	$basename = "$admin_file?adminjob=hack&hackset=teamwork";
	$cachefile = D_P.'data/bbscache/teamwork_config.php';
	$teamwork_level = array(1=>'p1',2=>'p2',3=>'p3',4=>'p4',5=>'p5');
	$teamwork_status = array( 0=>'未开始',1=>'正常执行中', 2=>'延期', 3=>'正常结束', 4=>'延期结束', 5=>'意外中止' );
	$teamwork = "<?php\r\n";
	$teamwork .= "\$teamwork_level=".pw_var_export($teamwork_level).";\r\n";
	$teamwork .= "\$teamwork_status=".pw_var_export($teamwork_status).";\r\n";
	$teamwork .= "?>";
	writeover($cachefile, $teamwork);
	echo "安装成功";
}

?>
