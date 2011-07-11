CREATE TABLE `shop_categories` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `shortname` varchar(150) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL
  PRIMARY KEY (`id_cat`),
  KEY `shortname` (`shortname`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `shop_catprods` (
  `product` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  KEY `product` (`product`),
  KEY `cat` (`cat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `shop_products` (
`id_product` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 100 ) NOT NULL ,
`nameid` VARCHAR( 100 ) NOT NULL ,
`description` TEXT NOT NULL ,
`price` DECIMAL( 6, 2 ) NOT NULL ,
`type` TINYINT( 1 ) NOT NULL ,
`available` TINYINT( 1 ) NOT NULL ,
`image` VARCHAR( 200 ) NOT NULL ,
`created` INT( 10 ) NOT NULL ,
`modified` INT( 10 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE `shop_meta` (
`name` VARCHAR( 50 ) NOT NULL ,
`value` TEXT NOT NULL ,
`product` INT NOT NULL ,
INDEX ( `name` , `product` )
) ENGINE = MYISAM;

CREATE TABLE shop_images (
`id_image` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 100 ) NOT NULL ,
`file` VARCHAR( 50 ) NOT NULL ,
`product` INT NOT NULL ,
`description` TEXT NOT NULL
) ENGINE = MYISAM ;

