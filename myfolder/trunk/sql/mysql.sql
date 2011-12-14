CREATE TABLE `rmmf_categos` (
  `id_cat` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default '0',
  `nombre` varchar(150) NOT NULL default '',
  `desc` text NOT NULL,
  `orden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `jd3371_rmmf_images`
-- 

CREATE TABLE `rmmf_images` (
  `id_img` int(11) NOT NULL auto_increment,
  `archivo` varchar(200) NOT NULL default '',
  `work` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_img`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `jd3371_rmmf_works`
-- 

CREATE TABLE `rmmf_works` (
  `id_w` int(11) NOT NULL auto_increment,
  `titulo` varchar(200) NOT NULL default '',
  `short` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `catego` int(11) NOT NULL default '0',
  `cliente` varchar(255) NOT NULL default '',
  `comentario` text NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `resaltado` tinyint(1) NOT NULL default '0',
  `imagen` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_w`)
) ENGINE=MyISAM;
