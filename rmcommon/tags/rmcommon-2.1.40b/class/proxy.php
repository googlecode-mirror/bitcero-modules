<?php
// $Id: proxy.php 825 2011-12-09 00:06:11Z i.bitcero $
// --------------------------------------------------------------
// EXM System
// Content Management System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once '../../../mainfile.php';
global $xoopsLogger, $xoopsConfig;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

/**
* @desc Proxy para realizar peticiones fuero del servidor
*/
class RMProxy
{
	private $url = '';
	private $type = '';
	
	function __construct($url, $type='text/html'){
		$this->url = $url;
		$this->type = $type;
	}
	
	function get(){
		global $exmConfig;
		// Creamos la petición
		$hdrs = array(
			'http'=>array(
				'method'=>"POST",
				'header'=>"accept-language: "._LANGCODE."\r\n"
			)
		);
		
		$context = stream_context_create($hdrs);
		$content = file_get_contents(urldecode($this->url), false, $context);
		header("Content-Type: $this->type");

		return $content;
	}
}
