<?php
// $Id$
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
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
// @author: Gina
// @copyright: 2007 - 2008 Red México


class BBReport extends EXMObject
{

	public function __construct($id=null){
        	$this->db =& Database::getInstance();
        	$this->_dbtable = $this->db->prefix("exmbb_report");
        	$this->setNew();
        	$this->initVarsFromTable();
        
        
        	if (!isset($id)) return;
        	/**
        	 * Cargamos los datos del reporte
        	 */
      		if (is_numeric($id)){
		        if (!$this->loadValues($id)) return;     
			$this->unsetNew();
        	} 
	}


	/**
	* @desc Metodos para acceso a las propiedades
	*/
   
	public function id(){
		return $this->getVar('report_id');
	}

	//Id del mensaje
	public function post(){
		return $this->getVar('post_id');
	}
 
	public function setPost($post){
		return $this->setVar('post_id',$post);
	}

	//Nombre del usuario del reporte
	public function user(){
		return $this->getVar('reporter_uid');
	}

	public function setUser($user){
		return $this->setVar('reporter_uid',$user);
	}
	
	//Ip de usuario
	public function ip(){
		return $this->getVar('reporter_ip');
	}

	public function setIp($ip){
		return $this->setVar('reporter_ip',$ip);
	}

	//Fecha de creación del reporte
	public function time(){
		return $this->getVar('report_time');
	}

	public function setTime($time){
		return $this->setVar('report_time',$time);
	}


	//Texto del reporte
	public function report(){
		return $this->getVar('report_text');
	}
	
	public function setReport($report){
		return $this->setVar('report_text',$report);
	}

	//Id de usuario que revisa el reporte
	public function zappedBy(){
		return $this->getVar('zappedby');
	}

	public function setZappedBy($zappedby){
		return $this->setVar('zappedby',$zappedby);
	}

	//Nombre de usuario que revisa el reporte
	public function zappedName(){
		return $this->getVar('zappedname');
	}	

	public function setZappedName($zappedname){
		return $this->setVar('zappedname',$zappedname);
	}

	//Fecha de revisión del reporte
	public function zappedTime(){
		return $this->getVar('zappedtime');
	}

	public function setZappedTime($zappedtime){
		return $this->setVar('zappedtime',$zappedtime);
	}
	
	//Indica reporte revisado
	public function zapped(){
		return $this->getVar('zapped');
	}

	public function setZapped($zapped){
		return $this->setVar('zapped',$zapped);
	}

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}else{
			return $this->UpdateTable();
		}
	}

	public function delete(){
		return $this->deleteFromTable();
	}
}

?>