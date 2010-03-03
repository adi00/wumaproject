<?php
!function_exists('adminmsg') && exit('Forbidden');

$optimize_conf=array(
	'size'=>array(
		'1'=>array(
			'db_size'=>'1',
			'db_opensch'=>'0		',
			'db_lp'=>'0',
			'db_obstart_tmp'=>'1',
			'db_ifonlinetime'=>'1',
			'db_refreshtime'=>'3',
			'db_onlinetime'=>'60',
			'db_maxresult'=>'500',
			'db_cvtimes'=>'30',
			'db_attachnum'=>'5',
			'db_indexshowbirth'=>'0',
			'db_indexonline'=>'1',
			'db_showguest'=>'0',
			'db_today'=>'0',
			'db_maxpage'=>'1000',
			'db_maxmember'=>'1000',
			'db_hithour'=>'0',
			'db_threadonline'=>'1',
			'db_showcolony'=>'0',
			'db_showcustom'=>'',
			'db_watermark'=>'0',
			'db_iffthumb'=>'0',
			'db_wapifopen'=>'1',
			'db_jsifopen'=>'0',
			'db_ifsafecv'=>'1',
			'db_safegroup'=>'',
			'db_htmifopen'=>'0',
			'db_ipstates'=>'0',
			'db_ads'=>'1',
		),
		'2'=>array(
			'db_size'=>'2',
			'db_opensch'=>'1	24	18',
			'db_lp'=>'1',
			'db_obstart_tmp'=>'1',
			'db_ifonlinetime'=>'0',
			'db_refreshtime'=>'5',
			'db_onlinetime'=>'45',
			'db_maxresult'=>'500',
			'db_cvtimes'=>'20',
			'db_attachnum'=>'5',
			'db_indexshowbirth'=>'0',
			'db_indexonline'=>'1',
			'db_showguest'=>'0',
			'db_today'=>'0',
			'db_maxpage'=>'800',
			'db_maxmember'=>'800',
			'db_hithour'=>'1',
			'db_threadonline'=>'1',
			'db_showcolony'=>'0',
			'db_showcustom'=>'',
			'db_watermark'=>'0',
			'db_iffthumb'=>'0',
			'db_wapifopen'=>'1',
			'db_jsifopen'=>'0',
			'db_ifsafecv'=>'1',
			'db_safegroup'=>',3,4,5,',
			'db_htmifopen'=>'0',
			'db_ipstates'=>'0',
			'db_ads'=>'1',
		),
		'3'=>array(
			'db_size'=>'3',
			'db_opensch'=>'1	8	14',
			'db_lp'=>'1',
			'db_obstart_tmp'=>'0',
			'db_ifonlinetime'=>'0',
			'db_refreshtime'=>'10',
			'db_onlinetime'=>'30',
			'db_maxresult'=>'300',
			'db_cvtimes'=>'10',
			'db_attachnum'=>'3',
			'db_indexshowbirth'=>'0',
			'db_indexonline'=>'0',
			'db_showguest'=>'0',
			'db_today'=>'0',
			'db_maxpage'=>'600',
			'db_maxmember'=>'600',
			'db_hithour'=>'4',
			'db_threadonline'=>'0',
			'db_showcolony'=>'0',
			'db_showcustom'=>'',
			'db_watermark'=>'0',
			'db_iffthumb'=>'0',
			'db_wapifopen'=>'0',
			'db_jsifopen'=>'0',
			'db_ifsafecv'=>'1',
			'db_safegroup'=>',3,4,5,',
			'db_htmifopen'=>'0',
			'db_ipstates'=>'0',
			'db_ads'=>'1',
		)
	),
	'func'=>array(
		'1'=>array(
			'db_func'=>'1',
			'db_upload'=>'0',
			'db_columns'=>'0',
			'db_msgsound'=>'0',
			'db_shield'=>'1',
			'db_tcheck'=>'1',
			'db_adminset'=>'0',
			'db_allowupload'=>'0',
			'db_replysendmail'=>'0',
			'db_replysitemail'=>'0',
			'db_pwcode'=>'0',
			'db_setform'=>'0',
			'db_autoimg'=>'0',
			'db_menu'=>'0',
			'db_topped'=>'0',
			'db_watermark'=>'0',
			'db_iffthumb'=>'0',
			'db_ifathumb'=>'0',
			'db_ifselfshare'=>'0',
		),
		'2'=>array(
			'db_func'=>'2',
			'db_upload'=>'1	200	180	20480',
			'db_columns'=>'0',
			'db_msgsound'=>'0',
			'db_shield'=>'1',
			'db_tcheck'=>'1',
			'db_adminset'=>'0',
			'db_allowupload'=>'1',
			'db_replysendmail'=>'0',
			'db_replysitemail'=>'1',
			'db_pwcode'=>'0',
			'db_setform'=>'0',
			'db_autoimg'=>'1',
			'db_menu'=>'7',
			'db_topped'=>'1',
			'db_watermark'=>'1',
			'db_iffthumb'=>'1',
			'db_ifathumb'=>'0',
			'db_ifselfshare'=>'0',
		),
		'3'=>array(
			'db_func'=>'3',
			'db_upload'=>'1	200	180	20480',
			'db_columns'=>'1',
			'db_msgsound'=>'1',
			'db_shield'=>'1',
			'db_tcheck'=>'1',
			'db_adminset'=>'1',
			'db_allowupload'=>'1',
			'db_replysendmail'=>'1',
			'db_replysitemail'=>'1',
			'db_pwcode'=>'1',
			'db_setform'=>'1',
			'db_autoimg'=>'1',
			'db_menu'=>'7',
			'db_topped'=>'1',
			'db_watermark'=>'1',
			'db_iffthumb'=>'1',
			'db_ifathumb'=>'1',
			'db_ifselfshare'=>'1',
		)
	)
);
?>