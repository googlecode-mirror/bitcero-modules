<?php
// $Id: index.php 14 2009-09-09 18:22:07Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------
include_once '../../mainfile.php';
include_once '../../include/cp_header.php';
include_once 'include/functions.php';
include_once 'class/plugin.php';
include_once 'class/theme.php';

// Jquery
function xt_show_init(){
	global $xoopsConfig;

	xoops_cp_header();
	
	// Check if installed theme is a valid I.Theme theme
	$theme_path = XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'];
	$theme_url = XOOPS_THEME_URL.'/'.$xoopsConfig['theme_set'];
	if (false===($theme = xt_is_valid($xoopsConfig['theme_set']))){
		// Not a Red Mexico theme
		$not_valid = true;
		if(is_file($theme_path.'/screenshot.png')){
			$file = '/screenshot.png';
		} elseif(is_file($theme_path.'/screenshot.jpg')){
			$file = '/screenshot.jpg';
		} elseif(is_file($theme_path.'/screenshot.gif')){
			$file = '/screenshot.gif';
		} else {
			$file = '';
		}
		
		$theme_info = array(
			'name'			=>	$xoopsConfig['theme_set'],
			'description'	=>	'This is XOOPS standar theme',
			'version'		=>	'',
			'author'		=>	'Unknow',
			'email'			=>	'unknow',
			'url'			=>	'unknow',
			'screenshot'	=>	$file
		);
		
	} else {
		$not_valid = false;
		$theme_info = $theme->get_info();
	}
	
	//Search other available themes
	
	$themes = array();
	$i = 0; // Counter
	$tpath = XOOPS_ROOT_PATH.'/themes';
	$dir_themes = opendir(XOOPS_ROOT_PATH.'/themes');
	while (false !== ($t = readdir($dir_themes))){
		if ($t=='.' || $t=='..') continue;
		if (!is_dir(XOOPS_ROOT_PATH.'/themes/'.$t)) continue;
		if ($t==$xoopsConfig['theme_set']) continue;
		
		$cf = XOOPS_ROOT_PATH.'/themes/'.$t.'/config/theme.php';
		if (file_exists($cf)){
			
			include_once $cf;
			$valid = true;

		} else {
			$valid = false;
		}
		
		$rtheme = preg_replace('/\s+/', '', strtolower($t));
		$rtheme = str_replace('-','',$rtheme);
		$class = "XTheme".ucfirst($rtheme);
		
		if (class_exists($class)){
            $to = new $class();
			$themes[$i]['info'] = $to->get_info();
			$themes[$i]['info']['dir'] = $t;
			$themes[$i]['valid'] = true;
		} else {
			
			if(is_file($tpath."/$t/screenshot.png")){
				$file = "screenshot.png";
			} elseif(is_file($tpath."/$t/screenshot.jpg")){
				$file = "screenshot.jpg";
			} elseif(is_file($tpath."/$t/screenshot.gif")){
				$file = "screenshot.gif";
			} else {
				$file = '';
			}
			
			$themes[$i]['valid'] = false;
			$themes[$i]['info'] = array(
				'name'			=>	$t,
				'description'	=>	'Not a valid <strong>I.Themes</strong> Theme',
				'version'		=>	'',
				'author'		=>	'Unknow',
				'email'			=>	'unknow',
				'url'			=>	'',
				'screenshot'	=>	$file,
				'dir'			=> $t
			);
		}
		$i++;
		
	}
	xt_load_jquery();
	xt_show_message();
	include_once 'templates/xt_index.php';
	
	xoops_cp_footer();
	
}

/**
* Configure theme settings
* 
* @var string
*/
function xt_configure_show(){
	global $xoopsConfig, $xoopsTpl;
	
	if (false === ($theme = xt_is_valid($xoopsConfig['theme_set'])))
		xt_redirect('index.php', 'This theme cannot be configured by I.Themes because is not a valid Theme', 1);

	if (!method_exists($theme, 'get_config'))
		xt_redirect('index.php', 'This theme has not configuration options', 1);
	
	xoops_cp_header();
	
	$element_info = $theme->get_info();
	$element = $xoopsConfig['theme_set'];
	xt_load_jquery();
	xt_show_message();
	$xt_show = 'theme';
    $current_settings = xt_get_current_config($xoopsConfig['theme_set']);
	include_once 'templates/xt_form_config.php';
	
	xoops_cp_footer();
		
}

