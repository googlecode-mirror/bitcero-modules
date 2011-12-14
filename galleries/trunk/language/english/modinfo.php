<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('galleries');

// Titulo de la Sección
define('_MI_GS_SECTIONTITLE', __('Section title','galleries'));

//Minimo número de caracteres de etiqueta
define('_MI_GS_MINTAG', __('Tag min char length','galleries'));

//Maximo número de caracteres de etiqueta
define('_MI_GS_MAXTAG', __('Tags max char length','galleries'));

//Permitir a todos los usuarios subir imágenes
define('_MI_GS_ALLOWEDALLS', __('All users can upload pictures','galleries'));

//Grupos que pueden subir imágenes
define('_MI_GS_GROUPSPICS', __('Groups allowed to upload pictures','galleries'));

//Url amigables
define('_MI_GS_URLMODE', __('Enable friendly URLs','galleries'));
define('_MI_GS_URLBASE', __('Base path for URLs', 'galleries'));

define('_MI_GS_NAVIMAGES', __('Show navigation bar as images', 'galleries'));
define('_MI_GS_NAVIMAGESD', __('When this option is enabled MyGalleries will show navigation bar in Picture Details using Next and Previous images.', 'galleries'));
define('_MI_GS_NAVIMAGESNUM', __('Number of images in navigation', 'galleries'));
define('_MI_GS_NAVIMAGESNUMD', __('Specify the number of images to show in navigation bar. This value is used for previous and next images.', 'galleries'));

//directorio de imágenes
define('_MI_GS_STOREDIR', __('Directory where pictures will be stored','galleries'));

//Cuota
define('_MI_GS_QUOTA', __('Users default quota','galleries'));
define('_MI_GS_DESCQUOTA', __('This value must be specified in megabytes','galleries'));

//Tamaño en pixeles de imagen
define('_MI_GS_IMAGE', __('Size in pixels for normal pictures','galleries'));
define('_MI_GS_IMAGE_DESC', __('Specify the size of the normal image. Values must be specified in format width|height.','galleries'));
define('_MI_GS_THS', __('Size in pixels for thumbnail picture.','galleries'));
define('_MI_GS_THS_DESC', __('Specify the size for thumbnail image. Values must be specified in format width|height.','galleries'));

//Tipo de redimension
define('_MI_GS_REDIMIMAGE', __('Resizing type','galleries'));
define('_MI_GS_CROPTHS', __('Crop thumbnail','galleries'));
define('_MI_GS_CROPBIG', __('Crop big image','galleries'));
define('_MI_GS_CROPBOTH', __('Crop both','galleries'));
define('_MI_GS_REDIM', __('Only resize','galleries'));

//Tamaño de archivo de imagen
define('_MI_GS_SIZE', __('Max file size for pictures','galleries'));
define('_MI_GS_DESCSIZE', __('Specify this value in kilobytes','galleries'));

// Almacenar tamñso orginales
define('_MI_GS_SAVEORIGINAL', __('Store original pictures','galleries'));
define('_MI_GS_DELORIGINAL', __('Maintain original pictures after deleting the picture from database','galleries'));

// Numero de imágenes recientes
define('_MI_GS_LASTNUM', __('Number of recent pictures in home page','galleries'));
define('_MI_GS_SETSNUM', __('Number of recent albums in home page','galleries'));
define('_MI_GS_SETSNUMIMGS',__('Images number for recent albums', 'galleries'));
define('_MI_GS_SETSNUMIMGSD',__('Specify the number of images that will be shown for each recent album in home page.', 'galleries'));

// Quickview
define('_MI_GS_QUICKVIEW',__('Enable quick view', 'galleries'));
define('_MI_GS_QUICKVIEWD',__('When quick view is enabled, MyGalleries will show an option on every image that can show the big image using lightbox plugin for Common Utilities.', 'galleries'));

//Número de albumes
define('_MI_GS_LIMITSETS', __('Number of albums in albums list','galleries'));

