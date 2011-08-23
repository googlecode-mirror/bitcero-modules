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

$adminTemplate = "admin/forums_index.html";

$db = $xoopsDB;

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

xoops_cp_header();

xoops_cp_footer();

