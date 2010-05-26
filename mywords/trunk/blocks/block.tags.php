<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwtag.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

function myWordsBlockTags($options){
    
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".$db->prefix("mw_tags")." ORDER BY RAND() LIMIT 0,$options[0]";
    $result = $db->query($sql);
    
    $block = array();
    $max = 0;
    $min = 0;
    while($row = $db->fetchArray($result)){
        $tag = new MWTag();
        $tag->assignVars($row);
        $block['tags'][] = array(
            'id'=>$tag->id(),
            'posts'=>$tag->getVar('posts'),
            'link'=>$tag->permalink(),
            'name'=>$tag->getVar('tag'),
            'size'=>($options[1] * $tag->getVar('posts') + 0.9)
        );
    }
    
    RMTemplate::get()->add_xoops_style('mwblocks.css', 'mywords');
    
    return $block;
}

function myWordsBlockTagsEdit($options){
    
    $form = "<strong>".__('Number of tags','mywords').'</strong><br />
            <input type="text" size="5" name="options[0]" value="'.$options[0].'" /><br /><br />
            <strong>'.__('Size increment per post','mywords').'</strong><br />
            <input type="text" size="5" name="options[1]" value="'.$options[1].'" />';
    
    return $form;
    
}
