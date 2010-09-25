<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','index');
include 'header.php';

$db = Database::getInstance();
$myts =& MyTextSanitizer::getInstance();
//Inicio
$tpl->append('options', array('text'=>_AS_GS_HOME, 'info'=>_AS_GS_CLICK,
		'link'=>'../','icon'=>'../images/home48.png'));

//Albumes
$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_sets');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_GS_SETS, 'info'=>sprintf(_AS_GS_SETSNUM, $num),
		'link'=>'sets.php','icon'=>'../images/album48.png'));

//Etiquetas
$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tags');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_GS_TAGS, 'info'=>sprintf(_AS_GS_TAGSNUM, $num),
		'link'=>'tags.php','icon'=>'../images/tags48.png'));

//Usuarios
$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_GS_USERS, 'info'=>sprintf(_AS_GS_USERSNUM, $num),
		'link'=>'users.php','icon'=>'../images/users48.png'));

//Imágenes
$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_images');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_GS_IMGS, 'info'=>sprintf(_AS_GS_IMGSNUM, $num),
		'link'=>'images.php','icon'=>'../images/images48.png'));

//Postales
$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_postcards');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_GS_POSTCARDS, 'info'=>sprintf(_AS_GS_POSTCARDSNUM, $num),
		'link'=>'postcards.php','icon'=>'../images/postcard48.png'));

//Espacio utilizado en disco
$dir = str_replace(XOOPS_URL,XOOPS_ROOT_PATH,$xoopsModuleConfig['storedir']);
$num = GSFunctions::folderSize($dir);
$tpl->append('options', array('text'=>_AS_GS_SIZE, 'info'=>sprintf(_AS_GS_SIZENUM, RMUtilities::formatBytesSize($num)),
		'link'=>'','icon'=>'../images/hd48.png'));


$tpl->append('options', array('text'=>"Red México", 'info'=>_AS_GS_CLICK,
		'link'=>'http://redmexico.com.mx','icon'=>'../images/rm48.png'));

$tpl->append('options', array('text'=>"EXM System", 'info'=>_AS_GS_CLICK,
		'link'=>'http://exmsystem.org','icon'=>'../images/exm.png'));

$tpl->append('options', array('text'=>_AS_GS_HELP, 'info'=>_AS_GS_CLICK,
		'link'=>'http://exmsystem.org','icon'=>'../images/help.png'));


$access = GSFunctions::accessInfo();
if (!$access){
	$tpl->assign('err_access', 1);
	$tpl->assign('lang_showcode', _AS_GS_SHOWCODE);
	$tpl->assign('lang_erraccess', _AS_GS_ERRACCESS);
	
	$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
	$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/galleries/');
	$code="[code]RewriteEngine On\nRewriteBase $path\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^usr/(.*)/?$ user.php?id=usr/$1 [L]\nRewriteRule ^explore/(.*)/?$ explore.php?by=explore/$1 [L]\nRewriteRule ^submit/(.*)/?$ submit.php?submit=submit/$1 [L]\nRewriteRule ^cpanel/(.*)/?$ cpanel.php?s=cpanel/$1 [L]\nRewriteRule ^postcard/(.*)/?$ postcard.php?id=postcard/$1 [L][/code]";
	$tpl->assign('code', $myts->displayTarea($code, 0,0,1,0,1));
	
} else {
	// Comprobamos que el archivo .htaccess no tenga permisos de escritura
	$file = XOOPS_ROOT_PATH.'/modules/galleries/.htaccess';
	if (is_writable($file)){
		$tpl->assign('err_access', 1);
		$tpl->assign('lang_erraccess', _AS_GS_ERRACCWRITE);
	}
}


$adminTemplate = "admin/gs_index.html";
xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a>");


$ver=$xoopsModule->getVar('version');
$version=$ver['number'].'.'.$ver['revision'].'.'.$ver['status'];
$url = "http://redmexico.com.mx/modules/versions/gallery-system/$version/module";
$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
$cHead .= '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
xoops_cp_header($cHead);

xoops_cp_footer();
