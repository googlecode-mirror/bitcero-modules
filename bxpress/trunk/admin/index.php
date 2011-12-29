<?php
// $Id$
// --------------------------------------------------------------
// EXMBB Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
include 'header.php';

$db = $xoopsDB;

// Categorías
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_categories");
list($catnum) = $db->fetchRow($db->query($sql));

// Forums
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_forums");
list($forumnum) = $db->fetchRow($db->query($sql));

// Topics
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_topics");
list($topicnum) = $db->fetchRow($db->query($sql));

// Posts
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_posts");
list($postnum) = $db->fetchRow($db->query($sql));

// Announcements
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_announcements");
list($annum) = $db->fetchRow($db->query($sql));

//Attachments
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_attachments");
list($attnum) = $db->fetchRow($db->query($sql));

// Reports
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_report");
list($repnum) = $db->fetchRow($db->query($sql));

// Days running
$daysnum = time() - $xoopsModule->getVar('last_update');
$daysnum = ceil($daysnum/86400);

//Lista de Mensajes recientes
$tbl1= $db->prefix('exmbb_posts');
$tbl2= $db->prefix('exmbb_topics');
$tbl3=$db->prefix('exmbb_posts_text');
$sql=" SELECT a.*,b.id_topic,b.title,c.post_text FROM $tbl1 a, $tbl2 b, $tbl3 c WHERE a.id_topic=b.id_topic AND c.post_id=a.id_post 
AND a.post_time>".(time()-($xoopsModuleConfig['time_topics']*3600))." ORDER BY post_time DESC LIMIT 0,5";
$result=$db->queryF($sql);
while ($row=$db->fetchArray($result)){
	$post['id']=$row['id_topic'];
	$posts['date']=BBFunctions::formatDate($row['post_time']);
	$posts['by']= sprintf(_AS_BB_BY, $row['poster_name']);
	$tpl->append('topics',array('id'=>$row['id_topic'],'title'=>$row['title'],'text'=>substr($util->FilterTags($row['post_text']),0,100),
	'posts'=>$posts));
}

RMTemplate::get()->add_xoops_style('dashboard.css', 'bxpress');
RMTemplate::get()->add_local_script('dashboard.js', 'bxpress');
RMTemplate::get()->set_help('http://www.redmexico.com.mx/docs/bxpress-forums/introduccion/standalone/1/');
bXFunctions::menu_bar();

$rblocks = RMEvents::get()->run_event("bxpress.dashboard.right.blocks", $rblocks);

xoops_cp_header();

include RMTemplate::get()->get_template("admin/forums_index.php", 'module', 'bxpress');

xoops_cp_footer();

