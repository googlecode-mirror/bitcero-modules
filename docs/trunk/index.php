<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('INCLUDED_INDEX',1);
/**
* This file redirects all petions directly to his content
*/

$query = isset($_GET['page']) ? $_GET['page'] : '';
if (substr($query, strlen($query)-1)=='/')
	$query = substr($query, 0, strlen($query)-1);
if (substr($query, 0, 1)=='/')
	$query = substr($query, 1);

if ($query==''){
	require 'mainpage.php';
	exit();
}

$params = explode('/',$query);

// Here must be the search verification


// Error verification
if (count($params)>2){
	/**
	* @todo Error 404 template
	*/
}

if ($params[0]=='publish' && count($params)==1){
	require 'publish.php';
	exit();
}

// Print
if ($params[0]=='print' && count($params)>2){
	$id_res = $params[1];
	$id_sec = $params[2];
	$print = true;
	require 'content.php';
	exit();
}

// Edit
if ($params[0]=='edit' && count($params)>1){
	$id_res = $params[1];
	$id_sec = $params[2];
	$op = 'edit';
	require 'edit.php';
	exit();
}

// LIsta de Secciones
if (($params[0]=='list' || $params[0]=='newpage') && count($params)>1){
	$id_res = $params[1];
	$op = $params[0]=='list' ? '' : 'new';
	require 'edit.php';
	exit();
}

// Búsqueda
if ($params[0]=='search'){
	
	require 'search.php';
	exit();
	
}

// Figures
if ($params[0]=='figures' || $params[0]=='references'){
	$id = $params[1];
	$special = $params[0];
	require 'resource.php';
	exit();
}

// Content
if (count($params)>1){
	$id_res = $params[0];
	$id_sec = $params[1];
	$print = 0;
	require 'content.php';
	exit();
}

/**
* @todo Crear sección para revisar mis secciones
*/

// If at this point params count > 1 then seems to be an error 404
if (count($params)>1){
	require 'error.php';
	exit;
}
// Once all verifications has been passed then the resource
// param is present, then we will show it

$id = $params[0];
require 'resource.php';
