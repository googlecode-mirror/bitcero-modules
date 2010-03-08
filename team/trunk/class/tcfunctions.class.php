<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class TCFunctions
{
	public function get($name){
		return isset($_GET[$name]) ? $_GET[$name] : '';
	}
	
	public function post($name){
		return isset($_POST[$name]) ? $_POST[$name] : '';
	}
	
	public function request($name){
		return isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
	}
	
}
