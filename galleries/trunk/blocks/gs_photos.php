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
	
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
	
	$options[0]<=0 ? 4 : $options[0];
	$db =& Database::getInstance();
	
	$sql = "SELECT * FROM ".$db->prefix("gs_images");
	$order = $options[1]=='0' ? "created DESC" : ($options[1]=='1' ? 'RAND()' : 'views DESC');
	$sql .= " ORDER BY $order LIMIT 0,$options[0]";
	$result = $db->query($sql);
	$mc =& RMUtilities::module_config('galleries');
	$block = array();
	
    $tf = new RMTimeFormatter(0, '%T% %d%, %Y%');
    
	while ($row = $db->fetchArray($result)){
		$pic = new GSImage();
		$pic->assignVars($row);
		
		if (!isset($users[$pic->owner()])) $users[$pic->owner()] = new GSUser($pic->owner(), 1);
		$user =& $users[$pic->owner()];
		
		$rtn = array();
		if($options[3]) $rtn['title'] = $pic->title();
		$rtn['created'] = $tf->format($pic->created());
		$rtn['views'] = $pic->views();
		$rtn['by'] = sprintf(__('by %s','galleries'), '<a href="'.$user->userURL().'">'.$user->uname().'</a>');
		$rtn['link'] = $user->userURL().($mc['urlmode'] ? 'img/'.$pic->id().'/' : '&amp;img='.$pic->id());
		$rtn['file'] = $user->filesURL().'/ths/'.$pic->image();
		$block['pics'][] = $rtn;
	}
    
    RMTemplate::get()->add_xoops_style('blocks.css', 'galleries');
    RMTemplate::get()->add_local_script('blocks.js','galleries');
	
	$block['item_width'] = $options[2];
	return $block;
	
}

function gs_photos_edit($options){
    
    $form = __('Number of pictures:','galleries')."<br /> <input type='text' name='options[0]' value='$options[0]' /><br />";
    $form .= __('Type of pictures:','galleries')."<br /> <select name='options[1]'><option value='0'".($options[1]==0?' checked="checked"' : '').">".__('Recent','galleries')."</option>";
    $form .= "<option value='1'".($options[1]==1?' checked="checked"' : '').">".__('Random','galleries')."</option>";
    $form .= "<option value='2'".($options[1]==2?' checked="checked"' : '').">".__('Top viewed','galleries')."</option></select><br />";
    $form .= __('Item width:','galleries')."<br /> <input type='text' name='options[2]' value='$options[2]' /><br />";
    $form .= __('Show title:','galleries')."<br />";
    $form .= '<label><input type="radio" name="options[3]" value="1"'.($options[3]==1?' checked="checked"':'').' /> '.__('Yes','galleries').'</label>';
    $form .= '<label><input type="radio" name="options[3]" value="0"'.($options[3]==0?' checked="checked"':'').' /> '.__('No','galleries').'</label>';
	
	/*include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsusers.class.php';
	
	$form->addElement(new RMFormUser(_BK_GS_PICUSER, 'options[3]', false, array($options[3]), 40, 500, 300, 1));
	$form->addElement(new RMFormYesNo(_BK_GS_PICDESC, 'options[4]', $options[4]));*/
	
	return $form;
}
