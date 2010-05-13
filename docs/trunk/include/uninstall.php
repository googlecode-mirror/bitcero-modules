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
* @desc Elimina directorios y archivos pertenecientes a ahelp
**/
function xoops_module_uninstall_ahelp(&$mod){
	
	//Elimina directorio de uploads/ahelp
	$dir=XOOPS_ROOT_PATH."/uploads/ahelp/";
	xoops_delete_directory($dir);

	//Elimina archivo recommends.php
	$file=XOOPS_ROOT_PATH."/cache/recommends.php";
	unlink($file);	

	if (!file_exists($dir) && !file_exists($file)){
		return true;	
	}
	else{
		return false;
	}
}


?>
