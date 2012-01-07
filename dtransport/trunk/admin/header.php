<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


include '../../../include/cp_header.php';


/**
* Verificamos el directorio para los archivos de imagen
**/
if (!file_exists(XOOPS_UPLOAD_PATH.'/dtransport')){
	mkdir(XOOPS_UPLOAD_PATH.'/dtransport',511);
}

/**
* Verificamos el directorio ths para imagenes miniatura
**/
if (!file_exists(XOOPS_UPLOAD_PATH.'/dtransport/ths')){
	mkdir(XOOPS_UPLOAD_PATH.'/dtransport/ths',511);
}


$mc =& $xoopsModuleConfig;
/**
* Verificamos la existencia de directorio de descargas no seguras
**/
if (!file_exists($mc['directory_insecure'])){
	if (!mkdir($mc['directory_insecure'],511)){
		showMessage(sprintf(_AS_DT_NOTDIRINSECURE,$mc['directory_insecure']));
	}
}


/**
* Verificamos la existencia de directorio de descargas seguras
**/
if (!file_exists($mc['directory_secure'])){
	if (!mkdir($mc['directory_secure'],511)){
		showMessage(sprintf(_AS_DT_NOTDIRSECURE,$mc['directory_secure']));
	}
}
