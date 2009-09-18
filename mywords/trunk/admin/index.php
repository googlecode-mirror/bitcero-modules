<?php
// $Id: index.php 29 2009-09-13 23:41:38Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
require 'header.php';

/**
 * Muestra el estado actual del módulo
 */
function showState(){
	global $xoopsDB, $db, $util, $tpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsTpl;
	
	list($categos) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_categos")));
	list($posts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")));
	list($aprovados) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE aprovado='1'"));
	list($esperando) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE aprovado='0'"));
	list($tracks) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_trackbacks")));
	list($editores) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_editors")));
	list($replaces) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_replacements")));
    list($books) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_bookmarks")));
	
	/**
	* @desc Caragmaos los artículos recientemente enviados
	*/
	$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." ORDER BY fecha DESC LIMIT 0,5");
	$tpl->assign('lang_recents', sprintf(_AS_MW_RECENTS, $db->getRowsNum($result)));
	while ($row = $db->fetchArray($result)){
		$post = new MWPost();
		$post->assignVars($row);
		$tpl->append('posts', array('id'=>$post->getID(),'title'=>$post->getTitle(),
				'desc'=>substr($util->filterTags($post->getHomeText()), 0, 100), 'link'=>$post->getPermaLink(),
				'uname'=>$post->getAuthorName(),'uid'=>$post->getAuthor(),'date'=>formatTimestamp($post->getDate(), 's'),
				'approved'=>$post->getApproved() ? _YES : NO));
	}
	
	$tpl->assign('lang_author', _AS_MW_AUTHOR);
	$tpl->assign('lang_version', _AS_MW_VERINFO);
	$tpl->assign('lang_date', _AS_MW_SENTDATE);
	$tpl->assign('lang_approved', _AS_MW_APPROVED);
	$tpl->assign('lang_edit', _EDIT);
	
	$url = "http://www.redmexico.com.mx/modules/vcontrol/?id=5";
	
	//$adminTemplate = "admin/mywords_theindex.html";
	$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
	$cHead .= "<link href=\"".XOOPS_URL."/modules/mywords/styles/admin.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />";
	xoops_cp_location($xoopsModule->name());
	
    include 'menu.php';
	xoops_cp_header();
	
	// Show Templates
	$tpl->add_style('../css/admin.css');
	//$tpl->header();
	include_once '../templates/admin/mywords_theindex.php';    
	//$tpl->footer();
	xoops_cp_footer();
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	default:
		showState();
		break;
}
