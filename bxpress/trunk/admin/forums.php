<?php
// $Id: forums.php 55 2008-01-28 23:50:07Z BitC3R0 $
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

define('BB_LOCATION', 'forums');
include 'header.php';

/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
    
    $tpl->append('xoopsOptions', array('link' => './forums.php', 'title' => _AS_BB_FORUMS, 'icon' => '../images/forum16.png'));
    $tpl->append('xoopsOptions', array('link' => './forums.php?op=new', 'title' => _AS_BB_NEWFORUM, 'icon' => '../images/add.png'));
}

/**
* @desc Muestra la lista de foros existentes
*/
function showForums(){
    global $xoopsModule, $adminTemplate, $db, $tpl, $util;
    
    $catid = isset($_REQUEST['catid']) ? intval($_REQUEST['catid']) : 0;
    
    $sql = "SELECT * FROM ".$db->prefix("exmbb_forums");
    if ($catid>0){
        $sql .= " WHERE cat='$catid'";
    }
    $sql .= " ORDER BY cat,`order`";
    
    $result = $db->query($sql);
    $categos = array();
    while ($row = $db->fetchArray($result)){
        $forum = new BBForum();
        $forum->assignVars($row);
        // Cargamos la categoría
        if (isset($categos[$forum->category()])){
            $catego = $categos[$forum->category()];   
        } else {
            $categos[$forum->category()] = new BBCategory($forum->category());
            $catego = $categos[$forum->category()];
        }
        // Asignamos los valores
        $tpl->append('forums', array('id'=>$forum->id(), 'title'=>$forum->name(),
                     'topics'=>$forum->topics(), 'posts'=>$forum->posts(),
                     'catego'=>$catego->title(),'active'=>$forum->active(),
                     'attach'=>$forum->attachments(),'order'=>$forum->order()));
    }
    
    // Lenguaje para la tabla
    $tpl->assign('lang_forumslist', _AS_BB_LFORUMS);
    $tpl->assign('lang_id', _AS_BB_ID);
    $tpl->assign('lang_name', _AS_BB_LNAME);
    $tpl->assign('lang_numtopics', _AS_BB_LNUMTOPICS);
    $tpl->assign('lang_numposts', _AS_BB_LNUMPOSTS);
    $tpl->assign('lang_catego', _AS_BB_LCATEGO);
    $tpl->assign('lang_active', _AS_BB_LACTIVE);
    $tpl->assign('lang_attachments', _AS_BB_LATTACH);
    $tpl->assign('lang_options', _OPTIONS);
    $tpl->assign('lang_edit', _EDIT);
    $tpl->assign('lang_delete', _DELETE);
    $tpl->assign('lang_order', _AS_BB_LORDER);
    $tpl->assign('lang_deactivate', _AS_BB_LDEACTIVATE);
    $tpl->assign('lang_activate', _AS_BB_LACTIVATE);
    $tpl->assign('lang_save', _AS_BB_LSAVE);
    $tpl->assign('lang_moderator',_AS_BB_MODERATORS);
    $tpl->assign('token', $util->getTokenHTML());
    
    $adminTemplate = "admin/forums_forums.html";
    optionsBar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_BB_FORUMS);
    xoops_cp_header();
    
    xoops_cp_footer();
    
}

