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
`posted` INT(10) NOT NULL DEFAULT '0',
`status` VARCHAR(10) NOT NULL DEFAULT 'waiting',
INDEX ( `id_obj` , `type` )
) ENGINE = MYISAM;

CREATE TABLE `rmc_comusers` (
`id_user` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`xuid` INT NOT NULL DEFAULT '0',
`name` VARCHAR( 150 ) NOT NULL ,
`email` VARCHAR( 150 ) NOT NULL,
`url`   VARCHAR(150) NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `rmc_plugins` (
`id_plugin` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 150 ) NOT NULL ,
`description` TEXT NOT NULL ,
`dir` VARCHAR( 100 ) NOT NULL ,
`version` TEXT NOT NULL ,
`status` TINYINT( 1 ) NOT NULL DEFAULT '1'
) ENGINE = MYISAM ;

CREATE TABLE `rmc_settings` (
`conf_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`element` VARCHAR( 50 ) NOT NULL ,
`name` VARCHAR( 50 ) NOT NULL ,
`type` VARCHAR( 20 ) NOT NULL ,
`value` TEXT NOT NULL ,
`valuetype` VARCHAR( 20 ) NOT NULL ,
INDEX ( `element` , `name` )
) ENGINE = MYISAM ;

CREATE TABLE `rmc_blocks` (
  `bid` mediumint(8) unsigned NOT NULL auto_increment,
  `element` varchar(50)   NOT NULL,
  `element_type` varchar(20)   NOT NULL,
  `options` text   NOT NULL,
  `name` varchar(150)   NOT NULL default '',
  `description` varchar(255)   NOT NULL,
  `canvas` tinyint(1) unsigned NOT NULL default '0',
  `weight` smallint(5) unsigned NOT NULL default '0',
  `visible` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(6)   NOT NULL,
  `content_type` varchar(20)   NOT NULL,
  `content` text NOT NULL,
  `isactive` tinyint(1) unsigned NOT NULL default '0',
  `dirname` varchar(50)   NOT NULL default '',
  `file` varchar(150)   NOT NULL,
  `show_func` varchar(50) NOT NULL default '',
  `edit_func` varchar(50) NOT NULL default '',
  `template` varchar(150) NOT NULL,
  `bcachetime` int(10) NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `element` (`element`),
  KEY `visible` (`visible`)
) ENGINE=MyISAM;

CREATE TABLE `rmc_blocks_positions` (
  `id_position` int(11) NOT NULL auto_increment,
  `name` varchar(150) collate latin1_general_ci NOT NULL,
  `tag` varchar(150) collate latin1_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_position`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM;

CREATE TABLE `rmc_bkmod` (
`bid` INT NOT NULL ,
`mid` INT NOT NULL ,
`page` VARCHAR( 50 ) NOT NULL ,
INDEX ( `bid` , `mid` )
) ENGINE = MYISAM ;