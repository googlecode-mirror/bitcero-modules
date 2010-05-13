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
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México

/**
* @desc Constantes para selección de permisos del usuario
*/
define('EXMBB_PERM_VIEW','view');
define('EXMBB_PERM_TOPIC','topic');
define('EXMBB_PERM_REPLY','reply');
define('EXMBB_PERM_EDIT','edit');
define('EXMBB_PERM_DELETE','delete');
define('EXMBB_PERM_VOTE','vote');
define('EXMBB_PERM_ATTACH','attach');
define('EXMBB_PERM_APPROVE','approve');

/**
* @desc Clase para el manejo de objetos foro
*/
class BBForum extends EXMObject
{
    /**
    * @param int $id Identificador del Foro
    * @param string $id Identificador alfanumerico
    */
    public function __construct($id=null){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("exmbb_forums");
        $this->setNew();
        $this->initVarsFromTable();
        
        $this->setVarType('moderators', XOBJ_DTYPE_ARRAY);
        $this->setVarType('permissions', XOBJ_DTYPE_ARRAY);
        $this->setVarType('attach_ext', XOBJ_DTYPE_ARRAY);
        
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
            $this->primary = 'id_cat';   
        }
    }
    
    /**
    * @desc Metodos para acceso a las propiedades
    */
    
    public function id(){
        return $this->getVar('id_forum');
    }
    /**
    * @desc Nombre del Foro
    */
    public function name(){
        return $this->getVar('name');
    }
    public function setName($value){
        return $this->setVar('name', $value);   
    }
    /**
    * @desc Descripción del Foro
    */
    public function description(){
        return $this->getVar('desc');
    }
    public function setDescription($value){
        return $this->setVar('desc', $value);
    }
    /**
    * @desc Foro padre del foro actual
    */
    public function parent(){
        return $this->getVar('parent');
    }
    /**
    * @param int $value Identificador del foro padre
    */
    public function setParent($value){
        return $this->setVar('parent', $value);
    }
    /**
    * @desc Moderadores del Foro
    * @return array Ids de los moderadores
    */
    public function moderators(){
        return $this->getVar('moderators');
    }
    /**
    * @param array $value Ids de los moderadores
    * @param string $value Lista separada por comas con los ids
    */
    public function setModerators($value){
        return $this->setVar('moderators', $value);
    }
    /**
    * @desc Número de temas existentes en el foro
    * @return int
    */
    public function topics(){
        return $this->getVar('topics');
    }
    /**
    * @param int $value Numero de temas del foro
    */
    public function setTopics($value){
        return $this->setVar('topics', $value);
    }
    /**
    * @desc Incrementa el contador de temas
    */
    public function addTopic(){
    	$this->setTopics($this->topics()+1);
    }
    /**
    * @desc Obtiene el Número de envios en el foro
    * @return int
    */
    public function posts(){
        return $this->getVar('posts');
    }
    /**
    * @desc Establece el número de envios en el foro
    * @param int $value Numero de envios del foro
    */
    public function setPosts($value){
        return $this->setVar('posts', $value);
    }
    /**
    * @desc Incrementa en uno el contador de mensajes
    */
    public function addPost(){
    	$this->setPosts($this->posts()+1);
    }
    /**
    * @desc Devuelve el id del último envío en el foro
    * @return int
    */
    public function lastPostId(){
        return $this->getVar('last_post_id');
    }
    /**
    * @desc Establece el id del último envío en el foro
    * @param int $value Id del último envío
    */
    public function setPostId($value){
        return $this->setVar('last_post_id', $value);
    }
    /**
    * @desc Devuelve el id de la categoría a la que pertenece el foro
    * @return int
    */
    public function category(){
        return $this->getVar('cat');
    }
    /**
    * @desc Establece el id de la categoría a la que pertenece el foro
    * @param int $value Id de la categoría
    */
    public function setCategory($value){
        return $this->setVar('cat', $value);
    }
    /**
    * @desc Inidica si el módulo esta activo (1) o inactivo (0)
    * @return int
    */
    public function active(){
        return $this->getVar('active');
    }
    /**
    * @desc Establece l módulo como activo (1) o inactivo (0)
    * @param int $value 1 o 0
    */
    public function setActive($value){
        return $this->setVar('active', $value);
    }
    /**
    * @desc Permite saber si estanb activas (1) o inactivas (0) las firmas en los envios
    * @return int
    */
    public function signature(){
        return $this->getVar('sig');
    }
    /**
    * @desc Activa (1) o desactiva (0) las firmas en lso envios
    * @param int $value 1 o 0
    */
    public function setSignature($value){
        return $this->setVar('sig', $value);
    }
    /**
    * @desc Determina si los prefijos para titulos de envios estan activos(1) o inactivos(0)
    * @return int
    */
    public function prefix(){
        return $this->getVar('prefix');
    }
    /**
    * @desc Activa (1) o desactiva (0) el uso de prefijos en lso titulos de los envios
    * @param int $value 1 o 0
    */
    public function setPrefix($value){
        return $this->setVar('prefix', $value);
    }
    /**
    * @desc Devuelve el numero de respuestas para considerar popular un tema
    * @return int
    */
    public function hotThreshold(){
        return $this->getVar('hot_threshold');
    }
    /**
    * @desc Establece el numero de envios para considerar popular un tema
    * @param int $value Numero de envios
    */
    public function setHotThreshold($value){
        return $this->setVar('hot_threshold', $value);
    }
    /**
    * @desc Orden del foro
    * @return int
    */
    public function order(){
        return $this->getVar('order');
    }
    public function setOrder($value){
        return $this->setVar('order', $value);
    }
    
    /**
    * @desc Acepta archivos adjuntos
    * @return int
    */
    public function attachments(){
        return $this->getVar('attachments');
    }
    public function setAttachments($value){
        return $this->setVar('attachments', $value);
    }
    
    /**
    * @desc Tamaño máximo de los archivos adjuntos
    * @return int
    */
    public function maxSize(){
        return $this->getVar('attach_maxkb');
    }
    public function setMaxSize($value){
        return $this->setVar('attach_maxkb', $value);
    }
    
    /**
    * @desc Extensiones permitidas en los archivos adjuntos
    * @return array
    */
    public function extensions(){
        return $this->getVar('attach_ext');
    }
    /**
    * @desc Establece el tipo de extensiones permitidas para los archivos adjuntos
    * @param array $value
    * @param string $value Extensiones separadas por coma
    */
    public function setExtensions($value){
        if (!is_array($value)){
            $value = explode(',', trim($ext));
        }
        return $this->setVar('attach_ext', $value);
    }
    
    /**
    * @desc Numero de subforos
    * @return int
    */
    public function subforums(){
        return $this->getVar('subforums');
    }
    public function setSubforums($value){
        return $this->setVar('subforums', $value);
    }
    
    /**
    * @desc Nombre amigable para urls
    * @return int
    */
    public function friendName(){
        return $this->getVar('friendname');
    }
    public function setFriendName($value){
        return $this->setVar('friendname', $value);
    }
    
    /**
    * @desc Permisos del foro
    * @return array
    */
    public function permissions(){
        return $this->getVar('permissions');
    }
    /**
    * @param $value = array
    */
    public function setPermissions($value){
        if (!is_array($value)) return false;
        return $this->setVar('permissions', $value);
    }
    /**
    * @desc Crea el enlace hacia al foro apartir del metodo de urls
    * @param int $mode 0 por Defecto, 1 Basado en Nombres
    */
    public function makeLink($mode = 0){
        $link = XOOPS_URL.'/modules/exmbb/';
        $link .= "forum.php?id=".$this->id();
        return $link;
    }
    /**
    * @desc Permite saber si un usuario cuenta con permisos
    * especificos en el foro dependiendo de su grupo
    * @param int,array Id del grupo
    * @param string $type Tipo de permiso (view,topic,reply,edit,delete,vote,attach,approve)
    * @return bool
    */
    public function isAllowed($gid, $type){
        
        if ($type=='') return false;
        
        $perms =& $this->permissions();
        if (!isset($perms[$type])) return false;
        
        // Comprobamos si "Todos" esta activo
        if (in_array(0, $perms[$type])) return true;
        
        if (!is_array($gid)){
        	// Comprobamos si el grupo de usuario es "Administradores"
        	if ($gid==XOOPS_GROUP_ADMIN) return true;
            return in_array($gid, $perms[$type]);
        }
        
        if (in_array(XOOPS_GROUP_ADMIN, $gid)) return true;
        
        foreach ($gid as $id){
            if (in_array($id, $perms[$type])) return true;
        }
        
        return false;
        
    }
    /**
    * @desc Determina si un usuario es moderador del foro
    * @param int Id del Usuario
    * @return bool
    */
    public function isModerator($id){
    	if ($id<=0) return false;
    	
    	$moderators = $this->moderators();
    	if (!is_array($moderators)) return false;
    	return in_array($id, $moderators);
    	
    }
    /**
    * @desc Obtiene todos los temas del foro
    * @param int $level 0 = Sticky, 1 = Sticky y Multiples, 2 = Todos
    */
    public function getTopics($level = 2){
        $tbl1 = $this->db->prefix("exmbb_topics");
        $tbl2 = $this->db->prefix("exmbb_forumtopics");
        
        $sql = "SELECT tbl1.* FROM $tbl1,$tbl2 WHERE $tbl2.forum='".$this->id()."' AND 
                $tbl1.id_topic=$tbl2.topic ORDER BY sticky, date DESC";
    }

    /**
    * @desc Obtenemos el último mensaje del foro
    **/
    public function getLastPost(){
	$post = 0;

	$sql = "SELECT a.* FROM ".$this->db->prefix('exmbb_posts')." a INNER JOIN ".$this->db->prefix('exmbb_topics')." b ON ";
	$sql.= " (a.id_topic=b.id_topic AND a.id_forum=".$this->id()." AND b.approved=1) ORDER BY a.post_time DESC";
	$result = $this->db->query($sql);
	while ($rows = $this->db->fetchArray($result)){

		$post = $rows['id_post'];
		
		break;
	}

	return $post;

    }

    
    public function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
    /**
    * @desc Elimina un foro junto con sus temas y mensajes
    */
    public function delete(){
    	
    	$sql = "SELECT * FROM ".$this->db->prefix("exmbb_topics")." WHERE id_forum='".$this->id()."'";
    	$result = $this->db->query($sql);
    	while ($row = $this->db->fetchArray($result)){
    		$topic = new BBTopic();
    		$topic->assignVars($row);
    		$topic->delete();
    	}
    	
    	return $this->deleteFromTable();
    	
    }
    
}

