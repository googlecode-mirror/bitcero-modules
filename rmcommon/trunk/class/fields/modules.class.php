<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class RMFormModules extends RMFormElement
{
	private $multi = 0;
	private $type = 0;
	private $selected = null;
	private $cols = 2;
	private $inserted = array();
	private $dirnames = true;
	private $subpages = 0;
	private $selectedSubPages = array();
	
	/**
	 * Constructor
	 * @param string $caption
	 * @param string $name Nombre del campo
	 * @param int $multi Selecciona multiple activada o desactivada
	 * @param int $type 0 = Select, 1 = Tabla
	 * @param array $selected Valor seleccionado por defecto
	 * @param array $selected Grupo de vlores seleccionado por defecto
	 * @param int $cols Numero de columnas para la tabla o filas para un campo select multi
	 * @param array $insert Array con valores para agregar a la lista
	 * @param bool $dirnames Devolver nombres de directorios (true) o ids (false)
	 * @param int Mostrar Subpáginas
	 */
	function __construct($caption, $name, $multi = 0, $type = 0, $selected = null, $cols = 2, $insert = null, $dirnames = true, $subpages = 0){
		$this->setName($multi ? str_replace('[]', '', $name) : $name);
		$this->setCaption($caption);
		$this->multi = $multi;
		$this->type = $type;
		$this->cols = $cols;
		$this->selected = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $selected;
		$this->inserted = $insert;
		$this->dirnames = $dirnames;
		$this->subpages = $subpages;
		!defined('RM_FRAME_APPS_CREATED') ? define('RM_FRAME_APPS_CREATED', 1) : '';
	}
	public function multi(){
		return $this->multi;
	}
	public function setMulti($value){
		if ($value==0 || $value==1){
			//$this->setName($value ? str_replace('[]','',$this->getName()).'[]' : str_replace('[]','',$this->getName()));
			$this->multi = $value;
		}
	}
	public function type(){
		return $this->type;
	}
	public function setType($value){
		return $this->type = $value;
	}
	public function selected(){
		return $this->selected;
	}
	public function setSelected($value){
		return $this->selected = $value;
	}
	public function sizeOrCols(){
		return $this->cols;
	}
	public function setSizeOrCols($value){
		return $this->cols = $value;
	}
	public function inserted(){
		return $this->inserted;
	}
	/**
	 * Inserta nuevas opciones en el campo
	 * @param array $value Array con valor=>caption para las opciones a insertar
	 */
	public function setInserted($value){
		$this->inserted = array();
		$this->inserted = $value;
	}
	
	public function dirnames(){
		return $this->dirnames;	
	}
	/**
	 * Establece si se devuelven los valores con 
	 * el nombre del directorio del módulo o con 
	 * el identificador del módulo
	 * @param bool $value
	 */
	public function setDirNames($value = true){
		$this->dirnames = $value;
	}
	/**
	* @desc Establece las subpáginas seleccionadas por defecto
	* @param array Subpáginas seleccionadas
	*/
	public function subpages($subs){
		$this->selectedSubPages = $subs;
	}
	
	function render(){
		$module_handler =& xoops_gethandler('module');
        $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
        $criteria->add(new Criteria('isactive', 1));
        $modules = array();
        if (is_array($this->inserted)) $modules = $this->inserted;
        foreach ($module_handler->getList($criteria, $this->dirnames) as $k => $v){
        	$modules[$k] = $v;
        }
		
		if ($this->type){
            // Add js script
            RMTemplate::get()->add_local_script('modules_field.js', 'rmcommon', 'include');
            
			$rtn = '<div class="modules_field">';
			$i = 1;
			foreach ($modules as $k => $v){
                $app = RMFunctions::load_module($k);
				$rtn .= "<div class=\"mod_item\">";
                $name = $this->multi ? $this->getName()."[$k]" : $this->getName();
				if ($this->multi){
					$rtn .= "<label id=\"modlabel-$k\" class='field_module_names'><input type='checkbox' value='$k' name='".$name."' id='".$this->getName()."-$k'".(is_array($this->selected) ? (in_array($k, $this->selected) ? " checked='checked'" : '') : '')." /> $v</label>";
				} else {
					$rtn .= "<label id=\"modlabel-$k\" class=\"field_module_names\"><input type='radio' value='$k' name='".$this->getName()."' id='".$this->getName()."-$k'".(!empty($this->selected) ? ($k == $this->selected ? " checked='checked'" : '') : '')." /> $v</label>";
				}

				/**
				* Mostramos las subpáginas
				*/
				if ($this->subpages && $k>0){
                    
					$subpages = $app->getInfo('subpages');
					$selected = $this->selectedSubPages;
					$cr = 0;
					$rtn.="<div id=\"subpages-".$k."\" class=\"subpages_container\">
                            <div class='sp_title'><span id=\"close-$k\"></span>".__('Inner pages','rmcommon')."</div>
							<div class='sub_item'>
							<label><input type='checkbox' name='subpages[$k][null]' id='subpages[$k][null]' value='--'".(!is_array($subpages) || @in_array('--', $selected[$k]) ? " checked='checked'" : '')." onclick=\"checkSubpageClick('subpages[$k][null]','$k',$k);\" /> ".__('All','rmcommon')."</label></div>";
					$j = 2;
					$cr = 2;
					if (!is_array($subpages)) $subpages = array();
					
					foreach ($subpages as $page=>$caption){
						
						$rtn.="<div class=\"sub_item\"><label><input type='checkbox' name='subpages[$k][$page]' id='subpages[$k][$page]' value='$page'".(is_array($subpages) && @in_array($page, $selected[$k]) ? " checked='checked'" : '')." onclick=\"checkSubpageClick('subpages[$k][$page]', $k);\" /> $caption</label></div>";
						$j++;
						$cr++;
					}
			   		$rtn.='</div>';
			   
				}
				$rtn .= "</div>";
				$i++;
			}

			$rtn .= "</div>";
		} else {
			if ($this->multi){
                $name = $this->getName()."[$k]";
				$rtn = "<select name='".$name."' id='".$name."' size='$this->cols' multiple='multiple'>";
				foreach ($modules as $k => $v){
					$rtn .= "<option value='$k'".(is_array($this->selected) ? (in_array($k, $this->selected) ? " selected='selected'" : '') : '').">$v</option>";
				}
				$rtn .= "</select>";
			} else {
				$rtn = "<select name='".$this->getName()."' id='".$this->getName()."'>";
				foreach ($modules as $k => $v){
					$rtn .= "<option value='$k'".(!empty($this->selected) ? ($k==$this->selected ? " selected='selected'" : '') : '').">$v</option>";
				}
				$rtn .= "</select>";
			}
		}
		
		return $rtn;
		
	}
}
