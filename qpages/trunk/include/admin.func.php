<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Comprueba y modifica el archivo htaccess en base
 * a las prefernecias del módulo
 */
function checkHTAccess(){
	global $mc, $tpl;
	$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
	$towrite = "# Begin QPages
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase ".str_replace($docroot, '', QP_PATH.'/')."
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . ".str_replace($docroot, '', QP_PATH.'/')."index.php [L]
</IfModule>
# End QPages";
	
	if (!file_exists(XOOPS_ROOT_PATH.'/uploads/qpcheck')){
		//file_put_contents(XOOPS_ROOT_PATH.'/uploads/qpcheck', $mc['links']);
		$prevlink = -1;
	} else {
		$prevlink = file_get_contents(XOOPS_ROOT_PATH.'/uploads/qpcheck');
	}
	
	if ($prevlink==$mc['links']) return true;
	
	if (!is_writable(QP_PATH.'/.htaccess')){
		showMessage(sprintf(_AS_QP_ERRHTACCESS, QP_PATH.'/.htaccess'));
		return false;
	}
	
	file_put_contents(XOOPS_ROOT_PATH.'/uploads/qpcheck', $mc['links']);
	
	if ($mc['links']){
		/**
		 * Si el archivo no existe entonces solo debemos
		 * escribir el archivo
		*/
		if (!file_exists(QP_PATH.'/.htaccess')) return file_put_contents(QP_PATH.'/.htaccess', $towrite);
		
		/**
		 * Si el archivo existe debemos buscar el texto
		 * para comprobar si existe, en caso de que no
		 * escribirmos las nuevas instrucciones
		 */
		file_put_contents(QP_PATH.'/.htaccess', $towrite);
		
		
	} else {
		/**
		 * Si el archivo no existe salimos
		 */
		if (!file_exists(QP_PATH.'/.htaccess')) return true;
		/**
		 * Si el archivo existe buscamos las instrucciones
		 * para qpages, si se encuentran las eliminamos
		 */
		return file_put_contents(QP_PATH.'/.htaccess', $towrite);
		
		
	}
}
