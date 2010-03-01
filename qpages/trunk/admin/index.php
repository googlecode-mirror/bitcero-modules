<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

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
	
	$url = "http://redmexico.com.mx/modules/vcontrol/?id=9";
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