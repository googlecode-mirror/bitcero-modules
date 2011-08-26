<?php
// $Id: bbpost.class.php 61 2008-03-15 19:46:48Z ginis $
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
// @copyright: 2007 - 2008 Red México

/**
* @desc Clase para el manejo de mensajes
*/
class BBPost extends EXMObject
{
	private $havetext = false;
	private $numposts = 0;
		
	public function __construct($id = null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("exmbb_posts");
        $this->setNew();
        $this->initVarsFromTable();
        $this->initVar('post_text', XOBJ_DTYPE_TXTAREA);
        $this->initVar('post_edit', XOBJ_DTYPE_TXTAREA);
        
        if (!isset($id)) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();
        
        // Cargamos el texto
        $sql = "SELECT * FROM ".$this->db->prefix("exmbb_posts_text")." WHERE post_id='".$this->id()."'";
        $result = $this->db->queryF($sql);
        if ($this->db->getRowsNum($result)<=0) return;
        
        $this->havetext = true;
        $row = $this->db->fetchArray($result);
        $this->setVar('post_text', $row['post_text']);
        $this->setVar('post_edit', $row['post_edit']);
        
	}
	
	public function id(){
		return $this->getVar('id_post');
	}
	
	public function pid(){
		return $this->getVar('pid');
	}
	public function setPid($value){
		return $this->setVar('pid', intval($value));
	}
	
	public function topic(){
		return $this->getVar('id_topic');
	}
	public function setTopic($value){
		return $this->setVar('id_topic', intval($value));
	}
	
	public function forum(){
		return $this->getVar('id_forum');
	}
	public function setForum($value){
		return $this->setVar('id_forum', intval($value));
	}
	
	public function date(){
		return $this->getVar('post_time');
	}
	public function setDate($value){
		return $this->setVar('post_time', intval($value));
	}
	
	public function user(){
		return $this->getVar('uid');
	}
	public function setUser($value){
		return $this->setVar('uid', intval($value));
	}
	
	public function uname(){
		return $this->getVar('poster_name');
	}
	public function setUname($value){
		return $this->setVar('poster_name', $value);
	}
	
	public function ip(){
		return $this->getVar('poster_ip');
	}
	public function setIP($value){
		return $this->setVar('poster_ip', $value);
	}
	
	public function html(){
		return $this->getVar('dohtml');
	}
	public function setHTML($value){
		return $this->setVar('dohtml', intval($value));
	}
	
	public function smiley(){
		return $this->getVar('dosmiley');
	}
	public function setSmiley($value){
		return $this->setVar('dosmiley', intval($value));
	}
	
	public function bbcode(){
		return $this->getVar('doxcode');
	}
	public function setBBCode($value){
		return $this->setVar('doxcode', intval($value));
	}
	
	public function br(){
		return $this->getVar('dobr');
	}
	public function setBR($value){
		return $this->setVar('dobr', intval($value));
	}
	
	public function image(){
		return $this->getVar('doimage');
	}
	public function setImage($value){
		return $this->setVar('doimage', intval($value));
	}
	
	public function icon(){
		return $this->getVar('icon');
	}
	public function setIcon($value){
		return $this->setVar('icon', $value);
	}
	
	public function signature(){
		return $this->getVar('attachsig');
	}
	public function setSignature($value){
		return $this->setVar('attachsig', intval($value));
	}
	
	public function approved(){
		return $this->getVar('approved');
	}
	public function setApproved($value){
		return $this->setVar('approved', $value);
	}
	
	public function text(){
		if (!$this->havetext && $this->getVar('post_text','n')==''){
			// Cargamos el texto
	        $sql = "SELECT * FROM ".$this->db->prefix("exmbb_posts_text")." WHERE post_id='".$this->id()."'";
	        $result = $this->db->queryF($sql);
	        if ($this->db->getRowsNum($result)<=0) return;
	        
	        $this->havetext = true;
	        $row = $this->db->fetchArray($result);
	        $this->setVar('post_text', $row['post_text']);
	        $this->setVar('post_edit', $row['post_edit']);
		}
		return $this->getVar('post_text');
	}
	public function setText($value){
		$this->havetext = true;
		return $this->setVar('post_text', $value);
	}
	
