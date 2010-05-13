<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

/**
* @desc Verifica el tipo de acceso a la información y si es necesario 
*la existencia del archivo htaccess
**/
function accessInfo(){
	global $xoopsModuleConfig;

	if ($xoopsModuleConfig['access']==0) return true;
	
	$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
	$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/ahelp/');
	if (substr($path, 0, 1)!='/'){
		$path = '/'.$path;
	}
	$file=XOOPS_ROOT_PATH.'/modules/ahelp/.htaccess';

	if (!file_exists($file)){
		return false;
	}
		
	//Determina permisos de lectura y escritura a htacces
	if ((!is_readable($file)))		
	{
		return false;
	}

	//Verifica que información contiene htaccess y si es necesario reescribe htacces
	$info = file_get_contents($file);

	//Si acceso es por id numérico
	if ($xoopsModuleConfig['access']==1){
		$numeric="RewriteEngine On\nRewriteBase $path\n\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^resource/(.*)/?$ resource.php?id=$1\nRewriteRule ^content/(.*)/?$ content.php?id=$1\nRewriteRule ^article/(.*)/?$ content.php?t=a&id=$1";
		
		//Compara contenido de htaccess
		$pos = stripos(file_get_contents($file),$numeric);		
		
		if ($pos!==false) return true;
		
		if ((!is_writable($file)))		
		{
			return false;
		}
		
		//Copia información a archivo
		return file_put_contents($file,$numeric);
	
	}

}