/**
* This funtion redirect to theme url owner
*/
function xt_goto_url(){
	global $xoopsConfig;
	
	if (false === ($theme = xt_is_valid($xoopsConfig['theme_set'])))
		xt_redirect('index.php', 'This theme cannot be configured by X.Themes because is not a valid Theme', 1);
	
	$element_info = $theme->get_info();
	
	if ($element_info['url']!=''){
		header('location: '.$element_info['url']);
		die();
	} else {
		header('location: index.php');
		die();
	}
}

/**
* Activate a theme
*/
function xt_install_theme(){
    global $xoopsConfig;
    
    $previous = $xoopsConfig['theme_set'];
    
    $dir = isset($_GET['theme']) ? $_GET['theme'] : '';
    
    $pdir = XOOPS_THEME_PATH.'/'.$dir;
   
    if (!is_file($pdir.'/config/theme.php'))
    	xt_redirect('index.php', 'Theme "'.$dir.'" does not exists!', 1);
    
    if (false===($theme = xt_is_valid($dir)))
   		xt_redirect('index.php', 'Theme "'.$dir.'" is not a valid I.Themes theme!', 1);
    
    include_once $pdir.'/config/theme.php';
    $rtheme = preg_replace('/\s+/', '', strtolower($dir));
	$rtheme = str_replace('-','',$rtheme);
	$class = "XTheme".ucfirst($rtheme);
    if (!class_exists($class))
    	xt_redirect('index.php', 'Theme "'.$dir.'" is not a valid <strong>I.Themes</strong> theme!', 1);
    
    $db = Database::getInstance();
    //$theme = new $class();
    
    // Check requirements
    if (method_exists($theme, 'check_requirements')){
    	if (!$theme->check_requirements())
    		xt_redirect('index.php','Theme '.$theme->name().' return next errors:<br /><br />'.implode("<br />", $theme->errors()), 1);
	}

    // Insert theme data
    if (!$db->queryF("UPDATE ".$db->prefix("config")." SET `conf_value`='$dir' WHERE `conf_name`='theme_set' AND conf_modid='0'"))
        xt_redirect('index.php', 'The theme has not been installed.<br />'.$db->error(), 1);
    $_SESSION['xoopsUserTheme'] = $dir;
    // Delete previous data if valid
    if (false !== ($previous = xt_is_valid($previous))){
		if (method_exists($previous, 'uninstall')){
			$messages = array();
			if (!$previous->uninstall()) $mssages = $previous->errors();
		}
	}
    
    // Run install method from plugin.
    // This method allows insertion on several data for plugin
    if (method_exists($theme, 'install'))
    	$theme->install();

    xt_redirect('index.php', 'Theme installed successfully'.(count($messages)>0 ? " but there was some errors:<br />".implode("<br />".$messages) : '!'), 0);
}

/**
* Install a normal theme
*/
function xt_install_normal(){
    global $xoopsConfig;
    
    $previous = $xoopsConfig['theme_set'];
    
    $dir = isset($_GET['theme']) ? $_GET['theme'] : '';
    
    $pdir = XOOPS_THEME_PATH.'/'.$dir;
   
    if (is_file($pdir.'/config/theme.php'))
    	xt_redirect('index.php', '"'.$dir.'" is a premium theme!', 1);
    
    if (xt_is_valid($dir))
   		xt_redirect('index.php', '"'.$dir.'" is premium I.Themes theme!', 1);
		
	$db = Database::getInstance();
    // Insert theme data
    if (!$db->queryF("UPDATE ".$db->prefix("config")." SET `conf_value`='$dir' WHERE `conf_name`='theme_set' AND conf_modid='0'"))
        xt_redirect('index.php', 'The theme has not been installed.<br />'.$db->error(), 1);
    $_SESSION['xoopsUserTheme'] = $dir;
    // Delete previous data if valid
    if (false !== ($previous = xt_is_valid($previous))){
		if (method_exists($previous, 'uninstall')){
			$messages = array();
			if (!$previous->uninstall()) $mssages = $previous->errors();
		}
	}

    xt_redirect('index.php', 'Theme installed successfully'.(count($messages)>0 ? " but there was some errors:<br />".implode("<br />".$messages) : '!'), 0);
}