/**
* @desc Muestra el formulario para creación de Foros
* @param int $edit Determina si se esta editando un foro existente
*/
function showForm($edit = 0){
    global $xoopsModule, $db, $xoopsConfig;
    
    if ($edit){
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id<=0){
            redirectMsg('forums.php', _AS_BB_NOID, 1);
            die();
        }
        
        $forum = new BBForum($id);
        if ($forum->isNew()){
            redirectMsg('forums.php', _AS_BB_NOEXISTS, 1);
            die();
        }
    }
    
    optionsBar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_BB_FEDIT : _AS_BB_FNEW));
    xoops_cp_header();
    
    $bcHand = new BBCategoryHandler();
    $bfHand = new BBForumHandler();
    
    $form = new RMForm($edit ? _AS_BB_FEDIT : _AS_BB_FNEW, 'frmForum', 'forums.php');
    // Categorias
    $ele = new RMSelect(_AS_BB_FCATEGO, 'cat', 0, $edit ? array($forum->category()) : null);
    $ele->addOption(0, _SELECT, $edit ? 0 : 1);
    $ele->addOptionsArray($bcHand->getForSelect());
    $form->addElement($ele, true, 'noselect:0');
    // NOmbre
    $form->addElement(new RMText(_AS_BB_FNAME, 'name', 50, 150, $edit ? $forum->name() : ''), true);
    // Descripcion
    $form->addElement(new RMEditor(_AS_BB_FDESC, 'desc', '90%', '300px', $edit ? $forum->description() : '',$xoopsConfig['editor_type']));
    if ($edit){
    	$dohtml = $forum->getVar('dohtml');
    	$doxcode = $forum->getVar('doxcode');
    	$dobr = $forum->getVar('dobr');
    	$doimage = $forum->getVar('doimage');
    	$dosmiley = $forum->getVar('dosmiley');
	} else {
		$dohtml = $xoopsConfig['editor_type']=='tiny' || $xoopsConfig['editor_type']=='fck' ? 1 : 0;
		$doxcode = $xoopsConfig['editor_type']=='tiny' || $xoopsConfig['editor_type']=='fck' ? 0 : 1;
    	$dobr = $xoopsConfig['editor_type']=='tiny' || $xoopsConfig['editor_type']=='fck' ? 0 : 1;
    	$doimage = $xoopsConfig['editor_type']=='tiny' || $xoopsConfig['editor_type']=='fck' ? 0 : 1;
    	$dosmiley = 1;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));

    // Activo
    $form->addElement(new RMYesNo(_AS_BB_FACTIVATE, 'active', $edit ? $forum->active() : 1));
    // Firmas
    $form->addElement(new RMYesNo(_AS_BB_FALLOWSIG, 'sig', $edit ? $forum->signature() : 1));
    // Prefijos
    $form->addElement(new RMYesNo(_AS_BB_FALLOWPREFIX, 'prefix', $edit ? $forum->prefix() : 1));
    // Temas Populares
    $form->addElement(new RMText(_AS_BB_FTHRESHOLD, 'hot_threshold', 10, 5, $edit ? $forum->hotThreshold() : 10), true, 'bigger:1');
    // Orden en la lista
    $form->addElement(new RMText(_AS_BB_FORDER, 'order', 10, 5, $edit ? $forum->order() : 0), false, 'bigger:-1');
    // Adjuntos
    $form->addElement(new RMYesNo(_AS_BB_FATTACH, 'attachments', $edit ? $forum->attachments() : 1));
    $ele = new RMText(_AS_BB_FATTACHSIZE, 'attach_maxkb', 10, 20, $edit ? $forum->maxSize() : 50);
    $ele->setDescription(_AS_BB_FATTACHSIZE_DESC);
    $form->addElement($ele, false, 'bigger:0');
    $ele = new RMText(_AS_BB_FATTACHEXT, 'attach_ext', 50, 0, $edit ? implode("|", $forum->extensions()) : 'zip|tar|jpg|gif|png|gz');
    $ele->setDescription(_AS_BB_FATTACHEXT_DESC);
    $form->addElement($ele);
    // Grupos con permiso
    if ($edit){
        $grupos = $forum->permissions();
    }
    $form->addElement(new RMGroups(_AS_BB_GROUPSVIEW, 'perm_view', 1, 1, 5, $edit ? $grupos['view'] : array(0)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSTOPIC, 'perm_topic', 1, 1, 5, $edit ? $grupos['topic'] : array(1,2)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSREPLY, 'perm_reply', 1, 1, 5, $edit ? $grupos['reply'] : array(1,2)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSEDIT, 'perm_edit', 1, 1, 5, $edit ? $grupos['edit'] : array(1,2)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSDELETE, 'perm_delete', 1, 1, 5, $edit ? $grupos['delete'] : array(1)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSVOTE, 'perm_vote', 1, 1, 5, $edit ? $grupos['vote'] : array(1,2)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSATTACH, 'perm_attach', 1, 1, 5, $edit ? $grupos['attach'] : array(1,2)));
    $form->addElement(new RMGroups(_AS_BB_GROUPSAPPROVE, 'perm_approve', 1, 1, 5, $edit ? $grupos['approve'] : array(1,2)));
    
    $ele = new RMButtonGroup();
    $ele->addButton('sbt', $edit ? _AS_BB_FEDIT : _AS_BB_FCREATE, 'submit', '', 1);
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'forums.php\';"');
    $form->addElement($ele);
    $form->addElement(new RMHidden('op', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMHidden('id', $forum->id()));
    $form->display();
    
    xoops_cp_footer();
}

