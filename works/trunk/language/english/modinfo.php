<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if(function_exists("load_mod_locale")) load_mod_locale("works");

define('_MI_PW_MODDESC',__('Module to manage professional portfolios', 'works'));

// Configuraciones
define('_MI_PW_URLMODE', __('Use friendly URLs','works'));
define('_MI_PW_URLMODE_DESC', __('By enabling this option the module will manage the urls trough mod_rewrite (Apache Only).','works'));

//Título
define('_MI_PW_TITLE',__('Header Title','works'));
define('_MI_PW_DESCTITLE',__('This title will be show as header of module','works'));

//Tamaño en pixeles de imagen
define('_MI_PW_IMAGEMAIN',__('Size in pixels of the main image','works'));
define('_MI_PW_DESCIMAGEMAIN',__('This values must be specified in the format "width|height". Note the "|".','works'));
define('_MI_PW_IMAGE',__('Size in pixels of the normal image','works'));
define('_MI_PW_DESCIMAGE',_MI_PW_DESCIMAGEMAIN);
define('_MI_PW_THS',__('Size in pixels of the thumbnail','works'));
define('_MI_PW_DESCTHS',_MI_PW_DESCIMAGEMAIN);
define('_MI_PW_THSMAIN',__('Size in pixels fo the thumbnail for main image','works'));
define('_MI_PW_THSMAIN_DESC',__('This value will be used to resize main images to show in the works list.','works').' '._MI_PW_DESCIMAGEMAIN);

//Tipo de redimension
define('_MI_PW_REDIMIMAGE',__('Resizing Method','works'));
define('_MI_PW_CROPTHS',__('Crop Thumbnail','works'));
define('_MI_PW_CROPBIG',__('Crop big image','works'));
define('_MI_PW_CROPBOTH',__('Crop both images','works'));
define('_MI_PW_REDIM',__('Resize','works'));

//Tamaño de archivo de imagen
define('_MI_PW_SIZE',__('Image file size','works'));
define('_MI_PW_DESCSIZE',__('This value must be specified in KB','works'));

//Mostrar el costo del trabajo
define('_MI_PW_COST',__('Show work cost','works'));

//Formato de moneda
define('_MI_PW_FORMATCURRENCY',__('Currency Format','works'));

//Trabajos recientes
define('_MI_PW_RECENT',__('Number of Recent Works','works'));
define('_MI_PW_DESCRECENT',__('The number of recent works that will be shown in the home page','works'));
//Trabajos destacados
define('_MI_PW_FEATURED',__('Number of Featured Works','works'));
define('_MI_PW_DESCFEATURED',__('The number of featured works that will be shown in the home page','works'));

define('_MI_PW_BASEDIR',__('Base dir to friendly url management','works'));

//Otros trabajos
define('_MI_PW_OTHERWORKS',__('Other Works','works'));
define('_MI_PW_DESCOTHERWORKS',__('The type of works that will be shown in the details page','works'));
define('_MI_PW_NOSHOW',__('Disabled','works'));
define('_MI_PW_CATEGO',__('Same Category','works'));
define('_MI_PW_FEATUREDS',__('Featured','works'));
define('_MI_PW_NUMOTHER',__('Number of "Other Works"','works'));
define('_MI_PW_DESCNUMOTHER',__('The number of "Other Works" to show in the section','works'));

define('_MI_PW_WORKS',__('Works','works'));
define('_MI_PW_COMMENTS',__('Comments','works'));

//Páginas del módulo
define('_MI_PW_PINDEX',__('Home Page','works'));
define('_MI_PW_PRECENTS',__('Recent','works'));
define('_MI_PW_PFEATUREDS',__('Fetured','works'));
define('_MI_PW_PCATEGOS',__('Category','works'));
define('_MI_PW_PWORK',__('Work Details','works'));

define('_MI_PW_CUSTOMERSHOW',__('Show customer information','works'));
define('_MI_PW_CUSTOMERSHOWD',__('When this option is enabled you will see the option to provide customer name and customer comment in works form.','works'));
define('_MI_PW_WEBSHOW', __('Show web site information','works'));
define('_MI_PW_WEBSHOWD',__('When this option is enabled you will see the option to provide web site name and url in works form.','works'));

// Version 1.0
define('_MI_PW_BKCATS',__('Categories','works'));

