<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include 'header.php';

$xoopsOption['template_main'] = "mch_index.html";
$xoopsOption['module_subpage'] = 'index';
include XOOPS_ROOT_PATH.'/header.php';

// Get current match
$id_champ = rmc_server_var($_REQUEST, 'ch',0);
if($id_champ<=0){
    $champ =  MCHFunctions::current_championship();
} else {
    $champ = new MCHChampionship($id_champ);
}

if(!is_object($champ)) $champ = MCHFunctions::last_championship();

// Time Formatter
$tf = new RMTimeFormatter(0, __('%M% %d%, %Y%','match'));

$xoopsTpl->assign('champ', array(
    'id' => $champ->id(),
    'name' => $champ->getVar('name'),
    'start' => $tf->format($champ->getVar('start')),
    'end' => $tf->format($champ->getVar('end'))
));

// Get category
$id_cat = rmc_server_var($_REQUEST, 'cat', 0);
if($id_cat>0)
    $category = new MCHCategory($id_cat);
else
    $category = MCHFunctions::first_category();

$xoopsTpl->assign('category', array(
    'id' => $category->id(),
    'name' => $category->getVar('name'),
    'desc' => $category->getVar('description')
));

// Results
$data = MCHFunctions::latest_results($category->id(), $champ->id(), 20);
$xoopsTpl->assign('results', $data);

$categories = array();
MCHFunctions::categories_tree($categories);
foreach($categories as $k => $cat){
    $categories[$k]['guion'] = str_repeat("&#151;", $cat['indent']);
}
$xoopsTpl->assign('categories', $categories);

// Championships
$xoopsTpl->assign('champs', MCHFunctions::all_championships());

// Constants
$xoopsTpl->assign('mch_upurl', MCH_UP_URL);

// Language
$xoopsTpl->assign('lang_ranking', __('Ranking','match'));
$xoopsTpl->assign('lang_results', __('Results','match'));
$xoopsTpl->assign('lang_wons', __('Won matches:','match'));
$xoopsTpl->assign('lang_netxmatches', __('Next Matches','match'));
$xoopsTpl->assign('lang_local', __('Local Team','match'));
$xoopsTpl->assign('lang_visitor', __('Visitor Team','match'));
$xoopsTpl->assign('lang_score', __('Score','match'));
$xoopsTpl->assign('lang_info', __('Data','match'));
$xoopsTpl->assign('lang_current', __('(Current)','match'));
$xoopsTpl->assign('lang_selectchamp', __('Select championship...','match'));
$xoopsTpl->assign('lang_selectcat', __('Select category...','match'));

$xoopsTpl->assign('xoops_pagetitle', $champ->getVar('name').' &raquo; '.$category->getVar('name').' - '.$xoopsModuleConfig['title']);

RMTemplate::get()->add_xoops_style('match.css','match');
RMTemplate::get()->add_local_script('main.js','match');

include XOOPS_ROOT_PATH.'/footer.php';
