CREATE TABLE `coach_categos` (
  `id_cat` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `nameid` varchar(100) NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM ;

CREATE TABLE `coach_coachs` (
  `id_coach` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `nameid` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `role` varchar(150) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY  (`id_coach`),
  KEY `nameid` (`nameid`)
) ENGINE=MyISAM ;

CREATE TABLE `coach_players` (
  `id_play` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `nameid` varchar(100) NOT NULL,
  `birth` int(10) NOT NULL,
  `number` smallint(6) NOT NULL,
  `bio` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `date` int(10) NOT NULL,
  `team` int(11) NOT NULL,
  `dohtml` tinyint(1) NOT NULL default '0',
  `doxcode` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_play`)
) ENGINE=MyISAM ;

CREATE TABLE `coach_teamcoach` (
  `id_coach` int(11) NOT NULL,
  `id_team` int(11) NOT NULL,
  KEY `id_coach` (`id_coach`,`id_team`)
) ENGINE=MyISAM;

CREATE TABLE `coach_teams` (
  `id_team` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `nameid` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `cat` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` int(10) NOT NULL,
  `dohtml` tinyint(1) NOT NULL default '0',
  `doxcode` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_team`)
) ENGINE=MyISAM ;