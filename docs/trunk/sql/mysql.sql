CREATE TABLE `rd_figures` (
  `id_fig` int(10) unsigned NOT NULL auto_increment,
  `id_res` int(10) unsigned NOT NULL default '0',
  `attrs` text NOT NULL,
  `desc` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id_fig`)
) TYPE=MyISAM ;

CREATE TABLE `rd_references` (
  `id_ref` int(10) unsigned NOT NULL auto_increment,
  `id_res` int(10) unsigned NOT NULL default '0',
  `title` varchar(150) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY  (`id_ref`)
) TYPE=MyISAM ;

CREATE TABLE `rd_resources` (
  `id_res` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(150) NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `owner` int(11) NOT NULL,
  `owname` varchar(50) NOT NULL,
  `editors` text NOT NULL,
  `editor_approve` tinyint(1) NOT NULL default '0',
  `groups` text NOT NULL,
  `public` tinyint(1) NOT NULL default '0',
  `quick` tinyint(1) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `show_index` tinyint(1) NOT NULL default '0',
  `reads` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rating` int(11) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '1',
  `featured` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_res`),
  KEY `nameid` (`nameid`),
  KEY `title` (`title`)
) TYPE=MyISAM ;

CREATE TABLE `rd_sections` (
  `id_sec` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `order` int(11) NOT NULL default '0',
  `id_res` int(11) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `parent` int(10) unsigned NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `uname` varchar(40) NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id_sec`),
  KEY `nameid` (`nameid`)
) TYPE=MyISAM ;

CREATE TABLE `rd_votedata` (
  `uid` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `date` int(10) NOT NULL,
  `res` int(11) NOT NULL,
  KEY `uid` (`uid`,`ip`,`res`)
) TYPE=MyISAM;

CREATE TABLE `rd_edits` (
  `id_edit` int(10) unsigned NOT NULL auto_increment,
  `id_sec` int(10) NOT NULL default '0',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `order` int(11) NOT NULL default '0',
  `id_res` int(11) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `parent` int(10) unsigned NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rating` int(11) NOT NULL default '0',
  `reads` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `uname` varchar(40) NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_edit`),
  KEY `nameid` (`nameid`),
  KEY `id_sec` (`id_sec`)
) TYPE=MyISAM;

CREATE TABLE `rd_meta` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `section` int(11) NOT NULL,
  `id_meta` bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (`id_meta`),
  KEY `name` (`name`,`section`)
) ENGINE=MyISAM;
