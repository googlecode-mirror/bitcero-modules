<?php
// $Id$
// --------------------------------------------------------
// The Coach
// Manejo de Integrantes de Equipos Deportivos
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2008 Red México

define('_MI_PW_MODDESC','Module to manage professional portfolios');

define('_MI_PW_MHOME', 'Home');
define('_MI_PW_MCATS', 'Categories');
define('_MI_PW_MWORKS', 'Works');
define('_MI_PW_MCLIENTS', 'Clients');
define('_MI_PW_MTYPECLIENT', 'Client Types');

// Configuraciones
define('_MI_PW_URLMODE','Use friendly URLs');
define('_MI_PW_URLMODE_DESC','By enabling thios option the module will manage the urls trough mod_rewrite (Apache Only).');

//Título
define('_MI_PW_TITLE','Header Title');
define('_MI_PW_DESCTITLE','This title will be show as header of module');

//Tamaño en pixeles de imagen
define('_MI_PW_IMAGEMAIN','Size in pixels of the main image');
define('_MI_PW_DESCIMAGEMAIN','This values must be specified in the format "width|height". Note the "|".');
define('_MI_PW_IMAGE','Size in pixels of the normal image');
define('_MI_PW_DESCIMAGE',_MI_PW_DESCIMAGEMAIN);
define('_MI_PW_THS','Size in pixels of the thumbnail');
define('_MI_PW_DESCTHS',_MI_PW_DESCIMAGEMAIN);
define('_MI_PW_THSMAIN','Size in pixels fo the thumbnail for main image');
define('_MI_PW_THSMAIN_DESC','This value will be used to resize main images to show in the works list. '._MI_PW_DESCIMAGEMAIN);

//Tipo de redimension
define('_MI_PW_REDIMIMAGE','Resizing Method');
define('_MI_PW_CROPTHS','Crop Thumbnail');
define('_MI_PW_CROPBIG','Crop big image');
define('_MI_PW_CROPBOTH','Crop both images');
define('_MI_PW_REDIM','Resize');

//Tamaño de archivo de imagen
define('_MI_PW_SIZE','Image file size');
define('_MI_PW_DESCSIZE','This value must be specified in KB');

//Mostrar el costo del trabajo
define('_MI_PW_COST','Show work cost');

//Formato de moneda
define('_MI_PW_FORMATCURRENCY','Currency Format');

//Trabajos recientes
define('_MI_PW_RECENT','Number of Recent Works');
define('_MI_PW_DESCRECENT','The number of recent works that will be shown in the home page');
//Trabajos destacados
define('_MI_PW_FEATURED','Number of Featured Works');
define('_MI_PW_DESCFEATURED','The number of featured works that will be shown in the home page');

define('_MI_PW_BASEDIR','Base dir to friendly url management');

//Otros trabajos
define('_MI_PW_OTHERWORKS','Other Works');
define('_MI_PW_DESCOTHERWORKS','The type of works that will be shown in the details page');
define('_MI_PW_CATEGO','Same Category');
define('_MI_PW_FEATUREDS','Featured');
define('_MI_PW_NUMOTHER','Number of "Other Works"');
define('_MI_PW_DESCNUMOTHER','The number of "Other Works" to show in the section');

define('_MI_PW_WORKS','Works');
define('_MI_PW_COMMENTS','Comments');

//Páginas del módulo
define('_MI_PW_PINDEX','Home Page');
define('_MI_PW_PRECENTS','Recent');
define('_MI_PW_PFEATUREDS','Fetured');
define('_MI_PW_PCATEGOS','Category');
define('_MI_PW_PWORK','Work Details');

// Version 1.0
define('_MI_PW_BKCATS','Categories');

?>
