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