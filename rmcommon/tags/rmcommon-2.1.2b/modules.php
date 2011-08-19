<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','modules');
include_once '../../include/cp_header.php';

function show_modules_list(){
	global $xoopsSecurity;
    
    $installed_modules = array();
    
    $limit = rmc_server_var($_SESSION, 'mods_limit', 4);
    
    include_once XOOPS_ROOT_PATH.'/kernel/module.php';
    
    $db = Database::getInstance();

    $sql = "SELECT * FROM ".$db->prefix("modules")." ORDER BY `name`";
    $result = $db->query($sql);
    $installed_dirs = array();
    
    while($row = $db->fetchArray($result)){
        $mod = new XoopsModule();
        $mod->assignVars($row);
        $installed_dirs[] = $mod->dirname();
        
        if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$mod->getVar('dirname').'/class/'.strtolower($mod->getVar('dirname').'controller').'.php')){
            include_once XOOPS_ROOT_PATH.'/modules/'.$mod->getVar('dirname').'/class/'.strtolower($mod->getVar('dirname').'controller').'.php';
            $class = ucfirst($mod->getVar('dirname')).'Controller';
            $class = new $class();
            if (method_exists($class, 'get_main_link')){
                $main_link = $class->get_main_link();
            } else {
                
            if ($mod->getVar('hasmain')){
                $main_link = XOOPS_URL.'/modules/'.$mod->dirname();
            } else {
                $main_link = "#";
            }
                
            }
        } else {
            
            if ($mod->getVar('hasmain')){
                $main_link = XOOPS_URL.'/modules/'.$mod->dirname();
            } else {
                $main_link = "#";
            }
            
        }
        
        // Admin section
        $admin_link = $mod->getVar('hasadmin') ? XOOPS_URL.'/modules/'.$mod->dirname().'/'.$mod->getInfo('adminindex') : '';
        
        $modules[] = array(
            'id'            => $mod->getVar('mid'),
            'name'            => $mod->getVar('name'),
            'realname'        => $mod->getInfo('name'),
            'version'        => $mod->getInfo('rmnative') ? RMUtilities::format_version($mod->getInfo('rmversion')) : $mod->getInfo('version'),
            'description'    => $mod->getInfo('description'),
            'image'            => XOOPS_URL.'/modules/'.$mod->getVar('dirname').'/'.$mod->getInfo('image'),
            'link'            => $main_link,
            'admin_link'    => $admin_link,
            'updated'        => formatTimestamp($mod->getVar('last_update'), 's'),
            'author'        => $mod->getInfo('author'),
            'author_mail'    => $mod->getInfo('authormail'),
            'author_web'    => $mod->getInfo('authorweb'),
            'author_url'    => $mod->getInfo('authorurl'),
            'license'        => $mod->getInfo('license'),
            'dirname'        => $mod->getInfo('dirname'),
            'active'        => $mod->getVar('isactive')
        );
    }
    
    // Event for installed modules
    $modules = RMEvents::get()->run_event('rmcommon.installed.modules', $modules, $installed_dirs);
    
    require_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    $dirlist = XoopsLists::getModulesList();
    $available_mods = array();
    $module_handler = xoops_gethandler('module');
    foreach ($dirlist as $file) {
        clearstatcache();
        $file = trim($file);
        if (!in_array($file, $installed_dirs)) {
            $module =& $module_handler->create();
            if (!$module->loadInfo($file, false)) {
                continue;
            }
            $available_mods[] = $module;
            unset($module);
        }
    }
    
    // Event for available modules
    $available_mods = RMEvents::get()->run_event('rmcommon.available.modules', $available_mods);
    
    $GLOBALS['available_mods'] = $available_mods;
	
	RMFunctions::create_toolbar();
	RMTemplate::get()->assign('xoops_pagetitle', __('Modules Management','rmcommon'));
	RMTemplate::get()->add_style('modules.css', 'rmcommon');
	RMTemplate::get()->add_script(RMCURL.'/include/js/modules.js');
	xoops_cp_header();
	include RMTemplate::get()->get_template('rmc_modules.php', 'module', 'rmcommon');
	xoops_cp_footer();
	
}

/**
* This function show a resume of the changes that will be made by module
*/
function module_install(){
    global $xoopsSecurity;
    
    $dir = rmc_server_var($_GET,'dir','');
    
    if ($dir=='' || !file_exists(XOOPS_ROOT_PATH.'/modules/'.$dir.'/xoops_version.php')){
        redirectMsg('modules.php', __('Specified module is not valid!','rmcommon'), 1);
        die();
    }
    
    $module_handler = xoops_gethandler('module');
	
	if ($module = $module_handler->getByDirname($dir)){
		redirectMsg('modules.php', sprintf(__('%s is already installed!', 'rmcommon'), $module->name()), 1);
		die();
	}
    
    $module =& $module_handler->create();
    if (!$module->loadInfo($dir, false)) {
        redirectMsg('modules.php',__('Sepecified module is not a valid Xoops Module!','rmcommon'), 1);
        die();
    }
    
    $module = RMEvents::get()->run_event('rmcommon.preinstall.module', $module);
    
    RMTEmplate::get()->add_script('include/js/modules.js');
    RMTemplate::get()->add_style('modules.css', 'rmcommon');
    RMFunctions::create_toolbar();
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('rmc_mod_preinstall.php', 'module', 'rmcommon');
    
    xoops_cp_footer();
    
}

