<?php
// $Id$
// --------------------------------------------------------
// The Coach
// Manejo de Integrantes de Equipos Deportivos
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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
// @copyright: 2005 - 2007 Red México

$modversion['name'] = "The Coach";
$modversion['description'] = _MI_TC_MODDESC;
$modversion['version'] = "1.2";
$modversion['rmversion'] = array('number'=>0,'revision'=>1,'status'=>-3,'name'=>'The Coach');
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['author'] = "BitC3R0";
$modversion['authorlink'] = "mailto:bitc3r0@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['url'] = 'www.exmsystem.net';
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.redmexico.com.mx/modules/ahelp/";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "team";
$modversion['icon48'] = "images/logo.png";
$modversion['deflang'] = 'spanish';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['hasMain'] = 1;

// Tables
$modversion['tables'][0] = "coach_teams";
$modversion['tables'][1] = "coach_categos";
$modversion['tables'][2] = "coach_coachs";
$modversion['tables'][3] = "coach_players";
$modversion['tables'][4] = "coach_teamcoach";

$modversion['sqlfile']['mysql'] = "mysql/mysql.sql";

// Plantillas
$modversion['templates'][1]['file'] = 'admin/coach_categories.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'admin/coach_coachs.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'admin/coach_teams.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'admin/coach_players.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'coach_index.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'coach_header.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'coach_team.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'coach_player.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'coach_category.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'coach_coach.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'coach_comments.html';
$modversion['templates'][11]['description'] = '';

// COnfiguraciones
$modversion['config'][1]['name'] = 'filesize';
$modversion['config'][1]['title'] = '_MI_TC_FILESIZE';
$modversion['config'][1]['description'] = '_MI_TC_FILESIZE_DESC';
$modversion['config'][1]['formtype'] = 'text';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 500;
$modversion['config'][1]['size'] = 5;

// Tipo de Redimensión
$modversion['config'][2]['name'] = 'resize_method';
$modversion['config'][2]['title'] = '_MI_TC_RESIZEMETHOD';
$modversion['config'][2]['description'] = '_MI_TC_RESIZEMETHOD_DESC';
$modversion['config'][2]['formtype'] = 'radio';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;
$modversion['config'][2]['options'] = array(_MI_TC_NORMAL=>0, _MI_TC_CROP=>1);

// Tamaño de miniaturas
$modversion['config'][3]['name'] = 'th_size';
$modversion['config'][3]['title'] = '_MI_TC_THSIZE';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'text';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 80;
$modversion['config'][3]['size'] = 5;

// Tamaño de las imágenes normales
$modversion['config'][4]['name'] = 'img_size';
$modversion['config'][4]['title'] = '_MI_TC_IMGSIZE';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'text';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 500;
$modversion['config'][4]['size'] = 5;

// Método para las urls
$modversion['config'][5]['name'] = 'urlmode';
$modversion['config'][5]['title'] = '_MI_TC_URLMOD';
$modversion['config'][5]['description'] = '_MI_TC_URLMOD_DESC';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 0;

// Email para el envío de comentarios
$modversion['config'][6]['name'] = 'email';
$modversion['config'][6]['title'] = '_MI_TC_EMAIL';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'email';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = $xoopsConfig['adminmail'];
$modversion['config'][6]['size'] = 50;

// Explicacion para comentarios
$modversion['config'][7]['name'] = 'comment';
$modversion['config'][7]['title'] = '_MI_TC_COMMENTTEXT';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'editor';
$modversion['config'][7]['valuetype'] = 'text';
$modversion['config'][7]['default'] = '';
$modversion['config'][7]['size'] = '90%';

// Bloques Jugadores
$modversion['blocks'][1]['file'] = "block_players.php";
$modversion['blocks'][1]['name'] = '_MI_TC_BKPLAYERS';
$modversion['blocks'][1]['description'] = '';
$modversion['blocks'][1]['show_func'] = "tc_block_players";
$modversion['blocks'][1]['edit_func'] = "tc_block_players_edit";
$modversion['blocks'][1]['template'] = 'coach_players.html';
$modversion['blocks'][1]['options'] = array(0,1,1);

?>