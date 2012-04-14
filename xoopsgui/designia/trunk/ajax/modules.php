<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/*require dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/mainfile.php';

$xoopsLogger->renderingEnabled = false;
$xoopsLogger->activated = false;

if(!$xoopsSecurity->check()){
    die('Session token expired');    
}*/
$installed_modules = array();
     
include_once XOOPS_ROOT_PATH.'/kernel/module.php';
    
$db = XoopsDatabaseFactory::getDatabaseConnection();

$sql = "SELECT * FROM ".$db->prefix("modules")." WHERE isactive='1' ORDER BY `name`";
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
    
    $deficon = XOOPS_ROOT_PATH.'/modules/rmcommon/themes/designia/images/modules/'.$mod->dirname().'.png';
        
    $modules[] = array(
        'id'            => $mod->getVar('mid'),
        'name'            => $mod->getVar('name'),
        'realname'        => $mod->getInfo('name'),
        'version'        => $mod->getInfo('rmnative') ? RMUtilities::format_version($mod->getInfo('rmversion')) : $mod->getInfo('version'),
        'description'    => $mod->getInfo('description'),
        'image'            => is_file($deficon) ? str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $deficon) : (XOOPS_URL.'/modules/'.$mod->getVar('dirname').'/'.($mod->getInfo('icon32') ? $mod->getInfo('icon32') : $mod->getInfo('image'))),
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

//include dirname(dirname(__FILE__)).'/include/modules.php';

//die();