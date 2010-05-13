<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// CopyRight  2007 - 2008. Red México
// http://www.redmexico.com.mx
// http://www.exmsystem.com
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @copyright:  2007 - 2008. Red México


class AHSection extends EXMObject{

	function __construct($id=null, $res=0){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pa_sections");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$sql = "SELECT * FROM ".$this->_dbtable." WHERE nameid='$id' AND id_res='$res'";
			$result = $this->db->query($sql);
			if ($this->db->getRowsNum($result)<=0) return;
			
			$row = $this->db->fetchArray($result);
			$this->assignVars($row);
			$this->unsetNew();
		}	
	
	}


	public function id(){
		return $this->getVar('id_sec');
	}

	//Título de la sección
	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	//Contenido de la sección
	public function content(){
		return $this->getVar('content');
	}

	public function setContent($value){
		return $this->setVar('content',$value);	
	}

	//Orden de la sección en la publicación
	public function order(){
		return $this->getVar('order');
	}

	public function setOrder($order){
		return $this->setVar('order',$order);
	}
	
	//Recurso al que pertenece la seccion
	public function resource(){
		return $this->getVar('id_res');
	}

	public function setResource($resource){
		return $this->setVar('id_res',$resource);
	}

	//Nombre idntificador	
	public function nameId(){
		return $this->getVar('nameid');
	}

	public function setNameId($name){
		return $this->setVar('nameid',$name);
	}

	//Secciń padre
	public function parent(){
		return $this->getVar('parent');
	}

	public function setParent($parent){
		return $this->setVar('parent',$parent);
	}
	
	// Usuario
	public function uid(){
		return $this->getVar('uid');
	}
	public function setUid($uid){
		return $this->setVar('uid', $uid);
	}
	
	public function uname(){
		return $this->getVar('uname');
	}
	public function setUname($uname){
		return $this->setVar('uname', $uname);
	}
	
	public function featured(){
		return $this->getVar('featured');
	}
	public function setFeatured($value){
		return $this->setVar('featured',$value);
	}
	
	// Modificación y Creación
	public function created(){
		return $this->getVar('created');
	}
	public function setCreated($value){
		return $this->setVar('created', $value);
	}
	public function modified(){
		return $this->getVar('modified');
	}
	public function setModified($value){
		return $this->setVar('modified', $value);
	}
	
	
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}
		else{
			return $this->updateTable();
		}		

	}

	public function delete(){
		$ret=false;
	
		//Cambia las secciones hijas a secciones principales
		$sql="UPDATE ".$this->db->prefix('pa_sections')." SET parent=0 WHERE parent='".$this->id()."'";
		$result=$this->db->queryF($sql);

		if (!$result) return $ret;		

		$ret=$this->deleteFromTable();
	
		return $ret;

		
	}


}
?>
