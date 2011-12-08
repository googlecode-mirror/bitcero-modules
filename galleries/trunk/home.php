<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','index');

$xoopsOption['template_main'] = 'gs_index.html';
$xoopsOption['module_subpage'] = 'index';
include 'header.php';

GSFunctions::makeHeader();

$tpl->assign('lang_lastphotos', __('Recent Photos','galleries'));
$tpl->assign('last_sets', __('Recent Albums','galleries'));
$tpl->assign('lang_created', __('Created on:','galleries'));
$tpl->assign('lang_by', __('Created by:','galleries'));
$tpl->assign('lang_pics', __('Photos:','galleries'));
$tpl->assign('lang_other', __('Other Albums','galleries'));
$tpl->assign('lang_view', __('View Photos','galleries'));
$tpl->assign('lang_moresets', __('More albums','galleries'));
$tpl->assign('explore_sets_link', GSFunctions::get_url().($mc['urlmode'] ? 'explore/sets/' : '?explore=sets'));
$tpl->assign('lang_setbrowse', __('Browse albums','galleries'));
$tpl->assign('explore_imgs_link', GSFunctions::get_url().($mc['urlmode'] ? 'explore/photos/' : '?explore=photos'));
$tpl->assign('lang_imgbrowse', __('Browse photos','galleries'));
$tpl->assign('explore_tags_link', GSFunctions::get_url().($mc['urlmode'] ? 'explore/tags/' : '?explore=tags'));
$tpl->assign('lang_tagbrowse', __('Browse tags','galleries'));

// ültimas Fotos
$mc['last_num'] = $mc['last_num']<=0 ? 10 : $mc['last_num'];
$result = $db->query("SELECT * FROM ".$db->prefix("gs_images")." WHERE public=2 ORDER BY created DESC LIMIT 0,$mc[last_num]");

$tpl->assign('last_images', GSFunctions::process_image_data($result));

// Álbumes Recientes
$result = $db->query("SELECT * FROM ".$db->prefix("gs_sets")." WHERE public='2' ORDER BY date DESC LIMIT 0,".($mc['sets_num']>0 ? $mc['sets_num'] : 5));
while ($row = $db->fetchArray($result)){
	$set = new GSSet();
	$set->assignVars($row);
	$pics = $set->getPics('RAND()');
	$imgs = @array_slice($pics, 0, $xoopsModuleConfig['sets_num_images']);
	if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
	$images = array();
	if (!empty($imgs)){
		// Obtenemos las primeras 4 imágenes
		foreach ($imgs as $k){
			$img = new GSImage($k);
			if ($img->isPublic()!=2) continue;

			if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);
			$imglink = $users[$img->owner()]->userURL().'img/'.$img->id().'/';
			$images[] = array('id'=>$img->id(),'title'=>$img->title(),
				'thumbnail'=>$users[$img->owner()]->filesURL().'/ths/'.$img->image(),
                'image'=>$users[$img->owner()]->filesURL().'/'.$img->image(),
				'by'=>sprintf(__('by %s','galleries'), '<a href="'.$users[$img->owner()]->userUrl().'">'.$users[$img->owner()]->uname().'</a>'),
				'link'=>$imglink);
		}
	}
	$tpl->append('sets', array(
        'id'=>$set->id(),
        'title'=>$set->title(),
        'images'=>$images,
		'link'=>$users[$set->owner()]->userUrl().($mc['urlmode'] ? 'set/'.$set->id() : '&amp;set='.$set->id()),
		'date'=>formatTimestamp($set->date(), 'c'), 
        'by'=>'<a href="'.$users[$set->owner()]->userUrl().'">'.$users[$set->owner()]->uname().'</a>',
		'picsnum'=>count($pics)));
}

$result = $db->query("SELECT * FROM ".$db->prefix("gs_sets")." WHERE public='2' ORDER BY date DESC LIMIT $mc[sets_num],".($mc['sets_num']>0 ? $mc['sets_num'] : 5));
while ($row = $db->fetchArray($result)){
	$set = new GSSet();
	$set->assignVars($row);
	if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
	$tpl->append('other_sets', array(
        'id'=>$set->id(),
        'title'=>$set->title(),
        'link'=>$users[$set->owner()]->userUrl().($mc['urlmode'] ? 'set/'.$set->id() : '&amp;set='.$set->id()),
		'date'=>formatTimestamp($set->date(), 'c'), 
        'by'=>'<a href="'.$users[$set->owner()]->userUrl().'">'.$users[$set->owner()]->uname().'</a>',
		'picsnum'=>count($pics)));
}

RMTemplate::get()->add_style('index.css', 'galleries');

include 'footer.php';
