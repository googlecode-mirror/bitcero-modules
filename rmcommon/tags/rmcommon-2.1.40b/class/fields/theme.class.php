<?php
// $Id: theme.class.php 825 2011-12-09 00:06:11Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMFormTheme extends RMFormElement
{
	private $multi = 0;
	private $type = 0;
	private $selected = array();
	private $cols = 2;
	private $section = '';
	
	/**
	 * Constructor
	 * @param string $caption
	 * @param string $name Nombre del campo
	 * @param int $multi Selecciona multiple activada o desactivada
	 * @param int $type 0 = Select, 1 = Tabla
	 * @param int $selected Valor seleccionado por defecto
	 * @param array $selected Grupo de vlores seleccionado por defecto
	 * @param int $cols Numero de columnas para la tabla o filas para un campo select multi
	 * @param string 'GUI' for admin theme
	 */
	function __construct($caption, $name, $multi = 0, $type = 0, $selected = null, $cols = 2, $section=''){
		$this->setName($name);
		$this->setCaption($caption);
		$this->multi = $multi;
		$this->type = $type;
		$this->cols = $cols;
		$this->selected = $selected;
		$this->section = $section;
	}
	function multi(){
		return $this->multi;
	}
	function setMulti($value){
		return $this->multi = $value;
	}
	function type(){
		return $this->type;
	}
	function setType($value){
		return $this->type = $value;
	}
	function selected(){
		return $this->selected;
	}
	function setSelected($value){
		return $this->selected = $value;
	}
	
	function render(){
		require_once ABSPATH."/class/exmlists.php";
		$themes = $this->section=='GUI' ? EXMLists::getThemeGUIDirList() : EXMLists::getThemeDirListAsArray();
		if ($this->type){
			$rtn = "<table cellpadding='2' cellspacing='1' border='0'><tr>";
			$i = 1;
			foreach ($themes as $k){
				if ($i>$this->cols){
					$rtn .= "</tr><tr>";
					$i = 1;
				}
				$rtn .= "<td width='".((int)(100/$this->cols))."%'>";
				if ($this->multi){
					$rtn .= "<label><input type='checkbox' value='$k' name='".$this->getName()."[]' id='".$this->getName()."[]'".(is_array($this->selected) ? (in_array($k, $this->selected) ? " checked='checked'" : '') : '')." /> $k</label>";
				} else {
					$rtn .= "<label><input type='radio' value='$k' name='".$this->getName()."' id='".$this->getName()."'".(!empty($this->selected) ? ($k == $this->selected ? " checked='checked'" : '') : '')." /> $k</label>";
				}
				$rtn .= "</td>";
				$i++;
			}
			$rtn .= "</tr></table>";
		} else {
			if ($this->multi){
				$rtn = "<select name='".$this->getName()."[]' id='".$this->getName()."[]' size='6' multiple='multiple'>";
				foreach ($themes as $k){
					$rtn .= "<option value='$k'".(is_array($this->selected) ? (in_array($k, $this->selected) ? " selected='selected'" : '') : '').">$k</option>";
				}
				$rtn .= "</select>";
			} else {
				$rtn = "<select name='".$this->getName()."' id='".$this->getName()."'>";
				foreach ($themes as $k){
					$rtn .= "<option value='$k'".(!empty($this->selected) ? ($k==$this->selected ? " selected='selected'" : '') : '').">$k</option>";
				}
				$rtn .= "</select>";
			}
		}
		
		return $rtn;
		
	}
}
