<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function gs_rssdesc(){
	global $util;
	
	return _MI_GS_RSSDESC;
	
}

function &gs_rssfeed($limit, &$more){
	
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	
	$ret = array();
	$rtn = array();
	$ret['name'] = _MI_GS_RSSIMGS;
	$ret['desc'] = _MI_GS_RSSIMGS_DESC;
	$ret['params'] = "show=imgs";
	$rtn[] = $ret;
	
	$ret['name'] = _MI_GS_RSSSETS;
	$ret['desc'] = _MI_GS_RSSSETS_DESC;
	$ret['params'] = "show=sets";
	$rtn[] = $ret;
	
	return $rtn;
}

function &gs_rssshow($limit){
	global $util, $mc;
	
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
	
	foreach ($_GET as $k => $v){
		$$k = $v;
	}
	
	$feed = array();		// Información General
	$items = array();
	$mc =& $util->moduleConfig('galleries');
	
	if ($show == 'imgs'){
		$feed['title'] = htmlspecialchars(_MI_GS_RSSNAME);
		$feed['link'] = htmlspecialchars(XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'explore/photos/' : 'explore.php?by=explore/photos'));
		$feed['description'] = htmlspecialchars(_MI_GS_RSSIMGS_DESC);
		
		$sql = "SELECT * FROM ".$db->prefix("gs_images")." WHERE public='2' ORDER BY created DESC LIMIT 0,15";
		$result = $db->query($sql);
		$users = array();
		while ($row = $db->fetchArray($result)){
			$pic = new GSImage();
			$pic->assignVars($row);
			if (!isset($users[$pic->owner()])){
				$users[$pic->owner()] = new GSUser($pic->owner(), 1);
			}
			$user =& $users[$pic->owner()];
			$rtn = array();
			$rtn['title'] = htmlspecialchars($pic->title());
			$rtn['link'] = $user->userURL().'img/'.$pic->id().'/';
			$rtn['description'] = htmlspecialchars('<img src="'.$user->filesURL().'/ths/'.$pic->image().'" alt="" /><br />'.sprintf(_MI_GS_RSSIMGDESC, $pic->desc(), formatTimestamp($pic->created(),'string'),
					$user->uname(), $pic->views()));
			$rtn['date'] = formatTimestamp($pic->created());
			$items[] = $rtn;
		}
		
	} elseif ($show=='sets'){
		
		$feed['title'] = htmlspecialchars(_MI_GS_RSSSETS);
		$feed['link'] = htmlspecialchars(XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'explore/sets/' : 'explore.php?by=explore/sets'));
		$feed['description'] = htmlspecialchars(_MI_GS_RSSSETS_DESC);
		
		$sql = "SELECT * FROM ".$db->prefix("gs_sets")." WHERE public='2' ORDER BY date DESC LIMIT 0,15";
		$result = $db->query($sql);
		$users = array();
		while ($row = $db->fetchArray($result)){
			$set = new GSSet();
			$set->assignVars($row);
			if (!isset($users[$set->owner()])){
				$users[$set->owner()] = new GSUser($set->owner(), 1);
			}
			$user =& $users[$set->owner()];
			$rtn = array();
			$rtn['title'] = htmlspecialchars($set->title());
			$rtn['link'] = $user->userURL().'set/'.$set->id().'/';
			$rtn['description'] = htmlspecialchars(sprintf(_MI_GS_RSSSETDESC, $user->uname(), formatTimestamp($set->date(), 'string'), $set->pics()));
			$rtn['date'] = formatTimestamp($set->date());
			$items[] = $rtn;
		}
		
	}
	
	$ret = array('feed'=>$feed, 'items'=>$items);
	return $ret;
	
}
