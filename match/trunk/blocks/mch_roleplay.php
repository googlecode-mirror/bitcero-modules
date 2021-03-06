<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function mch_role_bkshow($options){
    
    include_once XOOPS_ROOT_PATH.'/modules/match/class/mchfunctions.php';
    
    RMTemplate::get()->add_xoops_style('blocks.css','match');
    
    $ch = rmc_server_var($_GET, 'champ', $options[0]);
    $champ = $ch<=0?MCHFunctions::current_championship():new MCHChampionship($ch);
    
    if(!is_object($champ)) $champ = MCHFunctions::last_championship();
    
    $ch = rmc_server_var($_GET, 'cat', $options[1]);
    $category = $ch<=0 ? MCHFunctions::first_category() : new MCHCategory($ch);
    
    // Role
    $data = MCHFunctions::next_matches($category->id(), $champ->id(), $options[2]);
    $block = array();
    
    $block['role'] = $data;
    $mc = RMUtilities::module_config('match');
    $block['link'] = $mc['urlmode'] ? XOOPS_URL.'/'.$mc['htbase'] : XOOPS_URL.'/modules/match';
    $block['lang_viewrol'] = __('View full roleplay &raquo;');
    $block['category'] = $category->id();
    
    return $block;
    
}

function mch_role_bkedit($options){
    
    $ch = $options[0];
    $cat = $options[1];
    $limit = $options[2];
    
    include_once XOOPS_ROOT_PATH.'/modules/match/class/mchfunctions.php';
    
    $form = '<label><strong>'.__('Championship:','match').'</strong><br />';
    $form .= '<select name="options[0]">';
    $form .= '<option value="0"'.($ch==0?' selected="selected"':'').">".__('From user selected','match').'</option>';
    $champs = MCHFunctions::all_championships();
    
    foreach($champs as $c){
        $form .= '<option value="'.$c['id'].'"'.($ch==$c['id']?' selected="selected"':'').'>'.$c['name'].'</option>';
    }
    
    $form .= '</select></label><br /><br />';
    
    $form .= '<label><strong>'.__('Category','match').'</strong><br />';
    $form .= '<select name="options[1]">';
    $form .= '<option value="0"'.($cat==0?' selected="selected"':'').">".__('From user selected','match').'</option>';
    $categories = array();
    MCHFunctions::categories_tree($categories);
    
    foreach($categories as $c){
        $form .= '<option value="'.$c['id_cat'].'"'.($cat==$c['id_cat']?' selected="selected"':'').'>'.str_repeat("&#151;",$c['indent']).$c['name'].'</option>';
    }
    
    $form .= '</select></label><br /><br />';
    
    $form .= '<label><strong>'.__('Number of items:','match').'</strong> ';
    $form .= '<input type="text" name="options[3]" value="'.$limit.'" size="5" />';
    
    return $form;
    
}
