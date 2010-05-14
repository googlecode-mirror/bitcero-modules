<?php
// $Id$
// --------------------------------------------------------------
// booster plugin for Common Utilities
// Speed up your Xoops web site with booster
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','booster');

function cache_show_options(){
	
	RMTemplate::get()->set_help('http://redmexico.com.mx/docs/xoops-booster/como-funciona');
	
    RMFunctions::create_toolbar();
	
	$plugin = RMFunctions::get()->load_plugin('booster');
	RMTemplate::get()->assign('xoops_pagetitle', __('Booster Zone','booster'));
	RMTemplate::get()->add_style('cache.css', 'rmcommon', 'plugins/booster');
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('cache_index.php', 'plugin', 'rmcommon', 'booster');
    
    xoops_cp_footer();
}


function cache_view_files(){
	global $xoopsSecurity;
	
	RMTemplate::get()->set_help('http://redmexico.com.mx/docs/xoops-booster/archivos-del-cache');
	
	RMTemplate::get()->assign('xoops_pagetitle', __('Booster Zone','booster'));
	RMTemplate::get()->add_style('cache.css', 'rmcommon', 'plugins/booster');
	RMFunctions::create_toolbar();
	xoops_cp_header();
	
	$plugin = RMFunctions::get()->load_plugin('booster');
	// Get files
	$items = XoopsLists::getFileListAsArray(XOOPS_CACHE_PATH.'/booster/files');
	$files = array();
	$count_expired = 0;
	foreach($items as $file){
		$tmp = explode('.',$file);
		if ($tmp[1]!='meta') continue;
		$content = json_decode(file_get_contents(XOOPS_CACHE_PATH.'/booster/files/'.$file), true);
		$files[] = array(
			'id' 	=> $tmp[0],
			'url'	=> $content['uri'],
			'date'	=> formatTimestamp($content['created'], 'l'),
			'time'	=> $content['created'],
			'size'	=> filesize(XOOPS_CACHE_PATH.'/booster/files/'.$tmp[0].'.html')
		);
		$count_expired = time()-$content['created']>=$plugin->get_config('time') ? $count_expired+1 : $count_expired;
	}
	
	include RMTemplate::get()->get_template('cache_files.php', 'plugin', 'rmcommon', 'booster');
	
	
	xoops_cp_footer();
	
}


function cache_delete_expired(){
	global $xoopsSecurity;
	
	if (!$xoopsSecurity->check()){
		redirectMsg('plugins.php?p=booster&action=view', __('Session token expired!','booster'), 1);
		die();
	}
	
	$plugin = RMFunctions::get()->load_plugin('booster');
	$items = XoopsLists::getFileListAsArray(XOOPS_CACHE_PATH.'/booster/files');
	$files = array();
	foreach($items as $file){
		$tmp = explode('.',$file);
		if ($tmp[1]!='meta') continue;
		$content = json_decode(file_get_contents(XOOPS_CACHE_PATH.'/booster/files/'.$file), true);
		if (time()-$content['created']>=$plugin->get_config('time')){
			@unlink(XOOPS_CACHE_PATH.'/booster/files/'.$file);
			@unlink(XOOPS_CACHE_PATH.'/booster/files/'.$tmp[0].'.html');
		}
	}
	
	redirectMsg('plugins.php?p=booster&action=view', __('Expired pages deleted successfully','boost'), 0);
	die();
	
}

function cache_delete_file(){
	global $xoopsSecurity;
	
	if (!$xoopsSecurity->check()){
		redirectMsg('plugins.php?p=booster&action=view', __('Session token expired!','booster'), 1);
		die();
	}
    
    $file = rmc_server_var($_POST, 'file', '');
    
    if($file==''){
		redirectMsg('plugins.php?p=booster&action=view', __('You must specify a file to delete!','booster'), 1);
		die();
    }
    
    $path = XOOPS_CACHE_PATH.'/booster/files/';
    if (!file_exists($path.$file.'.html')){
    	redirectMsg('plugins.php?p=booster&action=view', __('Selected file does not exists!','booster'), 1);
		die();
	}
	
	@unlink($path.$file.'.html');
	@unlink($path.$file.'.meta');
	
	redirectMsg('plugins.php?p=booster&action=view', __('File deleted successfully!','booster'), 0);
	
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'disable':
        $config = $this->get_config();
        $config['enabled'] = 0;
        $this->write_file($config);
        redirectMsg('plugins.php?p=booster', __('booster has been disabled','booster'), 0);
        break;
    case 'enable':
        $config = $this->get_config();
        $config['enabled'] = 1;
        $this->write_file($config);
        redirectMsg('plugins.php?p=booster', __('booster has been enabled','booster'), 0);
        break;
    case 'save':
        $duration = rmc_server_var($_POST, 'duration', 600);
        $prevent = rmc_server_var($_POST, 'prevent', '');
        $duration = $duration<=0 ? 600 : $duration;
        $config = $this->get_config();
        $config['time'] = $duration;
        $prevent = explode("\n", $prevent);
        $config['prevent'] = $prevent;
        $this->write_file($config);
        redirectMsg('plugins.php?p=booster', __('Settings saved!','booster'), 0);
        break;
    case 'clean':
    
        $files = XoopsLists::getFileListAsArray(XOOPS_CACHE_PATH.'/booster/files');
        foreach($files as $file){
            unlink(XOOPS_CACHE_PATH.'/booster/files/'.$file);
        }
        redirectMsg('plugins.php?p=booster', __('Cache deleted successfully!','booster'), 0);
        break;
    case 'view':
    	
    	cache_view_files();
    	break;
    
    case 'expired':
    	
    	cache_delete_expired();
    	break;
    
    case 'delfile':
    	
    	cache_delete_file();
    	break;
    	
    default:
        cache_show_options();
        break;
}