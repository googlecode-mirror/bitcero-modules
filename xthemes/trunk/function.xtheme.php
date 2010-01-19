<?php
// $Id$
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

function smarty_function_xtheme($params, &$smarty){
	
	if (!is_file(XOOPS_ROOT_PATH.'/modules/xthemes/controller.php'))
		return;
		
	include_once XOOPS_ROOT_PATH.'/modules/xthemes/controller.php';
	
	if (!isset($params['action'])) return;
	
	$action = $params['action'];

	if (!method_exists(xthemesController,$action)) return;
	
	$controller = xthemesController::get();
	
	$return = $controller->$action($smarty, !empty($params) ? array_slice($params, 1) : array());
	
	if ($return)
		return $return;
	
}
