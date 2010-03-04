<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/rmcommon/form.class.php';
/**
* @desc Clase para el manejo de campos de formulario de tipo usuarios
*/
class RMFormUserGS extends RMFormElement
{
	private $selected = array();
	private $limit = 100;
	private $multi = false;
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
	public function __construct($caption, $name, $multi = false, $select=array(), $limit=100, $width=600,$height=300, $showall = 0){
		$this->selected = $select;
		$this->limit = $limit;
		$this->multi = $multi;
		$this->setCaption($caption);
		$this->setName($name);
		$this->width = $width<=0 ? 600 : $width;
		$this->height = $height<=0 ? 300 : $height;
		$this->showall = $showall;
		define('RM_FRAME_USERS_CREATED', 1);
	}
	
	/**
	* @desc Crea un manejador para el evento onchange
	*/
	public function onChange($action){
		$this->_onchange = base64_encode(addslashes($action));
	}
	
	/**
	* @desc Genera el código HTML para el campo
	*/
	public function render(){
		$rtn = "<script type='text/javascript' src='".XOOPS_URL."/modules/galleries/include/fieldusers.js'></script>";
		$rtn .= "<div id='rmformUsers_".$this->getName()."'".($this->getExtra()!='' ? " ".$this->getExtra() : '').">
				<ul id='rmformUsersList_".$this->getName()."' style='list-style: none; margin: 0; padding: 0; overflow: hidden;'>";
		$db =& Database::getInstance();
		
		if ($this->showall && in_array(0, $this->selected)){
			$rtn .= "<li>\n
						<label><input type='".($this->multi ? 'checkbox' : 'radio')."' name='".($this->multi ? $this->getName().'[]' : $this->getName())."' id='".($this->multi ? $this->getName().'[]' : $this->getName())."'
				 		value='0' checked='checked' /> "._RMS_CF_ALLUSERS."</label></li>";
		}
		
		if (is_array($this->selected) && !empty($this->selected) && !(count($this->selected)==1 && $this->selected[0]==0)){
			$sql = "SELECT uid,uname FROM ".$db->prefix("gs_users")." WHERE ";
			$sql1 = '';
			if ($this->multi){
				foreach ($this->selected as $id){
					if ($id!=0) $sql1 .= $sql1 == '' ? "uid='$id'" : " OR uid='$id'";
				}
			} else {
				if ($this->selected[0]!=0) $sql1 = "uid='".$this->selected[0]."'";
			}
			$result = $db->query($sql.$sql1);
			while ($row = $db->fetchArray($result)){
				$rtn .= "<li>\n
						<label><input type='".($this->multi ? 'checkbox' : 'radio')."' name='".($this->multi ? $this->getName().'[]' : $this->getName())."' id='".($this->multi ? $this->getName().'[]' : $this->getName())."'
				 		value='$row[uid]' checked='checked' /> $row[uname]</label></li>";
			}
		}
		
		$rtn .= "</ul></div><br />
				<a href='javascript:;' onclick=\"selectGSUsers('".$this->getForm()."', '".$this->getName().($this->multi ? "[]" : "")."',".$this->limit.", '".XOOPS_URL."',".$this->width.",".$this->height.",".($this->multi ? 1 : 0).",".($this->showall ? 1 : 0).",'$this->_onchange');\" style='background: url(".XOOPS_URL."/rmcommon/images/".($this->multi ? 'users.png' : 'user.png').") no-repeat left; padding: 3px 1px 3px 18px;'>".($this->multi ? _RMS_CF_SELUSERS : _RMS_CF_SELUSER)."</a>";
		return $rtn;
	}
}
