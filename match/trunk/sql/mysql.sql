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
