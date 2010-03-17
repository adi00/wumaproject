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
Query Error: UPDATE pw_teamtasks SET real_start_time=1268707471,modify_time=1268707471 WHERE tid=10 AND owner_id=1
Query Error: UPDATE pw_teamtasks SET real_end_time=1268707473,modify_time=1268707473 WHERE tid=10 AND owner_id=1
Query Error: UPDATE pw_teamtasks SET real_start_time=1268707474,modify_time=1268707474 WHERE tid=10 AND owner_id=1
Query Error: UPDATE pw_teamtasks SET real_start_time=1268707492,modify_time=1268707492 WHERE tid=10 AND owner_id=1
Query Error: UPDATE pw_teamtasks SET real_start_time=1268707497,modify_time=1268707497 WHERE tid=10 AND owner_id=1
Query Error: INSERT INTO pw_teamtasks (pid,taskname,owner,priority,plan_start_time,plan_end_time,real_start_time,real_end_time,remark,content,status,publisher_id,create_time,modify_time,owner_id) VALUES ('1','aaaa','','5','1267459200','1268841600','1267459200','1269964800','sdfasdfasd','asdfasdf','2','1','1268794461','1268794461','1') 
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
Query Error: SELECT tp.pid, tp.priority, tp.projectname, tp.plan_start_time, tp.real_start_time, tp.status, tp.create_time FROM pw_teamprojects AS tp WHERE tp.owner_id =  order by tp.priority asc limit 10
