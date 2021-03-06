<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
load_mod_locale("mywords");
$show = rmc_server_var($_GET,'show','all');

$xoopsModule = RMFunctions::load_module('mywords');
$config = RMUtilities::module_config('mywords');
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

$rss_channel = array();

switch($show){
	case 'cat':
		include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
		$id = rmc_server_var($_GET,'cat',0);
		if ($id<=0){
			redirect_header('backend.php', 1, __('Sorry, specified category was not foud!','mywords'));
			die();
		}
		
		$cat = new MWCategory($id);
		if ($cat->isNew()){
			redirect_header('backend.php', 1, __('Sorry, specified category was not foud!','mywords'));
			die();
		}
		
		$rss_channel['title'] = $xoopsModule->name();
		$rss_channel['link'] = $cat->permalink();
        $rss_channel['description'] = htmlspecialchars($cat->getVar('description'), ENT_QUOTES);
		$rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
		$rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['category'] = $cat->getVar('name');
	    $rss_channel['generator'] = 'Common Utilities';
	    $rss_channel['language'] = RMCLANG;
	    
	    $posts = MWFunctions::get_posts_by_cat($id, 0, 10);
	    $rss_items = array();
	    foreach($posts as $post){
	    	$item = array();
			$item['title'] = $post->getVar('title');
			$item['link'] = $post->permalink();
			$item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($post->content(true), ENT_QUOTES));
			$item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
			$item['guid'] = $post->permalink();
			$rss_items[] = $item;
	    }
	    
		break;
	
	case 'tag':
		include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwtag.class.php';
		$id = rmc_server_var($_GET,'tag',0);
		if ($id<=0){
			redirect_header('backend.php', 1, __('Sorry, specified tag was not foud!','mywords'));
			die();
		}
		
		$tag = new MWTag($id);
		if ($tag->isNew()){
			redirect_header('backend.php', 1, __('Sorry, specified tag was not foud!','mywords'));
			die();
		}
		
		$rss_channel['title'] = $xoopsModule->name();
		$rss_channel['link'] = $tag->permalink();
        $rss_channel['description'] = sprintf(__('Posts tagged as %s','mywords'), $tag->getVar('tag'));
		$rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
		$rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['category'] = "Blog";
	    $rss_channel['generator'] = 'Common Utilities';
	    $rss_channel['language'] = RMCLANG;
	    
	    $posts = MWFunctions::get_posts_by_tag($id, 0, 10);
	    $rss_items = array();
	    foreach($posts as $post){
	    	$item = array();
			$item['title'] = $post->getVar('title');
			$item['link'] = $post->permalink();
			$item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($post->content(true), ENT_QUOTES));
			$item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
			$item['guid'] = $post->permalink();
			$rss_items[] = $item;
	    }
	    
		break;
		
	case 'author':
		include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mweditor.class.php';
		$id = rmc_server_var($_GET,'author',0);
		if ($id<=0){
			redirect_header('backend.php', 1, __('Sorry, specified author was not foud!','mywords'));
			die();
		}
		
		$ed = new MWEditor($id);
		if ($ed->isNew()){
			redirect_header('backend.php', 1, __('Sorry, specified author was not foud!','mywords'));
			die();
		}
		
		$rss_channel['title'] = $xoopsModule->name();
		$rss_channel['link'] = $ed->permalink();
        $rss_channel['description'] = sprintf(__('Posts published by %s.','mywords'), $ed->getVar('name')).' '.htmlspecialchars(strip_tags($ed->getVar('bio')), ENT_QUOTES);
		$rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
		$rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['category'] = "Blog";
	    $rss_channel['generator'] = 'Common Utilities';
	    $rss_channel['language'] = RMCLANG;
	    
	    $posts = MWFunctions::get_filtered_posts("author=$id", 0, 10);
	    $rss_items = array();
	    foreach($posts as $post){
	    	$item = array();
			$item['title'] = $post->getVar('title');
			$item['link'] = $post->permalink();
			$item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($post->content(true), ENT_QUOTES));
			$item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
			$item['guid'] = $post->permalink();
			$rss_items[] = $item;
	    }
	    
		break;
	
	case 'all':
	default:
		$rss_channel['title'] = $xoopsModule->name();
		$rss_channel['link'] = XOOPS_URL.($config['permalinks'] ? $config['basepath'] : '/modules/mywords');
        $rss_channel['description'] = __('All recent published posts','mywords');
		$rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
		$rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['category'] = 'Blog';
	    $rss_channel['generator'] = 'Common Utilities';
	    $rss_channel['language'] = RMCLANG;
	    
	    // Get posts
	    $posts = MWFunctions::get_posts(0, 10);
	    $rss_items = array();
	    foreach($posts as $post){
	    	$item = array();
			$item['title'] = $post->getVar('title');
			$item['link'] = $post->permalink();
			$item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($post->content(true), ENT_QUOTES));
			$item['pubdate'] = formatTimestamp($post->getVar('pubdate'), 'rss');
			$item['guid'] = $post->permalink();
			$rss_items[] = $item;
	    }
	    
		break;
}

