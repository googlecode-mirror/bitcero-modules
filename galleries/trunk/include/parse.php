<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Obtenemos el valor de la variable $toget
* $toget debe definirse antes de la inclusión de etse archivo
*/
$params = isset($_REQUEST[$toget]) ? $_REQUEST[$toget] : '';

$params = explode("/", $params);
$number = intval(count($params)/2);
/**
* @desc Generamos las variable sen base a los parámetros
* Los parámetros son divididos por "/". Es importante que
* siempre esten en pares para que todo funcione
*/
if ($number>0){
	for ($i=0;$i<($number*2);$i+=2){
		$$params[$i] = $params[$i+1];
	}
}

