<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$show = rmc_server_var($_GET,'show','all');

$xoopsModule = RMFunctions::load_module('mywords');
$config = RMUtilities::module_config('mywords');
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';

switch($show){
	case 'all':
	default:
		$posts = MWFunctions::get_posts(0, 10);
		$tpl->assign('channel_title', $xoopsModule->name());
		$tpl->assign('channel_link', XOOPS_URL.($config['permalinks'] ? $config['basepath'] : '/modules/mywords'));
		$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
		$tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true));
	    $tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true));
	    $tpl->assign('channel_category', 'Blog');
	    $tpl->assign('channel_generator', 'Common Utilities');
	    $tpl->assign('channel_language', RMCLANG);
		break;
}

