CREATE TABLE `dtrans_alerts` (
  `id_alert` int(11) NOT NULL auto_increment,
  `id_soft` int(11) NOT NULL default '0',
  `limit` smallint(6) NOT NULL default '0',
  `mode` tinyint(1) NOT NULL default '0',
  `lastactivity` int(10) NOT NULL default '0',
  `alerted` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id_alert`),
  KEY `id_soft` (`id_soft`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_categos` (
  `id_cat` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `nameid` varchar(150) NOT NULL,
  `desc` text NOT NULL,
  `parent` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_downs` (
  `id_down` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `id_soft` int(11) NOT NULL,
  `downs` int(11) NOT NULL default '0',
  `ip` varchar(50) NOT NULL,
  `date` int(10) NOT NULL,
  `id_file` int(11) NOT NULL,
  PRIMARY KEY  (`id_down`),
  KEY `uid` (`uid`,`id_soft`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_features` (
  `id_feat` int(11) NOT NULL auto_increment,
  `id_soft` int(11) NOT NULL default '0',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `created` int(10) NOT NULL,
  `modified` int(10) NOT NULL default '0',
  `nameid` varchar(200) NOT NULL,
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_feat`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_files` (
  `id_file` int(11) NOT NULL auto_increment,
  `id_soft` int(11) NOT NULL default '0',
  `file` varchar(255) NOT NULL,
  `remote` tinyint(1) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL,
  `group` int(11) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  `date` int(10) NOT NULL,
  `mime` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_file`),
  KEY `id_soft` (`id_soft`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_groups` (
  `id_group` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `id_soft` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_group`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_licences` (
  `id_lic` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_lic`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_licsoft` (
  `id_lic` int(11) NOT NULL,
  `id_soft` int(11) NOT NULL,
  KEY `id_lic` (`id_lic`,`id_soft`)
) ENGINE=MyISAM;

CREATE TABLE `dtrans_logs` (
  `id_log` int(11) NOT NULL auto_increment,
  `id_soft` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `date` int(10) NOT NULL default '0',
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_log`),
  KEY `id_soft` (`id_soft`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_platforms` (
  `id_platform` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_platform`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_platsoft` (
  `id_platform` int(11) NOT NULL,
  `id_soft` int(11) NOT NULL,
  KEY `id_platform` (`id_platform`,`id_soft`)
) ENGINE=MyISAM;

CREATE TABLE `dtrans_screens` (
  `id_screen` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `id_soft` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_screen`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_softtag` (
  `id_soft` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  KEY `id_soft` (`id_soft`,`id_tag`)
) ENGINE=MyISAM;

CREATE TABLE `dtrans_software` (
  `id_soft` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `version` varchar(50) NOT NULL,
  `shortdesc` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `limits` smallint(6) NOT NULL default '0',
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `uname` varchar(50) NOT NULL,
  `secure` tinyint(1) NOT NULL default '0',
  `groups` text NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rating` int(11) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `daily` tinyint(1) NOT NULL default '0',
  `mark` tinyint(1) NOT NULL default '0',
  `siterate` smallint(6) NOT NULL,
  `screens` int(11) NOT NULL default '0',
  `id_cat` int(11) NOT NULL,
  `comments` int(11) NOT NULL default '0',
  `author` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `langs` varchar(255) NOT NULL,
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_soft`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_software_edited` (
  `id_soft` int(11) NOT NULL auto_increment,
  `soft` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `version` varchar(50) NOT NULL,
  `shortdesc` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `limits` smallint(6) NOT NULL default '0',
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `uname` varchar(50) NOT NULL,
  `secure` tinyint(1) NOT NULL default '0',
  `groups` text NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rating` int(11) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `daily` tinyint(1) NOT NULL default '0',
  `mark` tinyint(1) NOT NULL default '0',
  `siterate` smallint(6) NOT NULL,
  `screens` int(11) NOT NULL default '0',
  `id_cat` int(11) NOT NULL,
  `comments` int(11) NOT NULL default '0',
  `author` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `langs` varchar(255) NOT NULL,
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `fields` text NOT NULL,
  PRIMARY KEY  (`id_soft`)
) ENGINE=MyISAM ;


CREATE TABLE `dtrans_tags` (
  `id_tag` int(11) NOT NULL auto_increment,
  `tag` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_tag`)
) ENGINE=MyISAM ;

CREATE TABLE `dtrans_votedata` (
  `uid` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `date` int(10) NOT NULL default '0',
  `id_soft` int(11) NOT NULL,
  KEY `uid` (`uid`,`ip`)
) ENGINE=MyISAM;
