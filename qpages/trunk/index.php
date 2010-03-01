<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';

$docroot = strtolower(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']));
$root = strtolower(rtrim(XOOPS_ROOT_PATH, '/'));
$request = str_replace($root, '', $docroot.$_SERVER['REQUEST_URI']);

// Replace the base paths
$request = trim(str_replace($xoopsModuleConfig['basepath'], '', $request),'/');

/**
 * Decodifica los parámetros de la URI
 * para permitir el mejor manejo de las
 * distintas secciones
 * @return array
 */
function headerDecode(){
	global $xoopsModuleConfig, $xoopsModule, $request;

	$header = array();
	if ($xoopsModuleConfig['links']==0){
		foreach ($_REQUEST as $k => $v){
			$header[$k] = $v;
		}
		if (!isset($header['show'])) $header['show'] = 'index';
		return $header;
	}

	if ($request=='' || substr($request,0,9)=='index.php' || substr($request, 0, 1)=='?'){
		$header['show'] = 'index';
		return $header;
	}
	
	$vars = explode("/", $request);
	
	if ($vars[0]=='category'){
		$header['show'] = 'category';
		return $header;
	}

	$header['show'] = 'page';	
	return $header;
}

$header = headerDecode();

switch($header['show']){
	case 'category':
		require 'catego.php';
		break;
	case 'page':
		require 'page.php';
		break;
	case 'index':
		require 'home.php';
		break;
}
