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
	global $xoopsModuleConfig, $xoopsModule, $xoopsUser;
    
	$mc = $xoopsModule && $xoopsModule->getVar('dirname')=='mywords' ? $xoopsModuleConfig : RMUtilities::module_config('mywords');
	$db =& Database::getInstance();
    RMTemplate::get()->add_xoops_style('blocks.css', 'mywords');
    
    switch($options[1]){
        case 'recent':
            $sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE status='publish' AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY pubdate DESC LIMIT 0,$options[0]";
            break;
        case 'popular':
            $sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE status='publish' AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY `reads` DESC LIMIT 0,$options[0]";
            break;
        case 'comm':
            $sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE status='publish' AND ((visibility='public' OR visibility='password') OR (visibility='private' AND author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY `comments` DESC LIMIT 0,$options[0]";
            break;
    }
    
	$result = $db->query($sql);
	$block = array();
	while ($row = $db->fetchArray($result)){
		$ret = array();
		$post = new MWPost();
		$post->assignVars($row);
		$ret['id'] = $post->id();
		$ret['title'] = $post->getVar('title');
		$ret['link'] = $post->permalink();
        // Content
        if ($options[2]){
            $ret['content'] = TextCleaner::getInstance()->truncate($post->content(true), $options[3]);
        }
        // Pubdate
        if ($options[4]){
            $ret['date'] = formatTimestamp($post->getVar('pubdate'), 'c');
        }
        // Show reads
        if ($options[1]=='popular'){
            $ret['hits'] = sprintf(__('%u Reads','mywords'), $post->getVar('reads'));
        } elseif($options[1]=='comm'){
            $ret['comments'] = sprintf(__('%u Comments','mywords'), $post->getVar('comments'));
        }
		$block['posts'][] = $ret;
	}
	return $block;
}

function mywordsBlockRecentEdit($options){
    
    $form = '<strong>'.__('Posts Number:','mywords').'</strong><br />
            <input type="text" size="10" value="'.$options[0].'" name="options[0]" /><br /><br />
            <strong>'.__('Block type:','mywords').'</strong><br />
            <label><input type="radio" name="options[1]"'.(!isset($options[1]) || $options[1]=='recent' ? ' checked="checked"' : '').' value="recent" />
            '.__('Recent Posts','mywords').'</label>
            <label><input type="radio" name="options[1]"'.(isset($options[1]) && $options[1]=='popular' ? ' checked="checked"' : '').' value="popular" />
            '.__('Popular Posts','mywords').'</label>
            <label><input type="radio" name="options[1]"'.(isset($options[1]) && $options[1]=='comm' ? ' checked="checked"' : '').' value="comm" />
            '.__('Most Commented','mywords').'</label><br /><br />
            <strong>'.__('Show text:','mywords').'</strong><br />
            <label><input type="radio" name="options[2]" value="1"'.(isset($options[2]) && $options[2]==1 ? ' checked="checked"' : '').' /> '.__('Yes','mywords').'</label> 
            <label><input type="radio" name="options[2]" value="0"'.(!isset($options[2]) || $options[2]==0 ? ' checked="checked"' : '').' /> '.__('No','mywords').'</label>
            <br /><br />
            <strong>Text lenght:</strong> <input type="text" size="10" value="'.(isset($options[3]) ? $options[3] : 50).'" name="options[3]" />
            <br /><br /><strong>'.__('Show date:','mywords').'</strong> 
            <label><input type="radio" name="options[4]" value="1"'.(isset($options[4]) && $options[4]==1 ? ' checked="checked"' : '').' /> '.__('Yes','mywords').'</label> 
            <label><input type="radio" name="options[4]" value="0"'.(!isset($options[4]) || $options[4]==0 ? ' checked="checked"' : '').' /> '.__('No','mywords').'</label>
            ';
    
    return $form;
    
}
