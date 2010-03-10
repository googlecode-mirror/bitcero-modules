<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function gs_photos_show($options){
	
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
	
	$options[0]<=0 ? 4 : $options[0];
	$db =& Database::getInstance();
	
	$sql = "SELECT * FROM ".$db->prefix("gs_images");
	$order = $options[2]=='0' ? "created DESC" : ($options[2]=='1' ? 'RAND()' : 'views DESC');
	$sql .= " ORDER BY $order LIMIT 0,$options[0]";
	$result = $db->query($sql);
	$mc =& RMUtilities::get()->module_config('galleries');
	$block = array();
	
	while ($row = $db->fetchArray($result)){
		$pic = new GSImage();
		$pic->assignVars($row);
		
		if (!isset($users[$pic->owner()])) $users[$pic->owner()] = new GSUser($pic->owner(), 1);
		$user =& $users[$pic->owner()];
		
		$rtn = array();
		$rtn['title'] = $pic->title();
		$rtn['created'] = formatTimestamp($pic->created(), 'string');
		$rtn['views'] = $pic->views();
		$rtn['by'] = sprintf(_BK_GS_BY, $user->userURL(), $user->uname());
		$rtn['link'] = $user->userURL().'img/'.$pic->id().'/';
		$rtn['file'] = $user->filesURL().'/ths/'.$pic->image();
		$block['pics'][] = $rtn;
	}
	
	$block['cols'] = $options[1];
	$block['width'] = floor(100/$options[1]);
	$block['imgw'] = $options[3];
	return $block;
	
}

function gs_photos_edit($options){
    
    $form = _BK_GS_PICNUM." <input type='text' name='options[0]' value='$options[0]' /><br />";
    $form .= _BK_GS_COLSNUM." <input type='text' name='options[1]' value='$options[1]' /><br />";
    $form .= _BK_GS_PICTYPE." <select name='options[2]'><option value='0'>"._BK_GS_PICRECENT."</option>";
    $form .= "<option value='1'>"._BK_GS_PICRANDOM."</option>";
    $form .= "<option value='2'>"._BK_GS_PICVIEWED."</option></select><br />";
    $form .= _BK_GS_IMGWIDTH." <input type='text' name='options[3]' value='$options[3]' /><br />";
	
	/*include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsusers.class.php';
	
	$form->addElement(new RMFormUser(_BK_GS_PICUSER, 'options[3]', false, array($options[3]), 40, 500, 300, 1));
	$form->addElement(new RMFormYesNo(_BK_GS_PICDESC, 'options[4]', $options[4]));*/
	
	return $form;
}
