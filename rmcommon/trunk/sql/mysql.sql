CREATE TABLE `api_events` (
  `id_event` bigint(20) NOT NULL auto_increment,
  `event` varchar(100) collate latin1_general_ci NOT NULL,
  `object` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  PRIMARY KEY  (`id_event`),
  KEY `object` (`object`),
  KEY `event` (`event`),
  KEY `eid` (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `api_methods` (
  `method` varchar(100) collate latin1_general_ci NOT NULL,
  `event` bigint(20) NOT NULL,
  `file` varchar(150) collate latin1_general_ci NOT NULL,
  `object` int(11) NOT NULL default '0',
  PRIMARY KEY  (`method`),
  KEY `event` (`event`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `api_objects` (
  `id_object` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  `type` varchar(20) collate latin1_general_ci NOT NULL,
  `path` varchar(150) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_object`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `rmc_img_cats` (
  `id_cat` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL default 'active',
  `groups` text NOT NULL,
  `filesize` int(11) NOT NULL default '0',
  `sizeunit` smallint not null default '1024',
  `sizes` text NOT NULL,
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM;

CREATE TABLE `rmc_images` (
`id_img` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 100 ) NOT NULL ,
`desc` TEXT NOT NULL ,
`date` INT( 10 ) NOT NULL ,
`file` VARCHAR( 150 ) NOT NULL ,
`cat` INT NOT NULL,
`uid` INT NOT NULL
) ENGINE = MYISAM ;