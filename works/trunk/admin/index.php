<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('PW_LOCATION','index');
include 'header.php';



//Inicio
$tpl->append('options', array('text'=>_AS_PW_HOME, 'info'=>_AS_PW_CLICK,
		'link'=>'../','icon'=>'../images/home48.png'));

//Categorías
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_categos');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_PW_CATEGOS, 'info'=>sprintf(_AS_PW_CATSNUM, $num),
		'link'=>'categos.php','icon'=>'../images/cats48.png'));

//Tipos de Cliente
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_types');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_PW_TYPES, 'info'=>sprintf(_AS_PW_TYPESNUM, $num),
		'link'=>'types.php','icon'=>'../images/types48.png'));

//Clientes
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_clients');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_PW_CLIENTS, 'info'=>sprintf(_AS_PW_CLIENTSNUM, $num),
		'link'=>'clients.php','icon'=>'../images/clients48.png'));

//Trabajos
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works');
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_PW_WORKS, 'info'=>sprintf(_AS_PW_WORKSNUM, $num),
		'link'=>'works.php','icon'=>'../images/works48.png'));

$tpl->append('options', array('text'=>"Red México", 'info'=>_AS_PW_CLICK,
		'link'=>'http://redmexico.com.mx','icon'=>'../images/rm48.png'));

$tpl->append('options', array('text'=>"EXM System", 'info'=>_AS_PW_CLICK,
		'link'=>'http://exmsystem.org','icon'=>'../images/exm.png'));

$tpl->append('options', array('text'=>_AS_PW_HELP, 'info'=>_AS_PW_CLICK,
		'link'=>'http://exmsystem.org','icon'=>'../images/help.png'));


$access = PWFunctions::accessInfo();
if (!$access){
	$tpl->assign('err_access', 1);
	$tpl->assign('lang_showcode', _AS_PW_SHOWCODE);
	$tpl->assign('lang_erraccess', _AS_PW_ERRACCESS);
	
	$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
	$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/works/');
	$code="[code]AddType application/x-rar .rar\nRewriteEngine On\nRewriteBase /exm/modules/works/\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^recent/(.*)/?$ recent.php$1 [L]\nRewriteRule ^featured/(.*)/?$ featured.php$1 [L]\nRewriteRule ^work/(.*)/?$ work.php?id=$1 [L]\n
RewriteRule ^cat/(.*)/?$ catego.php?id=$1 [L][/code]";
	$tpl->assign('code', $myts->displayTarea($code, 0,0,1,0,1));
	
} else {
	// Comprobamos que el archivo .htaccess no tenga permisos de escritura
	$file = XOOPS_ROOT_PATH.'/modules/works/.htaccess';
	if (is_writable($file)){
		$tpl->assign('err_access', 1);
		$tpl->assign('lang_erraccess', _AS_PW_ERRACCWRITE);
	}
}


$adminTemplate = "admin/pw_index.html";
xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a>");

//Control de Versiones
$ver=$xoopsModule->getVar('version');
$version=$ver['number'].'.'.$ver['revision'].'.'.$ver['status'];
$url = "http://redmexico.com.mx/modules/vcontrol/?id=10";
$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";

$cHead .= '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
xoops_cp_header($cHead);

xoops_cp_footer();
?>
