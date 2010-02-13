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

	
list($numcats) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_categories")));
list($numposts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")));
list($numdrafts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE status='draft'"));
list($numpending) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE status='waiting'"));
list($numeditors) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_editors")));
list($numsocials) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_bookmarks")));
list($numcoms) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("rmc_comments")." WHERE id_obj='mywords'"));
list($numtags) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_tags")));
	
/**
* @desc Caragmaos los artículos recientemente enviados
*/
$posts = array();
$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." WHERE status='publish' ORDER BY pubdate DESC LIMIT 0,5");
while ($row = $db->fetchArray($result)){
    $post = new MWPost();
    $post->assignVars($row);
    $posts[] = $post;
}
	
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
