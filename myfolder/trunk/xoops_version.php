<?php
/*******************************************************************
* $Id$    *
* -------------------------------------------------------------    *
* RMSOFT MyFolder 1.0                                              *
* Módulo para el manejo de un portafolio profesional               *
* CopyRight © 2006. Red México Soft                                *
* Autor: BitC3R0                                                   *
* http://www.redmexico.com.mx                                      *
* http://www.xoops-mexico.net                                      *
* --------------------------------------------                     *
* This program is free software; you can redistribute it and/or    *
* modify it under the terms of the GNU General Public License as   *
* published by the Free Software Foundation; either version 2 of   *
* the License, or (at your option) any later version.              *
*                                                                  *
* This program is distributed in the hope that it will be useful,  *
* but WITHOUT ANY WARRANTY; without even the implied warranty of   *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the     *
* GNU General Public License for more details.                     *
*                                                                  *
* You should have received a copy of the GNU General Public        *
* License along with this program; if not, write to the Free       *
* Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,   *
* MA 02111-1307 USA                                                *
*                                                                  *
* -------------------------------------------------------------    *
* xoops_version.php:                                               *
* Archivo instalador y configurador del módulo                     *
* -------------------------------------------------------------    *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.3.1                                                  *
* @modificado: 24/05/2006 12:52:56 a.m.                            *
*******************************************************************/

$modversion['name'] = "RMSOFT \nMyFolder";
$modversion['version'] = "1.2";
$modversion['description'] = _MI_RMMF_MODDESC;
$modversion['author'] = "BitC3R0 <http://www.redmexico.com.mx>";
$modversion['credits'] = "Red México Soft";
$modversion['help'] = "";
$modversion['license'] = "";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "rmmf";

//Archivo SQL
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu Principal
$modversion['hasMain'] = 1;

//Tablas creadas
$modversion['tables'][0] = "rmmf_categos";
$modversion['tables'][1] = "rmmf_works";
$modversion['tables'][2] = "rmmf_images";

// Templates del Modulo
$modversion['templates'][1]['file'] = 'rmmf_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'rmmf_categos.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'rmmf_view.html';
$modversion['templates'][3]['description'] = '';

// Blocks
$modversion['blocks'][1]['file'] = "rmmf_recent.php";
$modversion['blocks'][1]['name'] = _MI_RMMF_BKRECENT;
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "rmmf_bk_recent";
$modversion['blocks'][1]['edit_func'] = "rmmf_bk_recent_edit";
$modversion['blocks'][1]['options'] = "3";
$modversion['blocks'][1]['template'] = 'rmmf_bk_recent.html';

$modversion['blocks'][2]['file'] = "rmmf_recent.php";
$modversion['blocks'][2]['name'] = _MI_RMMF_BKCOMMENTS;
$modversion['blocks'][2]['description'] = "";
$modversion['blocks'][2]['show_func'] = "rmmf_bk_comments";
$modversion['blocks'][2]['edit_func'] = "rmmf_bk_comments_edit";
$modversion['blocks'][2]['options'] = "2";
$modversion['blocks'][2]['template'] = 'rmmf_bk_comments.html';

$modversion['blocks'][3]['file'] = "rmmf_recent.php";
$modversion['blocks'][3]['name'] = _MI_RMMF_BKFATURED;
$modversion['blocks'][3]['description'] = "";
$modversion['blocks'][3]['show_func'] = "rmmf_bk_featured";
$modversion['blocks'][3]['edit_func'] = "rmmf_bk_featured_edit";
$modversion['blocks'][3]['options'] = "3";
$modversion['blocks'][3]['template'] = 'rmmf_bk_featured.html';

$modversion['blocks'][4]['file'] = "rmmf_recent.php";
$modversion['blocks'][4]['name'] = _MI_RMMF_BKRANDOM;
$modversion['blocks'][4]['description'] = "";
$modversion['blocks'][4]['show_func'] = "rmmf_bk_random";
$modversion['blocks'][4]['edit_func'] = "rmmf_bk_random_edit";
$modversion['blocks'][4]['options'] = "2";
$modversion['blocks'][4]['template'] = 'rmmf_bk_random.html';

// Tipo de Editor
$modversion['config'][1]['name'] = 'editor';
$modversion['config'][1]['title'] = '_MI_RMMF_EDITOR';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'select';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = "dhtml";
$modversion['config'][1]['options'] = array(
											_MI_RMMF_FORM_DHTML=>'dhtml',
											_MI_RMMF_FORM_COMPACT=>'textarea',
											_MI_RMMF_FORM_SPAW=>'spaw',
											_MI_RMMF_FORM_HTMLAREA=>'htmlarea',
											_MI_RMMF_FORM_KOIVI=>'koivi',
											_MI_RMMF_FORM_FCK=>'fck'
											);

$modversion['config'][2]['name'] = 'dates';
$modversion['config'][2]['title'] = '_MI_RMMF_FORMATDATE';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = "d/m/Y";

$modversion['config'][3]['name'] = 'imgw';
$modversion['config'][3]['title'] = '_MI_RMMF_IMGW';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 450;

$modversion['config'][4]['name'] = 'imgh';
$modversion['config'][4]['title'] = '_MI_RMMF_IMGH';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 450;

$modversion['config'][5]['name'] = 'thw';
$modversion['config'][5]['title'] = '_MI_RMMF_THW';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 100;

$modversion['config'][6]['name'] = 'thh';
$modversion['config'][6]['title'] = '_MI_RMMF_THH';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 100;

$modversion['config'][7]['name'] = 'imgnum';
$modversion['config'][7]['title'] = '_MI_RMMF_IMGSNUM';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'select';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 5;
$modversion['config'][7]['options'] = array('1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'10'=>10);

$modversion['config'][8]['name'] = 'storedir';
$modversion['config'][8]['title'] = '_MI_RMMF_STORE';
$modversion['config'][8]['description'] = '_MI_RMMF_STORE_DESC';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = XOOPS_ROOT_PATH . '/modules/rmmf/uploads/';

$modversion['config'][9]['name'] = 'title';
$modversion['config'][9]['title'] = '_MI_RMMF_TITLE';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'text';
$modversion['config'][9]['default'] = 'RMSOFT MyFolder';

$modversion['config'][10]['name'] = 'recents';
$modversion['config'][10]['title'] = '_MI_RMMF_RECENTSNUM';
$modversion['config'][10]['description'] = '_MI_RMMF_RECENTSNUM_DESC';
$modversion['config'][10]['formtype'] = 'textbox';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 5;

$modversion['config'][11]['name'] = 'featured';
$modversion['config'][11]['title'] = '_MI_RMMF_FEATUREDNUM';
$modversion['config'][11]['description'] = '';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = 5;

$modversion['config'][12]['name'] = 'results';
$modversion['config'][12]['title'] = '_MI_RMMF_WORKSNUM';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype'] = 'textbox';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 5;

?>