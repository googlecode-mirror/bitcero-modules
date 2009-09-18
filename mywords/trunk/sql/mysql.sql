CREATE TABLE `mw_categories` (
  `id_cat` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `shortname` varchar(150) NOT NULL,
  `parent` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `posts` int(11) NOT NULL,
  PRIMARY KEY  (`id_cat`),
  KEY `shortname` (`shortname`),
  KEY `parent` (`parent`)
) TYPE=MyISAM ;

CREATE TABLE `mw_catpost` (
  `post` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  KEY `post` (`post`),
  KEY `cat` (`cat`)
) TYPE=MyISAM;

CREATE TABLE `mw_editors` (
  `uid` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  `categos` text NOT NULL,
  UNIQUE KEY `uid` (`uid`)
) TYPE=MyISAM;

CREATE TABLE `mw_posts` (
  `id_post` int(11) NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `titulo_amigo` varchar(255) NOT NULL,
  `autor` int(11) NOT NULL,
  `autorname` varchar(50) NOT NULL,
  `fecha` int(11) NOT NULL,
  `modificado` int(11) NOT NULL,
  `texto` longtext NOT NULL,
  `estado` tinyint(1) NOT NULL default '0',
  `comentarios` int(11) NOT NULL default '0',
  `trackbacks` int(11) NOT NULL default '0',
  `toping` text,
  `pinged` text,
  `allowpings` tinyint(1) NOT NULL default '1',
  `lecturas` int(11) NOT NULL default '0',
  `excerpt` text NOT NULL,
  `aprovado` tinyint(1) NOT NULL default '0',
  `blockimg` varchar(255) NOT NULL,
  `dohtml` tinyint(1) NOT NULL default '0',
  `doxcode` tinyint(1) NOT NULL default '1',
  `doimage` tinyint(1) NOT NULL default '1',
  `dobr` tinyint(1) NOT NULL default '1',
  `dosmiley` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_post`),
  KEY `titulo_amigo` (`titulo_amigo`),
  KEY `autor` (`autor`),
  KEY `autorname` (`autorname`)
) TYPE=MyISAM ;

CREATE TABLE `mw_trackbacks` (
  `id_t` int(11) NOT NULL auto_increment,
  `fecha` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `blog_name` varchar(150) NOT NULL,
  `excerpt` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `post` int(11) NOT NULL,
  PRIMARY KEY  (`id_t`),
  KEY `post` (`post`),
  KEY `url` (`url`)
) TYPE=MyISAM;

CREATE TABLE `mw_bookmarks` (
`id_book` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 60 ) NOT NULL ,
`alt` VARCHAR( 150 ) NOT NULL ,
`url` VARCHAR( 255 ) NOT NULL ,
`icon` VARCHAR( 100 ) NOT NULL ,
`active` TINYINT( 1 ) NOT NULL DEFAULT '1'
) ENGINE = MYISAM ;

INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(1, 'BlinkList.com', 'Agregar a BlinkList.com!', 'http://blinklist.com/blink?u={URL}&t={TITLE}&d={DESC}', 'blinklist.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(2, 'Delicious', 'Add to Del.icio.us!', 'http://delicious.com/save?jump=yes&url={URL}&title={TITLE}&notes={DESC}', 'delicious.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(3, 'Digg', 'Digg It!', 'http://digg.com/submit?phase=2&url={URL}&title={TITLE}', 'digg.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(4, 'Diigo', 'Add to Diigo!', 'http://www.diigo.com/post?url={URL}&title={TITLE}&desc={DESC}', 'diigo.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(5, 'FaceBook', 'Add to FaceBook!', 'http://www.facebook.com/share.php?u={URL}&t={TITLE}', 'facebook.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(6, 'Furl', 'Add to Furl!', 'http://www.furl.net/items/new?t={TITLE}&u={URL}&r=&v=1&c=', 'furl.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(7, 'Mister Wong', 'Agregar a Mister Wong!', 'http://www.mister-wong.com/add_url/?bm_url={URL}&bm_description={TITLE}&bm_notice={DESC}', 'misterwong.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(8, 'Reddit.com', 'Agregar a Reddit!', 'http://www.reddit.com/submit?url={URL}&title={TITLE}', 'reddit.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(9, 'Simpy', 'Agregar a Simpy!', 'http://www.simpy.com/simpy/LinkAdd.do?href={URL}', 'simpy.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(10, 'StumbleUpon', 'Agregar a StumbleUpon!', 'http://www.stumbleupon.com/submit?url={URL}&title={TITLE}', 'stumbleupon.png', 1);

CREATE TABLE `mw_meta` (
`name` VARCHAR( 50 ) NOT NULL ,
`value` TEXT NOT NULL ,
`post` INT NOT NULL ,
INDEX ( `name` , `post` )
) ENGINE = MYISAM ;

CREATE TABLE `mw_tags` (
`id_tag` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tag` VARCHAR( 60 ) NOT NULL ,
`shortname` VARCHAR( 60 ) NOT NULL ,
`posts` INT(11) NOT NULL,
INDEX ( `shortname` )
) ENGINE = MYISAM ;