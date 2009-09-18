<?php
// $Id: block.cats.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function mywordsBlockCats($options){
	global $xoopsConfig, $mc;
	include_once XOOPS_ROOT_PATH.'/modules/mywords/include/general.func.php';
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
	$categos = array();
	arrayCategos($categos,0,0);
	$block = array();
	$util =& RMUtils::getInstance();
	$mc =& $util->moduleConfig('mywords');
	foreach ($categos as $k){
		$ret = array();
		$catego = new MWCategory();
		$catego->assignVars($k);
		$catego->loadPosts();
		$ret['id'] = $k['id_cat'];
		$ret['nombre'] = $k['nombre'];
		$ret['numposts'] = $catego->getPosts();
		$ret['saltos'] = $k['saltos'] * 2;
		$ret['link'] = $catego->getLink();
		$block['categos'][] = $ret;
	}
	
	return $block;
	
}

function mywordsBlockCatsEdit($options){
	$form = _MB_MW_SHOWSUBCATS . '<br />
			<input type="radio" name="options[0]" value="1"'.($options[0] ? ' checked="checked"' : '').' /> '._YES;
	$form .= '<input type="radio" name="options[0]" value="0"'.($options[0]==0 ? ' checked="checked"' : '').' /> '._NO;
	return $form;
}

?>