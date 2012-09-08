CREATE TABLE `shop_categories` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `shortname` varchar(150) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
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

CREATE TABLE `shop_images` (
  `id_image` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `file` varchar(50) NOT NULL,
  `product` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_image`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `shop_meta` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `product` int(11) NOT NULL,
  KEY `name` (`name`,`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `shop_products` (
  `id_product` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nameid` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `buy` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `image` varchar(200) NOT NULL,
  `created` int(10) NOT NULL,
  `modified` int(10) NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
