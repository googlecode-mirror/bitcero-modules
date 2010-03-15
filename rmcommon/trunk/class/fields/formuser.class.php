<?php
// $Id$
// --------------------------------------------------------------
// EXM System
// Content Management System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Clase para el manejo de campos de formulario de tipo usuarios
*/
class RMFormUser extends RMFormElement
{
	private $selected = array();
	private $limit = 100;
	private $multi = false;
	private $width = 600;
	private $height = 500;
	private $showall = 0;
	
	// Eventos
	private $_onchange = '';
	
	/**
	* @param string Texto del campo
	* @param string Nombre del Campo
	* @param bool Seleccion múltiple
	* @param array Valores seleccionados por defecto
	* @param int Limite de resultados por página de usuarios
	* @param int Ancho de la ventana
	* @param int Alto de la ventana
	*/
	public function __construct($caption, $name, $multi = false, $select=array(), $limit=36, $width=600,$height=300, $showall = 0){
		$this->selected = is_array($select) ? $select : array($select);
		$this->limit = $limit;
		$this->multi = $multi;
		$this->setCaption($caption);
		$this->setName($name);
		$this->width = $width<=0 ? 600 : $width;
		$this->height = $height<=0 ? 500 : $height;
		$this->showall = $showall;
		!defined('RM_FRAME_USERS_CREATED') ? define('RM_FRAME_USERS_CREATED', 1) : '';
	}
	
	/**
	* @desc Crea un manejador para el evento onchange
	*/
	public function onChange($action){
		$this->_onchange = base64_encode(addslashes($action));
	}
	
	/**
	* Show the Users field
	* This field needs that form.css, jquery.css and forms.js would be included.
	*/
	public function render(){
		
		RMTemplate::get()->add_script(RMCURL.'/include/js/forms.js');
		RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js');
		RMTemplate::get()->add_style('forms.css','rmcommon');
		
		$rtn = "<div id='".$this->getName()."-users-container'".($this->getExtra()!='' ? " ".$this->getExtra() : '')." class='form_users_container'>
				<ul id='".$this->getName()."-users-list'>";
		$db = Database::getInstance();
		
		if ($this->showall && in_array(0, $this->selected)){
			$rtn .= "<li id='".$this->getName()."-exmuser-0'>\n
                        <label><input type='".($this->multi ? 'checkbox' : 'radio')."' name='".($this->multi ? $this->getName().'[]' : $this->getName())."' id='".$this->getName()."-0'
				 		value='0' checked='checked' /> ".__('All Users','rmcommon')."
                        <a href='javascript:;' onclick=\"users_field_name='".$this->getName()."'; usersField.remove(0);\"><span>delete</span></a>
                        </label></li>";
		}
		
		if (is_array($this->selected) && !empty($this->selected) && !(count($this->selected)==1 && $this->selected[0]==0)){
			$sql = "SELECT uid,uname FROM ".$db->prefix("users")." WHERE ";
			$sql1 = '';
			if ($this->multi){
				foreach ($this->selected as $id){
					if ($id!=0) $sql1 .= $sql1 == '' ? "uid='$id'" : " OR uid='$id'";
				}
			} else {
				if ($this->selected[0]!=0) $sql1 = "uid='".$this->selected[0]."'";
			}
			$result = $db->query($sql.$sql1);
			$selected = '';
			while ($row = $db->fetchArray($result)){
				$rtn .= "<li id='".$this->getName()."-exmuser-$row[uid]'>\n
						<label style='overflow: hidden;'>
                        <input type='".($this->multi ? 'checkbox' : 'radio')."' name='".($this->multi ? $this->getName().'[]' : $this->getName())."' id='".$this->getName()."-".$row['uid']."'
				 		value='$row[uid]' checked='checked' /> 
                        $row[uname] <a href='javascript:;' onclick=\"users_field_name='".$this->getName()."'; usersField.remove($row[uid]);\"><span>delete</span></a>
                        </label></li>";
			}
		}
		
		$rtn .= "</ul></div><br />
				<input type='button' value='".__('Search Users','rmcommon')."' onclick=\"usersField.form_search_users('".$this->getName()."',".$this->width.",".$this->height.",".$this->limit.",".intval($this->multi).",'".XOOPS_URL."');\" />
				<div id='".$this->getName()."-dialog-search' title='".__('Search Users','rmcommon')."' style='display: none;'>
				
				</div>";
		
		return $rtn;
	}
}

class RMFormFormUserSelect extends RMFormElement
{
    private $selected = array();
    private $limit = 100;
    private $width = 600;
    private $height = 300;
    private $showall = 0;
    
    // Eventos
    private $_onchange = '';
    
    /**
    * @param string Texto del campo
    * @param string Nombre del Campo
    * @param bool Seleccion múltiple
    * @param array Valores seleccionados por defecto
    * @param int Limite de resultados por página de usuarios
    * @param int Ancho de la ventana
    * @param int Alto de la ventana
    */
    public function __construct($caption, $name, $select=array(), $limit=36, $width=600,$height=300, $showall = 0){
        $this->selected = $select;
        $this->limit = $limit;
        $this->setCaption($caption);
        $this->setName($name);
        $this->width = $width<=0 ? 600 : $width;
        $this->height = $height<=0 ? 300 : $height;
        $this->showall = $showall;
        !defined('RM_FRAME_USERS_CREATED') ? define('RM_FRAME_USERS_CREATED', 1) : '';
    }
    
    public function render(){
        
    }
}
