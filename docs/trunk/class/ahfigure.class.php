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


class AHFigure extends EXMObject{


	function __construct($id=null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pa_figures");
		$this->setNew();
		$this->initVarsFromTable();
        
        $this->setVarType('desc', XOBJ_DTYPE_TXTAREA);

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}	
	
	}


	public function id(){
		return $this->getVar('id_fig');
	}
	
	//Recurso al que pertenece la figura
	public function resource(){
		return $this->getVar('id_res');
	}	

	public function setResource($resource){
		return $this->setVar('id_res',$resource);
	}

	//Texto al que pertenece la figura
	public function section(){
		return $this->getVar('id_sec');
	}
	
	public function setSection($section){
		return $this->setVar('id_sec',$section);
	}
	
	public function _class(){
		return $this->getVar('class');
	}
	
	public function setClass($class){
		return $this->setVar('class',$class);
	}

	public function style(){
		return $this->getVar('style');
	}

	public function setStyle($style){
		return $this->setVar('style',$style);
	}

	public function desc(){
		return $this->getVar('desc');
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}

	public function figure(){
		return $this->getVar('text');
	}

	public function setFigure($figure){
		return $this->setVar('text',$figure);
	}


	//Habilitar código HTML
	public function html(){
		return $this->getVar('dohtml');
	}

	public function setHTML($value){
		return $this->setVar('dohtml',$value);
	}
	
	//Habilitar código XOOPS
	public function code(){
		return $this->getVar('doxcode');
	}

	public function setCode($value){
		return $this->setVar('doxcode',$value);
	}

	//Habilitar Saltos de Línea
	public function br(){
		return $this->getVar('dobr');
	}
	
	public function setBr($value){
		return $this->setVar('dobr',$value);
	}

	//Habilitar imágenes
	public function image(){
		return $this->getVar('doimage');
	}

	public function setImage($value){
		return $this->setVar('doimage',$value);
	}

	//Habilitar Emoticons
	public function smiley(){
		return $this->getVar('dosmiley');
	}


	public function setSmiley($value){
		return $this->setVar('dosmiley',$value);
	}



	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}else{
			return $this->updateTable();
		}
	}


	public function delete(){
		return $this->deleteFromTable();

	}
    
}

?>