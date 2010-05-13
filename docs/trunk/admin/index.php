<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


define('AH_LOCATION', 'index');
include 'header.php';

// Opciones de la Página Inicial
$tpl->append('options', array('text'=>_AS_AH_GOMOD, 'info'=>_AS_AH_CLICK,
        'link'=>'../','icon'=>'../images/gomod.png'));

// Recursos
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_resources");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_RES, 'info'=>sprintf(_AS_AH_RESNUM, $num),
        'link'=>'resources.php','icon'=>'../images/res48.png'));
        
// Recursos Aprobados
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_resources")." WHERE approved='1'";
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_RES, 'info'=>sprintf(_AS_AH_RESAPPROVE, $num),
        'link'=>'resources.php','icon'=>'../images/approved.png'));

// Recursos Aprobados
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_resources")." WHERE approved='0'";
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_RES, 'info'=>sprintf(_AS_AH_RESNOAPPROVE, $num),
        'link'=>'resources.php','icon'=>'../images/noapprove.png'));

// Secciones
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_sections");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_SECS, 'info'=>sprintf(_AS_AH_SECSNUM, $num),
        'link'=>'sections.php','icon'=>'../images/sections48.png'));

// Modificaciones
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_edits");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_EDITS, 'info'=>sprintf(_AS_AH_EDITSNUM, $num),
        'link'=>'edits.php','icon'=>'../images/edits48.png'));

// Referencias
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_references");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_REFS, 'info'=>sprintf(_AS_AH_REFSNUM, $num),
        'link'=>'refs.php','icon'=>'../images/refs48.png'));

// Figuras
$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_figures");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_AH_FIGS, 'info'=>sprintf(_AS_AH_FIGSNUM, $num),
        'link'=>'figs.php','icon'=>'../images/figs48.png'));
        
$tpl->append('options', array('text'=>'Red México', 'info'=>_AS_AH_CLICK,
        'link'=>'http://www.redmexico.com.mx','icon'=>'../images/rm48.png'));
        
$tpl->append('options', array('text'=>'EXM System', 'info'=>_AS_AH_CLICK,
        'link'=>'http://exmsystem.net','icon'=>'../images/exm.png'));

$tpl->append('options', array('text'=>_AS_AH_HELP, 'info'=>_AS_AH_CLICK,
        'link'=>'http://exmsystem.net/docs/manual-de-ability-help','icon'=>'../images/help.png'));

if ($mc['access']){
	
	$tpl->assign('access', 1);
	$tpl->assign('lang_showcode', _AS_AH_SHOWCODE);
	$tpl->assign('lang_access', _AS_AH_ACCESS);
	
	$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
	$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/ahelp/');
	$code = "[code]#Begins Ability Help
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /wiki/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^wiki/?(.*)/?$ /modules/ahelp/index.php?page=$1 [L]
</IfModule>
#end Ability Help[/code]";
	$tpl->assign('code', $myts->displayTarea($code, 0,0,1,0,1));
	
}

xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a>");
$adminTemplate = 'admin/ahelp_index.html';
$url = "http://redmexico.com.mx/modules/vcontrol/?id=8";
$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
$cHead .= '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
xoops_cp_header($cHead);
 
xoops_cp_footer();

?>