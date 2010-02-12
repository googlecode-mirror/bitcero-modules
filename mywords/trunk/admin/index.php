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
$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." ORDER BY pubdate DESC LIMIT 0,5");
	
$url = "http://www.redmexico.com.mx/modules/vcontrol/?id=5";

$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
$cHead .= "<link href=\"".XOOPS_URL."/modules/mywords/styles/admin.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />";
xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Dashboard','admin_mywords'));
	
include 'menu.php';
xoops_cp_header();
	
// Show Templates
RMTemplate::get()->add_style('dashboard.css', 'mywords');
RMTemplate::get()->add_style('admin.css', 'mywords');
//$tpl->header();
include RMtemplate::get()->get_template('admin/mywords_theindex.php', 'module', 'mywords');
//$tpl->footer();
xoops_cp_footer();

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	default:
		showState();
		break;
}