/**
* @desc Almacena los datos de un foro
*/
function saveForum($edit = 0){
    global $db, $util, $myts;
    
    if (!$util->validateToken()){
    	redirectMsg('forums.php', _AS_BB_ERRTOKEN, 1);
    	die();
    }
    
    foreach ($_POST as $k => $v){
        if (substr($k, 0, 5)=='perm_'){
            $permissions[substr($k, 5)] = $v;
        } else {
            $$k = $v;
        }
    }
    
    if ($edit){
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id<=0){
            redirectMsg('forums.php', _AS_BB_NOID, 1);
            die();
        }
        
        $forum = new BBForum($id);
        if ($forum->isNew()){
            redirectMsg('forums.php', _AS_BB_NOEXISTS, 1);
            die();
        }
    } else {
        $forum = new BBForum();
    }
    
    $forum->setName($util->filterTags($name));
    $forum->setDescription($desc);
    if (!$edit){
        $forum->setTopics(0);
        $forum->setPosts(0);
        $forum->setPostId(0);
        $forum->setSubforums(0);
    }
    $forum->setCategory($cat);
    $forum->setActive($active);
    $forum->setSignature($sig);
    $forum->setPrefix($prefix);
    $forum->setHotThreshold($hot_threshold);
    $forum->setOrder($order);
    $forum->setAttachments($attachments);
    $forum->setMaxSize($attach_maxkb);
    $forum->setExtensions(explode('|',$attach_ext));
    $forum->setFriendName($util->sweetstring($name));
    $forum->setPermissions($permissions);
    
    $forum->setVar('dohtml', isset($dohtml) ? 1 : 0);
    $forum->setVar('doxcode', isset($doxcode) ? 1 : 0);
    $forum->setVar('dobr', isset($dobr) ? 1 : 0);
    $forum->setVar('doimage', isset($doimage) ? 1 : 0);
    $forum->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
    
    if ($forum->save()){
        if ($parent>0){
            $pf = new BBForum($parent);
            if (!$pf->isNew()){
                $pf->setSubforums($pf->subforums() + 1);
                $pf->save();
            }
        }
	if (!$edit){
		//Redireccionamos a ventana de selección de moderadores
        	redirectMsg('forums.php?op=moderator&id='.$forum->id(), _AS_BB_DBOK, 0);
	}else{
		redirectMsg('forums.php', _AS_BB_DBOK, 0);
	}
    } else {
        redirectMsg('forums.php', _AS_BB_ERRACTION . $forum->errors() , 1);
    }    
    
}

/**
* @desc Almacena los cambios realizados en la lista de foros
*/
function saveChanges(){
	global $db,$util;
	
	if (!$util->validateToken()){
    	redirectMsg('forums.php', _AS_BB_ERRTOKEN, 1);
    	die();
    }
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	/**
	* Comprobamos que se haya proporcionado al menos un foro
	*/
	if (!is_array($orders) || empty($orders)){
		redirectMsg('forums.php', _AS_BB_NOSELECTFORUM, 1);
		die();
	}
	
	foreach ($orders as $k => $v){
		$sql = "UPDATE ".$db->prefix("exmbb_forums")." SET `order`='".$v."' WHERE id_forum='$k'";
		$db->queryF($sql);
	}
	
	redirectMsg('forums.php', _AS_BB_DBOK, 0);
	
}

/**
* @desc Activa o desactiva un foro
*/
function activateForum($status=1){
	global $db,$util;
	
	if (!$util->validateToken()){
    	redirectMsg('forums.php', _AS_BB_ERRTOKEN, 1);
    	die();
    }
	
	$forums = isset($_POST['forums']) ? $_POST['forums'] : null;
	
	if (!is_array($forums) || empty($forums)){
		redirectMsg('forums.php', _AS_BB_NOSELECTFORUM, 1);
		die();
	}
	
	$sql = "UPDATE ".$db->prefix("exmbb_forums")." SET active='$status' WHERE ";
	$sql1 = '';
	foreach ($forums as $k => $v){
		$sql1.= $sql1 == '' ? "id_forum='$v' " : "OR id_forum='$v' ";
	}
	
	$db->queryF($sql . $sql1);
	redirectMsg('forums.php', _AS_BB_DBOK, 0);
	
}

