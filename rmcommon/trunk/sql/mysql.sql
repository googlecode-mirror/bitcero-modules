CREATE TABLE `api_events` (
  `id_event` bigint(20) NOT NULL auto_increment,
  `event` varchar(100) collate latin1_general_ci NOT NULL,
  `object` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  PRIMARY KEY  (`id_event`),
  KEY `object` (`object`),
  KEY `event` (`event`),
  KEY `eid` (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `et_api_methods`
-- 

CREATE TABLE `api_methods` (
  `method` varchar(100) collate latin1_general_ci NOT NULL,
  `event` bigint(20) NOT NULL,
  `file` varchar(150) collate latin1_general_ci NOT NULL,
  `object` int(11) NOT NULL default '0',
  PRIMARY KEY  (`method`),
  KEY `event` (`event`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `et_api_objects`
-- 

CREATE TABLE `api_objects` (
  `id_object` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  `type` varchar(20) collate latin1_general_ci NOT NULL,
  `path` varchar(150) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_object`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `rmc_img_cats` (
  `id_cat` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL default 'active',
  `width` smallint(6) NOT NULL,
  `height` smallint(6) NOT NULL,
  `groups` text NOT NULL,
  `sizes` text NOT NULL,
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM;