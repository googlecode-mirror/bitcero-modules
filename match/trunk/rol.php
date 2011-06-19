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

$xoopsOption['template_main'] = "mch_rol.html";
$xoopsOption['module_subpage'] = 'index';
include XOOPS_ROOT_PATH.'/header.php';

// Get current match
$id_champ = rmc_server_var($_REQUEST, 'ch',0);
if($id_champ<=0){
    $champ =  MCHFunctions::current_championship();
} else {
    $champ = new MCHChampionship($id_champ);
}

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

// Get category
$id_team = rmc_server_var($_REQUEST, 'team', 0);
if($id_team>0){
    $team = new MCHTeam($id_team);
    $xoopsTpl->assign('team', array(
        'id' => $team->id(),
        'name' => $team->getVar('name'),
        'desc' => $team->getVar('info')
    ));
}

// Role
$data = MCHFunctions::next_matches($category->id(), $champ->id(), 0, $id_team);
$xoopsTpl->assign('roleplay', $data);

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

// Labguage
$xoopsTpl->assign('lang_selectchamp', __('Select championship...','match'));
$xoopsTpl->assign('lang_selectcat', __('Select category...','match'));
$xoopsTpl->assign('lang_roleplay', __('Role Play','match'));
$xoopsTpl->assign('lang_date', __('Date','match'));
$xoopsTpl->assign('lang_time', __('Time','match'));
$xoopsTpl->assign('lang_field', __('Field','match'));
$xoopsTpl->assign('lang_local', __('Local','match'));
$xoopsTpl->assign('lang_visitor', __('Visitor','match'));
$xoopsTpl->assign('lang_viewall', __('Ver Todos','match'));
if($id_team>0) $xoopsTpl->assign('lang_forteam',sprintf(__('Role Play for %s','match'), $team->getVar('name')));

RMTemplate::get()->add_xoops_style('match.css', 'match');
RMTemplate::get()->add_local_script('main.js', 'match');


include 'footer.php';
