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
			$rtn = "<table cellpadding='2' cellspacing='1' border='0'><tr>";
			$i = 1;
			foreach ($modules as $k => $v){
				if ($i>$this->cols){
					$rtn .= "</tr><tr>";
					$i = 1;
				}
                $app = new XoopsModule($k);
				$rtn .= "<td width='".((int)(100/$this->cols))."%'>";
                $name = $this->multi ? $this->getName()."[$k]" : $this->getName();
				if ($this->multi){
					$rtn .= "<label id='rm_module_$k' class='field_module_names'><input type='checkbox' value='$k' name='".$name."' id='".$name."'".(is_array($this->selected) ? (in_array($k, $this->selected) ? " checked='checked'" : '') : '')." onclick=\"modCheckAll('subpages_$k','".$name."',0);\" /> $v</label>";
				} else {
					$rtn .= "<label><input type='radio' value='$k' name='".$this->getName()."' id='".$this->getName()."'".(!empty($this->selected) ? ($k == $this->selected ? " checked='checked'" : '') : '')." /> $v</label>";
				}
				
				$rtn .= ($this->subpages && $k>0) ? " <a href='javascript:;' onclick=\"\$('.subpages_container:visible').slideUp('slow');\$('#subpages-".$app->dirname()."').slideToggle('slow');\"' title='".__('Show module sections','rmcommon')."'><img src='".ABSURL."/rmcommon/images/subpages.gif' align='absmiddle' /></a>" : "";

				/**
				* Mostramos las subpáginas
				*/
				if ($this->subpages && $k>0){
					$subpages = $app->getInfo('subpages');
					$selected = $this->selectedSubPages;
					$cr = 0;
					$rtn.="<div id=\"subpages-".$app->dirname()."\" class=\"subpages_container\">
                            <table class='outer' cellspacing='0'>
							<tr><th class='round_top_left round_top_right' colspan='2'>
							<a href='javascript:;'><img src='".ABSURL."/rmcommon/images/close16.png' width='16' alt='' style='float: right;' onclick=\"\$('#subpages-".$app->dirname()."').slideToggle('slow');\"' /></a>".sprintf(_RMS_CF_MODSUBS, $v)."</th></tr>
							<tr class='odd'><td>
							<label><input type='checkbox' name='subpages[$k][null]' id='subpages[$k][null]' value='--'".(!is_array($subpages) || @in_array('--', $selected[$k]) ? " checked='checked'" : '')." onclick=\"checkSubpageClick('subpages[$k][null]','$k',$k);\" /> ".__('All')."</label></td>";
					$j = 2;
					$cr = 2;
					if (!is_array($subpages)) $subpages = array();
					
					foreach ($subpages as $page=>$caption){
						
						if ($cr>2){
							$rtn .= "</tr><tr class='odd'>";
							$cr = 1;
						}
						$rtn.="<td><label><input type='checkbox' name='subpages[$k][$page]' id='subpages[$k][$page]' value='$page'".(is_array($subpages) && @in_array($page, $selected[$k]) ? " checked='checked'" : '')." onclick=\"checkSubpageClick('subpages[$k][$page]', $k);\" /> $caption</label></td>";
						$j++;
						$cr++;
					}
			   		$rtn.='</tr></table></div>';
			   
				}
				$rtn .= "</td>";
				$i++;
			}

			$rtn .= "</tr></table>";
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
