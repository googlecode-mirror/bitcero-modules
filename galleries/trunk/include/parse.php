<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2008 Red México

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

?>
