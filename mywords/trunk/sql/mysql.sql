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
) ENGINE=MyISAM ;

CREATE TABLE `mw_catpost` (
  `post` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  KEY `post` (`post`),
  KEY `cat` (`cat`)
) ENGINE=MyISAM;

CREATE TABLE `mw_editors` (
  `id_editor` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `shortname` varchar(150) NOT NULL,
  `bio` text NOT NULL,
  `privileges` text NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_editor`),
  UNIQUE KEY `uid` (`uid`),
  KEY `shortname` (`shortname`)
) ENGINE=MyISAM;

CREATE TABLE `mw_posts` (
  `id_post` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `shortname` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `status` varchar(20) NOT NULL,
  `visibility` varchar(20) NOT NULL,
  `schedule` int(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `comments` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `comstatus` varchar(10) NOT NULL,
  `pingstatus` varchar(10) NOT NULL,
  `authorname` varchar(50) NOT NULL,
  `pubdate` int(10) NOT NULL,
  `reads` int(11) NOT NULL,
  `toping` TEXT NOT NULL,
  `pinged` TEXT NOT NULL,
  `image` varchar(20) NOT NULL,
  PRIMARY KEY  (`id_post`),
  KEY `shortname` (`shortname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `mw_trackbacks` (
  `id_t` int(11) NOT NULL auto_increment,
  `date` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `blog_name` varchar(150) NOT NULL,
  `excerpt` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `post` int(11) NOT NULL,
  PRIMARY KEY  (`id_t`),
  KEY `post` (`post`),
  KEY `url` (`url`)
) ENGINE=MyISAM;

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
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(5, 'FaceBook', 'Add to FaceBook!', 'http://www.facebook.com/share.php?u={URL}&t={TITLE}', 'facebook.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(6, 'Furl', 'Add to Furl!', 'http://www.furl.net/items/new?t={TITLE}&u={URL}&r=&v=1&c=', 'furl.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(8, 'Reddit.com', 'Agregar a Reddit!', 'http://www.reddit.com/submit?url={URL}&title={TITLE}', 'reddit.png', 1);
INSERT INTO `mw_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES(10, 'StumbleUpon', 'Agregar a StumbleUpon!', 'http://www.stumbleupon.com/submit?url={URL}&title={TITLE}', 'stumbleupon.png', 1);

CREATE TABLE `mw_meta` (
`id_meta` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
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

CREATE TABLE `mw_tagspost` (
`post` INT NOT NULL ,
`tag` INT NOT NULL ,
INDEX ( `post` , `tag` )
) ENGINE = MYISAM ;