<?php
// $Id$
// --------------------------------------------------------------
// Cachetizer plugin for Common Utilities
// Speed up your Xoops web site with cachetizer
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','cachetizer');

function cache_show_options(){
    RMFunctions::create_toolbar();
	
	$plugin = RMFunctions::get()->load_plugin('cachetizer');
	RMTemplate::get()->add_style('cache.css', 'rmcommon', 'plugins/cachetizer');
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('cache_index.php', 'plugin', 'rmcommon', 'cachetizer');
    
    xoops_cp_footer();
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'disable':
        $config = $this->get_config();
        $config['enabled'] = 0;
        $this->write_file($config);
        redirectMsg('plugins.php?p=cachetizer', __('Cachetizer has been disabled','cachetizer'), 0);
        break;
    case 'enable':
        $config = $this->get_config();
        $config['enabled'] = 1;
        $this->write_file($config);
        redirectMsg('plugins.php?p=cachetizer', __('Cachetizer has been enabled','cachetizer'), 0);
        break;
    case 'save':
        
        $duration = rmc_server_var($_POST, 'duration', 600);
        $duration = $duration<=0 ? 600 : $duration;
        $config = $this->get_config();
        $config['time'] = $duration;
        $this->write_file($config);
        redirectMsg('plugins.php?p=cachetizer', __('Settings saved!','cachetizer'), 0);
        break;
    
    case 'clean':
    
        $files = XoopsLists::getFileListAsArray(XOOPS_CACHE_PATH.'/cachetizer/files');
        foreach($files as $file){
            unlink(XOOPS_CACHE_PATH.'/cachetizer/files/'.$file);
        }
        redirectMsg('plugins.php?p=cachetizer', __('Cache deleted successfully!','cachetizer'), 0);
        break;
        
    default:
        cache_show_options();
        break;
}