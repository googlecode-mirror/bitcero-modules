<?php
// $Id: index.php 14 2009-09-09 18:22:07Z i.bitcero $
// --------------------------------------------------------------
// xThemes for XOOPS
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
require '../../include/cp_header.php';
include_once 'include/functions.php';
include_once 'class/theme.php';

load_theme_locale($xoopsConfig['theme_set']);

// Jquery
function xt_show_init(){
	global $xoopsConfig;
	
	xt_menu_options();
	if (defined(RMCPATH))
		RMTemplate::get()->set_help('http://redmexico.com.mx/docs/xthemes');
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
			'description'	=>	__('This is XOOPS standar theme','xthemes'),
			'version'		=>	'',
			'author'		=>	__('Unknow','xthemes'),
			'email'			=>	__('unknow','xthemes'),
			'url'			=>	__('unknow','xthemes'),
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
				'description'	=>	__('Not a valid <strong>xThemes</strong> Theme','xthemes'),
				'version'		=>	'',
				'author'		=>	__('Unknow','xthemes'),
				'email'			=>	__('unknow','xthemes'),
				'url'			=>	__('unknow','xthemes'),
				'screenshot'	=>	$file,
				'dir'			=> $t
			);
		}
		$i++;
		
	}
	
	$xoTheme = $GLOBALS['xoTheme'];
	$xoTheme->addMeta('stylesheet', 'themes.css', array('href'=>'css/themes.css','type'=>'text/css'));
	//RMTemplate::get()->add_style('themes.css', 'xthemes');
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
		redirectMsg('index.php', __('This theme cannot be configured by XThemes because is not a valid Theme','xthemes'), 1);

	if (!method_exists($theme, 'get_config'))
		redirectMsg('index.php', __('This theme has not configuration options', 'xthemes'), 1);
	
	xt_menu_options();
	xoops_cp_header();
	
	$xoTheme = $GLOBALS['xoTheme'];
	$xoTheme->addMeta('stylesheet', 'themes.css', array('href'=>'css/themes.css','type'=>'text/css'));
	$xoTheme->addMeta('script', 'include/js/xthemes.js', array('src'=>'include/js/xthemes.js','type'=>'text/javascript'));
	
	$element_info = $theme->get_info();
	$element = $xoopsConfig['theme_set'];

	$xt_show = 'theme';
    $current_settings = xt_get_current_config($xoopsConfig['theme_set'], true);
	include_once 'templates/xt_form_config.php';
	
	xoops_cp_footer();
		
}

/**
* This funtion redirect to theme url owner
*/
function xt_goto_url(){
	global $xoopsConfig;
	
	if (false === ($theme = xt_is_valid($xoopsConfig['theme_set'])))
		xt_redirect('index.php', 'This theme cannot be configured by XThemes because is not a valid Theme', 1);
	
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
    	redirectMsg('index.php', sprintf(__('Theme "%s" does not exists!','xthemes'), $dir), 1);
    
    if (false===($theme = xt_is_valid($dir)))
   		redirectMsg('index.php', sprintf(__('Theme "%s" is not a valid XThemes theme!','xthemes'), $dir), 1);
    
    include_once $pdir.'/config/theme.php';
    $rtheme = preg_replace('/\s+/', '', strtolower($dir));
	$rtheme = str_replace('-','',$rtheme);
	$class = "XTheme".ucfirst($rtheme);
    if (!class_exists($class))
    	redirectMsg('index.php', sprintf(__('Theme "%s" is not a valid XThemes theme!','xthemes'), $dir), 1);
    
    $db = Database::getInstance();
    //$theme = new $class();
    
    // Check requirements
    if (method_exists($theme, 'check_requirements')){
    	if (!$theme->check_requirements())
    		redirectMsg('index.php',sprintf(__('Theme "%s" return next errors:', 'xthemes'), $theme->name()).'<br /><br />'.implode("<br />", $theme->errors()), 1);
	}

    // Insert theme data
    if (!$db->queryF("UPDATE ".$db->prefix("config")." SET `conf_value`='$dir' WHERE `conf_name`='theme_set' AND conf_modid='0'"))
        redirectMsg('index.php',__('The theme has not been installed!','xthemes').'<br />'.$db->error(), 1);
        
    $db->queryF("UPDATE ".$db->prefix("config")." SET `conf_value`='".serialize(array($dir))." WHERE `conf_name`='theme_set_allowed' AND conf_modid='0'");
        
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

    redirectMsg('index.php', __('Theme installed successfully','xthemes').(count($messages)>0 ? " ".__("but there was some errors:",'xthemes')."<br />".implode("<br />".$messages) : ''), 0);
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
    	redirectMsg('index.php', sprintf(__('¡"%s" is a xThemes theme!','xthemes'), $dir), 1);
    
    if (xt_is_valid($dir))
   		redirectMsg('index.php', sprintf(__('¡"%s" is a xThemes theme!','xthemes'), $dir), 1);
		
	$db = Database::getInstance();
    // Insert theme data
    if (!$db->queryF("UPDATE ".$db->prefix("config")." SET `conf_value`='$dir' WHERE `conf_name`='theme_set' AND conf_modid='0'"))
        redirectMsg('index.php', __('The theme has not been installed¡','xthemes').'<br />'.$db->error(), 1);
    $_SESSION['xoopsUserTheme'] = $dir;
    // Delete previous data if valid
    if (false !== ($previous = xt_is_valid($previous))){
		if (method_exists($previous, 'uninstall')){
			$messages = array();
			if (!$previous->uninstall()) $mssages = $previous->errors();
		}
	}

    redirectMsg('index.php', __('Theme installed successfully','xthemes').(count($messages)>0 ? " ".__("but there was some errors:",'xthemes')."<br />".implode("<br />".$messages) : ''), 0);
}

/**
* Check the settings and save data
*/
function xt_save_settings(){
	global $xoopsConfig;

	$myts = MyTextSanitizer::getInstance();
	
	if (false === ($theme = xt_is_valid($xoopsConfig['theme_set'])))
		redirectMsg('index.php', __('This is a not valid theme','xthemes'), 1);
	
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
			$value = serialize($value);
			$type = 'array';
		} else {
			$value = $myts->addSlashes($value);
			$type = '';
		}
		
		if (!$db->queryF(sprintf($sql, $id, $value, $type)))
			$errors[] = $db->error();
		
	}
    
    RMEvents::get()->run_event('xtheme.save.settings', $xt_to_save, $errors);
    
	if (!empty($errors)){
		redirectMsg('index.php?op=config', __('There was errors during this operation:','xthemes').'<br />'.implode('<br />', $errors), 1);
	} else {
		redirectMsg('index.php?op=config', __('Settings updated!', 'xthemes'), false);
	}
	
}

function clean_values(&$value, $key){

	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
	
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