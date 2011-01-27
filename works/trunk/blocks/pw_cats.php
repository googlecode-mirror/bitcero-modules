<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function pw_categories_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';

	$db =& Database::getInstance();

	if (isset($xoopsModule) && ($xoopsModule->dirname()=='works')){
		$mc =& $xoopsModuleConfig;
	}else{
		$mc =& RMUtilities::module_config('works');
	}
	
	$db =& Database::getInstance();
	$result = $db->query("SELECT * FROM ".$db->prefix("pw_categos")." ORDER BY name");
	
	$block = array();
	
	while($row = $db->fetchArray($result)){
        $cat = new PWCategory();
        $cat->assignVars($row);
		$ret = array();
		$ret['name'] = $row['name'];
		$ret['link'] = $cat->link();
		$block['categos'][] = $ret;
	}
	
	return $block;
}
