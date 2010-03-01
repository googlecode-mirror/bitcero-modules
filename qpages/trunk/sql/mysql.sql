CREATE TABLE `qpages_categos` (
  `id_cat` int(11) NOT NULL auto_increment,
  `nombre` varchar(150) NOT NULL,
  `nombre_amigo` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL default '0',
  `descripcion` text NOT NULL,
  PRIMARY KEY  (`id_cat`)
) TYPE=MyISAM ;

CREATE TABLE `qpages_pages` (
  `id_page` int(11) NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `titulo_amigo` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `texto` longtext NOT NULL,
  `fecha` int(11) NOT NULL,
  `modificado` int(11) NOT NULL,
  `lecturas` int(11) NOT NULL default '0',
  `acceso` tinyint(1) NOT NULL default '0',
  `grupos` varchar(255) NOT NULL,
  `menu` tinyint(1) NOT NULL default '0',
  `dohtml` tinyint(1) NOT NULL default '1',
  `doxcode` tinyint(1) NOT NULL default '0',
  `doimage` tinyint(1) NOT NULL default '0',
  `dobr` tinyint(1) NOT NULL default '0',
  `dosmiley` tinyint(1) NOT NULL default '1',
  `uid` int(11) NOT NULL default '0',
  `porder` int(6) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_page`),
  KEY `titulo_amigo` (`titulo_amigo`),
  KEY `cat` (`cat`)
) TYPE=MyISAM ;

CREATE TABLE `qpages_meta` (
`name` VARCHAR( 50 ) NOT NULL ,
`value` TEXT NOT NULL ,
`page` INT NOT NULL ,
INDEX ( `name` , `page` )
) ENGINE = MYISAM ;