CREATE TABLE `pw_categos` (
  `id_cat` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `desc` text NOT NULL,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  `nameid` varchar(150) NOT NULL,
  `created` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id_cat`)
) TYPE=MyISAM ;

CREATE TABLE `pw_clients` (
  `id_client` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id_client`)
) TYPE=MyISAM ;

CREATE TABLE `pw_images` (
  `id_img` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `image` varchar(200) NOT NULL,
  `work` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_img`)
) TYPE=MyISAM ;

CREATE TABLE `pw_types` (
  `id_type` int(11) NOT NULL auto_increment,
  `type` varchar(100) NOT NULL,
  `created` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id_type`)
) TYPE=MyISAM ;

CREATE TABLE `pw_works` (
  `id_work` int(11) NOT NULL auto_increment,
  `title` varchar(200) collate latin1_general_ci NOT NULL,
  `titleid` varchar(200) collate latin1_general_ci NOT NULL,
  `short` varchar(255) collate latin1_general_ci NOT NULL default '',
  `desc` text collate latin1_general_ci NOT NULL,
  `catego` int(11) NOT NULL default '0',
  `client` int(11) NOT NULL,
  `comment` text collate latin1_general_ci NOT NULL,
  `site` varchar(150) collate latin1_general_ci NOT NULL,
  `url` varchar(255) collate latin1_general_ci NOT NULL default '',
  `mark` tinyint(1) NOT NULL default '0',
  `image` varchar(255) collate latin1_general_ci NOT NULL,
  `created` int(10) NOT NULL default '0',
  `date_start` int(10) NOT NULL default '0',
  `period` varchar(255) collate latin1_general_ci NOT NULL,
  `cost` float NOT NULL,
  `public` tinyint(1) NOT NULL default '0',
  `rating` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_work`),
  KEY `titleid` (`titleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
