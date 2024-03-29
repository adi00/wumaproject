<?php
!function_exists('readover') && exit('Forbidden');

$lang['refreshto'] = array(
	'operate_success'		=> '操作完成',
	'have_login'			=> '您已经顺利登录{$GLOBALS[showmsginfo]}',
	'login_out'				=> '状态：您已经顺利退出网站{$GLOBALS[showmsginfo]}',
	'enter_thread'			=> '<a href=thread.php?fid={$GLOBALS[fid]}>[ 发帖完毕点击进入主题列表 ]</a>',
	'enter_words'			=> '你发布的内容包含敏感词“{$GLOBALS[banword]}”，相关内容被替换，请及时更新',
	'reg_success'			=> '恭喜您,完成注册现在可以开始使用您的会员权利了',
	'mail_success'			=> '邮件发送完成',
	'enter_c_thread'		=> '<a href=c_thread.php?fid={$GLOBALS[fid]}>[ 发帖完毕点击进入主题列表 ]</a>',
	'post_word_check'		=> '你发布的内容包含敏感词“{$GLOBALS[banword]}”，请等待管理员审核!',
	'post_check'			=> '文章发表成功,本版块开启了发帖审核功能,请等待管理员审核!',
	'post_link_check'		=> '文章发表成功，但内容中链接个数过多，帖子需管理员审核通过后才能显示',
	'masigle_ban_success'	=> '完成禁言,将自动跳转至会员资料页面',
	'update_cache'			=> '正在更新榜单排行缓存,请耐心等待...',
	'colony_regsuccess'		=> '{$GLOBALS[cn_name]}注册成功，进入{$GLOBALS[cn_name]}基本资料设置',
	'colony_cardsuccess'	=> '{$GLOBALS[cn_name]}名片修改成功。',
	'colony_postsuccess'	=> '发言成功。',
	'colony_editsuccess'	=> '编辑成功。',
	'colony_delsuccess'		=> '删除成功。',
	'colony_quitsuccess'	=> '退出成功。',
	'colony_setsuccess'		=> '{$GLOBALS[cn_name]}基本设置修改成功。',
	'colony_topsuccess'     => '帖子置顶成功。',
	'showsign_success'		=> '签名展示设置成功。',
	'bank_savesuccess'		=> '完成存款!',
	'bank_drawsuccess'		=> '完成取款!',
	'bank_viresuccess'		=> '完成转帐!',
	'bank_creditsuccess'	=> '完成积分转换!',
	'blog_enterhomepage'	=> '进入 {$GLOBALS[username]} 的个人主页',
	'friend_update_success'	=> '好友列表更新成功。',
	'friend_setting_success'=> '好友设置修改完成。',
	'friend_accept_success'	=> '您接受了好友请求',
	'friend_accept_add'		=> '你接受了请求并申请加对方为好友',
	'friend_refuse_success'	=> '您拒绝了好友请求',
	'friend_add_check'		=> '请求添加为好友，正在等待好友验证。',
	'friend_edit_descrip'	=> '好友描述编辑完成。',

	'complete_list'			=> '该订单已完成支付。',
	'bakup_step'			=> '正在备份数据库表 {$GLOBALS[t_name]}: 共 {$GLOBALS[rows]} 条记录，已经备份至 {$GLOBALS[c_n]}  条记录<br><br>已生成 {$GLOBALS[f_num]} 个备份文件，程序将自动备份余下部分',
	'bakup_out'				=> '已全部备份,备份文件保存在data目录下，备份文件为<br>{$GLOBALS[bakfile]}',
	'backup_data'           => '论坛进入自动数据备份中，您将无法访问本论坛',
	'group_buy_success'     => '特殊组购买成功!!',

	'debate_post_success'	=> '辩论主题发表成功，现转入辩论主题页面！',
	'debate_post_check'		=> '辩论主题发表成功，请等待管理员的审核！',
	'debate_edit_success'	=> '辩论主题编辑成功!',
	'debate_del_success'	=> '辩论主题删除成功！',
	'debate_reply_success'	=> '观点发表成功！',
	'msg_unread'			=> '标记未读成功！',

	'delete_recycle'		=> '正在清理回收站数据，请稍侯...',

	'alipay_failure'		=> '安全验证参数校验失败，无法完成充值！',
	'alipay_ordersfailure'	=> '系统中没有您的支付订单，无法完成支付！',
	'alipay_orderssuccess'	=> '该订单已经支付成功！',
	'alipay_topayfailure'	=> '支付失败，无法完成操作！',

	'report_success'		=> '帖子报告成功!',
	'password_change_success'=> '完成密码修改',

	'email_invite_success'	=> '注册码购买成功，现在跳转至注册页，如果丢失注册码或有购买多个，请进入邮箱查收!',

	'upload_icon_success'	=> '头像修改成功!',
	'album_error'			=> '请先建立相册',
	'myapp_success'			=> '操作成功!',
	'add_friend_success'	=> '成功加该用户为好友',
	//7.5RC1 End
	'pcalipay_none'			=> '不存在此订单或者已完成支付！',
	'pcalipay_success'		=> '您好，您已经完成支付！',
	'del_success'			=> '完成删除操作',
);
?>