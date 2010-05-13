<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

function ahelp_block_index($options){
	global $xoopsModule, $xoopsModuleConfig, $res, $id_sec;
	
	if ($xoopsModule->dirname()!='ahelp') return;
	if (!defined('AH_LOCATION') || AH_LOCATION!='content') return;
	
	// get the sections
	$sections = array();
	assignSectionTree(0, 0, $res, '', '', false, $sections);
	
	// Get the references and figures for this resource
	$refs =& $res->get_references();
	$figs =& $res->get_figures();
	
	if (!empty($refs) || !empty($figs)){
		$sections[]  = array('title'=>_BS_AH_SPECIALS, 'nameid'=>'', 'link'=>'', 'jump'=>0, 'number'=>'');
	}
	
	if (!empty($figs)){
		$sections[] = array(
			'title'=>_BS_AH_FIGS,
			'nameid'=>'',
			'link'=>AHURL.'/figures/'.$res->nameId(),
			'jump'=>1,
			'number'=>'a'
		);
	}
	
	if (!empty($refs)){
		$sections[] = array(
			'title'=>_BS_AH_REFS,
			'nameid'=>'',
			'link'=>AHURL.'/references/'.$res->nameId(),
			'jump'=>1,
			'number'=>'b'
		);
	}
	
	$block['sections'] = $sections;
	$block['section'] = $id_sec;
	$block['resource'] = $res->nameId();
	$block['ah_url'] = ah_make_link();
	return $block;
}
