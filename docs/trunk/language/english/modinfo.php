<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('_MI_AH_NAME','Ability Help');
define('_MI_AH_DESC','Module for management online documentation');

define('_MI_AH_INIT','Start');
define('_MI_AH_RESOURCES','Resources');
define('_MI_AH_SECTIONS','Sections');
define('_MI_AH_PAGES','Contents');
define('_MI_AH_REFS','References');
define('_MI_AH_FIGURES','Figures');
define('_MI_AH_EDITS','Modifications');
define('_MI_AH_NEW','Create resource');

//Imagen
define('_MI_AH_IMAGE','Image Size');
define('_MI_AH_DESCIMAGE','Specify the image size as pixels');
define('_MI_AH_REDIMIMAGE','Resizing Type');
define('_MI_AH_CROP','Crop');
define('_MI_AH_REDIM','Resize');
define('_MI_AH_FILE','Image file size');
define('_MI_AH_DESCSIZE','Image size in kilobytes');

//Formato de acceso a información
define('_MI_AH_ACCESS','Method for URLS management');
define('_MI_AH_DESCACCESS','Determines the way Ability Help will manage the URLS to access to the resources.');
define('_MI_AH_PHP','PHP default method');
define('_MI_AH_NUMERIC','Numeric Mode');
define('_MI_AH_ALPHA','Based on Names');

define('_MI_AH_BASEPATH','Base path to access');
define('_MI_AH_BASEPATHD','This path specify the url that will be formed in order to access this section. Generally it will be left as is, however if you modify the htaccess file from you rsite then you can specify a different directory (eg. docs/).');

//Editor de título
define('_MI_AH_TITLE','Section title');
define('_MI_AH_TITLE_DESC','Título con el que se identificará el módulo en la sección frontal');

//Limite de publicaciones recientes a visualizar en página frontal
define('_MI_AH_PUBLIC','Total resources in the home page');
define('_MI_AH_DESCPUBLIC','Total recent resources to be seen in the home page');


//Mostrar publicaciones recientes o populares
define('_MI_AH_PUBLICTYPE','Resouces Type to be show');
define('_MI_AH_DESCPUBLICTYPE','Define the resource type to show. May be recent o popular');
define('_MI_AH_RECENT','Recents');
define('_MI_AH_POPULAR','Populars');
define('_MI_AH_VOTES','Best voted');

//Número de Lecturas Recomendadas a visualizar
define('_MI_AH_RECOMMEND','Featured elements in the home page');
define('_MI_AH_DESCRECOMMEND','Total number of elements that will be showed in home page.');

// Ancho del indice en la información del Recurso
define('_MI_AH_INDEXWIDTH','Index width in pixels');

// Método para las referencias
define('_MI_AH_REFSMETHOD','References Display method');
define('_MI_AH_REFSMETHODBOTTOM','Page footer');
define('_MI_AH_REFSMETHODDIV','Float div');

define('_MI_AH_REFSCOLOR','Reference highlight color');

define('_MI_AH_PRINT','Enable content prints');

define('_MI_AH_CREATERES','Allow new resources creation');
define('_MI_AH_CREATEGROUPS','Groups that can create resources');

//Aprobar automáticamente publicación
define('_MI_AH_APPROVED','Approve resources automatically');

//Direccion de correo para notificacion
define('_MI_AH_MAIL','Email Adress');
define('_MI_AH_DESCMAIL','Email addres whre the notification will be received.');

//Limite de publicaciones en pagina de búsqueda
define('_MI_AH_SEARCH','Search results limit');
define('_MI_AH_DESCSEARCH','Max number of resources to show in search pages');

// Home text
define('_MI_AH_HOMETEXT','Homepage text');
define('_MI_AH_HOMETEXTD','This text will be showed as welcome info to users viewing the home page of module.');

// Modificaciones
define('_MI_AH_MODLIMIT','Latest editions limit');

// BLOQUES
define('_MI_AH_BKRES','Resources');
define('_MI_AH_BKRES_DESC','Block to show resources');
define('_MI_AH_BKINDEX','Section Content');
define('_MI_AH_BKINDEXD','Shows the sections index.');

//Páginas del módulo
define('_MI_AH_INDEX','Home Page');
define('_MI_AH_RESOURCE','Resource Index');
define('_MI_AH_CONTENT','Resource Content');
define('_MI_AH_EDIT','Resource Editing');
define('_MI_AH_PUBLISH','New Resource');
define('_MI_AH_PSEARCH','Search');