/**
* Check the settings and save data
*/
function xt_save_settings(){
	global $xoopsConfig;

	$myts = MyTextSanitizer::getInstance();
	
	if (false === ($theme = xt_is_valid($xoopsConfig['theme_set'])))
		xt_redirect('index.php', 'This is a not valid theme', true);
	
	$xt_to_save = array();
	
	$element = $_POST['element'];
	
	foreach ($_POST as $id => $v){
		if(substr($id, 0, 7)!='xtconf_') continue;
		
		if (method_exists($theme, 'verify_settings')){
			$xt_to_save[substr($id, 7)] = $theme->verify_settings($v);
		} else {
			$xt_to_save[substr($id, 7)] = $v;
		}		
	}
	
	$db = Database::getInstance();

	// Delete current config
	$db->queryF("DELETE FROM ".$db->prefix("xtheme_config")." WHERE `element`='$element'");
	
	// Save data
	$sql = "INSERT INTO ".$db->prefix("xtheme_config")." (`name`,`value`,`type`,`element`) VALUES ('%s','%s','%s','$element')";
	$errors = array();
	array_walk_recursive($xt_to_save, 'clean_values');
	foreach ($xt_to_save as $id => $value){
		
		if (is_array($value)){
			$value = json_encode($value);
			$type = 'array';
		} else {
			$value = $myts->addSlashes($value);
			$type = '';
		}
		
		if (!$db->queryF(sprintf($sql, $id, $value, $type)))
			$errors[] = $db->error();
		
	}
	if (!empty($errors)){
		xt_redirect('index.php', 'There was errors during this operation:<br />'.implode('<br />', $errors), true);
	} else {
		xt_redirect('index.php', 'Settings updated!', false);
	}
	
}

function clean_values(&$value, $key){

	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
	
}

/**
* Shows the plugins list
*/
function xt_show_plugins(){
	global $xoopsConfig;
	
	// Search active plugins
	$db = Database::getInstance();
	$result = $db->query("SELECT * FROM ".$db->prefix("xtheme_plugins"));
	$active_plugins = array();
	while ($row = $db->fetchArray($result)){
		if (!is_file(ITPATH.'/plugins/'.$row['dir'].'/xthemes_plugin_'.$row['dir'].'.php')){
			// Plugin file does not exists, then delete it
			$db->queryF("DELETE FROM ".$db->prefix("xtheme_plugins")." WHERE id_plugin='$row[id_plugin]");
			continue;
		}
		include_once(ITPATH.'/plugins/'.$row['dir'].'/xthemes_plugin_'.$row['dir'].'.php');
        if (!class_exists('XThemes'.ucfirst($row['dir']))) continue;
        $class = 'XThemes'.ucfirst($row['dir']);
        $plugin = new $class();
        $active_plugins[$row['dir']] = $plugin->get_info();
	}
	
    // Search inactive plugins
    $inactive_plugins = array();
    $odir = opendir(ITPATH.'/plugins');
    
    while(false!==($file = readdir($odir))){
        
        if ($file=='.' || $file=='..') continue;
        if (isset($active_plugins[$file])) continue;
        if (is_file(ITPATH.'/plugins/'.$file)) continue;
        
        if (!is_file(ITPATH.'/plugins/'.$file.'/xthemes_plugin_'.$file.'.php')) continue;
        
        include_once(ITPATH.'/plugins/'.$file.'/xthemes_plugin_'.$file.'.php');
        if (!class_exists('XThemes'.ucfirst($file))) continue;
        $class = 'XThemes'.ucfirst($file);
        $plugin = new $class();
        $inactive_plugins[$file] = $plugin->get_info();
            
    }
    
	xoops_cp_header();

	xt_load_jquery();
	xt_show_message();
	include_once 'templates/xt_plugins.php';
	
	xoops_cp_footer();
}