function module_install_now(){
	global $xoopsSecurity, $xoopsConfig;
	
	$mod = rmc_server_var($_POST, 'module', '');
	
	if (!$xoopsSecurity->check()){
		redirectMsg('modules.php?action=install&dir='.$module, __('Sorry, this operation could not be completed!', 'rmcommon'), 1);
		die();
	}
	
	$module_handler = xoops_gethandler('module');
	
	if ($module = $module_handler->getByDirname($mod)){
		redirectMsg('modules.php', sprintf(__('%s is already installed!', 'rmcommon'), $module->name()), 1);
		die();
	}
	
	$file = XOOPS_ROOT_PATH.'/modules/system/language/'.$xoopsConfig['language'].'/admin/modulesadmin.php';
	if (file_exists($file)){
		include_once $file;
	} else {
		include_once str_replace($xoopsConfig['language'], 'english', $file);
	}
	
	
	include_once XOOPS_ROOT_PATH.'/modules/system/admin/modulesadmin/modulesadmin.php';
    
    RMEvents::get()->run_event('rmcommon.installing.module', $mod);
    
	$module_log = xoops_module_install($mod);
    
    $module_log = RMEvents::get()->run_event('rmcommon.module.installed', $module_log, $mod);
	
	RMFunctions::create_toolbar();
    RMTemplate::get()->add_style('modules.css', 'rmcommon');
	xoops_cp_header();
	
    $module = $module_handler->getByDirname($mod);
    $log_title = sprintf(__('Installation log for %s', 'rmcommon'), $module ? $module->name() : $mod);
    $action = rmc_server_var($_POST, 'action', '');
	include RMTemplate::get()->get_template('rmc_mod_log.php','module','rmcommon');
	
	xoops_cp_footer();
	
}


function module_uninstall_now(){
    global $xoopsSecurity, $xoopsConfig;
    
    $mod = rmc_server_var($_POST, 'module', '');
    
    if (!$xoopsSecurity->check()){
        redirectMsg('modules.php', __('Sorry, this operation could not be completed!', 'rmcommon'), 1);
        die();
    }
    
    $module_handler = xoops_gethandler('module');
    
    if (!$module_handler->getByDirname($mod)){
        redirectMsg('modules.php', sprintf(__('Module %s is not installed yet!', 'rmcommon'), $mod), 1);
        die();
    }
    
    $file = XOOPS_ROOT_PATH.'/modules/system/language/'.$xoopsConfig['language'].'/admin/modulesadmin.php';
    if (file_exists($file)){
        include_once $file;
    } else {
        include_once str_replace($xoopsConfig['language'], 'english', $file);
    }
    
    include_once XOOPS_ROOT_PATH.'/modules/system/admin/modulesadmin/modulesadmin.php';
    
    RMEvents::get()->run_event('rmcommon.uninstalling.module', $mod);
    
    $module_log = xoops_module_uninstall($mod);
    
    $module_log = RMEvents::get()->run_event('rmcommon.module.uninstalled', $module_log, $mod);
    
    RMFunctions::create_toolbar();
    RMTemplate::get()->add_style('modules.css', 'rmcommon');
    xoops_cp_header();
    
    $module = new XoopsModule();
    $module->loadInfo($mod, false);
    $log_title = sprintf(__('Uninstall log for %s', 'rmcommon'), $module ? $module->getInfo('name') : $mod);
    $action = rmc_server_var($_POST, 'action', '');
    include RMTemplate::get()->get_template('rmc_mod_log.php','module','rmcommon');
    
    xoops_cp_footer();
    
}


