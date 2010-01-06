<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','tags');
require('header.php');

include_once '../include/general.func.php';

/**
* Show all existing tags
*/
function show_tags(){
    global $xoopsModule, $xoopsSecurity;
    
    MWFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Tags','admin_mywords'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Tags Management','admin_mywords'));
    
    // More used tags
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".$db->prefix("mw_tags")." ORDER BY posts DESC LIMIT 0,30";
    $result = $db->query($sql);
    $mtags = array();
    $size = 0;
    while ($row = $db->fetchArray($result)){
		$mtags[$row['tag']] = $row;
		$size = $row['posts']>$size ? $row['posts'] : $size;
    }
    
    ksort($mtags);
    
    // All tags
    list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_tags")));
    $page = rmc_server_var($_GET, 'page', 1);
	$limit = isset($limit) && $limit>0 ? $limit : 15;
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('tags.php?page={PAGE_NUM}');
	
	$sql = "SELECT * FROM ".$db->prefix("mw_tags")." ORDER BY id_tag DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $tags = array();
    while($row = $db->fetchArray($result)){
		$tags[] = $row;
    }
    
    xoops_cp_header();   
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('..//include/js/scripts.php?file=tags-list.js');
    RMTemplate::get()->add_style('jquery.css','rmcommon');
    include RMTemplate::get()->get_template('admin/mywords_tags.php','module','mywords');
    
    xoops_cp_footer();
    
}

/**
* Save a new tag or update an existing tag
* @param bool Save or edit
*/
function save_tag($edit = false){
	global $xoopsConfig, $xoopsSecurity;
	
	$page = rmc_server_var($_POST, 'page', 1);
	
	if (!$xoopsSecurity->check()){
		redirectMsg('tags.php?page='.$page, __('Operation not allowed!','admin_mywords'), 1);
		die();
	}
	
	$name = rmc_server_var($_POST,'name','');
	$short = rmc_server_var($_POST,'short','');
	
	if ($name==''){
		redirectMsg('tags.php?page='.$page, __('You must provide a name!','admin_mywords'), 1);
		die();
	}
	
	if ($edit){
		
		$id = rmc_server_var($_POST,'id',0);
		if ($id<=0){
			redirectMsg('tags.php?page='.$page, __('Tag id not provided!','admin_mywords'), 1);
			die();
		}
		
		$tag = new MWTag($id);
		if($tag->isNew()){
			redirectMsg('tags.php?page='.$page, __('Tag does not exists!','admin_mywords'), 1);
			die();
		}
		
		
	} else {
		
		$tag = new MWTag();
		
	}
	
	if (trim($short)==''){
		$short = TextCleaner::sweetstring($name);
	} else {
		$short = TextCleaner::sweetstring($short);
	}
	
	// Check if tag exists
	$db = Database::getInstance();
	if ($edit){
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_tags")." WHERE (tag='$name' OR shortname='$short') AND id_tag<>$id";
	} else {
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_tags")." WHERE tag='$name' OR shortname='$short'";
	}
	
	list($num) = $db->fetchRow($db->query($sql));
	if($num>0){
		redirectMsg('tags.php?page='.$page, __('A tag with same name or same short name already exists!','admin_mywords'), 1);
		die();
	}
	
	$tag->setVar('tag', $name);
	$tag->setVar('shortname', $short);
	if ($tag->save()){
		redirectMsg('tags.php', __('Database updated successfully!','admin_mywords'), 0);
		die();
	} else {
		redirectMsg('tags.php?page='.$page, __('A problem occurs while trying to save tag.','admin_mywords').'<br />'.$tag->errors(), 1);
		die();
	}
	
}

function edit_form(){
	global $xoopsModule, $xoopsSecurity;
	
	$id = rmc_server_var($_GET,'id',0);
	if($id<=0){
		redirectMsg('tags.php?page='.$page, __('Tag ID not provided!.','admin_mywords'), 1);
		die();
	}
	$tag = new MWTag($id);
	if($tag->isNew()){
		redirectMsg('tags.php?page='.$page, __('Tag does not exists!','admin_mywords'), 1);
		die();
	}
	
	MWFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; <a href="tags.php">'.__('Tags','admin_mywords').'</a> &raquo; '.__('Edit Tag','admin_mywords'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Editing Tag','admin_mywords'));
	xoops_cp_header();   
	$show_edit = true;
    include RMTemplate::get()->get_template('admin/mywords_tags.php','module','mywords');
    
    xoops_cp_footer();
	
}

/**
* Deletes a existing tag or set of tags* 
*/
function delete_tag(){
	global $xoopsModule, $xoopsSecurity;
	
	$page = rmc_server_var($_POST, 'page', 1);
	$tags = rmc_server_var($_POST, 'tags', array());
	
	if (!$xoopsSecurity->check()){
		redirectMsg('tags.php?page='.$page, __('Sorry, operation not allowed!','admin_mywords'), 1);
		die();
	}
	
	if (!is_array($tags) || empty($tags)){
		redirectMsg('tags.php?page='.$page, __('Please, specify a valid tag id!','admin_mywords'), 1);
		die();
	}
	
	// Delete all relations
	$db = Database::getInstance();
	$sql = "DELETE FROM ".$db->prefix("mw_tagspost")." WHERE tag IN(".implode(",",$tags).")";
	if (!$db->queryF($sql)){
		redirectMsg('tags.php?page='.$page, __('Errors ocurred while trying to delete tags!','admin_mywords').'<br />'.$db->error(), 1);
		die();
	}
	
	$sql = "DELETE FROM ".$db->prefix("mw_tags")." WHERE id_tag IN(".implode(",",$tags).")";
	if (!$db->queryF($sql)){
		redirectMsg('tags.php?page='.$page, __('Errors ocurred while trying to delete tags!','admin_mywords').'<br />'.$db->error(), 1);
		die();
	}
	
	redirectMsg('tags.php?page='.$page, __('Database updated succesfully!','admin_mywords'), 0);
	
}


function update_tag(){
	global $xoopsModule, $xoopsSecurity;
	
	$page = rmc_server_var($_POST, 'page', 1);
	$tags = rmc_server_var($_POST, 'tags', array());
	
	if (!$xoopsSecurity->check()){
		redirectMsg('tags.php?page='.$page, __('Sorry, operation not allowed!','admin_mywords'), 1);
		die();
	}
	
	if (!is_array($tags) || empty($tags)){
		redirectMsg('tags.php?page='.$page, __('Please, specify a valid tag id!','admin_mywords'), 1);
		die();
	}
	
	foreach ($tags as $id){
		
		$tag = new MWTag($id);
		if($tag->isNew()) continue;
		
		$tag->update_posts();
		
	}
	
	redirectMsg('tags.php?page='.$page, __('Tags updated!','admin_mywords'), 0);
	
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
	case 'new':
		save_tag(false);
		break;
	case 'saveedit':
		save_tag(true);
		break;
	case 'edit':
		edit_form();
		break;
	case 'delete':
		delete_tag();
		break;
	case 'update':
		update_tag();
		break;
    default:
        show_tags();
        break;
}