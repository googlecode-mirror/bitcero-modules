CREATE TABLE `contactme` (
`id_msg` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`subject` VARCHAR( 150 ) NOT NULL ,
`ip` VARCHAR( 50 ) NOT NULL ,
`email` VARCHAR( 100 ) NOT NULL ,
`name` VARCHAR( 100 ) NOT NULL ,
`org` VARCHAR( 100 ) NOT NULL ,
`body` TEXT NOT NULL ,
`phone` VARCHAR( 50 ) NOT NULL ,
`register` TINYINT( 1 ) NOT NULL DEFAULT '0',
`xuid` INT(11) NOT NULL DEFAULT '0',
`date` INT( 10 ) NOT NULL
) ENGINE = MYISAM ;