/**
* @desc Eliminar un foro
*/
function deleteForum(){
	global $tpl, $xoopsModule, $xoopsConfig, $util;
	
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	
	if ($id<=0){
		redirectMsg('forums.php', _AS_BB_NOID, 1);
		die();
	}
	
	$forum = new BBForum($id);
	if ($forum->isNew()){
		redirectMsg('forums.php', _AS_BB_NOEXISTS, 1);
		die();
	}
	
	if ($ok){
		
		if (!$util->validateToken()){
    		redirectMsg('forums.php', _AS_BB_ERRTOKEN, 1);
    		die();
	    }
		
		if ($forum->delete()){
			redirectMsg('forums.php', _AS_BB_DBOK, 0);
		} else {
			redirectMsg('forums.php', _AS_BB_ERRACTION, 1);
		}
		
	} else {
		
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_BB_DELETELOC);
		xoops_cp_header();
		
		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$hiddens['op'] = 'delete';
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'forums.php\';"';
		
		$util->msgBox($hiddens, 'forums.php', _AS_BB_DELETECONF, XOOPS_ALERT_ICON, $buttons, true, '400px');
		
		xoops_cp_footer();
		
	}
	
}


/**
* @desc Visualiza lista de usuarios para determinar moderadores
**/
function moderators(){
	global $xoopsModule;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	
	if ($id<=0){
		redirectMsg('forums.php', _AS_BB_NOID, 1);
		break;
	}
	
	$forum = new BBForum($id);
	if ($forum->isNew()){
		redirectMsg('forums.php', _AS_BB_NOEXISTS, 1);
		break;
	}


	optionsBar();
	xoops_cp_header();


	//Lista de usuarios
	$form = new RMForm(_AS_BB_MODERATORS, 'formmdt','forums.php');

	$form->addElement(new RMLabel(_AS_BB_LIST,''));


	$form->addElement(new RMFormUserEXM(_AS_BB_USERS,'users',1, $forum->moderators(),30),true,'checked');	

	
	$buttons= new RMButtonGroup();
	$buttons->addButton('sbt', _SUBMIT, 'submit');
	$buttons->addButton('cancel', _CANCEL, 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($buttons);

	$form->addElement(new RMHidden('op','savemoderat'));
	$form->addElement(new RMHidden('id',$id));

	$form->display();


	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_BB_MODERATORS);
	xoops_cp_footer();


}

/**
* @desc Almacena los usuarios moderadores
**/
function saveModerators(){
	global $db, $util;
	
	if (!$util->validateToken()){
    	redirectMsg('forums.php', _AS_BB_ERRTOKEN, 1);
    	die();
    }
	
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	//Verificamos si el foro es válido	
	if ($id<=0){
		redirectMsg('./forums.php',_AS_BB_NOID, 1);
		die();
	}

	//Comprobamos que el foro exista
	$forum = new BBForum($id);
	if ($forum->isNew()){
		redirectMsg('forums.php', _AS_BB_NOEXISTS, 1);
		die();
	}

	$forum->setModerators($users);
	if ($forum->save()){
		redirectMsg('./forums.php',_AS_BB_DBOK,0);
	}
	else{
		redirectMsg('./forums.php',_AS_BB_NOTSAVE,1);
	}
	

}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
    case 'new':
        showForm();
        break;
    case 'edit':
        showForm(1);
        break;
    case 'save':
        saveForum();
        break;
    case 'saveedit':
        saveForum(1);
        break;
    case 'savechanges':
    	saveChanges();
    	break;
    case 'activate':
    	activateForum(1);
    	break;
    case 'deactivate':
    	activateForum(0);
    	break;
    case 'delete':
    	deleteForum();
    	break;
    case 'moderator':
	moderators();
    break;
    case 'savemoderat':
	saveModerators();
    break;
    default:
        showForums();
        break;
}
?>
