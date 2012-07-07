CREATE TABLE `dtrans_alerts` (
  `id_alert` int(11) NOT NULL AUTO_INCREMENT,
  `id_soft` int(11) NOT NULL DEFAULT '0',
  `limit` smallint(6) NOT NULL DEFAULT '0',
  `mode` tinyint(1) NOT NULL DEFAULT '0',
  `lastactivity` int(10) NOT NULL DEFAULT '0',
  `alerted` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_alert`),
  KEY `id_soft` (`id_soft`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_categos` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `nameid` varchar(150) NOT NULL,
  `desc` text NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_catsoft` (
  `cat` int(11) NOT NULL,
  `soft` int(11) NOT NULL,
  KEY `cat` (`cat`),
  KEY `soft` (`soft`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_downs` (
  `id_down` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `id_soft` int(11) NOT NULL,
  `downs` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `date` int(10) NOT NULL,
  `id_file` int(11) NOT NULL,
  PRIMARY KEY (`id_down`),
  KEY `uid` (`uid`,`id_soft`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_features` (
  `id_feat` int(11) NOT NULL AUTO_INCREMENT,
  `id_soft` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `created` int(10) NOT NULL,
  `modified` int(10) NOT NULL DEFAULT '0',
  `nameid` varchar(200) NOT NULL,
  PRIMARY KEY (`id_feat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_files` (
  `id_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_soft` int(11) NOT NULL DEFAULT '0',
  `file` varchar(255) NOT NULL,
  `remote` tinyint(1) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `group` int(11) NOT NULL DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL,
  `mime` varchar(50) NOT NULL,
  PRIMARY KEY (`id_file`),
  KEY `id_soft` (`id_soft`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_groups` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `id_soft` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_licences` (
  `id_lic` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id_lic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_licsoft` (
  `id_lic` int(11) NOT NULL,
  `id_soft` int(11) NOT NULL,
  KEY `id_lic` (`id_lic`,`id_soft`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_logs` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_soft` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `date` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_log`),
  KEY `id_soft` (`id_soft`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_platforms` (
  `id_platform` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id_platform`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_platsoft` (
  `id_platform` int(11) NOT NULL,
  `id_soft` int(11) NOT NULL,
  KEY `id_platform` (`id_platform`,`id_soft`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_screens` (
  `id_screen` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `id_soft` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_screen`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_softtag` (
  `id_soft` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  KEY `id_soft` (`id_soft`,`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_software` (
  `id_soft` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `version` varchar(50) NOT NULL,
  `shortdesc` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `image` varchar(30) NOT NULL,
  `limits` smallint(6) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `secure` tinyint(1) NOT NULL DEFAULT '0',
  `groups` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `nameid` varchar(150) NOT NULL,
  `daily` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `siterate` smallint(6) NOT NULL,
  `screens` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `langs` varchar(255) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `author_url` varchar(255) NOT NULL,
  `author_email` varchar(100) NOT NULL,
  `author_contact` tinyint(1) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id_soft`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_tags` (
  `id_tag` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  `tagid` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dtrans_votedata` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `date` int(10) NOT NULL DEFAULT '0',
  `id_soft` int(11) NOT NULL,
  KEY `uid` (`uid`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE  `dtrans_meta` (
`id_meta` INT NOT NULL AUTO_INCREMENT ,
`type` VARCHAR( 5 ) NOT NULL ,
`name` VARCHAR( 50 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY (  `id_meta` )
) ENGINE = MYISAM ;