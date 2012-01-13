<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Clase abstracta para derivar todos los elementos del formulario
 * Esta clase no puede ser instanciada directamente
 */
abstract class RMFormElement
{
	private $_name = '';
	private $_caption = '';
	private $_class = '';
	private $_extra = '';
	private $_required = '';
	private $_description = '';
	private $_formname = '';
	
	/**
	 * Establece el nombre de un elemento espec?fico del formulario
	 * @param string $name Nombre del elemento
	 */
	public function setName($name){
		$this->_name = trim($name);
        return $this;
	}
	/**
	 * Obtiene el nombre de un elemento espec?fico en el formulario
	 * @return string Nombre del elemento
	 */
	public function getName(){
		return $this->_name;
	}
	/**
	 * Establece el nombre de la clase CSS que se usará en un
	 * elemento específico del formulario
	 * @param string $class Nombre de la clase (eg. "even")
	 */
	public function setClass($class){
		$this->_class = $class;
        return $this;
	}
	public function addClass($class){
		$this->_class .= " $class";
        return $this;
	}
	/**
	 * Recupera el nombre de clase de un elemento espe?fico del formulario
	 * @return string Nombre de la clase
	 */
	public function getClass(){
		return $this->_class;
	}
	/**
	 * Establece el texto de la etiqueta para un
	 * elemento espec?fico del formulario
	 * @param string $caption Texto de la etiqueta
	 */
	public function setCaption($caption){
		$this->_caption = trim($caption);
        return $this;
	}
	/**
	 * Recuepera el texto de la etiqueta de un elemento espec?fico
	 * @return string Texto de la etiqueta
	 */
	public function getCaption(){
		return $this->_caption;
	}
	/**
	 * Establece el texto descriptivo de un elemento
	 * espec?fico en el formulario. Este texto se presenta
	 * por debajo del texto de la etiqueta ({@link setCaption()}) en letra
	 * peque?a.
	 * @param string $desc Descripci?n del elemento
	 */
	public function setDescription($desc){
		$this->_description = $desc;
        return $this;
	}
	/**
	 * Recupera el texto descriptivo del elemento del formulario
	 * @return string Descripci?n
	 */
	public function getDescription(){
		return $this->_description;
	}
	/** 
	 * Establece texto adicional para el elemento del formulario.
	 * Este texto se insertar? dentro de la etiqueta <input ... /> o la
	 * que corresponda. Puede insertarse c?digo para funciones JavaScript (onclick, onfocus, etc)
	 * o cualquier otro dato v?lido para el elemento
	 * @param string $extra Texto a insertar
	 */
	public function setExtra($extra){
		$this->_extra = $extra;
        return $this;
	}
	/**
	 * Recuepera la informaci?n extra del elemento
	 * @return string Texto extra
	 */
	public function getExtra(){
		return $this->_extra;
	}
	/**
	* @desc Asigna el formulario (nombre) al elemento actual
	*/
	public function setForm($name){
		$this->_formname = $name;
        return $this;
	}
	public function getForm(){
		return $this->_formname;
	}
	/**
	 * Obtenemos la tabla html formateada
	 * Este m?todo es abstracto y solo puede ser
	 * llamado desde la clase heredada
	 */
	abstract function render();
}
?>