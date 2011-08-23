<?php
// $Id: bbtopic.class.php 61 2008-03-15 19:46:48Z ginis $
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
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México

/**
* @desc Clase para el manejo de temas en EXM BB
*/
class BBTopic extends EXMObject
{
    function __construct($id = null){
        
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("exmbb_topics");
        $this->setNew();
        $this->initVarsFromTable();
        
        if (!isset($id)) return;
        /**
         * Cargamos los datos del foro
         */
        if (is_numeric($id)){
            if (!$this->loadValues($id)) return;     
            $this->unsetNew();
        } else {
            $this->primary = 'friendname';
            if ($this->loadValues($id)) $this->unsetNew();
            $this->primary = 'id_topic';
        }
        
    }
    /**
    * Funciones para obtener los datos del Tema
    */
    public function id(){
        return $this->getVar('id_topic');
    }
    
    public function title(){
        return $this->getVar('title');
    }
    public function setTitle($value){
        return $this->setVar('title', $value);
    }
    
    public function poster(){
        return $this->getVar('poster');
    }
    public function setPoster($value){
        return $this->setVar('poster', $value);
    }
    
    public function date(){
        return $this->getVar('date');
    }
    public function setDate($value){
        return $this->setVar('date', $value);
    }
    
    public function views(){
        return $this->getVar('views');
    }
    public function setViews($value){
        return $this->setVar('views', $value);
    }
    public function addView(){
        $this->setViews($this->views()+1);
    }
    
    public function replies(){
        return $this->getVar('replies');
    }
    public function setReplies($value){
        return $this->setVar('replies', $value);
    }
    public function addReply(){
        $this->setReplies($this->replies() + 1);
    }
    
    public function lastPost(){
        return $this->getVar('last_post');
    }
    public function setLastPost($value){
        return $this->setVar('last_post', $value);
    }
	
    public function forum(){
        return $this->getVar('id_forum');
    }
    public function setForum($value){
        return $this->setVar('id_forum', $value);
    }
    
    public function status(){
        return $this->getVar('status');
    }
    public function setStatus($value){
        return $this->setVar('status', $value);
    }
    
    public function sticky(){
        return $this->getVar('sticky');
    }
    public function setSticky($value){
        return $this->setVar('sticky', $value);   
    }
    
    public function digest(){
        return $this->getVar('digest');
    }
    public function setDigest($value){
        return $this->setVar('digest', $value);
    }
    
    public function digestTime(){
        return $this->getVar('digest_time');
    }
    public function setDigestTime($value){
        return $this->setVar('digest_time', $value);
    }
    
    public function approved(){
        return $this->getVar('approved');
    }
    public function setApproved($value){
        return $this->setVar('approved', $value);
    }
    
    public function posterName(){
        return $this->getVar('poster_name');
    }
    public function setPosterName($value){
        return $this->setVar('poster_name', $value);
    }
    
    public function rating(){
        return $this->getVar('rating');
    }
    public function setRating($value){
        return $this->setVar('rating', $value);
    }
    
    public function votes(){
        return $this->getVar('votes');
    }
    public function setVotes($value){
        return $this->setVar('votes', $value);
    }
    
    public function friendName(){
        return $this->getVar('friendname');
    }
    public function setFriendName($value){
        return $this->setVar('friendname', $value);
    }
    
    public function getPosts($object = true, $id_as_key = true){
    	$result = $this->db->query("SELECT * FROM ".$this->db->prefix("exmbb_posts")." WHERE id_topic='".$this->id()."'");
		$ret = array();
		while ($row = $this->db->fetchArray($result)){
			if ($object){
				$attach = new BBPost();
				$attach->assignVars($row);
				if ($id_as_key){
					$ret[$row['id_post']] = $attach;
				} else {
					$ret[] = $attach;
				}
			} else {
				if ($id_as_key){
					$ret[$row['id_post']] = $row;
				} else {
					$ret[] = $row;
				}
			}
		}
		
		return $ret;
    }
    
    public function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
    public function delete(){
    	
    	foreach ($this->getPosts() as $post){
    		$post->delete();
    	}
    	
    	$forum = new BBForum($this->forum());
    	$forum->setTopics($forum->topics()-1 > 0 ? $forum->topics()-1 : 0);
	$forum->save();
    	
    	return $this->deleteFromTable();
    	
    }
    
}
?>
