<?php
// $Id$
// --------------------------------------------------------
// Quick Pages
// Módulo para la publicación de páginas individuales
// CopyRight © 2007 - 2008. Red México
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
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

$xoopsOption['template_main'] = 'qpages_index.html';
$xoopsOption['module_subpage'] = 'index';
require 'header.php';

$tpl->assign('page_title', $xoopsModule->name());
$location = '<a href="'.QP_URL.'" title="'.$xoopsModule->name().'">'._MS_QP_HOMEPAGE.'</a>';
$tpl->assign('page_location',$location);
$tpl->assign('xoops_pagetitle',$xoopsModule->name() . ' &raquo; ' . _MS_QP_HOMEPAGE);
$tpl->assign('home_text', $mc['texto']);

$result = $db->query("SELECT * FROM ".$db->prefix("qpages_categos")." WHERE parent='0' ORDER BY nombre ASC");
$categos = array();
qpArrayCategos($categos);

while ($k = $db->fetchArray($result)){
	
	$catego = new QPCategory();
	$catego->assignVars($k);
	$lpages = $catego->loadPages();
	$pages = array();
	foreach ($lpages as $p){
		$ret = array();
		$ret['titulo'] = $myts->makeTboxData4Show($p['titulo']);
		$ret['desc'] = $util->filterTags($myts->makeTareaData4Show($p['desc']));
		$ret['link'] = $mc['links'] ? QP_URL.'/'.$p['titulo_amigo'].'/' : QP_URL.'/page.php?page='.$p['titulo_amigo'];
		$pages[] = $ret;
	}
	$link = $catego->getLink();
	$subcats = $catego->getSubcategos();
	$tpl->append('categos', array('id'=>$catego->getID(),'nombre'=>$catego->getName(),'desc'=>$catego->getDescription(),
			'pages'=>$pages,'pages_count'=>count($lpages), 'link'=>$link,
			'subcats'=>count($subcats)>0 ? $subcats : '','subcats_count'=>count($subcats)));
	
}

$tpl->assign('lang_subcats',_MS_QP_SUBCATS);
$tpl->assign('lang_pagesin', _MS_QP_PAGESIN);

require 'footer.php';
?>