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
    RMFunctions::create_toolbar();
	
	$plugin = RMFunctions::get()->load_plugin('booster');
	RMTemplate::get()->add_style('cache.css', 'rmcommon', 'plugins/booster');
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('cache_index.php', 'plugin', 'rmcommon', 'booster');
    
    xoops_cp_footer();
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
        
    default:
        cache_show_options();
        break;
}