/**
* @desc Manejador para la tabla de foros
*/
class BBForumHandler
{
    private $db;
    private $table = '';
    
    function __construct(){
        $this->db =& Database::getInstance();
        $this->table = $this->db->prefix("exmbb_forums");
    }
    /**
    * @desc Obtiene todos los foros de la base de datos
    * @param int $parent Id del foro raíz
    * @param int $category Identificador de la categoría. 0 indica todas
    * @param int $activo -1 devuelve todos los foros, 0 solo los inactivos y 1 solo activos
    * @param bool $object Indica si se devueven los objetos BBForum
    */
    public function getForums($category=0, $active=-1, $object = false){
        
        $db =& Database::getInstance();
        
        $sql = "SELECT * FROM ".$db->prefix("exmbb_forums")." WHERE ".($active>-1 ? " active='$active' " : '').
                ($category>0 ? " AND cat='$category' " : '')." ORDER BY `cat`,`order`";
        $result = $db->queryF($sql);
        $retorno = array();
        while ($row = $db->fetchArray($result)){
            if ($object){
                $forum = new BBForum();
                $forum->assignVars($row); 
                $retorno[] = $forum;  
            } else {
                $retorno[] = $row;
            }
        }
        return $retorno;
        
    }
    /**
    * @desc Obtiene los foros ordenados en padres e hijos
    * @param array $retorno Referencia al arreglo que se llenará
    * @param int $parent Id del foro en el cual se empiezan a buscar hijos
    * @param int $category Id de la categoria. 0 Indica todas
    * @param int $saltos Número de saltos que se asignarán al nivel actual
    * @param int $activo -1 devuelve todos los foros, 0 solo los inactivos y 1 solo activos
    * @param bool $object Indica si se devueven los objetos BBForum
    */
    private function getForumsTree($retorno, $parent = 0, $category=0, $saltos = 0, $active = -1, $object = false){
        $sql = "SELECT * FROM $this->table WHERE `parent`='$parent'".($active>-1 ? " AND active='$active' " : '').
                ($category>0 ? " AND cat='$category' " : '')." ORDER BY `cat`,`order`";
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)){
            if ($object){
                $forum = new BBForum();
                $forum->assignVars($row); 
                $retorno[] =& $forum;  
            } else {
                $retorno[] = $row;
            }
            $this->getForumsTree($retorno, $row['id_forum'], $category, $saltos + 1, $active, $object);
        }
    }
    
}
?>
