<?php
!function_exists('adminmsg') && exit('Forbidden');
$basename="$admin_file?adminjob=hack&hackset=fish";
@include_once(D_P."data/bbscache/level.php");


$wnfpp=D_P.'data/bbscache/fish/';
if(!is_dir("$wnfpp")) {
    @mkdir("$wnfpp");
    @chmod("$wnfpp", 0777);
    @fclose(@fopen("$wnfpp".'/index.html', 'w'));
    @chmod("$wnfpp".'/index.html', 0777);
    config();        

}


InitGP(array('action'));

if(empty($action)){
	@include_once(D_P."data/bbscache/fish/config.php");

	$guestview==1 ? $ifguest1="checked" : $ifguest0="checked";
	$cheatopen==1 ? $ifcheat1="checked" : $ifcheat0="checked";
	$cview==1 ? $ifc1="checked" : $ifc0="checked";

}elseif ($action=="save"){

        InitGP(array('cview','cutime','groups','guestview','cheatopen','cheatopenmsg','vtime','fish1money','fish2money','fish3money','fish4money','fish5money','fish6money','fish7money','fish8money','fish9money','fish10money','fish11money','fish12money','fish13money','fish14money'));

        if($vtime < 1){
           $vtime=1;
        }

        $fish_groups = ','.implode(',',$groups).',';

	$content.="\$cheatopen=\"$cheatopen\";\r\n";
        $content.="\$cheatopenmsg=\"$cheatopenmsg\";\r\n";
        $content.="\$fish_groups=\"$fish_groups\";\r\n";
        $content.="\$guestview=\"$guestview\";\r\n";
        $content.="\$fish1money=\"$fish1money\";\r\n";
        $content.="\$fish2money=\"$fish2money\";\r\n";
        $content.="\$fish3money=\"$fish3money\";\r\n";
        $content.="\$fish4money=\"$fish4money\";\r\n";
        $content.="\$fish5money=\"$fish5money\";\r\n";
        $content.="\$fish6money=\"$fish6money\";\r\n";
        $content.="\$fish7money=\"$fish7money\";\r\n";
		$content.="\$fish8money=\"$fish8money\";\r\n";
		$content.="\$fish9money=\"$fish9money\";\r\n";
		$content.="\$fish10money=\"$fish10money\";\r\n";
		$content.="\$fish11money=\"$fish11money\";\r\n";
		$content.="\$fish12money=\"$fish12money\";\r\n";
		$content.="\$fish13money=\"$fish13money\";\r\n";
		$content.="\$fish14money=\"$fish14money\";\r\n";
        $content.="\$vtime=\"$vtime\";\r\n";
        $content.="\$cview=\"$cview\";\r\n";
        $content.="\$cutime=\"$cutime\";";
	writeover(D_P."data/bbscache/fish/config.php","<?php\r\n".$content."\r\n?>");

	adminmsg("设置保存完成",$basename);
}

require_once(PrintHack('admin'));


function config(){
        global $db,$imgpath,$db_bbsurl;

	$content.="\$cheatopen=\"0\";\r\n";
	$content.="\$cheatopenmsg=\"管理员把去探宝的地图弄丢啦，快去找他要\";\r\n";
	$content.="\$fish_groups=\",3,4,5,8,16,9,10,11,12,13,14,15,\";\r\n";
	$content.="\$guestview=\"0\";\r\n";
	$content.="\$fish1money=\"10\";\r\n";
	$content.="\$fish2money=\"50\";\r\n";
	$content.="\$fish3money=\"50\";\r\n";
	$content.="\$fish4money=\"30\";\r\n";
	$content.="\$fish5money=\"30\";\r\n";
	$content.="\$fish6money=\"20\";\r\n";
	$content.="\$fish7money=\"20\";\r\n";
	$content.="\$fish8money=\"20\";\r\n";
	$content.="\$fish9money=\"20\";\r\n";
	$content.="\$fish10money=\"20\";\r\n";
	$content.="\$fish11money=\"20\";\r\n";
	$content.="\$fish12money=\"20\";\r\n";
	$content.="\$fish13money=\"20\";\r\n";
	$content.="\$fish14money=\"20\";\r\n";
	$content.="\$vtime=\"1\";\r\n";
	$content.="\$cview=\"0\";\r\n";
	$content.="\$cutime=\"20\";";


	writeover(D_P."data/bbscache/fish/config.php","<?php\r\n".$content."\r\n?>");
}

?>
