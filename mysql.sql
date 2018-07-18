
create database cms_company

// 后台用户表
CREATE TABLE `cms_admin` (
  `admin_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `lastloginip` varchar(15) DEFAULT '0',
  `lastlogintime` int(10) unsigned DEFAULT '0',
  `email` varchar(40) DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`admin_id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO cms_admin (username, password, lastloginip, lastlogintime, email, realname, status) 
VALUES ('admin', 'e10adc3949ba59abbe56e057f20f883', 0, 0, '', '', 1);
update cms_admin set password = '6b88fddc75f60aad4901d5ead680f7ef' where username = 'admin';


// 菜单表
CREATE TABLE `cms_menu` (
  `menu_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `parentid` smallint(6) NOT NULL DEFAULT '0',
  `m` varchar(20) NOT NULL DEFAULT '' COMMENT '模块',
  `c` varchar(20) NOT NULL DEFAULT '' COMMENT '控制器',
  `f` varchar(20) NOT NULL DEFAULT '' COMMENT '方法',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned  NOT NULL DEFAULT '0' COMMENT '前后端栏目',
  PRIMARY KEY (`menu_id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`m`, `c`, `f`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table cms_menu add create_time varchar(50) not null default '' comment '创建时间';
alter table cms_menu add update_time varchar(50) not null default '' comment '修改时间';
alter table cms_menu add create_user varchar(50) not null default '' comment '创建人';
alter table cms_menu add update_user varchar(50) not null default '' comment '修改人';
alter table cms_menu modify status tinyint(2) not null default '0' comment '状态';


// 新闻文章主表
CREATE TABLE `cms_news` (
  `news_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '标题',
  `small_title` varchar(30) NOT NULL DEFAULT '' COMMENT '副标题',
  `title_font_color` varchar(250) DEFAULT NULL COMMENT '标题颜色',
  `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `keywords` char(40) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(250) NOT NULL COMMENT '',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `copyfrom` varchar(250) DEFAULT NULL COMMENT '来源',
  `username` char(20) NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `listorder` (`listorder`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table cms_news add create_user varchar(50) not null default '' comment '创建人';
alter table cms_news add update_user varchar(50) not null default '' comment '修改人';
alter table cms_news modify status tinyint(2) not null default '0' comment '状态';
alter table cms_news change create_time create_time varchar(50) not null default '' comment '创建时间';
alter table cms_news change update_time update_time varchar(50) not null default '' comment '修改时间';


// 新闻文章内容副表
CREATE TABLE `cms_news_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` mediumint(8) unsigned NOT NULL,
  `content` mediumint NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table cms_news_content add create_user varchar(50) not null default '' comment '创建人';
alter table cms_news_content add update_user varchar(50) not null default '' comment '修改人';
alter table cms_news_content change create_time create_time varchar(50) not null default '' comment '创建时间';
alter table cms_news_content change update_time update_time varchar(50) not null default '' comment '修改时间';


// 推荐位标识表
CREATE TABLE `cms_position` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` char(100) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table cms_position add create_user varchar(50) not null default '' comment '创建人';
alter table cms_position add update_user varchar(50) not null default '' comment '修改人';
alter table cms_position modify status tinyint(2) not null default '0' comment '状态';
alter table cms_position change create_time create_time varchar(50) not null default '' comment '创建时间';
alter table cms_position change update_time update_time varchar(50) not null default '' comment '修改时间';


// 推荐位内容表
CREATE TABLE `cms_position_content` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(5) unsigned NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) DEFAULT NULL,
  `news_id` mediumint(8) unsigned NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  key `position_id` (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table cms_position_content add create_user varchar(50) not null default '' comment '创建人';
alter table cms_position_content add update_user varchar(50) not null default '' comment '修改人';
alter table cms_position_content modify status tinyint(2) not null default '0' comment '状态';
alter table cms_position_content change create_time create_time varchar(50) not null default '' comment '创建时间';
alter table cms_position_content change update_time update_time varchar(50) not null default '' comment '修改时间';
