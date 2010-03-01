<?php
/*******************************************************************
* $Id$          *
* -------------------------------------------------------          *
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
* -------------------------------------------------------          *
* categos.php:                                                     *
* Categorías del Módulo                                            *
* -------------------------------------------------------          *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:50:11 a.m.                            *
*******************************************************************/

include 'header.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id<=0){ header('location: index.php'); die(); }

$xoopsOption['template_main'] = "rmmf_categos.html";

// Cargo las categorías
$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_categos")." WHERE parent='$id' ORDER BY orden");
while ($row=$db->fetchArray($result)){
	$tpl->append('categos', array('id'=>$row['id_cat'],'nombre'=>$row['nombre'],'desc'=>$myts->makeTareaData4Show($row['desc'])));	
}

$tpl->assign('lang_works', _RMMF_WORKS);
$tpl->assign('lang_view', _RMMF_VIEWINFO);
$tpl->assign('lang_categos', _RMMF_CATEGOS);
$tpl->assign('lang_featured', _RMMF_FEATURED);
$tpl->assign('lang_recent', _RMMF_RECENTS);

// Cargo los trabajos en la categoría
$limit = $mc['results'];
$pag = isset($_GET['pag']) ? $_GET['pag'] : (isset($_POST['pag']) ? $_POST['pag'] : 0);
if ($pag > 0){ $pag -= 1; }
$start = $pag * $limit;
list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("rmmf_works")." WHERE catego='$id'"));
$rtotal = $num;
$tpages = (int)($num / $limit);
if (($num % $limit) > 0){ $tpages++; }
$pactual = $pag + 1;

if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}


$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_works")." WHERE catego='$id' ORDER BY id_w DESC LIMIT $start,$limit");
while ($row=$db->fetchArray($result)){
	$tpl->append('works', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->makeTareaData4Show($row['short']),'img'=>$row['imagen']));	
}

for ($i=1;$i<=$tpages;$i++){
	$nav .= "<a href='?pag=$i&amp;id=$id'>$i</a>&nbsp;";
}

$tpl->assign('pages', $nav);
$tpl->assign('lang_pages', _RMMF_PAGES);

// Cargo los trabajos destacados
$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_works")." WHERE catego='$id' AND resaltado='1' ORDER BY id_w DESC LIMIT 0,$mc[featured]");
while ($row=$db->fetchArray($result)){
	$tpl->append('destacados', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->makeTareaData4Show($row['short']),'img'=>$row['imagen']));	
}

$result = $db->query("SELECT * FROM ".$db->prefix("rmmf_works")." WHERE catego='$id' ORDER BY id_w DESC LIMIT 0,$mc[recents]");
while ($row=$db->fetchArray($result)){
	$tpl->append('recientes', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->makeTareaData4Show($row['short']),'img'=>$row['imagen']));	
}

$tpl->assign('footer', rmmf_make_footer(false));

// Creamos la barra de navegación
$tpl->assign('localize_bar', ":: <a href='index.php'>$mc[title]</a>" . rmmf_localize($id, 0));

include 'footer.php';
?>