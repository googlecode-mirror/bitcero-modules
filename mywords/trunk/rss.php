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
	case 'all':
	default:
		$posts = MWFunctions::get_posts(0, 10);
		$rss_channel['title'] = $xoopsModule->name();
		$rss_channel['link'] = XOOPS_URL.($config['permalinks'] ? $config['basepath'] : '/modules/mywords');
        $rss_channel['description'] = __('All recent published posts','mywords');
		$rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
		$rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['category'] = 'Blog';
	    $rss_channel['generator'] = 'Common Utilities';
	    $rss_channel['language'] = RMCLANG;
		break;
}

