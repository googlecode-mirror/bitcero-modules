<?php
// $Id: functions.php 10 2009-08-30 23:32:21Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

/**
* Several functions for themes management
* @author Eduardo Cortés <i.bitcero@gmail.com>
*/

define('ITPATH', XOOPS_ROOT_PATH.'/modules/xthemes');
define('ITURL', XOOPS_URL.'/modules/xthemes');

/**
* Checks if a theme is valid.
* If thame is valid then returns the prefix for functions names
* 
* @param string $theme
* @return bool|string
*/
function &xt_is_valid($theme){
	global $xoopsConfig;

	if (!is_file(XOOPS_THEME_PATH.'/'.$theme.'/config/theme.php'))
		return false;
		
	include_once XOOPS_THEME_PATH.'/'.$theme.'/config/theme.php';
	$rtheme = preg_replace('/\s+/', '', strtolower($theme));
	$rtheme = str_replace('-','',$rtheme);
	$class ='XTheme'.ucfirst($rtheme);
	if (!class_exists($class))
		return false;
        
    $object = new $class();
	return $object;
}

function values_decode(&$value, $key){
	$value = utf8_decode($value);
}

/**
* Redirect with a message
*/
function xt_redirect($url, $message, $error=false){
	
	$_SESSION['redir_message'] = $message;
	$_SESSION['redir_error'] = $error;
	header('location: '.$url);
	exit();
	
}

/**
* print the message
*/
function xt_show_message(){
	// Check if there are some error
	if (isset($_SESSION['redir_message'])){
		echo "<div id='it-redir-message'";
		if ($_SESSION['redir_error']){
			echo " class='error'";
		}
		echo ">".$_SESSION['redir_message']."</div>";
		unset($_SESSION['redir_message']);
		unset($_SESSION['redir_error']);
	}
}

/**
* Gets the current theme config
*/
function xt_get_current_config($element=''){
	$db = Database::getInstance();
	$sql = "SELECT * FROM ".$db->prefix("xtheme_config").($element != '' ? " WHERE element='$element'" : '');
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$ret[$row['name']] = $row['type']=='array' ? json_decode($row['value'], true) : $row['value'];
	}
	
	array_walk_recursive($ret, 'decode_entities');
	
	return $ret;
}

function decode_entities(&$value, $key){
	$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
}


function xt_load_jquery(){
	global $xoopsTpl, $xoopsConfig, $xoopsLogger;
	
	if ($xoopsConfig['cpanel']!='oxygen'){
		/*
		$salida = ob_get_contents();
		$pos = strpos($salida,'</head>');
		
		$pre = substr($salida, 0, $pos);
		$post = substr($salida, $pos);
		$salida = $pre;*/
		echo '<script type="text/javascript" src="'.XOOPS_URL.'/modules/xthemes/include/js/jquery-1.3.2.min.js?ver=1.0"></script>
			<script type="text/javascript" src="'.XOOPS_URL.'/modules/xthemes/include/js/jquery-ui-1.7.2.custom.min.js"></script>';
		//$salida .= $post;
		
	}
	
	echo '<script type="text/javascript" src="'.XOOPS_URL.'/modules/xthemes/include/js/xthemes.js"></script>';
	echo '<link rel="stylesheet" type="text/css" href="'.ITURL.'/css/themes.css?ver=1.0" media="all" />';
	
}

function xt_menu_options(){
?>	
<div id="px-menu-options">
	<a href="index.php" title="Show all themes" class="themes">Themes</a>
	<a href="index.php?op=config" title="Configure current theme" class="settings">Configure</a>
	<a href="index.php?op=catalog" title="Show themes catalog" class="catalog">Themes Catalog</a>
	<a href="index.php?op=about" title="About I.Themes Module" class="about">About I.Themes</a>
</div>
<?php
}

/**
* Insert configuration in db
* @param array Array of configurations
* @param string Element type: theme or plugin
*/
function xt_insert_configs($configs, $element){
	
	if (empty($configs)) return;
	
	$db = Database::getInstance();
	
	$db->queryF("DELETE FROM ".$db->prefix("xtheme_config")." WHERE element='$element'");
	
	$sql = "INSERT INTO ".$db->prefix("xtheme_config")." (`name`,`value`,`type`,`element`) VALUES ('%s','%s','%s','$element')";
	foreach ($configs as $name => $value){
		$type = '';
		if (is_array($value)){
			$value = json_encode($value);
			$type = 'array';
		}
		
		$db->queryF(sprintf($sql, $name, $value, $type));
		
	}
	
}

/**
* Check if a plugin is installed
* When plugin is installed then this function returns an array with
* all plugin info. This array can be used to check version or another
* things.
* 
* @param string Plugin name
* @return bool|array
*/
function xt_plugin_installed($plugin){
	
	if ($plugin=='') return false;
	
	$db = Database::getInstance();
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("xtheme_plugins")." WHERE dir='".MyTextSanitizer::addSlashes($plugin)."'";
	list($num) = $db->fetchRow($db->query($sql));
	
	if ($num<=0) return;
	
	$path = ITPATH.'/plugins/'.$plugin;
	
	if (!is_file($path.'/xthemes_plugin_'.$plugin.'.php')) return false;
	
	include_once $path.'/xthemes_plugin_'.$plugin.'.php';
	$class = "XThemes".ucfirst($plugin);
	if (!class_exists($class)) return false;
	
	$plugin = new $class();
	return $plugin->get_info();
	
}

/**
* Check if a modules is installed
* @param string Module dirname
* @return bool
*/
function xt_module_installed($dir){
	if ($dir=='') return;
	
	$db = Database::getInstance();
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("modules")." WHERE dirname='".MyTextSanitizer::addSlashes($dir)."'";
	list($num) = $db->fetchRow($db->query($sql));
	
	if ($num<=0) return false;
	
	return true;
	
}
