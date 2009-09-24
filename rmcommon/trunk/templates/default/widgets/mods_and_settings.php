<?php
// $Id: mods_and_settings.php 52 2009-09-18 06:01:48Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('MODLOCATION',!isset($xoopsModule) ? 'system' : ($xoopsModule->getVar('dirname')=='system' ? 'system' : ''));

// add MODULES  Menu items

$left_widgets = array();
$left_widgets[0]['title'] = __('Modules','rmcommon');
$left_widgets[0]['icon'] = RMCURL.'/templates/default/images/mods.png';
$module_handler =& xoops_gethandler('module');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('hasadmin', 1));
$criteria->add(new Criteria('isactive', 1));
$criteria->setSort('mid');
$mods = $module_handler->getObjects($criteria);
$moduleperm_handler = xoops_gethandler('groupperm');
foreach ($mods as $mod) {
    
	if ($mod->getVar('dirname','n')!='system' && (isset($xoopsModule) && $xoopsModule->getVar('dirname','n')==$mod->getVar('dirname','n'))) continue;

 	$rtn = array();
    $modOptions = array(); //add for sub menus
    $sadmin = $moduleperm_handler->checkRight('module_admin', $mod->getVar('mid'), $xoopsUser->getGroups());
    if ($sadmin) {
    	$info = $mod->getInfo();
        if (!empty($info['adminindex'])) {
	        $rtn['link'] = XOOPS_URL . '/modules/'. $mod->getVar('dirname', 'n') . '/' . $info['adminindex'];
        } else {
	        $rtn['link'] = XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $mod->getVar('mid');
        }
        $rtn['title'] = $mod->name();
        $rtn['location'] = $mod->getVar('dirname','n');
        $rtn['absolute'] = 1;
        $rtn['url'] = XOOPS_URL . '/modules/'. $mod->getVar('dirname', 'n') . '/'; //add for sub menus
        $modOptions = $mod->getAdminMenu();//add for sub menus

        if ($modOptions){
            $options = array();
            foreach ($modOptions as $option){
                $options[] = array(
                    'title'=>$option['title'],
                    'link' => strpos($option['link'], array('http://','ftp://'))!==FALSE ? $option['link'] : XOOPS_URL.'/modules/'.$mod->getVar('dirname','n').'/'.$option['link']
                );
            }
            $rtn['options'] = $options;     //add for sub menus
        }

        if (isset($info['icon16']) && $info['icon16'] != '' ) {
	        $rtn['icon'] = XOOPS_URL . '/modules/' . $mod->getVar('dirname', 'n') . '/' . $info['icon16'];
        } elseif($mod->getVar('dirname','n')=='system'){
            $rtn['icon'] = RMCURL.'/templates/default/images/system.png';
        }
    }
    $menu[] = $rtn;
 }
 
 $menu[] = array(
 	'title'		=> __('Modules Management','rmcommon'),
 	'link'		=> XOOPS_URL.'/modules/system/admin.php?fct=modulesadmin'
 );

// Event for those elements that want to insert new options in modules menus
$menu = RMEventsApi::get()->run_event('rmevent_modules_menu', $menu);
 
$rtn = '';
foreach ($menu as $mod){
	$wcounter++;
	$rtn .= '<div class="menu'.(isset($mod['location']) && $mod['location']==MODLOCATION ? ' selected':'').'" id="menu-'.$wcounter.'">';
	if (isset($mod['options'])){
		$rtn .= '<span class="toggle" id="switch-'.$wcounter.'">&nbsp;</span>';
	}
	$rtn .= '<a href="'.$mod['link'].'" style="'.(isset($mod['icon']) ? "background-image: url(".$mod['icon']."); padding-left: 22px; width: 100px" : '').'"'.(isset($mod['options']) ? ' class="reduced"' : '').'">'.$mod['title'].'</a>';
	$rtn .= "</div>";
	if (isset($mod['options'])){
		$rtn .= '<div class="submenu" id="container-'.$wcounter.'" style="'.($mod['location']==MODLOCATION ? 'display: block;' : 'display: none;').'">';
		foreach ($mod['options'] as $submenu){
			$rtn .= '<a href="'.$submenu['link'].'">'.$submenu['title'].'</a>';
		}
		$rtn .= "</div>";
	}
}

