<?php
// $Id$
// --------------------------------------------------------------
// EXM System
// Content Management System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: bitc3r0@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains a set of useful functions for template designers
*/
function tpl_cycle($values, $delimiter = ',', $reset = false){
    static $cycle_index;

    if (trim($values)=='') {
        return;
    }
    
    if(is_array($values)) {
        $cycle_array = $values;
    } else {
        $cycle_array = explode($delimiter,$values);
    }
    
    if(!isset($cycle_index) || $reset) {
    	$cycle_index = 0;
    }
          
    $retval = $cycle_array[$cycle_index];

    if ( $cycle_index >= count($cycle_array) -1 ) {
    	$cycle_index = 0;
    } else {
    	$cycle_index++;
    }

    return $retval;
}

/**
* Create location for modules
*/
function xoops_cp_location($location){
	RMTemplate::get()->assign('admin_location', $location);
}

/**
 * Función para mostrar un mensaje de error en determinadas pginas
 * @param string $url Pgina en la que se mostrar el error
 * @param string $msg Mensaje de Error
 * @param int $level 0 = Informacin, 1 = Error
 */
function redirectMsg($url, $msg='', $level=0){
    $i = isset($_SESSION['rmMsg']) ? count($_SESSION['rmMsg']) + 1 : 0;
    $_SESSION['rmMsg'][$i]['text'] = htmlentities($msg);
    $_SESSION['rmMsg'][$i]['level'] = $level;
    header('location: '.preg_replace("/[&]amp;/i", '&', $url));
    die();
}

/**
* GET Predefined Variable
* @param var Server VAR ($_POST, $_GET, $_SERVER, etc.)
* @param string Value key
* @param any Default value to return if the var is not located.
* @return any
*/
function rmc_server_var($from, $key, $default=''){
	$ret = isset($from[$key]) ? $from[$key] : $default;
	return $ret;
}

function showMessage($msg, $level=0){
	$_SESSION['rmMsg'][] = array(
        'text' => htmlentities($msg),
	    'level' => $level
    );
}
