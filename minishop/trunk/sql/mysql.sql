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