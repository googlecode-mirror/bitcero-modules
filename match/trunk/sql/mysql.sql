CREATE TABLE `mch_categories` (
`id_cat` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 150 ) NOT NULL ,
`nameid` VARCHAR( 150 ) NOT NULL ,
`active` TINYINT( 1 ) NOT NULL DEFAULT '1',
`description` TEXT NOT NULL ,
`parent` INT(10) NOT NULL DEFAULT '0',
INDEX ( `nameid` )
) ENGINE = MYISAM ;

CREATE TABLE `mch_teams` (
`id_team` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 100 ) NOT NULL ,
`nameid` VARCHAR(100) NOT NULL,
`info` TEXT NOT NULL ,
`logo` VARCHAR( 150 ) NOT NULL ,
`wins` INT NOT NULL ,
`category` INT NOT NULL,
`active` TINYINT( 1 ) NOT NULL,
`created` INT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `mch_players` (
  `id_player` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `surname` varchar(60) NOT NULL,
  `bio` text NOT NULL,
  `team` int(11) NOT NULL,
  `nameid` varchar(200) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `created` int(10) NOT NULL,
  `position` tinyint(1) NOT NULL,
  `birth` int(10) NOT NULL,
  PRIMARY KEY (`id_player`),
  KEY `team` (`team`),
  KEY `lastname` (`lastname`)
) ENGINE=MyISAM;

CREATE TABLE `mch_coaches` (
  `id_coach` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `surname` varchar(60) NOT NULL,
  `bio` text NOT NULL,
  `team` int(11) NOT NULL,
  `nameid` varchar(200) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `created` int(10) NOT NULL,
  `charge` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_coach`),
  KEY `team` (`team`),
  KEY `lastname` (`lastname`)
) ENGINE=MyISAM;

CREATE TABLE `mch_champs` (
  `id_champ` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `nameid` varchar(200) NOT NULL,
  `start` int(10) NOT NULL,
  `end` int(10) NOT NULL,
  `description` TEXT NOT NULL,
  `current` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_champ`),
  KEY `nameid` (`nameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `mch_fields` (
  `id_field` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `nameid` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_field`),
  KEY `nameid` (`nameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `mch_role` (
`id_role` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`local` INT NOT NULL ,
`visitor` INT NOT NULL ,
`time` INT( 10 ) NOT NULL ,
`field` INT NOT NULL ,
`champ` INT NOT NULL ,
`category` INT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE  `mch_score` (
`id_score` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`item` INT NOT NULL ,
`local` TINYINT NOT NULL ,
`visitor` TINYINT NOT NULL ,
`other` TINYINT NOT NULL ,
`win` INT( 11 ) NOT NULL,
`comments` VARCHAR( 255 ) NOT NULL,
`champ` INT(11) NOT NULL
) ENGINE = MYISAM ;
