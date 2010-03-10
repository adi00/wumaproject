<?php die;?>
Query Error: SELECT health,toolname,tool,score,allscore FROM pw_gold where id='1' 
Query Error: SELECT health,tool,score,allscore FROM pw_gold where id='1' 
Query Error: DROP TABLE IF EXISTS 
Query Error: DROP TABLE IF EXISTS 
Query Error: CREATE TABLE IF NOT EXISTS `pw_teamtask`{`tid` int(10) unsigned not null auto_increment,`pid` int(10) unsigned not null default 0,`priority` tinyint(1) unsigned not null default 0,`owner` char(15) not null,`ownerid` mediumint(8) unsigned not null,`content` text,  `plan_start_time` int(10) unsigned not null default 0, `plan_end_time` int(10) unsigned not null default 0,`real_start_time` int(10) unsigned not null default 0,`real_end_time` int(10) unsigned not null default 0,`founderid` mediumint(8) unsigned not null default 0,`create_time` int(10) unsigned not null default 0,`status` tinyint(1) unsigned not null default 0,PRIMARY KEY(`tid`)} ENGINE=MYISAM DEFAULT CHARSET=UTF8;
Query Error: CREATE TABLE IF NOT EXISTS `pw_teamtask`{`tid` int(10) unsigned not null auto_increment,`pid` int(10) unsigned not null default 0,`priority` tinyint(1) unsigned not null default 0,`owner` char(15) not null,`ownerid` mediumint(8) unsigned not null,`content` text,  `plan_start_time` int(10) unsigned not null default 0, `plan_end_time` int(10) unsigned not null default 0,`real_start_time` int(10) unsigned not null default 0,`real_end_time` int(10) unsigned not null default 0,`founderid` mediumint(8) unsigned not null default 0,`create_time` int(10) unsigned not null default 0,`status` tinyint(1) unsigned not null default 0,PRIMARY KEY(`tid`)};
Query Error: CREATE TABLE IF NOT EXISTS `test`{`tid` int(10) ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE TABLE IF NOT EXISTS `test`{`tid` int(10) ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE TABLE IF NOT EXISTS `test`{`tid` int(10) ENGINE= DEFAULT CHARSET=utf8;
Query Error: DROP TABLE IF EXISTS int
Query Error: DROP TABLE IF EXISTS int
Query Error: DROP TABLE IF EXISTS int
Query Error: DROP TABLE IF EXISTS 
Query Error: DROP TABLE IF EXISTS 
Query Error: DROP TABLE IF EXISTS 
Query Error: CREATE INDEX ownerid ON pw_teamtasks(`ownerid`,`create_time`,)ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE INDEX owner_id ON pw_teamtasks(`owner_id`,`create_time`,)ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE INDEX owner_id ON pw_teamtasks(`owner_id`,`create_time`)ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE INDEX in_owner_id ON pw_teamtasks(`owner_id`,`create_time`)ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE TABLE IF NOT EXISTS `pw_teamtasks`(`tid` int(10) unsigned not null auto_increment,`pid` int(10) unsigned not null default 0,`priority` tinyint(1) unsigned not null default 0,`owner` char(15) not null,`owner_id` mediumint(8) unsigned not null,`content` text,  `plan_start_time` int(10) unsigned not null default 0, `plan_end_time` int(10) unsigned not null default 0,`real_start_time` int(10) unsigned not null default 0,`real_end_time` int(10) unsigned not null default 0,`publish_id` mediumint(8) unsigned not null default 0,`create_time` int(10) unsigned not null default 0,`status` tinyint(1) unsigned not null default 0,PRIMARY KEY(`tid`))ENGINE= DEFAULT CHARSET=utf8;
Query Error: CREATE TABLE IF NOT EXISTS `pw_teamtasks`(`tid` int(10) unsigned not null auto_increment,`pid` int(10) unsigned not null default 0,`priority` tinyint(1) unsigned not null default 0,`owner` char(15) not null,`owner_id` mediumint(8) unsigned not null,`content` text,  `plan_start_time` int(10) unsigned not null default 0, `plan_end_time` int(10) unsigned not null default 0,`real_start_time` int(10) unsigned not null default 0,`real_end_time` int(10) unsigned not null default 0,`publisher_id` mediumint(8) unsigned not null default 0,`status` tinyint(1) unsigned not null default 0,`create_time` int(10) unsigned not null default 0,PRIMARY KEY(`tid`))ENGINE= DEFAULT CHARSET=utf8;
Cannot use database
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM tw_projects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tp.priority, tp.projectname, tp.status FROM pw_teamprojects AS tp WHERE owner_id = 
Query Error: SELECT tt.priority, tt.taskname, tt.status FROM pw_teamtaks AS tt WHERE owner_id = 1
Query Error: SELECT tt.priority, tt.taskname, tt.status, tt.plan_start_time, tt.real_start_time, tt,status, tt.create_time FROM pw_teamtasks AS tt WHERE owner_id = 1 and tt.status in(0,1,2)
Query Error: SELECT tt.priority, tt.taskname, tt.status, tt.plan_start_time, tt.real_start_time, tt,status, tt.create_time FROM pw_teamtasks AS tt WHERE owner_id = 1 and status in(0,1,2)
