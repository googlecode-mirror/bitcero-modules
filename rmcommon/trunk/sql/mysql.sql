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
  `sizeunit` MEDIUMINT(9) not null default '1024',
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

CREATE TABLE `rmc_comments` (
`id_com` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`id_obj` VARCHAR( 50 ) NOT NULL ,
`type` VARCHAR( 50 ) NOT NULL DEFAULT 'module',
`parent` BIGINT NOT NULL DEFAULT '0',
`params` VARCHAR( 200 ) NOT NULL ,
`content` TEXT NOT NULL ,
`user` INT NOT NULL ,
`ip` VARCHAR(40) NOT NULL,
INDEX ( `id_obj` , `type` )
) ENGINE = MYISAM;

CREATE TABLE `rmc_comusers` (
`id_user` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`xuid` INT NOT NULL DEFAULT '0',
`name` VARCHAR( 150 ) NOT NULL ,
`email` VARCHAR( 150 ) NOT NULL
) ENGINE = MYISAM ;