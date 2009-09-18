CREATE TABLE `xtheme_config` (
  `id_conf` bigint(20) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(5) NOT NULL,
  `element` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_conf`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `xtheme_plugins` (
`id_plugin` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`dir` VARCHAR( 50 ) NOT NULL
) ENGINE = MYISAM ;