function xoops_module_update($dirname){
    global $xoopsConfig, $xoopsDB;
    
    $dirname = trim($dirname);
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->getByDirname($dirname);
    // Save current version for use in the update function
    $prev_version = $module->getVar('version');
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->clearCache($dirname);
    //xoops_template_clear_module_cache($module->getVar('mid'));
    // we dont want to change the module name set by admin
    $temp_name = $module->getVar('name');
    $module->loadInfoAsVar($dirname);
    $module->setVar('name', $temp_name);
    
    $log = '';
    if (!$module_handler->insert($module)) {
        $log .=  sprintf(__('Could not update %s','rmcommon'), $module->getVar('name'));
    } else {
        $newmid = $module->getVar('mid');
        $msgs = array();
        $msgs[] = sprintf(__('Updating module %s','rmcommon'), $module->getVar('name'));
        $tplfile_handler =& xoops_gethandler('tplfile');
        $deltpl = $tplfile_handler->find('default', 'module', $module->getVar('mid'));
        $delng = array();
        if (is_array($deltpl)) {
            // delete template file entry in db
            $dcount = count($deltpl);
            for ($i = 0; $i < $dcount; $i++) {
                if (!$tplfile_handler->delete($deltpl[$i])) {
                    $delng[] = $deltpl[$i]->getVar('tpl_file');
                }
            }
        }
        $templates = $module->getInfo('templates');
        if ($templates != false) {
            $msgs[] = __('Updating templates...','rmcommon');
            foreach ($templates as $tpl) {
                $tpl['file'] = trim($tpl['file']);
                if (!in_array($tpl['file'], $delng)) {
                    $tpldata =& xoops_module_gettemplate($dirname, $tpl['file']);
                    $tplfile =& $tplfile_handler->create();
                    $tplfile->setVar('tpl_refid', $newmid);
                    $tplfile->setVar('tpl_lastimported', 0);
                    $tplfile->setVar('tpl_lastmodified', time());
                    if (preg_match("/\.css$/i", $tpl['file'])) {
                        $tplfile->setVar('tpl_type', 'css');
                    } else {
                        $tplfile->setVar('tpl_type', 'module');
                    }
                    $tplfile->setVar('tpl_source', $tpldata, true);
                    $tplfile->setVar('tpl_module', $dirname);
                    $tplfile->setVar('tpl_tplset', 'default');
                    $tplfile->setVar('tpl_file', $tpl['file'], true);
                    $tplfile->setVar('tpl_desc', $tpl['description'], true);
                    if (!$tplfile_handler->insert($tplfile)) {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('Template %s could not be inserted!','rmcommon'), "<strong>".$tpl['file']."</strong>").'</span>';
                    } else {
                        $newid = $tplfile->getVar('tpl_id');
                        $msgs[] = '&nbsp;&nbsp;'.sprintf(__('Template %s inserted to the database.','rmcommon'), "<strong>".$tpl['file']."</strong>");
                        if ($xoopsConfig['template_set'] == 'default') {
                            if (!xoops_template_touch($newid)) {
                                $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Could not recompile template %s','rmcommon'), "<strong>".$tpl['file']."</strong>").'</span>';
                            } else {
                                $msgs[] = '&nbsp;&nbsp;<span>'.sprintf(__('Template %s recompiled','rmcommon'), "<strong>".$tpl['file']."</strong>").'</span>';
                            }
                        }
                    }
                    unset($tpldata);
                } else {
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Could not delete old template %s. Aborting update of this file.','rmcommon'), "<strong>".$tpl['file']."</strong>").'</span>';
                }
            }
        }
        $blocks = $module->getInfo('blocks');
        $msgs[] = __('Rebuilding blocks...','rmcommon');
        if ($blocks != false) {
            $showfuncs = array();
            $funcfiles = array();
            foreach ($blocks as $i => $block) {
                if (isset($block['show_func']) && $block['show_func'] != '' && isset($block['file']) && $block['file'] != '') {
                    $editfunc = isset($block['edit_func']) ? $block['edit_func'] : '';
                    $showfuncs[] = $block['show_func'];
                    $funcfiles[] = $block['file'];
                    $template = '';
                    if ((isset($block['template']) && trim($block['template']) != '')) {
                        $content = xoops_module_gettemplate($dirname, $block['template'], true);
                    }
                    if (!$content) {
                        $content = '';
                    } else {
                        $template = $block['template'];
                    }
                    $options = '';
                    if (!empty($block['options'])) {
                        $options = $block['options'];
                    }
                    $sql = "SELECT bid, name FROM ".$xoopsDB->prefix('newblocks')." WHERE mid=".$module->getVar('mid')." AND func_num=".$i." AND show_func='".addslashes($block['show_func'])."' AND func_file='".addslashes($block['file'])."'";
                    $fresult = $xoopsDB->query($sql);
                    $fcount = 0;
                    while ($fblock = $xoopsDB->fetchArray($fresult)) {
                        $fcount++;
                        $sql = "UPDATE ".$xoopsDB->prefix("newblocks")." SET name='".addslashes($block['name'])."', edit_func='".addslashes($editfunc)."', content='', template='".$template."', last_modified=".time()." WHERE bid=".$fblock['bid'];
                        $result = $xoopsDB->query($sql);
                        if (!$result) {
                            $msgs[] = "&nbsp;&nbsp;".sprintf(__('ERROR: Could not update %s'), $fblock['name']);
                        } else {
                            $msgs[] = "&nbsp;&nbsp;".sprintf(__('Block %s updated.','rmcommon'), $fblock['name']).sprintf(__('Block ID: %s','rmcommon'), "<strong>".$fblock['bid']."</strong>");
                            if ($template != '') {
                                $tplfile = $tplfile_handler->find('default', 'block', $fblock['bid']);
                                if (count($tplfile) == 0) {
                                    $tplfile_new =& $tplfile_handler->create();
                                    $tplfile_new->setVar('tpl_module', $dirname);
                                    $tplfile_new->setVar('tpl_refid', $fblock['bid']);
                                    $tplfile_new->setVar('tpl_tplset', 'default');
                                    $tplfile_new->setVar('tpl_file', $block['template'], true);
                                    $tplfile_new->setVar('tpl_type', 'block');
                                } else {
                                    $tplfile_new = $tplfile[0];
                                }
                                $tplfile_new->setVar('tpl_source', $content, true);
                                $tplfile_new->setVar('tpl_desc', $block['description'], true);
                                $tplfile_new->setVar('tpl_lastmodified', time());
                                $tplfile_new->setVar('tpl_lastimported', 0);
                                if (!$tplfile_handler->insert($tplfile_new)) {
                                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Could not update %s template.','rmcommon'), "<strong>".$block['template']."</strong>").'</span>';
                                } else {
                                    $msgs[] = "&nbsp;&nbsp;".sprintf(__('Template %s updated.','rmcommon'), "<strong>".$block['template']."</strong>");
                                    if ($xoopsConfig['template_set'] == 'default') {
                                        if (!xoops_template_touch($tplfile_new->getVar('tpl_id'))) {
                                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Could not recompile template %s','rmcommon'), "<strong>".$block['template']."</strong>").'</span>';
                                        } else {
                                            $msgs[] = "&nbsp;&nbsp;".sprintf(__('Template %s recompiled','rmcommon'), "<strong>".$block['template']."</strong>");
                                        }
                                    }

                                }
                            }
                        }
                    }
                    if ($fcount == 0) {
                        $newbid = $xoopsDB->genId($xoopsDB->prefix('newblocks').'_bid_seq');
                        $block_name = addslashes($block['name']);
                        $block_type = ($module->getVar('dirname') == 'system') ? 'S' : 'M';
                        $sql = "INSERT INTO ".$xoopsDB->prefix("newblocks")." (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, isactive, dirname, func_file, show_func, edit_func, template, last_modified) VALUES (".$newbid.", ".$module->getVar('mid').", ".$i.",'".addslashes($options)."','".$block_name."', '".$block_name."', '', 0, 0, 0, '{$block_type}', 1, '".addslashes($dirname)."', '".addslashes($block['file'])."', '".addslashes($block['show_func'])."', '".addslashes($editfunc)."', '".$template."', ".time().")";
                        $result = $xoopsDB->query($sql);
                        if (!$result) {
                            $msgs[] = '&nbsp;&nbsp;'.sprintf(_('ERROR: Could not create %s','rmcommon'), $block['name']);$log .=  $sql;
                        } else {
                            if (empty($newbid)) {
                                $newbid = $xoopsDB->getInsertId();
                            }
                            if ($module->getInfo('hasMain')) {
                                $groups = array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS);
                            } else {
                                $groups = array(XOOPS_GROUP_ADMIN);
                            }
                            $gperm_handler =& xoops_gethandler('groupperm');
                            foreach ($groups as $mygroup) {
                                $bperm =& $gperm_handler->create();
                                $bperm->setVar('gperm_groupid', $mygroup);
                                $bperm->setVar('gperm_itemid', $newbid);
                                $bperm->setVar('gperm_name', 'block_read');
                                $bperm->setVar('gperm_modid', 1);
                                if (!$gperm_handler->insert($bperm)) {
                                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.__('ERROR: Could not add block access right','rmcommon') .' '.sprintf(__("Block__('Blockk ID: %s",'rmcommon'), "<strong>".$newbid."</strong>"). ' '.sprintf(__('Group ID: %s','rmcommon'), "<strong>".$mygroup."</strong>").'</span>';
                                } else {
                                    $msgs[] = '&nbsp;&nbsp;'.__('Added block access right','rmcommon'). ' ' . sprintf(__("Bloc__('Block ID: %s",'rmcommon'), "<strong>".$newbid."</strong>") . ' ' . sprintf(__('Group ID: %s','rmcommon'), "<strong>".$mygroup."</strong>");
                                }
                            }

                            if ($template != '') {
                                $tplfile =& $tplfile_handler->create();
                                $tplfile->setVar('tpl_module', $dirname);
                                $tplfile->setVar('tpl_refid', $newbid);
                                $tplfile->setVar('tpl_source', $content, true);
                                $tplfile->setVar('tpl_tplset', 'default');
                                $tplfile->setVar('tpl_file', $block['template'], true);
                                $tplfile->setVar('tpl_type', 'block');
                                $tplfile->setVar('tpl_lastimported', 0);
                                $tplfile->setVar('tpl_lastmodified', time());
                                $tplfile->setVar('tpl_desc', $block['description'], true);
                                if (!$tplfile_handler->insert($tplfile)) {
                                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Could not insert template %s to the database.','rmcommon'), "<strong>".$block['template']."</strong>").'</span>';
                                } else {
                                    $newid = $tplfile->getVar('tpl_id');
                                    $msgs[] = '&nbsp;&nbsp;'.sprintf(__('Template %s added to the database','rmcommon'), "<strong>".$block['template']."</strong>");
                                    if ($xoopsConfig['template_set'] == 'default') {
                                        if (!xoops_template_touch($newid)) {
                                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Template %s recompile failed','rmcommon'), "<strong>".$block['template']."</strong>").'</span>';
                                        } else {
                                            $msgs[] = '&nbsp;&nbsp;'.sprintf(__('Template %s recompiled','rmcommon'), "<strong>".$block['template']."</strong>");
                                        }
                                    }
                                }
                            }
                            $msgs[] = '&nbsp;&nbsp;'.sprintf(__('Block %s created','rmcommon'), "<strong>".$block['name']."</strong>").sprintf(__("Bloc__('Block ID: %s",'rmcommon'), "<strong>".$newbid."</strong>");
                            $sql = 'INSERT INTO '.$xoopsDB->prefix('block_module_link').' (block_id, module_id) VALUES ('.$newbid.', -1)';
                            $xoopsDB->query($sql);
                        }
                    }
                }
            }
            $block_arr = XoopsBlock::getByModule($module->getVar('mid'));
            foreach ($block_arr as $block) {
                if (!in_array($block->getVar('show_func'), $showfuncs) || !in_array($block->getVar('func_file'), $funcfiles)) {
                    $sql = sprintf("DELETE FROM %s WHERE bid = %u", $xoopsDB->prefix('newblocks'), $block->getVar('bid'));
                    if(!$xoopsDB->query($sql)) {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.sprintf(__('ERROR: Could not delete block %s','rmcommon'), "<strong>".$block->getVar('name')."</strong>").sprintf(__("Bloc__('Block ID: %s",'rmcommon'), "<strong>".$block->getVar('bid')."</strong>").'</span>';
                    } else {
                        $msgs[] = '&nbsp;&nbsp;Block <strong>'.$block->getVar('name').' deleted. Block ID: <strong>'.$block->getVar('bid').'</strong>';
                        if ($block->getVar('template') != '') {
                            $tplfiles = $tplfile_handler->find(null, 'block', $block->getVar('bid'));
                            if (is_array($tplfiles)) {
                                $btcount = count($tplfiles);
                                for ($k = 0; $k < $btcount; $k++) {
                                    if (!$tplfile_handler->delete($tplfiles[$k])) {
                                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.__('ERROR: Could not remove deprecated block template.','rmcommon'). '(ID: <strong>'.$tplfiles[$k]->getVar('tpl_id').'</strong>)</span>';
                                    } else {
                                        $msgs[] = '&nbsp;&nbsp;'.sprintf(__('Block template %s deprecated','rmcommon'), "<strong>".$tplfiles[$k]->getVar('tpl_file')."</strong>");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // reset compile_id
        $xoopsTpl->setCompileId();

        // first delete all config entries
        $config_handler =& xoops_gethandler('config');
        $configs = $config_handler->getConfigs(new Criteria('conf_modid', $module->getVar('mid')));
        $confcount = count($configs);
        $config_delng = array();
        if ($confcount > 0) {
            $msgs[] = __('Deleting module config options...','rmcommon');
            for ($i = 0; $i < $confcount; $i++) {
                if (!$config_handler->deleteConfig($configs[$i])) {
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">'.__('ERROR: Could not delete config data from the database','rmcommon'). sprintf(__('Config ID: %s','rmcommon'), "<strong>".$configs[$i]->getvar('conf_id')."</strong>").'</span>';
                    // save the name of config failed to delete for later use
                    $config_delng[] = $configs[$i]->getvar('conf_name');
                } else {
                    $config_old[$configs[$i]->getvar('conf_name')]['value'] = $configs[$i]->getvar('conf_value', 'N');
                    $config_old[$configs[$i]->getvar('conf_name')]['formtype'] = $configs[$i]->getvar('conf_formtype');
                    $config_old[$configs[$i]->getvar('conf_name')]['valuetype'] = $configs[$i]->getvar('conf_valuetype');
                    $msgs[] = "&nbsp;&nbsp;".__('Config data deleted from the database.','rmcommon'). ' ' . sprintf(__('Config ID: %s','rmcommon'), "<strong>".$configs[$i]->getVar('conf_id')."</strong>");
                }
            }
        }

        // now reinsert them with the new settings
        $configs = $module->getInfo('config');
        // Include 
        if ($configs != false) {
            if ($module->getVar('hascomments') != 0) {
                include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
                array_push($configs, array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE, '_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN)));
                array_push($configs, array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0));
            }
        } else {
            if ($module->getVar('hascomments') != 0) {
                $configs = array();
                include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
                $configs[] = array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE, '_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN));
                $configs[] = array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0);
            }
        }
        // RMV-NOTIFY
        if ($module->getVar('hasnotification') != 0) {
            if (empty($configs)) {
                $configs = array();
            }
            // Main notification options
            include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
            include_once XOOPS_ROOT_PATH . '/include/notification_functions.php';
            $options = array();
            $options['_NOT_CONFIG_DISABLE'] = XOOPS_NOTIFICATION_DISABLE;
            $options['_NOT_CONFIG_ENABLEBLOCK'] = XOOPS_NOTIFICATION_ENABLEBLOCK;
            $options['_NOT_CONFIG_ENABLEINLINE'] = XOOPS_NOTIFICATION_ENABLEINLINE;
            $options['_NOT_CONFIG_ENABLEBOTH'] = XOOPS_NOTIFICATION_ENABLEBOTH;

            //$configs[] = array ('name' => 'notification_enabled', 'title' => '_NOT_CONFIG_ENABLED', 'description' => '_NOT_CONFIG_ENABLEDDSC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1);
            $configs[] = array ('name' => 'notification_enabled', 'title' => '_NOT_CONFIG_ENABLE', 'description' => '_NOT_CONFIG_ENABLEDSC', 'formtype' => 'select', 'valuetype' => 'int', 'default' => XOOPS_NOTIFICATION_ENABLEBOTH, 'options'=>$options);
            // Event specific notification options
            // FIXME: for some reason the default doesn't come up properly
            //  initially is ok, but not when 'update' module..
            $options = array();
            $categories =& notificationCategoryInfo('',$module->getVar('mid'));
            foreach ($categories as $category) {
                $events =& notificationEvents ($category['name'], false, $module->getVar('mid'));
                foreach ($events as $event) {
                    if (!empty($event['invisible'])) {
                        continue;
                    }
                    $option_name = $category['title'] . ' : ' . $event['title'];
                    $option_value = $category['name'] . '-' . $event['name'];
                    $options[$option_name] = $option_value;
                    //$configs[] = array ('name' => notificationGenerateConfig($category,$event,'name'), 'title' => notificationGenerateConfig($category,$event,'title_constant'), 'description' => notificationGenerateConfig($category,$event,'description_constant'), 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1);
                }
            }
            $configs[] = array ('name' => 'notification_events', 'title' => '_NOT_CONFIG_EVENTS', 'description' => '_NOT_CONFIG_EVENTSDSC', 'formtype' => 'select_multi', 'valuetype' => 'array', 'default' => array_values($options), 'options' => $options);
        }

        if ($configs != false) {
            $msgs[] = 'Adding module config data...';
            $config_handler =& xoops_gethandler('config');
            $order = 0;
            foreach ($configs as $config) {
                // only insert ones that have been deleted previously with success
                if (!in_array($config['name'], $config_delng)) {
                    $confobj =& $config_handler->createConfig();
                    $confobj->setVar('conf_modid', $newmid);
                    $confobj->setVar('conf_catid', 0);
                    $confobj->setVar('conf_name', $config['name']);
                    $confobj->setVar('conf_title', $config['title'], true);
                    $confobj->setVar('conf_desc', $config['description'], true);
                    $confobj->setVar('conf_formtype', $config['formtype']);
                    $confobj->setVar('conf_valuetype', $config['valuetype']);
                    if (isset($config_old[$config['name']]['value']) && $config_old[$config['name']]['formtype'] == $config['formtype'] && $config_old[$config['name']]['valuetype'] == $config['valuetype']) {
                        // preserver the old value if any
                        // form type and value type must be the same
                        $confobj->setVar('conf_value', $config_old[$config['name']]['value'], true);
                    } else {
                        $confobj->setConfValueForInput($config['default'], true);

                        //$confobj->setVar('conf_value', $config['default'], true);
                    }
                    $confobj->setVar('conf_order', $order);
                    $confop_msgs = '';
                    if (isset($config['options']) && is_array($config['options'])) {
                        foreach ($config['options'] as $key => $value) {
                            $confop =& $config_handler->createConfigOption();
                            $confop->setVar('confop_name', $key, true);
                            $confop->setVar('confop_value', $value, true);
                            $confobj->setConfOptions($confop);
                            $confop_msgs .= '<br />&nbsp;&nbsp;&nbsp;&nbsp; ' . __('Config option added','rmcommon') . ' ' . __('Name:','rmcommon') . ' <strong>' . ( defined($key) ? constant($key) : $key ) . '</strong> ' . __('Value:','rmcommon') . ' <strong>' . $value . '</strong> ';
                            unset($confop);
                        }
                    }
                    $order++;
                    if (false != $config_handler->insertConfig($confobj)) {
                        //$msgs[] = '&nbsp;&nbsp;Config <strong>'.$config['name'].'</strong> added to the database.'.$confop_msgs;
                        $msgs[] = "&nbsp;&nbsp;".sprintf(__('Config %s added to the database','rmcommon'), "<strong>" . $config['name'] . "</strong>") . $confop_msgs;
                    } else {
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">' . sprintf(__('ERROR: Could not insert config %s to the database.','rmcommon'), "<strong>" . $config['name'] . "</strong>") . '</span>';
                    }
                    unset($confobj);
                }
            }
            unset($configs);
        }

        // execute module specific update script if any
        $update_script = $module->getInfo('onUpdate');
        if (false != $update_script && trim($update_script) != '') {
            include_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/'.trim($update_script);
            if (function_exists('xoops_module_update_'.$dirname)) {
                $func = 'xoops_module_update_'.$dirname;
                if (!$func($module, $prev_version)) {
                    $msgs[] = "<p>".sprintf(__('Failed to execute %s','rmcommon'), $func)."</p>";
                } else {
                    $msgs[] = "<p>".sprintf(__('%s executed successfully.','rmcommon'), "<strong>".$func."</strong>")."</p>";
                }
            }
        }

        foreach ($msgs as $msg) {
            $log .=  $msg.'<br />';
        }
        $log .=  "<p>".sprintf(__('Module %s updated successfully!','rmcommon'), "<strong>".$module->getVar('name')."</strong>")."</p>";
    }
    
    // Flush cache files for cpanel GUIs
    xoops_load("cpanel", "system");
    XoopsSystemCpanel::flush();
    
    return $log;
    
}


function module_update_now(){
    
    global $xoopsSecurity, $xoopsConfig;
    
    $mod = rmc_server_var($_POST, 'module', '');
    
    if (!$xoopsSecurity->check()){
        redirectMsg('modules.php', __('Sorry, this operation could not be completed!', 'rmcommon'), 1);
        die();
    }
    
    $module_handler = xoops_gethandler('module');
    $module = $module_handler->getByDirname($mod);
    
    if (!$module){
        redirectMsg('modules.php', sprintf(__('Module %s is not installed yet!', 'rmcommon'), $mod), 1);
        die();
    }
    
    $file = XOOPS_ROOT_PATH.'/modules/system/language/'.$xoopsConfig['language'].'/admin/modulesadmin.php';
    if (file_exists($file)){
        include_once $file;
    } else {
        include_once str_replace($xoopsConfig['language'], 'english', $file);
    }
    
    include_once XOOPS_ROOT_PATH.'/modules/system/admin/modulesadmin/modulesadmin.php';
    
    RMEvents::get()->run_event('rmcommon.updating.module', $module);
    
    $module_log = xoops_module_update($mod);
    
    $module_log = RMEvents::get()->run_event('rmcommon.module.updated', $module_log, $module_log);
    
    RMFunctions::create_toolbar();
    RMTemplate::get()->add_style('modules.css', 'rmcommon');
    xoops_cp_header();
    
    $log_title = sprintf(__('Update log for %s', 'rmcommon'), $module ? $module->getInfo('name') : $mod);
    $action = rmc_server_var($_POST, 'action', '');
    include RMTemplate::get()->get_template('rmc_mod_log.php','module','rmcommon');
    
    xoops_cp_footer();
    
}


function module_disable_now($enable=0){
    global $xoopsSecurity, $xoopsConfig;
    
    $mod = rmc_server_var($_POST, 'module', '');
    
    if (!$xoopsSecurity->check()){
        redirectMsg('modules.php', __('Sorry, this operation could not be completed!', 'rmcommon'), 1);
        die();
    }
    
    $module_handler = xoops_gethandler('module');
    
    if (!$module = $module_handler->getByDirname($mod)){
        redirectMsg('modules.php', sprintf(__('Module %s is not installed yet!', 'rmcommon'), $mod), 1);
        die();
    }
    
    RMEvents::get()->run_event('rmcommon.disabling.module', $module);
    
    $db = Database::getInstance();
    $sql = "UPDATE ".$db->prefix("modules")." SET isactive='$enable' WHERE dirname='$mod'";
    if (!$db->queryF($sql)){
        redirectMsg('modules.php', sprintf(__('Module %s could not be disabled!', 'rmcommon'), $mod), 1);
        die();
    }
    
    RMEvents::get()->run_event('rmcommon.module.disabled', $module);
    
    redirectMsg("modules.php", sprintf(__('Module %s successfully disabled!','rmcommon'), $module->name()), 0);
    
}


function load_modules_page(){
    global $xoopsLogger, $xoopsSecurity;
    error_reporting(0);
    $xoopsLogger->activated = false;
    
    if (!$xoopsSecurity->check(true, rmc_server_var($_POST, 'token', ''))){
        echo __("Sorry, you don't have access to this page", 'rmcommon');
        echo "<br /><a href='javascript:;' onclick='location.reload();'>".__('Click here to refresh','rmcommon')."</a>";
        die();
    }
    
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".$db->prefix("modules")." ORDER BY `name`";
    $result = $db->query($sql);
    $installed_dirs = array();
    
    while($row = $db->fetchArray($result)){
        $installed_dirs[] = $row['dirname'];
    }
    
    require_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    $dirlist = XoopsLists::getModulesList();
    $available_mods = array();
    $module_handler = xoops_gethandler('module');
    foreach ($dirlist as $file) {
        clearstatcache();
        $file = trim($file);
        if (!in_array($file, $installed_dirs)) {
            $module =& $module_handler->create();
            if (!$module->loadInfo($file, false)) {
                continue;
            }
            $available_mods[] = $module;
            unset($module);
        }
    }
    
    unset($dirlist);
    unset($module_handler);
    
    $limit = 10;
    $tpages = ceil(count($available_mods)/$limit);
    $page = rmc_server_var($_POST, 'page', 1);
    if ($page>$tpages) $page = 1;
    $start = ($page<=0 ? 0 : $page-1) * $limit;
    
    $nav = new RMPageNav(count($available_mods), $limit, $page, 3);
    $nav->target_url('javascript:;" onclick="load_page({PAGE_NUM});');
    
    // Event for available modules
    $available_mods = RMEvents::get()->run_event('rmcommon.available.modules', $available_mods);
    $end = ($page*$limit);
    
    if ($end>count($available_mods)) $end = count($available_mods);
    
    ob_start();
?>
    <div class="rmc_widget_content_reduced rmc_modules_widget">
        <img id="img-load" src="images/loading.gif" style="display: none; margin: 15px auto;" />
        <div id="mods-widget-container">
        <?php for($i=$start;$i<$end;$i++): ?>
        <?php $mod = $available_mods[$i]; ?>
        <div class="<?php echo tpl_cycle("even,odd"); ?>">
            <a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>" class="rmc_mod_img" style="background: url(<?php echo XOOPS_URL; ?>/modules/<?php echo $mod->getInfo('dirname'); ?>/<?php echo $mod->getInfo('image'); ?>) no-repeat center;"><span>&nbsp;</span></a>
            <strong><a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>"><?php echo $mod->getInfo('name'); ?></a></strong>
            <span class="rmc_available_options">
                <a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>"><?php _e('Install','rmcommon'); ?></a> |
                <a href="javascript:;" onclick="show_module_info('<?php echo $mod->getInfo('dirname'); ?>');"><?php _e('More info','rmcommon'); ?></a>
            </span>
            <span class="rmc_mod_info" id="mod-<?php echo $mod->getInfo('dirname'); ?>">
                <?php _e('Version:','rmcommon'); ?> 
                <?php if($mod->getInfo('rmnative')): ?>
                    <?php echo RMUtilities::format_version($mod->getInfo('rmversion')); ?>
                <?php else: ?>
                    <?php echo $mod->getInfo('version'); ?>
                <?php endif; ?><br />
                <?php _e('Author:', 'rmcommon'); ?> <?php echo substr(strip_tags($mod->getInfo('author')), 0, 12); ?>
            </span>
        </div>
        <input type="hidden" id="token" value="<?php echo $xoopsSecurity->createToken(); ?>" />
        <?php endfor; ?>
        <?php $nav->display(false); ?>
        </div>
    </div>
<?php
    $ret = ob_get_clean();
    echo $ret;
}


function module_rename(){
	global $xoopsSecurity, $xoopsLogger;
	
	error_reporting(0);
	$xoopsLogger->activated = false;
	
	if (!$xoopsSecurity->check()){
		
		$ret = array(
			'error'		=> 1,
			'message'	=> __('You can not rename modules. Session token not valid!','rmcommon').' '.rmc_server_var($_POST, 'token')
		);
		
		echo json_encode($ret);
		die();
		
	}
	
	$id = rmc_server_var($_POST, 'id', 0);
	$name = trim(rmc_server_var($_POST, 'name', ''));
	
	if ($id<=0 || $name==''){
		$ret = array(
			'error'		=> 1,
			'message'	=> __('Data provided is not valid','rmcommon'),
			'token'	=> $xoopsSecurity->createToken()
		);
		
		echo json_encode($ret);
		die();
	}
	
	$db = Database::getInstance();
	$sql = "UPDATE ".$db->prefix("modules")." SET `name`='$name' WHERE mid='$id'";
	if (!$db->queryF($sql)){
		$ret = array(
			'error'		=> 1,
			'message'	=> __('Module name could not be changed!','rmcommon').'\n'.$db->error(),
			'token'	=> $xoopsSecurity->createToken()
		);
		
		echo json_encode($ret);
		die();
	}
	
	$ret = array(
		'error'=>0,
		'message'=>__('Module name changed successfully!','rmcommon'),
		'token'=>$xoopsSecurity->createToken(),
		'id'=>$id
	);
	
	echo json_encode($ret);
	
	die();
	
}


$action = rmc_server_var($_REQUEST, 'action', '');
switch($action){
    case 'install':
        module_install();
        break;
    case 'install_now':
    	module_install_now();
    	break;
    case 'uninstall_module':
        module_uninstall_now();
        break;
    case 'update_module':
        module_update_now();
        break;
    case 'disable_module':
        module_disable_now(0);
        break;
    case 'enable_module':
        module_disable_now(1);
        break;
    case 'load_page':
        load_modules_page();
        break;
    case 'savename':
    	module_rename();
    	break;
	default:
        $_REQUEST['action'] = '';
		show_modules_list();
		break;
}
