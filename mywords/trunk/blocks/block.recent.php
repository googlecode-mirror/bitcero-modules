<?php
// $Id: block.recent.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function mywordsBlockRecent($options){
	
	global $xoopsConfig;
	include_once XOOPS_ROOT_PATH.'/modules/mywords/include/general.func.php';
	include_once XOOPS_ROOT_PATH.'/rmcommon/object.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
	
	$util = new RMUtils();
	$mc = array();
	global $mc;
	$mc = $util->moduleConfig('mywords');
	$db =& Database::getInstance();
	$result = $db->queryF("SELECT * FROM ".$db->prefix("mw_posts")." ORDER BY fecha DESC LIMIT 0, $options[2]");
	$block = array();
	while ($row = $db->fetchArray($result)){
		$ret = array();
		$post = new MWPost();
		$post->assignVars($row);
		$ret['id'] = $post->getID();
		$ret['titulo'] = $post->getTitle();
		$ret['link'] = $post->getPermaLink();
		if ($options[0]){
			$texto = array();
			$texto = explode(" ", $util->filterTags($post->getText()));
			if (count($texto)>$options[1]) $texto = array_slice($texto, 0, $options[1]);
			$ret['texto'] = join(" ",$texto); //substr($texto, 0, $options[1]);
		}
		if ($options[3]) $ret['fecha'] = date($options[4],$post->getDate());
		if ($options[5]) $ret['image'] = $post->getBlockImage()!='' ? XOOPS_URL.'/uploads/mywords/'.$post->getBlockImage() : $mc['defimg'];
		$block['posts'][] = $ret;
	}
	$block['showimage'] = $options[5];
	$block['size'] = $mc['imgsize'];
	return $block;
}

function mywordsBlockRecentEdit($options, &$form){
	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS,1));
	$form->addElement(new RMYesNo(_MB_MW_SHOWTEXT, 'options[0]', $options[0]));
	$form->addElement(new RMText(_MB_MW_TEXTLENGHT, 'options[1]', 5, 3, $options[1]));
	$form->addElement(new RMText(_MB_MW_POSTSNUM, 'options[2]', 5, 3, $options[2]));
	$form->addElement(new RMYesNo(_MB_MW_SHOWDATE, 'options[3]', $options[3]));
	$form->addElement(new RMText(_MB_MW_DATEFORMAT, 'options[4]', 15, 50, $options[4]));
	$form->addElement(new RMYesNo(_MB_MW_SHOWIMAGES, 'options[5]', $options[5]));
	return $form;
}
?>