/**
* Activate a plugin
*/
function xt_activate_plugin(){
    
    $dir = isset($_GET['plugin']) ? $_GET['plugin'] : '';
    
    $pdir = ITPATH.'/plugins/'.$dir;
    
    if (!is_file($pdir.'/xthemes_plugin_'.$dir.'.php'))
    	xt_redirect('index.php?op=plugins', 'Plugin "'.$dir.'" does not exists!', 1);
    
    if (xt_plugin_installed($dir))
   		xt_redirect('index.php?op=plugins', 'Plugin "'.$dir.'" is installled already!', 1);
    
    include_once $pdir.'/xthemes_plugin_'.$dir.'.php';
    $class = "XThemes".ucfirst($dir);
    if (!class_exists($class))
    	xt_redirect('index.php?op=plugins', 'Plugin "'.$dir.'" is not a valid <strong>I.Themes</strong> Plugin!', 1);
    
    $db = Database::getInstance();
    $plugin = new $class();
    
    // Check requirements
    if (method_exists($plugin, 'check_requirements')){
    	if (!$plugin->check_requirements())
    		xt_redirect('index.php?op=plugins','Plugin '.$plugin->name().' return next errors:<br /><br />'.implode("<br />", $plugin->errors()), 1);
	}
    
    // Insert plugin data
    if (!$db->queryF("INSERT INTO ".$db->prefix("xtheme_plugins")." (`dir`) VALUES ('$dir')"))
        xt_redirect('index.php?op=plugins', 'The plugin has not been activated.<br />'.$db->error(), 1);
    
    // Run install method from plugin.
    // This method allows insertion on several data for plugin
    if (method_exists($plugin, 'install'))
    	$plugin->install();
    
    xt_redirect('index.php?op=plugins', 'Plugin activated successfully!', 0);
}

/**
* Configure a single plugin
*/
function xt_configure_plugin(){
	global $xoopsConfig, $xoopsTpl;
	
	$dir = isset($_GET['plugin']) ? $_GET['plugin'] : '';
	if ($dir=='')
		xt_redirect('index.php?op=plugins', 'Please, select a plugin to configure', 1);
	
	$pdir = ITPATH.'/plugins/'.$dir;
    
    if (!is_file($pdir.'/xthemes_plugin_'.$dir.'.php'))
    	xt_redirect('index.php?op=plugins', 'Plugin "'.$dir.'" does not exists!', 1);
    
    if (!xt_plugin_installed($dir))
   		xt_redirect('index.php?op=plugins', 'Plugin "'.$dir.'" is not installled yet!', 1);
	
	include_once $pdir.'/xthemes_plugin_'.$dir.'.php';
    $class = "XThemes".ucfirst($dir);
    if (!class_exists($class))
    	xt_redirect('index.php?op=plugins', 'Plugin "'.$dir.'" is not a valid <strong>xThemes</strong> Plugin!', 1);
    
    $db = Database::getInstance();
    $plugin = new $class();
	
	xoops_cp_header();
	
	$element_info = $plugin->get_info();
	$element = $dir;
	xt_load_jquery();
	xt_show_message();
	$xt_show = 'plugin';
	$current_settings = xt_get_current_config($dir);
	include_once 'templates/xt_form_config.php';
	
	xoops_cp_footer();
		
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch($op){
	case 'install-theme':
		xt_install_theme();
		break;
	case 'install-normal':
		xt_install_normal();
		break;
	case 'config':
		xt_configure_show();
		break;
	case 'save_settings':
		xt_save_settings();
		break;
	case 'plugins':
		xt_show_plugins();
		break;
    case 'activate-plugin':
        xt_activate_plugin();
        break;
    case 'config-plugin':
    	xt_configure_plugin();
    	break;
    case 'url':
    	xt_goto_url();
    	break;
	default:
		xt_show_init();
		break;
}