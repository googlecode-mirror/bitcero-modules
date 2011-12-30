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
$tbl1= $db->prefix('bxpress_posts');
$tbl2= $db->prefix('bxpress_topics');
$tbl3=$db->prefix('bxpress_posts_text');
$tbl4=$db->prefix('bxpress_forums');
$sql = "SELECT a.*, b.*, c.post_text, d.* 
        FROM $tbl1 a, $tbl2 b, $tbl3 c, $tbl4 d 
        WHERE b.id_topic = a.id_topic AND c.post_id=a.id_post AND d.id_forum=b.id_forum
        GROUP BY a.id_topic 
        ORDER BY a.post_time DESC 
        LIMIT 0,5";
$result=$db->query($sql);

$posts = array();
$topics = array();
$topic = new bXTopic();
$forum = new bXForum();
$pt = new bXPost();
while ($row=$db->fetchArray($result)){
        //print_r($row);
        $pt->assignVars($row);
	$post = array(
            'id' => $row['last_post'],
            'date' => sprintf(__('Last post on %s','bxpress'), bXFunctions::formatDate($row['post_time'])),
            'by'=> sprintf(__('By %s','bxpress'), $row['poster_name']),
            'link' => $pt->permalink(),
            'uid' => $row['uid']
        );
        $topic->assignVars($row);
        $forum->assignVars($row);
	$topics[] = array(
            'id'=>$row['id_topic'],
            'title'=>$row['title'],
            'post'=>$post,
            'link' => $topic->permalink(),
            'forum' => array(
                'id' => $forum->id(),
                'name' => $forum->name(),
                'link' => $forum->permalink()
            )
        );
}

$sql = "SELECT * FROM $tbl2 ORDER BY replies DESC LIMIT 0,5";
$result = $db->query($sql);
$poptops = array();
$topic = new bXTopic();
while($row = $db->fetchArray($result)){
    $topic->assignVars($row);
    $forum->assignVars($row);
    $poptops[] = array(
        'id' => $topic->id(),
        'title' => $topic->title(),
        'date' => sprintf(__('Created on %s','bxpress'), bXFunctions::formatDate($row['date'])),
        'replies' => $topic->replies(),
        'link' => $topic->permalink(),
        'forum' => array(
                'id' => $forum->id(),
                'name' => $forum->name(),
                'link' => $forum->permalink()
            )
    );
}

unset($post,$pt,$topic,$result,$row,$sql,$tbl1,$tbl2,$tbl3);

RMTemplate::get()->add_xoops_style('dashboard.css', 'bxpress');
RMTemplate::get()->add_local_script('dashboard.js', 'bxpress');
RMTemplate::get()->set_help('http://www.redmexico.com.mx/docs/bxpress-forums/introduccion/standalone/1/');
bXFunctions::menu_bar();

// Activity
// 30 Days
$ago = strtotime("-30 days");
$sql = "SELECT id_post,post_time,id_forum FROM ".$db->prefix("bxpress_posts")." WHERE post_time>=$ago ORDER BY post_time ASC";
$result = $db->query($sql);
$posts = array();
$forums = array();
$p = '';
while($row = $db->fetchArray($result)){
    $ds = date("d-M-Y", $row['post_time']);
    
    if(!isset($posts[$row['id_forum']]))
        $forums[$row['id_forum']] = new bXForum($row['id_forum']);
    
    if(!isset($posts[$row['id_forum']][$ds])){
        $posts[$row['id_forum']][$ds] = 1;
    } else {
        $posts[$row['id_forum']][$ds]++;
    }
}

// Days
$days_rows = array();
$j = 0; $max = 0;
for($i=30;$i>=0;$i--){
    $j++;
    $ds = date("d-M-Y", strtotime("-".$i." days"));
    $days_rows[$i] = '["'.$ds.'"';
    foreach($forums as $id => $f){
        $max = isset($posts[$id][$ds]) ? ($posts[$id][$ds]>$max?$posts[$id][$ds]:$max) : $max;
        $days_rows[$i] .= ",".(isset($posts[$id][$ds]) ? $posts[$id][$ds] : '0');
    }
    $days_rows[$i] .= "]\n";
}
unset($d,$posts);
$max += 10-($max % 10);

$rblocks = '';
$rblocks = RMEvents::get()->run_event("bxpress.dashboard.right.blocks", $rblocks);

$lblocks = '';
$lblocks = RMEvents::get()->run_event("bxpress.dashboard.left.blocks", $lblocks);

xoops_cp_header();

include RMTemplate::get()->get_template("admin/forums_index.php", 'module', 'bxpress');

xoops_cp_footer();