$left_widgets[0]['content'] = $rtn;

// add preferences menu
$left_widgets[1]['title'] = __('Settings','rmcommon');
$left_widgets[1]['icon'] = RMCURL.'/templates/default/images/settings.png';

$menu = array();
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=1',
    'title'     => __('General Settings','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=2',
    'title'     => __('Users Settings','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=3',
    'title'     => __('Metas and Footer','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=4',
    'title'     => __('Words Censor','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=5',
    'title'     => __('Searchs Settings','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=6',
    'title'     => __('Mail Settings','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');
$menu[] = array(
    'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=7',
    'title'     => __('Authentication','rmcommon'),
    'icon'     => XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png');

$options = array();
foreach ($mods as $mod) {
    $rtn = array();
    $sadmin = $moduleperm_handler->checkRight('module_admin', $mod->getVar('mid'), $xoopsUser->getGroups());
    if ($sadmin && ($mod->getVar('hasnotification') || is_array($mod->getInfo('config')) || is_array($mod->getInfo('comments')))) {
		$rtn['link'] = XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $mod->getVar('mid');
		$rtn['title'] = $mod->name();
		$rtn['absolute'] = 1;
		$rtn['icon'] = XOOPS_URL . '/modules/system/class/gui/oxygen/images/navIcons/prefs_small.png';
		$options[] = $rtn;
    }
}

$menu[] = array(
	'title'		=> __('Modules Settings','rmcommon'),
	'link'		=> '',
	'icon'		=> RMCURL.'/templates/default/images/modset.png',
	'options'	=> $options
);

// Event for those elements that want to insert new options in settings menu
$menu = RMEventsApi::get()->run_event('rmevent_settings_menu', $menu);
    
$rtn = '';
foreach ($menu as $mod){
	$wcounter++;
	$rtn .= '<div class="menu" id="menu-'.$wcounter.'">';
	if (isset($mod['options'])){
		$rtn .= '<span class="toggle" id="switch-'.$wcounter.'">&nbsp;</span>';
	}
	$rtn .= '<a href="'.$mod['link'].'" style="'.(isset($mod['icon']) ? "background-image: url(".$mod['icon']."); padding-left: 22px; width: 100px" : '').'"'.(isset($mod['options']) ? ' class="reduced"' : '').'">'.$mod['title'].'</a>';
	$rtn .= "</div>";
	if (isset($mod['options'])){
		$rtn .= '<div class="submenu" id="container-'.$wcounter.'" style="'.(isset($mod['location']) && $mod['location']==RMCLOCATION ? 'display: block;' : 'display: none;').'">';
		foreach ($mod['options'] as $submenu){
			$rtn .= '<a href="'.$submenu['link'].'">'.$submenu['title'].'</a>';
		}
		$rtn .= "</div>";
	}
}

$left_widgets[1]['content'] = $rtn;

// Modules Menu
if (isset($xoopsModule) && $xoopsModule->getVar('dirname','n')!='system'){
    $amenu = $xoopsModule->getAdminMenu();
    if ($amenu){
	    foreach ($amenu as $menu){
		    RMTemplate::get()->add_menu($menu['title'], strpos($menu['link'], array('http://','ftp://'))!==FALSE ? $menu['link'] : XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname','n').'/'.$menu['link'], isset($menu['icon']) ? $menu['icon'] : '');
		    //RMTemplate::get()->add_tool($menu['title'], $menu['link'], isset($menu['icon']) ? $menu['icon'] : '');
	    }
    }
    
    if($xoopsModule->hasconfig()){
        RMTemplate::get()->add_menu(__('Configure','admin_mywords'), XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$xoopsModule->mid(), RMTHEMEURL.'/images/configure.png','');
    }
}