<?php
/*******************************************************************
* $Id$             *
* ----------------------------------------------------             *
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
* ----------------------------------------------------             *
* view.php:                                                        *
* Información completa de un trabajo                               *
* ----------------------------------------------------             *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.4                                                  *
* @modificado: 24/05/2006 12:52:24 a.m.                            *
*******************************************************************/

include 'header.php';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
if ($id<=0){ header('location: index.php'); die(); }

$xoopsOption['template_main'] = 'rmmf_view.html';

$tpl->assign('localize_bar', ":: <a href='index.php'>$mc[title]</a>" . rmmf_localize($id, 1));

include_once('class/work.class.php');
$work = new MFWork($id);

$tpl->assign('work', array('id'=>$work->getVar('id_w'),'titulo'=>$work->getVar('titulo'),
		'desc'=>$myts->makeTareaData4Show($work->getVar('desc')),
		'cliente'=>$work->getVar('cliente'),
		'comentario'=>$myts->makeTareaData4Show($work->getVar('comentario')),
		'url'=>$work->getVar('url'),'imagen'=>$work->getVar('imagen')));

$tpl->assign('lang_for', _RMMF_FOR);
$tpl->assign('lang_desc', _RMMF_DESC);
$tpl->assign('lang_url', _RMMF_URL);
$tpl->assign('lang_comment', sprintf(_RMMF_COMMENT, $work->getVar('cliente')));
$tpl->assign('lang_moreimgs', _RMMF_MOREIMAGES);

foreach ($work->getVar('images') as $k => $v){
	$tpl->append('images', $v['archivo']);
}

$xmh = "<script type=\"text/javascript\">
<!--
	function cambiar(img){
		document.getElementById(\"pics\").src = '".rmmf_add_slash(rmmf_web_dir($mc[storedir]))."' + img
	}
	
//-->
</script>";
$xoops_module_header .= $xmh;
$xoops_module_header .= '<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />';

include 'footer.php';
?>