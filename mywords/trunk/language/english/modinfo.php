<?php
// $Id: modinfo.php 53 2009-09-18 06:02:06Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('_MI_MW_DESC','Modules for the publishing and management of news style blog');

define('_MI_MW_AMENU1','Module Status');
define('_MI_MW_AMENU2','Categories');
define('_MI_MW_AMENU3','Articles');
define('_MI_MW_AMENU4','Editors');
define('_MI_MW_AMENU5','Bookmarks');
                                      
// Menu principal
define('_MI_MW_SEND', 'Send Article');

# Permalinks
define('_MI_MW_PERMAFORMAT','Links Format');
define('_MI_MW_PERMA_DESC','Determines the way the links in the module will be shown and will be processed.');
define('_MI_MW_PERMA_DEF','Default');
define('_MI_MW_PERMA_DATE','Basado en Fecha y Nombre');
define('_MI_MW_PERMA_NUMS','Numérico');
// Base path for permalinks
define('_MI_MW_BASEPATH','Base Path');
define('_MI_MW_BASEPATHD','This path is used when the links format has been stablished as based in dates or numbers.');
// Widget tags
define('_MI_MW_WIDGETTAGS','Number of tags on admin widget');
// Posts number per page
define('_MI_MW_PPP','Posts per page');
define('_MI_MW_CSS','Use CSS file');
define('_MI_MW_CSSFILE','CSS file to use');
define('_MI_MW_SHOWNAV', 'Show navigation bar between posts');
// Blog name
define('_MI_MW_BLOGNAME','Blog name (section name)');
define('_MI_MW_BLOGNAMED','This name will be used in trackbacks.');

# Imágenes para los bloques
define('_MI_MW_BIMGSIZE','Image Size for the Blocks');
define('_MI_MW_BIMGSIZE_DESC','The specified image in the article will be resized with this sizes. Format: "width|height"');
define('_MI_MW_DEFIMG','Defaul Image for the articles in blocks');
define('_MI_MW_DEFIMG_DESC','When the "graphic" mode is enable in the "Recent Articles" blocks this image will use when there is not a specified one for the article');

define('_MI_MW_FILESIZE','Maximum file size');

// BLOQUES
define('_MI_MW_BKCATEGOS','Categories');
define('_MI_MW_BKRECENT','Recent Articles');
define('_MI_MW_BKCOMMENTS','Recent Comments');

define('_MI_MW_RSSNAME','Posts Syndication');
define('_MI_MW_RSSNAMECAT','Articles Syndication in %s');
define('_MI_MW_RSSDESC','Syndication Description');
define('_MI_MW_RSSALL','All Recent Posts');
define('_MI_MW_RSSALLDESC','Show all recent posts');

// Subpáginas
define('_MI_MW_SPINDEX','Home Page');
define('_MI_MW_SPPOST','Post');
define('_MI_MW_SPCATEGO','Category');
define('_MI_MW_SPAUTHOR','Author');
define('_MI_MW_SPSUBMIT','Post Article');
