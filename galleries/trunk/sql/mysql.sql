CREATE TABLE `gs_favourites` (
  `uid` int(11) NOT NULL,
  `id_image` int(11) NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `gs_friends` (
  `gsuser` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `gs_images` (
  `id_image` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `owner` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL default '1',
  `user_format` tinyint(1) NOT NULL default '0',
  `set_format` tinyint(1) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `bigset_format` tinyint(1) NOT NULL default '0',
  `search_format` tinyint(1) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_image`)
) ENGINE=MyISAM ;

CREATE TABLE `gs_postcards` (
  `id_post` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `date` int(10) NOT NULL default '0',
  `toname` varchar(100) NOT NULL default '0',
  `tomail` varchar(100) NOT NULL,
  `id_image` int(11) NOT NULL default '0',
  `fromname` varchar(100) NOT NULL,
  `frommail` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL default '0',
  `ip` varchar(20) NOT NULL,
  `view` tinyint(1) NOT NULL default '0',
  `code` varchar(10) NOT NULL,
  PRIMARY KEY  (`id_post`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM ;

CREATE TABLE `gs_sets` (
  `id_set` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `date` int(10) NOT NULL,
  `public` tinyint(1) NOT NULL default '1',
  `pics` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id_set`)
) ENGINE=MyISAM ;

CREATE TABLE `gs_setsimages` (
  `id_set` int(11) NOT NULL,
  `id_image` int(11) NOT NULL,
  KEY `id_set` (`id_set`,`id_image`)
) ENGINE=MyISAM;

CREATE TABLE `gs_tags` (
  `id_tag` int(11) NOT NULL auto_increment,
  `tag` varchar(100) NOT NULL,
  `nameid` varchar(100) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_tag`)
) ENGINE=MyISAM ;

CREATE TABLE `gs_tagsimages` (
  `id_tag` int(11) NOT NULL,
  `id_image` int(11) NOT NULL,
  KEY `id_tag` (`id_tag`,`id_image`)
) ENGINE=MyISAM;

CREATE TABLE `gs_users` (
  `id_user` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `quota` int(11) NOT NULL default '0',
  `pics` smallint(6) NOT NULL default '0',
  `sets` smallint(6) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `blocked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_user`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM ;