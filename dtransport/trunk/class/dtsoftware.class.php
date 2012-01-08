<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DTSoftware extends RMObject
{
	private $_tags=array();
	private $_groups=array();
	private $limit=0;
	private $mode=0;
	private $_alert=null;
	private $_licences=array();
	private $_platforms=array();
	private $_screens = array();
	private $_features = array();
	private $_file = null;
	private $_logs = array();
	private $_fields = array();

	function __construct($id=null){
		
		$this->db =& Database::getInstance();
		
		$this->_dbtable = $this->db->prefix("dtrans_software");
		$this->setNew();
		$this->initVarsFromTable();

		$this->setVarType('groups', XOBJ_DTYPE_ARRAY);
		
		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_soft";
		}	
		
	}


	public function id(){
		return $this->getVar('id_soft');
	}


	/**
	* @desc Nombre del elemento
	**/	
	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}
	
	/**
	* @desc Obtiene la versión del elemento
	*/
	public function version(){
		return $this->getVar('version');
	}
	public function setVersion($version){
		return $this->setVar('version', $version);
	}

	/**
	* @desc Descripción corta del elemento
	**/
	public function shortdesc($format='s'){
		return $this->getVar('shortdesc', $format);
	}

	public function setShortDesc($shortdesc){
		return $this->setVar('shortdesc',$shortdesc);
	}

	public function desc($format = 's'){
		return $this->getVar('desc', $format);
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}

	/**
	* @desc Opcional Nombre del archivo de imagen del elemento
	**/
	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}

	/**
	* @desc Limite de descargas por usuario. = significa ilimitado
	**/
	public function limits(){
		return $this->getVar('limits');
	}

	public function setLimits($limits){
		return $this->setVar('limits',$limits);
	}

	/**
	* @desc Fecha de creacion
	**/
	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($created){
		return $this->setVar('created',$created);
	}
	
	/**
	* @desc Fecha de modificación
	**/
	public function modified(){
		return $this->getVar('modified');
	}

	public function setModified($modified){
		return $this->setVar('modified',$modified);
	}

	/**
	* @desc Id de usuario que crea el archivo
	**/
	public function uid(){
		return $this->getVar('uid');
	}


	public function setUid($uid){
		return $this->setVar('uid',$uid);
	}

	/**
	* @desc Nombre de usuario que crea el archivo
	**/
	public function uname(){
		return $this->getVar('uname');
	}

	public function setUname($uname){
		return $this->setVar('uname',$uname);
	}

	/**
	* @desc Establece si se trata de una descarga segura(1)
	* o de un archivo de libre acceso(2)
	**/
	public function secure(){
		return $this->getVar('secure');
	}

	public function setSecure($secure){
		return $this->setVar('secure',$secure);
	}

	/**
	* @desc Grupos con permiso de acceso al elemento
	**/
	public function groups(){
		return $this->getVar('groups');
	}

	public function setGroups($groups){
		return $this->setVar('groups',$groups);
	}
	public function canDownload($gid){
		
		if (!is_array($gid) && $gid==XOOPS_GROUP_ADMIN) return true;
		
		$gid = !is_array($gid) ? array($gid) : $gid;
		
		if (in_array(0, $gid)) return true;
		if (in_array(XOOPS_GROUP_ADMIN, $gid)) return true;
		
		foreach ($gid as $g){
			if (in_array($g, $this->groups())) return true;
		}
		
		return false;
		
	}
	
	/**
	* @desc Numero de comentarios
	*/
	public function comments(){
		return $this->getVar('comments');
	}
	public function setComments($value){
		return $this->setVar('comments', $value);
	}
	public function addComment(){
		$sql = "UPDATE ".$this->db->prefix("dtrans_software")." SET comments=comments+1 WHERE id_soft='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		} else {
			return true;
		}
	}
	
	/**
	* @desc Autor
	*/
	public function author(){
		return $this->getVar('author');
	}
	public function setAuthor($value){
		return $this->setVar('author', $value);
	}
	public function url(){
		return $this->getVar('url');
	}
	public function setUrl($value){
		return $this->setVar('url', $value);
	}
	/**
	* @desc Lenguajes
	*/
	public function langs(){
		return $this->getVar('langs');
	}
	public function setLangs($value){
		return $this->setVar('langs', $value);
	}

	/**
	* @desc Número de veces que se descarga un archivo
	**/
	public function hits(){
		return $this->getVar('hits');
	}
	public function setHits($hits){
		return $this->setVar('hits',$hits);
	}
	public function addHit(){
		$sql = "UPDATE ".$this->db->prefix("dtrans_software")." SET hits=hits+1 WHERE id_soft='".$this->id()."'";
		return $this->db->queryF($sql);
	}

	/**
	* @desc Número de votos que ha recibido un elemento
	**/
	public function votes(){
		return $this->getVar('votes');
	}

	public function setVotes($votes){

		return $this->setVar('votes',$votes);
	}

	/**
	* @desc Suma de las calificaciones de los votos
	* que ha recibido un elemento
	**/
	public function rating(){
		return $this->getVar('rating');
	}

	public function setRating($rating){
		return $this->setVar('rating',$rating);
	}
	
	/**
	* @desc Devuelve el rating del sitio
	*/
	public function siteRating(){
		return $this->getVar('siterate');
	}
	public function setSiteRating($value){
		$this->setVar('site_rate', $value);
	}
	
	/**
	* @desc Elemento aprobado
	**/
	public function approved(){
		return $this->getVar('approved');
	}

	public function setApproved($approved){
		return $this->setVar('approved',$approved);
	}

	public function nameId(){
		return $this->getVar('nameid');
	}

	/**
	* @desc Nombre corto del elemento
	**/
	public function setNameId($nameid){
		return $this->setVar('nameid',$nameid);
	}

	
	/**
	* @desc Descarga destacada
	**/
	public function mark(){
		return $this->getVar('mark');
	}

	public function setMark($mark){
		return $this->setVar('mark',$mark);
	}
	
	
	/**
	* @desc Descarga diaria
	**/
	public function daily(){
		return $this->getVar('daily');
	}

	public function setDaily($daily){
		return $this->setVar('daily',$daily);
	}

	
	/**
	*@desc Categoria del elemento
	**/	
	public function category(){
		return $this->getVar('id_cat');
	}

	public function setCategory($cat){
		return $this->setVar('id_cat',$cat);
	}

	/**
	* @desc 
	**/
	public function rate(){
		return $this->getVar('siterate');
	}

	public function setRate($rate){
		return $this->setVar('siterate',$rate);
	}


	public function addVote($rate){
		if ($this->isNew()) return;
		return $this->db->queryF("UPDATE ".$this->db->prefix("dtrans_software")." SET `votes`=`votes`+1, `rating`='".($this->rating()+$rate)."' WHERE id_soft='".$this->id()."'");
		$this->setRating($this->rating()+$rate);
	}




	
	/**
	* @desc Devuelve el número de Pantallas
	*/
	public function screensCount(){
		return $this->getVar('screens');
	}
	public function setScreensCount($value){
		return $this->setVar('screens', $value);
	}
	public function incrementScreens(){
		$sql = "UPDATE ".$this->_dbtable." SET screens=screens+1 WHERE id_soft='".$this->id()."'";
		return $this->db->queryF($sql);
	}
	public function decrementScreens(){
		$sql = "UPDATE ".$this->_dbtable." SET screens=screens-1 WHERE id_soft='".$this->id()."'";
		return $this->db->queryF($sql);
	}

	/**
	* @desc Obtiene las etiquestas a las que pertenece el software
	* @param bool True devuelve como objetos
	* @return array
	**/
	public function tags($asobj = false){
		
		$tbl1 = $this->db->prefix("dtrans_softtag");
		$tbl2 = $this->db->prefix("dtrans_tags");
		
		if (empty($this->_tags) || ($asobj && !is_a($this->_tags[0], 'DTTag'))){
			$this->_tags = array();
			$sql="SELECT b.* FROM $tbl1 a, $tbl2 b WHERE a.id_soft='".$this->id()."' AND b.id_tag=a.id_tag";
			$result=$this->db->queryF($sql);
			while ($rows=$this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTTag();
					$tmp->assignVars($rows);
				} else {
					$tmp = $rows['id_tag'];
				}
				
				$this->_tags[]=$tmp;
			}
		}
	
		return $this->_tags;

	}	
	

	public function setTags($tags){
		$this->tags=$tags;
	}
	
	/**
	* @desc Obtenemos el archivo por defecto
	*/
	public function file(){
	
		if (!$this->_file){
			$sql = "SELECT * FROM ".$this->db->prefix("dtrans_files")." WHERE id_soft='".$this->id()."' AND `default`='1'";
			$result = $this->db->query($sql);
			if ($this->db->getRowsNum($result)>0){
				$row = $this->db->fetchArray($result);
				$this->_file = new DTFile();
				$this->_file->assignVars($row);
			} else {
				$sql = "SELECT * FROM ".$this->db->prefix("dtrans_files")." WHERE id_soft='".$this->id()."' ORDER BY id_file LIMIT 0,1";
				$result = $this->db->query($sql);
				if ($this->db->getRowsNum($result)<=0) return;
				$row = $this->db->fetchArray($result);
				$this->_file = new DTFile();
				$this->_file->assignVars($row);
			}
		}
		
		return $this->_file;
	}

	/**
	* @desc Grupos de archivos que pertencen a este elemento
	* @param bool True devuelve objetos {@link DTFileGroup()}
	* @return array();
	**/	
	public function fileGroups($asobj = false){
		
		if (empty($this->_groups) || ($asobj && !is_a($this->_groups[0], 'DTFileGroup'))){
			$this->_groups = array();
			$sql="SELECT * FROM ".$this->db->prefix('dtrans_groups')." WHERE id_soft=".$this->id()." ORDER id_group DESC";
			$result=$this->db->queryF($sql);
			while ($rows=$this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTFileGroup();
					$tmp->assignVars($rows);
				} else {
					$tmp = $rows['id_group'];
				}
				$this->_groups[]=$tmp;
			}
		}
	
		return $this->_groups;

	}

	/**
	* @desc Obtiene la alerta correspondiente del software
	* @return object
	**/
	public function alert(){
		if (!is_a($this->_alert, 'DTAlert')){
			$sql="SELECT * FROM ".$this->db->prefix('dtrans_alerts')." WHERE id_soft=".$this->id();
			$result=$this->db->queryF($sql);
			if ($this->db->getRowsNum($result)<=0) return;
			$this->_alert = new DTAlert();
			$this->_alert->assignVars($this->db->fetchArray($result));
		}
		
		return $this->_alert;
	}


	/**
	* @desc Obtiene valor para determinar si se crea la alerta para el software
	**/
	public function createAlert($alert){
		$this->_alert = new DTAlert($this->id());
	}
	

	/**
	* @desc Obtiene el limite de dias de la alerta
	* @param int $limit
	**/
	public function setLimit($limit){
		if (!$this->_alert) return false;
		$this->_alert->setLimit($limit);
	}

	/**
	* @desc Obtiene el modo de envio de la alerta
	* @param int $mode 
	**/
	public function setMode($mode){
		if (!$this->_alert) return false;
		$this->_alert->setMode($mode);
	}


	/**
	* @desc Establece las licencias del elemento
	* @param array $licences Arreglo de ids de licencias
	**/
	public function setLicences($licences){
		$this->licences=$licences;
		
	}
	/**
	* @desc Obtiene las licencias del elemento
	* @param bool True devuelve objetos {@link DTLicense()}
	* @return array
	*/
	public function licences($asobj = false){
		
		$tbl1 = $this->db->prefix("dtrans_licsoft");
		$tbl2 = $this->db->prefix("dtrans_licences");
		
		if (empty($this->_licences) || ($asobj && !is_a($this->_licences[0], 'DTLicense'))){
			$this->_licences = array();
			$sql="SELECT b.* FROM $tbl1 a, $tbl2 b  WHERE a.id_soft='".$this->id()."' AND b.id_lic=a.id_lic";
			$result=$this->db->queryF($sql);
			while ($rows=$this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTLicense();
					$tmp->assignVars($rows);
				} else {
					$tmp = $rows['id_lic'];
				}
				$this->_licences[]=$tmp;
			}
		}

		return $this->_licences;

	}


	/**
	* @des Establece las plataformas del software
	**/
	public function setPlatforms($platforms){

		$this->_platforms=$platforms;
		
	}
 	/**
 	* @desc Obtiene las plataformas a las que pertenece el elemento
 	* @param bool True obtiene los objetos {@link DTPlatform}
 	* @return array
 	*/
	public function platforms($asobj = false){
		
		$tbl1 = $this->db->prefix("dtrans_platsoft");
		$tbl2 = $this->db->prefix("dtrans_platforms");
		
		if (empty($this->_platforms) || ($asobj && !is_a($this->_platforms, 'DTPlatform'))){
			$this->_platforms = array();
			$sql="SELECT b.* FROM $tbl1 a, $tbl2 b WHERE a.id_soft='".$this->id()."' AND b.id_platform=a.id_platform";
			$result=$this->db->queryF($sql);
			while ($rows=$this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTPlatform();
					$tmp->assignVars($rows);
				} else {
					$tmp = $rows['id_platform'];
				}
				$this->_platforms[]=$tmp;
			}
		}
		
		return $this->_platforms;

	}
	
	/**
	* @desc Obtiene las pantallas del elemento
	* @param bool True devuelve objetos {@link DTFeature}
	* @return array
	*/
	function features($asobj = false){
		
		if (empty($this->_features) || ($asobj && !is_a($this->_features[0], 'DTFeature'))){
			$this->_features = array();
			$sql = "SELECT * FROM ".$this->db->prefix("dtrans_features")." WHERE id_soft='".$this->id()."' ORDER BY modified DESC";
			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTFeature();
					$tmp->assignVars($row);
				} else {
					$tmp = $row['id_feat'];
				}
				$this->_features[] = $tmp;
			}
		}
		
		return $this->_features;
		
	}
	
	/**
	* @desc Obtiene los logs del elemento
	* @param bool True devuelve objetos {@link DTLog}
	* @return array
	*/
	function logs($asobj = false){
		
		if (empty($this->_logs) || ($asobj && !is_a($this->_logs[0], 'DTLog'))){
			$this->_logs = array();
			$sql = "SELECT * FROM ".$this->db->prefix("dtrans_logs")." WHERE id_soft='".$this->id()."' ORDER BY date DESC";
			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTLog();
					$tmp->assignVars($row);
				} else {
					$tmp = $row['id_log'];
				}
				$this->_logs[] = $tmp;
			}
		}
		
		return $this->_logs;
		
	}
	
	/**
	* @desc Obtiene las caracteristicas del elemento
	* @param bool True devuelve objetos {@link STScreenshot}
	* @return array
	*/
	function screens($asobj = false){
		
		if (empty($this->_screens) || ($asobj && !is_a($this->_screens[0], 'DTScreenshot'))){
			$this->_screens = array();
			$sql = "SELECT * FROM ".$this->db->prefix("dtrans_screens")." WHERE id_soft='".$this->id()."' ORDER BY modified DESC";
			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)){
				if ($asobj){
					$tmp = new DTScreenshot();
					$tmp->assignVars($row);
				} else {
					$tmp = $row['id_screen'];
				}
				$this->_screens[] = $tmp;
			}
		}
		
		return $this->_screens;
		
	}
	
	/**
	* @desc Determina el número de descargas realizadas para un archivo
	*/
	public function downloadsCount($id_or_ip = ''){
		
		global $xoopsUser;
		$id = $id_or_ip;
		
		if ($id==''){
			$id = $xoopsUser ? $xoopsUser->uid() :  $_SERVER['REMOTE_ADDR'];
		}
		
		if (is_int($id)){
			$sql = "SELECT COUNT(*) FROM ".$this->db->prefix("dtrans_downs")." WHERE uid='".$id."' AND id_soft='".$this->id()."'";
		} else {
			$sql = "SELECT COUNT(*) FROM ".$this->db->prefix("dtrans_downs")." WHERE ip='".$id."' AND id_soft='".$this->id()."'";
		}
		$result = $this->db->query($sql);
		list($num) = $this->db->fetchRow($result);
		return $num;
		
	}


	/**
	* @desc Determina si usuario tiene permiso para enviar descargas
	* @param int array $gid  Ids de grupos a que pertenece usuario
	* @param int array $groups Ids de grupos con permiso a de enviar descargas
	**/	
	public function isAllowedDowns($gid,$groups){
		if (!is_array($gid)){
			if ($gid == XOOPS_GROUP_ADMIN) return true;
			return in_array($gid, $groups);
		}

		if (in_array(XOOPS_GROUP_ADMIN,$gid)) return true;
				
		foreach ($gid as $k){

			if (in_array($k, $groups)) return true;
		}
		
		return false;

	}




	/**
	* @desc Almacena las etiquetas del elemento
	*/
	private function saveTags(){
		$sql ="DELETE FROM ".$this->db->prefix('dtrans_softtag')." WHERE id_soft=".$this->id();
		$this->db->queryF($sql);
		
		$sql = "INSERT INTO ".$this->db->prefix('dtrans_softtag')." (`id_soft`,`id_tag`) VALUES ";
		$sql1='';	
		foreach ($this->tags as $k){
			$sql1.= $sql1=="" ? "('".$this->id()."','$k')" : ",('".$this->ID()."','$k')";
		}
				
	
		if ($this->db->queryF($sql.$sql1)){
			return true;
		}
		
		$this->addError($this->db->error());
		return false;
		
	}
	
	/**
	* @desc Almacena la alerta del elemento si existe
	*/
	private function saveAlert(){
		
		if (!$this->_alert) return;
		
		if (!$this->_alert->save()){
			$this->addError($this->_alert->errors());
			return false;
		}
		
		return true;
		
	}
	
	/**
	* @desc Almacena las licencias del elemento
	*/
	private function saveLics(){
		$sql ="DELETE FROM ".$this->db->prefix('dtrans_licsoft')." WHERE id_soft=".$this->id();
		$this->db->queryF($sql);
		
		$sql = "INSERT INTO ".$this->db->prefix('dtrans_licsoft')." (`id_soft`,`id_lic`) VALUES ";
		$sql1='';	
		foreach ($this->licences as $k){
			$sql1.= $sql1=="" ? "('".$this->id()."','$k')" : ",('".$this->id()."','$k')";
		}
		
		if (!$this->db->queryF($sql.$sql1)){
			$this->addError($this->db->error());
			return false;
		}
		
		return true;
		
	}
	
	/**
	* @desc Almacena las plataformas del elemento
	*/
	private function savePlatforms(){
		
		if (empty($this->_platforms)) return;
		$sql ="DELETE FROM ".$this->db->prefix('dtrans_platsoft')." WHERE id_soft=".$this->id();
		$this->db->queryF($sql);
		
		$sql = "INSERT INTO ".$this->db->prefix('dtrans_platsoft')." (`id_soft`,`id_platform`) VALUES ";
		$sql1='';	
		foreach ($this->_platforms as $k){
			$sql1.= $sql1=="" ? "('".$this->id()."','$k')" : ",('".$this->id()."','$k')";
		}
				
		if (!$this->db->queryF($sql.$sql1)){
			$this->addError($this->db->error());
			return false;
		}
		
		return true;
	}

	
	/**
	* @desc Almacena los datos del elemento
	* @param bool Guardar etiquetas
	* @param bool Guardar alerta
	* @param bool Guardar Licencias
	* @param bool Guardar Plataformas
	*/
	public function save($tags = false, $alert = false, $lics = false, $os = false){

		$ret = false;

		if ($this->isNew()){
			$ret= $this->saveToTable();
		}
		else{
			$ret= $this->updateTable();
		}		
		
		if (!$ret) return false;
		
		
		// Etiquetas
		if ($tags) $this->saveTags();
		// ALerta
		if ($alert){
			$this->_alert->setSoftware($this->id());
			if (!$this->saveAlert()){
				$this->addError($this->errors());
			}
		}
		// Licnecias
		if ($lics) $this->saveLics();
		// Plataformas
		if ($os) $this->savePlatforms();
		
				
		if ($this->errors()!='') return false;
		return true;
	}

	public function delete(){
		
		//Eliminamos las relaciones con etiquetas
		$sql="DELETE FROM ".$this->db->prefix('dtrans_softtag')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);
		
		if (!$result){
			return false;
		}
		
		//Eliminar caracteristicas
		$sql="DELETE FROM ".$this->db->prefix('dtrans_features')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);

		if (!$result){
			return false;
		}

		//Eliminar pantallas
		$sql="SELECT * FROM ".$this->db->prefix('dtrans_screens')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);
		while ($rows=$this->db->fetchArray($result)){
			$sc=new DTScreenshot();
			$sc->assignVars($rows);			
			$sc->delete();
		}
			
		//Eliminar Archivos
		$sql="SELECT * FROM ".$this->db->prefix('dtrans_files')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);
		while ($rows=$this->db->fetchArray($result)){
			$file=new DTFile();
			$file->assignVars($rows);			
			$file->delete();
		}

		//Eliminar Logs
		$sql="DELETE FROM ".$this->db->prefix('dtrans_logs')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);

		if (!$result){
			return false;
		}

		//Eliminar grupo
		$sql="DELETE FROM ".$this->db->prefix('dtrans_groups')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);

		if (!$result){
			return false;
		}		


		//Eliminar relación de licencias
		$sql="DELETE FROM ".$this->db->prefix('dtrans_licsoft')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);
		
		if (!$result){
			return false;
		}

		//Eliminar relación de plataformas
		$sql="DELETE FROM ".$this->db->prefix('dtrans_platsoft')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);
		
		if (!$result){
			return false;
		}

		//Eliminar alerta
		$sql="DELETE FROM ".$this->db->prefix('dtrans_alerts')." WHERE id_soft=".$this->id();
		$result = $this->db->queryF($sql);
		
		if (!$result){
			return false;
		}
		
		
		@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$this->image());
		@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$this->image());

		return $this->deleteFromTable();
	}


}
