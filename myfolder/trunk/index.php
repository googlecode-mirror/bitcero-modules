<?php
/*******************************************************************
* $Id$            *
* -----------------------------------------------------            *
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
* -----------------------------------------------------            *
* index.php:                                                       *
* Archivo inicial del módulo                                       *
* -----------------------------------------------------            *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.3                                                  *
* @modificado: 24/05/2006 12:51:59 a.m.                            *
*******************************************************************/

include 'header.php';

$xoopsOption['template_main'] = "rmmf_index.html";

// Cargo las categoras
$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_categos")." WHERE parent='0' ORDER BY orden");
while ($row=$db->fetchArray($result)){
	$tpl->append('categos', array('id'=>$row['id_cat'],'nombre'=>$row['nombre'],'desc'=>$myts->makeTareaData4Show($row['desc'])));	
}

$tpl->assign('lang_categos', _RMMF_CATEGOS);
$tpl->assign('lang_featured', _RMMF_FEATURED);
$tpl->assign('lang_recent', _RMMF_RECENTS);

// Cargo los trabajos destacados
$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_works")." WHERE resaltado='1' ORDER BY id_w DESC LIMIT 0,$mc[featured]");
while ($row=$db->fetchArray($result)){
	$tpl->append('destacados', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->makeTareaData4Show($row['short']),'img'=>$row['imagen']));	
}

$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_works")." ORDER BY id_w DESC LIMIT 0,$mc[recents]");
while ($row=$db->fetchArray($result)){
	$tpl->append('recientes', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->makeTareaData4Show($row['short']),'img'=>$row['imagen']));	
}

include 'footer.php';
?>