// Formato de imágenes de usuarios
define('_MI_GS_USRIMGFORMATMODE', __('Modify picture format for user pictures list','galleries'));
define('_MI_GS_USRIMGFORMAT', __('User pictures format specifications','galleries'));
define('_MI_GS_USRIMGFORMAT_DESC', __('Values must be specified according next format: Crop(must be 1 or 0)|Width|Height|Results(per page)|Columns|Description|Format name','galleries'));

//Número de Imágenes
define('_MI_GS_LIMITPICS', __('Number of images in images list','galleries'));

//Número de etiquetas
define('_MI_GS_NUMTAGS', __('Number of tags','galleries'));
define('_MI_GS_HITSTAGS', __('Min hits to show tags','galleries'));
define('_MI_GS_FONTTAGS', __('Max font size for top tags','galleries'));
define('_MI_GS_DESCFONTTAGS', __('Size in points','galleries'));

//Formato de imágenes de etiquetas
define('_MI_GS_NUMIMGSTAGS', __('Number of images in tagged pictures','galleries'));

// Formato de imágenes de albumes
define('_MI_GS_SETFORMATMODE', __('Modify picture format for album pictures list','galleries'));
define('_MI_GS_SETFORMAT', __('Format specifications for albums images','galleries'));
define('_MI_GS_SETBIGFORMAT', __('Format specifications for large albums images','galleries'));

// POstalaes
define('_MI_GS_POSTCARDS', __('Enable postcards','galleries'));

// Formato de imágenes de albumes
define('_MI_GS_NUMSEARCH', __('Number of images in search listing','galleries'));
define('_MI_GS_SEARCHFORMATMODE', __('Modify the format for pictures in search listing','galleries'));
define('_MI_GS_SEARCHFORMAT', __('Format specifications for pictures in search listing','galleries'));

//Duración en días de las postales
define('_MI_GS_TIMEPOSTCARD', __('Days to maintain postcards','galleries'));

// Servicio RSS
define('_MI_GS_RSSNAME', __('RSS of Pictures','galleries'));
define('_MI_GS_RSSDESC', __('Subscribe to updates on our photos via RSS.','galleries'));
define('_MI_GS_RSSIMGS', __('Recent Pictures','galleries'));
define('_MI_GS_RSSIMGS_DESC', __('Last pictures added to our galleries.','galleries'));
define('_MI_GS_RSSSETS', __('Recent Albums','galleries'));
define('_MI_GS_RSSSETS_DESC', __('The newest album created by users of our galleries.','galleries'));
define('_MI_GS_RSSUSRS', __('Recent Users','galleries'));
define('_MI_GS_RSSUSRS_DESC', __('Displays list of recently registered users in the galleries.','galleries'));

define('_MI_GS_RSSIMGDESC', __('Description: %s<br />Created on: %s | User: %s | %u Hits','galleries'));
$gu = XOOPS_URL.'/modules/galleries/images';
define('_MI_GS_RSSSETDESC', __('By: %s<br />Created on: %s<br />Pictures: %u','galleries'));

// Bloques
define('_MI_GS_BKPICS', __('Pictures','galleries'));
define('_MI_GS_BKSETS', __('Albums','galleries'));

//Páginas del módulo
define('_MI_GS_INDEX', __('Home page','galleries'));
define('_MI_GS_USERPICS', __('User pictures','galleries'));
define('_MI_GS_PICSDETAILS', __('Picture details','galleries'));
define('_MI_GS_USERSET', __('User album','galleries'));
define('_MI_GS_CPANEL', __('Control panel','galleries'));
define('_MI_GS_EXPLORESETS', __('Explore albums','galleries'));
define('_MI_GS_EXPLOREPICS', __('Explore pictures','galleries'));
define('_MI_GS_TAGS', __('Popular tags','galleries'));
define('_MI_GS_EXPLORETAGS', __('Explore tags','galleries'));
define('_MI_GS_SUBMIT', __('Upload pictures','galleries'));
define('_MI_GS_SEARCH', __('Search','galleries'));
