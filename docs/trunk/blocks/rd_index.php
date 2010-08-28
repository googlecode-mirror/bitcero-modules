<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rd_block_index($options){
	global $xoopsModule, $xoopsModuleConfig, $res, $sec;
	
	if (!$xoopsModule || $xoopsModule->dirname()!='docs') return;
	if (!defined('RD_LOCATION') || (RD_LOCATION!='content' && RD_LOCATION!='resource_content')) return;
	
	// get the sections
	$sections = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $sections, false);
	
	$block['sections'] = $sections;
	$block['section'] = $sec;
	$block['resource'] = $res->getVar('nameid');
	return $block;
}
