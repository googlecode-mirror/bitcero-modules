<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

function mywordsBlockCats($options){
	global $xoopsModuleConfig, $xoopsModule;
	
	$categos = array();
	MWFunctions::categos_list($categos, 0, 0, $options[0]);
	$block = array();
	$mc = $xoopsModule && $xoopsModule->getVar('dirname')=='mywords' ? $xoopsModuleConfig : RMUtilities::get()->module_config('mywords');
	foreach ($categos as $k){
		$ret = array();
		$cat = new MWCategory();
		$cat->assignVars($k);
		$cat->loadPosts();
		$ret['id'] = $cat->id();
		$ret['name'] = $cat->getVar('name');
		if (isset($options[1]) && $options[1]) $ret['posts'] = $cat->getVar('posts');
		$ret['indent'] = $k['indent'] * 2;
		$ret['link'] = $cat->permalink();
		$block['categos'][] = $ret;
	}
	
    RMTemplate::get()->add_xoops_style('mwblocks.css', 'mywords');
    
	return $block;
	
}

function mywordsBlockCatsEdit($options){
	$form = '<strong>'.__('Show subcategories:','mywords').'
			<label><input type="radio" name="options[0]" value="1"'.($options[0] ? ' checked="checked"' : '').' /> '.__('Yes','mywords').'
	        </label><label><input type="radio" name="options[0]" value="0"'.($options[0]==0 ? ' checked="checked"' : '').' /> '.__('No','mywords').'
            </label><br /><br />
            <strong>'.__('Show posts number:','mywords').'
            <label><input type="radio" name="options[1]" value="1"'.(isset($options[1]) && $options[1]==1 ? ' checked="checked"' : '').' /> '.__('Yes','mywords').'
            </label><label><input type="radio" name="options[1]" value="0"'.(!isset($options[1]) ||$options[1]==0 ? ' checked="checked"' : '').' /> '.__('No','mywords').'
            </label>';
	return $form;
}
