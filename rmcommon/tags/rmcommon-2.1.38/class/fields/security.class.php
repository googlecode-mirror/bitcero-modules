<?php
// $Id: security.class.php 183 2010-02-02 07:00:52Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require_once RMCPATH.'/class/utilities.php';

/**
 * Clase para el manejo e inclusión de código de
 * seguridad verificador del formulario
 */
class RMFormSecurityCode extends RMFormElement
{
	private $_size = 20;
	private $_max = 50;
	private $_refreshlink;
	private $_lencode = 5;
	/**
	 * Constructor de la clase
	 * @param sring $caption Texto de la etiqueta
	 * @param string $name Nombre del campo
	 * @param int $size Longitud del campo
	 * @param int $max Longitud máxima de carácteres del campo
	 * @param string $field Nombre del campo a modificar cuando no se ve el código
	 * @param string $value Valor del campo a modificar cuando no se el código
	 */
	function __construct($caption, $name, $lencode=5, $size=20, $max=50, $refreshlink=''){
		$this->setName($name);
		$this->setCaption($caption);
		$this->_size = $size;
		$this->_max = $max;
		$this->_refreshlink = $refreshlink;
		$this->_lencode = $lencode;
		
		if (isset($_REQUEST['rmseccode'])){
			if (file_exists(ABSPATH.'/uploads/rmccodes/'.$_REQUEST['rmseccode'].'.png'))
				unlink(ABSPATH.'/uploads/rmccodes/'.$_REQUEST['rmseccode'].'.png');
			
			if (file_exists(ABSPATH.'/uploads/rmccodes/'.$_REQUEST['rmseccode'].'.code'))
				unlink(ABSPATH.'/uploads/rmccodes/'.$_REQUEST['rmseccode'].'.code');
		}
	}
	/**
	 * Establece el tamño del campo.
	 * @param int $size Longitud del campo
	 */
	public function setSize($size){
		$this->_size = $size;
	}
	/**
	 * Recupera la longitud del campo
	 * @return int
	 */
	public function getSize(){
		return $this->_size;
	}
	/**
	 * Establece la acción javascript para actualizar el vínculo
	 * @param string $action Accion Javascript
	 */
	public function setRefreshAction($action){
		$this->_refreshlink = $action;
	}
	public function getRefreshAction(){
		return $this->_refreshlink;
	}
	/**
	 * Establece el número máximo de carácteres en el campo
	 * @param int $max Número de carácteres
	 */
	public function setMax($max){
		$this->_max = $max;
	}
	/**
	 * Recupera el número de carácteres
	 * @return int
	 */
	public function getMax(){
		return $this->_max;
	}
	/**
	 * Devuelve el código HTML para mostrar el campo.
	 * @return string
	 */
	public function render(){
		
		$util = new RMUtils();
		
		$ret = '<input type="text" size="'.$this->_size.'" name="'.$this->getName().'" id="'.$this->getName().'" maxlength="'.$this->getMax().'" value="" ';
		if ($this->getClass() != ''){
			$ret .= 'class="'.$this->getClass().'" '.$this->getExtra().' />';
		} else {
			$ret .= $this->getExtra().' />';
		}
		$code = $util->imageFromCode($util->randomString($this->_lencode, true, false, true));
		$ret .= "<br /><img src='".ABSURL."/uploads/rmccodes/$code.png' alt='' style='margin-top: 5px;' />
			<input type='hidden' name='rmseccode' value='$code' /><br />
			<a href='javascript:;' onclick=\"".$this->_refreshlink."\">"._RMS_CF_IFNOTVIEW."</a>";
		return $ret;
	}
}

?>