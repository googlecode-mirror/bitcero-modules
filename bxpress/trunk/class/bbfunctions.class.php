<?php
// $Id: bbfunctions.class.php 76 2009-02-15 10:52:06Z BitC3R0 $
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
* @desc Clase para el manejo de funciones internas del foro
*/
class BBFunctions
{
    private $db;
    
    public function __construct(){
        $this->db =& Database::getInstance();
    }
    
    public function getInstance(){
        static $instance;
        if (!isset($instance)) {
            $instance =& new BBFunctions();
        }
        return $instance;
    }
    /**
    * @desc Obtiene el último usuario registrado
    * @return objeto {@link XoopsUser}
    */
    function getLastUser(){
        $db =& Database::getInstance();
        $result = $db->query("SELECT * FROM ".$db->prefix("users")." WHERE level>'0' ORDER BY uid DESC LIMIT 0,1");
        if ($db->getRowsNum($result)>0){
            $row = $db->fetchArray($result);
            $user = new XoopsUser();
            $user->assignVars($row);
            return $user;
        }
        return false;
    }
    /**
    * @desc Obtiene el número de usuarios conectados
    * @param int $type Determina el tipo de usuario que devolvera:
    *         0 Devuelve usuarios anonimos
    *         1 Devuelve Usuarios registrados
    *         2 Devuelve todos los usuarios conectados
    * @return int
    */
    public function getOnlineCount($type = 1){
        global $xoopsModule;
        
        $db =& Database::getInstance();
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("online")." WHERE online_module='".$xoopsModule->mid()."'";
        
        if ($type==0){
            $sql .= " AND online_uid<'1'";
        }elseif($type==1){
            $sql .= " AND online_uid>'0'";
        }
        
        list($num) = $db->fetchRow($db->query($sql));
        return $num;
        
    }
    /**
    * @desc Total de Usuarios Registrados
    * @return int
    */
    public function totalUsers(){
        $db =& Database::getInstance();
        list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("users")." WHERE level>'0'"));
        return $num;
    }
    /**
    * @desc Total de Temas en los Foros
    */
    public function totalTopics(){
        $db =& Database::getInstance();
        list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("exmbb_topics")));
        return $num;
    }
    /**
    * @desc Total de Mensajes en los Foros
    */
    public function totalPosts(){
        $db =& Database::getInstance();
        list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("exmbb_posts")));
        return $num;
    }
    /**
    * @desc Formatea la fecha
    */
    public function formatDate($time){
    	global $mc;
    	
    	$real = time() - $time;
    
    	$today = date('G',time()) * 3600;
    	$today += (date('i', time()) * 60);
    	$today += date('s', time());
    	
    	if ($real<=$today){
    		return sprintf(_MS_EXMBB_TODAY, date('H:i:s', $time));
    	}elseif ($real<=($today-86400)){
    		return sprintf(_MS_EXMBB_YESTERDAY, date('H:i:s', $time));
	}else{
		return formatTimeStamp($time);
	}
    	
    }
    /**
    * @desc Creamos el encabezado del módulo
    */
    public function makeHeader(){
    	global $tpl, $xoopsModuleConfig, $xoopsUser;
    	
    	$tpl->assign('lang_index', _MS_EXMBB_INDEX);
    	$tpl->assign('forums_title', $xoopsModuleConfig['forum_title']);
    	if ($xoopsUser || $xoopsModuleConfig['search']){
    		$tpl->assign('lang_search',_MS_EXMBB_SEARCH);
    		$tpl->assign('lang_searchq', _MS_EXMBB_SEARCHQ);
    		$tpl->assign('can_search',1);
    	}
    	
	}
	/**
	* @desc Crea las páginas para el indice de temas
	*/
	public function paginateIndex($tpages, $limit=3){
		
		$ret = array();
		
		for ($i=1;$i<=$tpages;$i++){
			$ret[] = $i;
			if ($i==$limit && $tpages>$limit){
				$i = $tpages-1;
				$ret[] = '...';
			}
		}
		
		return $ret;
		
	}
	/**
	* @desc Determina la página del tema dependiendo del id de un post
	*/
	public function pageFromPID($pid){
		global $xoopsModuleConfig;
		
		$db =& Database::getInstance();
		
		$result = $db->query('SELECT id_topic FROM '.$db->prefix('exmbb_posts')." WHERE id_post='$pid'");
		if (!$db->getRowsNum($result)) return;

		list($id) = $db->fetchRow($result);

		// Determine on what page the post is located (depending on $pun_user['disp_posts'])
		$result = $db->query("SELECT id_post FROM ".$db->prefix('exmbb_posts')." WHERE id_topic='$id' ORDER BY post_time");
		$num = $db->getRowsNum($result);
		
		for ($i = 0; $i < $num; ++$i)
		{
			list($cur_id) = $db->fetchRow($result);
			if ($cur_id == $pid)
				break;
		}
		++$i;	// we started at 0
		$_GET['pag'] = ceil($i / $xoopsModuleConfig['perpage']);
		return $id;
	}
	/**
	* @desc Obtiene el id del primer mensaje de un tema
	* @param int Id del tema
	* @return int
	*/
	public function getFirstId($topic_id){
		
		$db =& Database::getInstance();
		$sql = "SELECT MIN(id_post) FROM ".$db->prefix("exmbb_posts")." WHERE id_topic='".$topic_id."'";
		list($first_id) = $db->fetchRow($db->query($sql));
		return $first_id;
		
	}
	
	public function forumList($varname = 'forums'){
		global $db, $tpl;
		$db =& Database::getInstance();
		$sql = "SELECT * FROM ".$db->prefix("exmbb_forums")." WHERE active='1' ORDER BY cat,`order`";
		$result = $db->query($sql);
		while ($row = $db->fetchArray($result)){
			$forum = new BBForum();
			$forum->assignVars($row);
			$tpl->append($varname, array('id'=>$forum->id(),'title'=>$forum->name()));
		}
	}
	
	public function loadAnnouncements($w, $forum=0){
		global $xoopsModuleConfig, $tpl;
		
		$db =& Database::getInstance();
		
		// Primero purgamos la tabla
		$db->queryF("DELETE FROM ".$db->prefix("exmbb_announcements")." WHERE expire<='".time()."'");
		
		if (!$xoopsModuleConfig['announcements']) return;
		$mc =& $xoopsModuleConfig;
		$sql = "SELECT * FROM ".$db->prefix("exmbb_announcements");
		
		switch ($w){
			case 0:
				$sql .= " WHERE (`where`='0' OR `where`='2') ";
				break;
			case 1:
				$sql .= " WHERE (`where`='1' AND forum='$forum') OR `where`='2' ";
				break;
		}
		
		if ($mc['announcements_mode']){
			$sql .= " ORDER BY RAND() ";
		} else {
			$sql .= " ORDER BY date DESC ";
		}
		
		$sql .= "LIMIT 0, $mc[announcements_max]";
		
		$result = $db->query($sql);
	
		while ($row = $db->fetchArray($result)){
			$an = new BBAnnouncement();
			$an->assignVars($row);
			$tpl->append('announcements', array('text'=>$an->text('s')));
		}
		
		$tpl->assign('lang_announcement', _MS_EXMBB_ANNOUNCEMENT);
		
		return true;
		
	}

	/**
	* @desc Notifica al grupo de administradores la creación de un nuevo tema no aprobado
	* @param {@link } Objetos de Foro, Tema y mensaje
	* @param int edit indica si es la edición de un mensaje o un nuevo tema no aprobado
	**/
	public function notifyAdmin($moderators,BBForum &$forum, BBTopic &$topic, BBPost &$post,$edit=0){
		global $db, $xoopsModule, $xoopsConfig;
	
		$mhand = new XoopsMemberHandler($db);
		$configCat = new XoopsConfigCategory('mailer', 'mailer');
		$config =& $configCat->getConfigs(3);
    	
		$users = $moderators;
		
		if (!$edit){
			if (file_exists(XOOPS_ROOT_PATH.'/modules/exmbb/language/'.$xoopsConfig['language'].'/mail_template/admin_notify.tpl')){
				$tpldir = XOOPS_ROOT_PATH.'/modules/exmbb/language/'.$xoopsConfig['language'].'/mail_template';
			} else {
				$tpldir = XOOPS_ROOT_PATH.'/modules/exmbb/language/spanish/mail_template';
			}
		}else{
			if (file_exists(XOOPS_ROOT_PATH.'/modules/exmbb/language/'.$xoopsConfig['language'].'/mail_template/admin_notify_post.tpl')){
				$tpldir = XOOPS_ROOT_PATH.'/modules/exmbb/language/'.$xoopsConfig['language'].'/mail_template';
			} else {
				$tpldir = XOOPS_ROOT_PATH.'/modules/exmbb/language/spanish/mail_template';
			}
		}
	
		
		foreach ($users as $k){
			$xoopsMailer =& getMailer();
			$xoopsMailer->setFromEmail($config['from']);
			$xoopsMailer->setFromName($config['fromname']);
			$xoopsMailer->setTemplateDir($tpldir);
			if (!$edit){
				$xoopsMailer->setSubject(sprintf(_AS_EXMBB_ADMSUBJECT, $forum->name()));
				$xoopsMailer->setTemplate('admin_notify.tpl');
			}else{
				$xoopsMailer->setSubject(sprintf(_AS_EXMBB_ADMSUBJECTPOST, $topic->title()));
				$xoopsMailer->setTemplate('admin_notify_post.tpl');
			}
		
			$xoopsMailer->assign('FORUM_NAME',$forum->name());
			$xoopsMailer->assign('FORUM_MODNAME', $xoopsModule->name());
			$xoopsMailer->assign('TOPIC_UNAME', $topic->posterName());
			$xoopsMailer->assign('TOPIC_NAME', $topic->title());
			$xoopsMailer->assign('TOPIC_APPROVED', $topic->approved() ? _YES : _NO);
			$xoopsMailer->assign('TOPIC_LINK', XOOPS_URL.'/modules/exmbb/moderate.php?id='.$forum->id());
			$xoopsMailer->assign('POST_UNAME',$post->uname());
			$xoopsMailer->assign('POST_LINK',XOOPS_URL.'/modules/exmbb/topic.php?pid='.$post->id()."#p".$post->id());

			$user = new XoopsUser($k);
			$xoopsMailer->setToUsers($user);
			$xoopsMailer->isMail = $user->getVar('notify_method')==2;
			$xoopsMailer->isPM = $user->getVar('notify_method')==1;
			$xoopsMailer->send(true);
			$xoopsMailer->clearAddresses();
			
		}
			
		echo $xoopsMailer->getErrors();
		
	
	}
}
?>
