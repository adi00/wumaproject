<?php
!defined('PW_UPLOAD') && exit('Forbidden');
$invokepieces=array(
	'0' => array(
		'id' => '1',
		'invokename' => '首页焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '1',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '56',
		),
		'cachetime' => '1800',
	),
	'1' => array(
		'id' => '2',
		'invokename' => '首页焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '15',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '1800',
	),
	'2' => array(
		'id' => '33',
		'invokename' => '首页社区热门',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '3',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '20',
		),
		'cachetime' => '1700',
	),
	'3' => array(
		'id' => '12',
		'invokename' => '首页循环版块',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '12',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '45',
		),
		'cachetime' => '1800',
	),
	'4' => array(
		'id' => '11',
		'invokename' => '首页循环版块',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '4',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '8',
		),
		'cachetime' => '1700',
	),
	'5' => array(
		'id' => '6',
		'invokename' => '首页最新图片',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '15',
		),
		'cachetime' => '1700',
	),
	'6' => array(
		'id' => '7',
		'invokename' => '首页某版块调用1',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '1800',
	),
	'7' => array(
		'id' => '8',
		'invokename' => '首页某版块调用2',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '1800',
	),
	'8' => array(
		'id' => '9',
		'invokename' => '版块排行',
		'title' => '版块模块',
		'action' => 'forum',
		'func' => 'forumsort',
		'num' => '12',
		'rang' => 'topic',
		'param' => array(
			'value' => 'default',
			'url' => 'default',
			'title' => 'default',
		),
		'cachetime' => '86400',
	),
	'9' => array(
		'id' => '13',
		'invokename' => '首页播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '288,200',
			'title' => '36',
		),
		'cachetime' => '1700',
	),
	'10' => array(
		'id' => '14',
		'invokename' => '首页播放器下方',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
		),
		'cachetime' => '1700',
	),
	'11' => array(
		'id' => '15',
		'invokename' => '首页播放器下方',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '30',
		),
		'cachetime' => '1800',
	),
	'12' => array(
		'id' => '18',
		'invokename' => '用户排行',
		'title' => '用户模块',
		'action' => 'user',
		'func' => 'usersort',
		'num' => '12',
		'rang' => 'money',
		'param' => array(
			'value' => 'default',
			'url' => 'default',
			'title' => 'default',
		),
		'cachetime' => '3000',
	),
	'13' => array(
		'id' => '94',
		'invokename' => '频道页中部焦点摘要图片',
		'title' => '图文模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '70',
		),
		'cachetime' => '9800',
	),
	'14' => array(
		'id' => '93',
		'invokename' => '频道页页面中部焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9800',
	),
	'15' => array(
		'id' => '92',
		'invokename' => '频道页本版热门',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9800',
	),
	'16' => array(
		'id' => '90',
		'invokename' => '频道页焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '70',
		),
		'cachetime' => '1800',
	),
	'17' => array(
		'id' => '91',
		'invokename' => '频道页焦点列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9600',
	),
	'18' => array(
		'id' => '88',
		'invokename' => '频道页播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '288,320',
			'title' => '36',
		),
		'cachetime' => '9800',
	),
	'19' => array(
		'id' => '34',
		'invokename' => 'fun_焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '100',
		),
		'cachetime' => '9600',
	),
	'20' => array(
		'id' => '35',
		'invokename' => 'fun_焦点列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'replysort',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '50',
		),
		'cachetime' => '9600',
	),
	'21' => array(
		'id' => '36',
		'invokename' => 'fun_本版热门',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'hitsort',
		'num' => '8',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '30',
		),
		'cachetime' => '9600',
	),
	'22' => array(
		'id' => '37',
		'invokename' => 'fun_播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '288,320',
			'title' => '36',
		),
		'cachetime' => '1700',
	),
	'23' => array(
		'id' => '39',
		'invokename' => '频道左侧站点推荐',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '5',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '20',
		),
		'cachetime' => '1700',
	),
	'24' => array(
		'id' => '40',
		'invokename' => 'fun_页面中部焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'25' => array(
		'id' => '43',
		'invokename' => 'fun_中部焦点摘要图片',
		'title' => '图文模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'26' => array(
		'id' => '54',
		'invokename' => 'auto_播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '288,320',
			'title' => '36',
		),
		'cachetime' => '9000',
	),
	'27' => array(
		'id' => '53',
		'invokename' => 'fun_频道页循环',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '0',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '30',
			'author' => 'default',
		),
		'cachetime' => '1800',
	),
	'28' => array(
		'id' => '47',
		'invokename' => 'fun_中部焦点_有作者',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'author' => 'default',
			'url' => 'default',
			'title' => '50',
		),
		'cachetime' => '9000',
	),
	'29' => array(
		'id' => '52',
		'invokename' => 'fun_频道页循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'30' => array(
		'id' => '55',
		'invokename' => 'auto_焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '70',
		),
		'cachetime' => '9000',
	),
	'31' => array(
		'id' => '56',
		'invokename' => 'auto_焦点列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'32' => array(
		'id' => '57',
		'invokename' => 'auto_本版热门',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => '',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '30',
		),
		'cachetime' => '8000',
	),
	'33' => array(
		'id' => '58',
		'invokename' => 'atuo_中部图片',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '9',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
		),
		'cachetime' => '9000',
	),
	'34' => array(
		'id' => '59',
		'invokename' => 'auto_中部焦点1',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'35' => array(
		'id' => '60',
		'invokename' => 'auto_中部焦点2',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => '',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'36' => array(
		'id' => '61',
		'invokename' => 'atuo_频道页循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'37' => array(
		'id' => '62',
		'invokename' => 'atuo_频道页循环',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '30',
			'author' => 'default',
		),
		'cachetime' => '9000',
	),
	'38' => array(
		'id' => '63',
		'invokename' => 'children_播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '288,320',
			'title' => '36',
		),
		'cachetime' => '1700',
	),
	'39' => array(
		'id' => '64',
		'invokename' => 'children_焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '70',
		),
		'cachetime' => '9000',
	),
	'40' => array(
		'id' => '65',
		'invokename' => 'children_焦点列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '3',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'41' => array(
		'id' => '66',
		'invokename' => 'children_本版热门',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => '',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'42' => array(
		'id' => '67',
		'invokename' => 'children_页面中部焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '50',
		),
		'cachetime' => '9000',
	),
	'43' => array(
		'id' => '68',
		'invokename' => 'children_中部焦点摘要图片',
		'title' => '图文模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'44' => array(
		'id' => '69',
		'invokename' => 'children_中部焦点_有作者',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'author' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'45' => array(
		'id' => '70',
		'invokename' => 'children_频道页循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'46' => array(
		'id' => '71',
		'invokename' => 'children_频道页循环',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '30',
			'author' => 'default',
		),
		'cachetime' => '9000',
	),
	'47' => array(
		'id' => '72',
		'invokename' => 'jia_播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '288,390',
			'title' => '36',
		),
		'cachetime' => '9000',
	),
	'48' => array(
		'id' => '73',
		'invokename' => 'jia_焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '4',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '40',
		),
		'cachetime' => '9000',
	),
	'49' => array(
		'id' => '74',
		'invokename' => 'jia_页面中部焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'50' => array(
		'id' => '75',
		'invokename' => 'jia_中部焦点摘要图片',
		'title' => '图文模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'51' => array(
		'id' => '76',
		'invokename' => 'jia_中部焦点_有作者',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'author' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'52' => array(
		'id' => '77',
		'invokename' => 'jia_频道页循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'53' => array(
		'id' => '78',
		'invokename' => 'jia_频道页循环',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '30',
			'author' => 'default',
		),
		'cachetime' => '9000',
	),
	'54' => array(
		'id' => '79',
		'invokename' => 'women_播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '288,256',
			'title' => '36',
		),
		'cachetime' => '9000',
	),
	'55' => array(
		'id' => '80',
		'invokename' => 'women_焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '25',
			'descrip' => '70',
		),
		'cachetime' => '9600',
	),
	'56' => array(
		'id' => '81',
		'invokename' => 'women_焦点图片摘要',
		'title' => '图文模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9500',
	),
	'57' => array(
		'id' => '82',
		'invokename' => 'women_本版热门',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '8000',
	),
	'58' => array(
		'id' => '83',
		'invokename' => 'women_页面中部焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '8000',
	),
	'59' => array(
		'id' => '84',
		'invokename' => 'women_中部焦点摘要图片',
		'title' => '图文模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9600',
	),
	'60' => array(
		'id' => '85',
		'invokename' => 'women_中部焦点_有作者',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'author' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9600',
	),
	'61' => array(
		'id' => '86',
		'invokename' => 'women_频道页循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '9000',
	),
	'62' => array(
		'id' => '87',
		'invokename' => 'women_频道页循环',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '30',
			'author' => 'default',
		),
		'cachetime' => '9000',
	),
	'63' => array(
		'id' => '95',
		'invokename' => '频道页中部焦点_有作者',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '5',
		'rang' => 'fid',
		'param' => array(
			'author' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9900',
	),
	'64' => array(
		'id' => '99',
		'invokename' => '频道页循环',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '30',
			'author' => 'default',
		),
		'cachetime' => '1800',
	),
	'65' => array(
		'id' => '98',
		'invokename' => '频道页循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '2',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '32',
			'descrip' => '50',
		),
		'cachetime' => '1700',
	),
	'66' => array(
		'id' => '100',
		'invokename' => 'home2_首页焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '3',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '40',
			'descrip' => '80',
		),
		'cachetime' => '9800',
	),
	'67' => array(
		'id' => '101',
		'invokename' => 'home2_首页播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '280,480',
			'title' => '36',
		),
		'cachetime' => '9700',
	),
	'68' => array(
		'id' => '104',
		'invokename' => 'home2_首页焦点附说明',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'digestsubject',
		'num' => '10',
		'rang' => '',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '24',
			'descrip' => '22',
		),
		'cachetime' => '9800',
	),
	'69' => array(
		'id' => '105',
		'invokename' => 'home2_热门话题',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'replysort',
		'num' => '10',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9000',
	),
	'70' => array(
		'id' => '106',
		'invokename' => 'home2_中部图片',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '8',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '16',
		),
		'cachetime' => '8000',
	),
	'71' => array(
		'id' => '107',
		'invokename' => 'home2_中部焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '2',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '36',
			'descrip' => '90',
		),
		'cachetime' => '9800',
	),
	'72' => array(
		'id' => '108',
		'invokename' => 'home2_中部大图',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '220,235',
		),
		'cachetime' => '99999',
	),
	'73' => array(
		'id' => '109',
		'invokename' => 'home2_中部帖子列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9999',
	),
	'74' => array(
		'id' => '110',
		'invokename' => 'home2_首页中部热门',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'hitsort',
		'num' => '10',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '9800',
	),
	'75' => array(
		'id' => '111',
		'invokename' => 'home2_首页循环版块',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '8',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '46',
		),
		'cachetime' => '9999',
	),
	'76' => array(
		'id' => '112',
		'invokename' => 'home1_首页焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '3',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '40',
			'descrip' => '80',
		),
		'cachetime' => '9800',
	),
	'77' => array(
		'id' => '116',
		'invokename' => 'home1_首页头部左侧焦点',
		'title' => '帖子模块',
		'action' => 'subject',
		'func' => 'replysort',
		'num' => '7',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '26',
		),
		'cachetime' => '3600',
	),
	'78' => array(
		'id' => '115',
		'invokename' => 'home1_首页头部左侧焦点',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '160,120',
		),
		'cachetime' => '1700',
	),
	'79' => array(
		'id' => '117',
		'invokename' => 'home1_首页播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '340,338',
			'title' => '36',
		),
		'cachetime' => '9800',
	),
	'80' => array(
		'id' => '118',
		'invokename' => 'home1_首页头部右侧焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'replysort',
		'num' => '8',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '6000',
	),
	'81' => array(
		'id' => '119',
		'invokename' => 'home1_首页中部帖子列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '22',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '1800',
	),
	'82' => array(
		'id' => '123',
		'invokename' => 'home1_首页中部右侧',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'digestsubject',
		'num' => '10',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'title' => '40',
		),
		'cachetime' => '9400',
	),
	'83' => array(
		'id' => '122',
		'invokename' => 'home1_首页中部右侧',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '160,120',
		),
		'cachetime' => '9800',
	),
	'84' => array(
		'id' => '124',
		'invokename' => 'home1_首页中部标签',
		'title' => '标签模块',
		'action' => 'tag',
		'func' => 'gettags',
		'num' => '10',
		'rang' => 'hot',
		'param' => array(
			'url' => 'default',
			'title' => 'default',
		),
		'cachetime' => '86400',
	),
	'85' => array(
		'id' => '125',
		'invokename' => 'home1_首页中部图片',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => '',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '8',
		),
		'cachetime' => '1700',
	),
	'86' => array(
		'id' => '126',
		'invokename' => 'home1_首页版块排行',
		'title' => '版块模块',
		'action' => 'forum',
		'func' => 'forumsort',
		'num' => '20',
		'rang' => 'topic',
		'param' => array(
			'value' => 'default',
			'url' => 'default',
			'title' => 'default',
		),
		'cachetime' => '86400',
	),
	'87' => array(
		'id' => '127',
		'invokename' => 'home1_首页版块循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
			'title' => '40',
			'descrip' => '60',
		),
		'cachetime' => '1700',
	),
	'88' => array(
		'id' => '128',
		'invokename' => 'home1_首页版块循环',
		'title' => '帖子模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '7',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '40',
		),
		'cachetime' => '1800',
	),
	'89' => array(
		'id' => '129',
		'invokename' => 'category_频道页头部左侧焦点',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '3',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '100,100',
		),
		'cachetime' => '1700',
	),
	'90' => array(
		'id' => '130',
		'invokename' => 'category_频道页头部左侧焦点',
		'title' => '帖子模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '7',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '36',
		),
		'cachetime' => '1800',
	),
	'91' => array(
		'id' => '131',
		'invokename' => 'category_频道页热门标签',
		'title' => '标签模块',
		'action' => 'tag',
		'func' => 'gettags',
		'num' => '10',
		'rang' => 'hot',
		'param' => array(
			'url' => 'default',
			'title' => 'default',
		),
		'cachetime' => '86400',
	),
	'92' => array(
		'id' => '132',
		'invokename' => 'category_频道页版块排行',
		'title' => '版块模块',
		'action' => 'forum',
		'func' => 'forumsort',
		'num' => '20',
		'rang' => 'topic',
		'param' => array(
			'value' => 'default',
			'url' => 'default',
			'title' => 'default',
		),
		'cachetime' => '86400',
	),
	'93' => array(
		'id' => '133',
		'invokename' => 'category_频道页焦点摘要',
		'title' => '帖子及摘要',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '3',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '40',
			'descrip' => '80',
		),
		'cachetime' => '1800',
	),
	'94' => array(
		'id' => '134',
		'invokename' => 'category_频道页焦点列表',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '1800',
	),
	'95' => array(
		'id' => '135',
		'invokename' => 'category_频道页播放器',
		'title' => '播放器',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '340,280',
			'title' => '36',
		),
		'cachetime' => '1700',
	),
	'96' => array(
		'id' => '136',
		'invokename' => 'category_频道页中部焦点',
		'title' => '帖子列表',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '12',
		'rang' => 'fid',
		'param' => array(
			'forumurl' => 'default',
			'forumname' => 'default',
			'url' => 'default',
			'title' => '32',
		),
		'cachetime' => '1800',
	),
	'97' => array(
		'id' => '137',
		'invokename' => 'category_频道页中部右侧',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '160,120',
		),
		'cachetime' => '1700',
	),
	'98' => array(
		'id' => '138',
		'invokename' => 'category_频道页中部右侧',
		'title' => '帖子排行模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '10',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'title' => '30',
		),
		'cachetime' => '1800',
	),
	'99' => array(
		'id' => '139',
		'invokename' => 'category_版块中部图片',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '6',
		'rang' => 'fid',
		'param' => array(
			'url' => 'default',
			'image' => '118,118',
			'title' => '16',
		),
		'cachetime' => '1700',
	),
	'100' => array(
		'id' => '140',
		'invokename' => 'category_频道页版块循环',
		'title' => '图片模块',
		'action' => 'image',
		'func' => 'newpic',
		'num' => '1',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'image' => '126,92',
			'title' => '40',
			'descrip' => '60',
		),
		'cachetime' => '1700',
	),
	'101' => array(
		'id' => '141',
		'invokename' => 'category_频道页版块循环',
		'title' => '帖子模块',
		'action' => 'subject',
		'func' => 'newsubject',
		'num' => '7',
		'rang' => '0',
		'param' => array(
			'url' => 'default',
			'title' => '40',
		),
		'cachetime' => '1800',
	),
);
$pw_invokepiece = L::loadDB('invokepiece');
$db->query("TRUNCATE TABLE `pw_invokepiece`");
foreach ($invokepieces as $key=>$value) {
	$pw_invokepiece->insertData($value);
}
?>