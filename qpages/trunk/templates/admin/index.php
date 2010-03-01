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

define('QP_LOCATION','index');
require 'header.php';

/**
 * Muestra el estado actual del módulo
 */
function showStatus(){
	global $db, $util, $tpl, $xoopsConfig, $xoopsModule, $adminTemplate;
	
	list($categos) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_categos")));
	list($pages) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")));
	list($public) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE acceso='1'"));
	list($private) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE acceso='0'"));
	
	$tpl->assign('lang_version', _AS_QP_VERSIONINFO);
	$tpl->assign('lang_moreread', _AS_QP_MOREVPAGES);
	$tpl->assign('qpages_version', $util->getVersion());
	
	$tpl->append('options', array('text'=>sprintf(_AS_QP_CATEGOSNUM, $categos), 'info'=>_AS_QP_DETAILS,
		'link'=>'cats.php','icon'=>'../images/categos48.png'));
	$tpl->append('options', array('text'=>sprintf(_AS_QP_PAGESNUM, $pages), 'info'=>_AS_QP_DETAILS,
		'link'=>'pages.php','icon'=>'../images/pages48.png'));
	$tpl->append('options', array('text'=>sprintf(_AS_QP_PUBLICNUM, $public), 'info'=>_AS_QP_DETAILS,
		'link'=>'pages.php?op=public','icon'=>'../images/public.png'));
	$tpl->append('options', array('text'=>sprintf(_AS_QP_PRIVATENUM, $private), 'info'=>_AS_QP_DETAILS,
		'link'=>'pages.php?op=private','icon'=>'../images/private.png'));
	$tpl->append('options', array('text'=>'Red México', 'info'=>_AS_QP_VISIT,
		'link'=>'http://www.redmexico.com.mx','icon'=>'../images/redmex.png'));
	$tpl->append('options', array('text'=>'EXM System', 'info'=>_AS_QP_VISIT,
		'link'=>'http://www.exmsystem.net','icon'=>XOOPS_URL.'/images/exm.png'));
	
	// Páginas mas leidas
	$result = $db->query("SELECT * FROM ".$db->prefix("qpages_pages")." ORDER BY lecturas DESC");
	while ($row = $db->fetchArray($result)){
		$page = new QPPage();
		$page->assignVars($row);
		$tpl->append('pages', array('id'=>$page->getID(),'title'=>$page->getTitle(),'link'=>$page->getPermaLink(),
			'desc'=>substr($util->filterTags($page->getText()), 0, 100),
			'date'=>formatTimestamp($page->getDate())));
	}
	
	$ver=$xoopsModule->getVar('version');
	$version=$ver['number'].'.'.$ver['revision'].'.'.$ver['status'];
	$name = 'quick-pages';
	$url = "http://www.redmexico.com.mx/modules/versions/".$name."/".$version."/module";
	$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
	
	$adminTemplate = "admin/qp_theindex.html";
	xoops_cp_location($xoopsModule->name());
	
	$cHead .= '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	xoops_cp_footer();
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	default:
		showStatus();
		break;
}

?>