	public function editText($format='s'){
		return $this->getVar('post_edit', $format);
	}
	public function setEditText($value){
		return $this->setVar('post_edit', $value);
	}
	
	public function totalAttachments(){
		if ($this->numposts==0){
			$result = $this->db->query("SELECT COUNT(*) FROM ".$this->db->prefix("exmbb_attachments")." WHERE post_id='".$this->id()."'");
			list($num) = $this->db->fetchRow($result);
			$this->numposts = $num;
		}
		return $this->numposts;
	}
	
	public function isOwner(){
		global $xoopsUser;
		
		if (!$xoopsUser) return false;
		
		return $xoopsUser->uid()==$this->user();
		
	}
	
	/**
	* @desc Obtiene los archivos adjuntos del mensaje
	* @param bool Devolver objetos {@link BBAttachment}
	* @param bool Indices del array equivalen al id del objeto
	* @return array
	*/
	function attachments($object = true, $id_as_keys = false){
		
		$result = $this->db->query("SELECT * FROM ".$this->db->prefix("exmbb_attachments")." WHERE post_id='".$this->id()."'");
		$ret = array();
		while ($row = $this->db->fetchArray($result)){
			if ($object){
				$attach = new BBAttachment();
				$attach->assignVars($row);
				if ($id_as_keys){
					$ret[$row['attach_id']] = $attach;
				} else {
					$ret[] = $attach;
				}
			} else {
				if ($id_as_keys){
					$ret[$row['attach_id']] = $row;
				} else {
					$ret[] = $row;
				}
			}
		}
		
		return $ret;
		
	}
	
	public function save(){
	
		$myts = MyTextSanitizer::getInstance();
		
		if ($this->isNew()){
			if (!$this->saveToTable()) return false;
			// Guardamos el texto
			$sql = "INSERT INTO ".$this->db->prefix("exmbb_posts_text")." (`post_id`,`post_text`,`post_edit`)
					VALUES ('".$this->id()."','".$myts->addSlashes($this->getVar('post_text','n'))."','".$myts->addSlashes($this->getVar('post_edit','n'))."')";
			
			if (!$this->db->queryF($sql)){
				$this->addError($this->db->error());
				return false;
			} else {
				return true;
			}
			
		} else {
			if (!$this->updateTable())  return false;
			
			if ($this->havetext){
				$sql = "UPDATE ".$this->db->prefix("exmbb_posts_text")." SET 
						`post_text`='".$myts->addSlashes($this->getVar('post_text','n'))."',`post_edit`='".$myts->addSlashes($this->getVar('post_edit','n'))."' WHERE
						post_id='".$this->id()."'";
			} else {
				$sql = "INSERT INTO ".$this->db->prefix("exmbb_posts_text")." (`post_id`,`post_text`,`post_edit`)
					VALUES ('".$this->id()."','".$this->getVar('post_text')."','".$this->getVar('post_edit')."')";
			}
			
			if (!$this->db->queryF($sql)){
				$this->addError($this->db->error());
				return false;
			} else {
				return true;
			}			
			
		}
	}
	
	/**
	* @desc Eliminar un post
	*/
	public function delete(){
		$tbl1 = $this->db->prefix("exmbb_posts_text");
		$tbl2 = $this->db->prefix("exmbb_attachments");
		$sql = "DELETE FROM $tbl1 WHERE post_id='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		}
		
		// Eliminamos attachments
		foreach ($this->attachments() as $file){
			$file->delete();
		}
		
		// Restamos uno al número de mensajes en temas y foros
		$sql = "UPDATE ".$this->db->prefix("exmbb_forums")." SET `posts`=`posts`-1 WHERE id_forum='".$this->forum()."'";
	    	$this->db->queryF($sql);
	    	$sql = "UPDATE ".$this->db->prefix("exmbb_topics")." SET `replies`=`replies`-1 WHERE id_topic='".$this->topic()."'";
	    	$this->db->queryF($sql);
	
		return $this->deleteFromTable();
	}
	
}
?>