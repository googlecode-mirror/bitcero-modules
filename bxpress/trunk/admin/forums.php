<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'forums');
include 'header.php';

/**
* @desc Muestra la lista de foros existentes
*/
function bx_show_forums(){
    global $xoopsModule, $xoopsSecurity;
    
    $catid = rmc_server_var($_REQUEST, 'catid', 0);

    $db = Database::getInstance();
    
    $sql = "SELECT * FROM ".$db->prefix("exmbb_forums");
    if ($catid>0){
        $sql .= " WHERE cat='$catid'";
    }
    $sql .= " ORDER BY cat,`order`";
    
    $result = $db->query($sql);
    $categos = array();
    while ($row = $db->fetchArray($result)){
        $forum = new bXForum();
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

    bXFunctions::menu_bar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Forums Management','bxpress'));
    xoops_cp_header();

    RMTemplate::get()->add_local_script('jquery.checkboxes.js','rmcommon','include');
    RMTemplate::get()->add_local_script('admin.js','bxpress');
    include RMTemplate::get()->get_template('admin/forums_forums.php', 'module', 'bxpress');
    
    xoops_cp_footer();
    
}

/**
* @desc Muestra el formulario para creación de Foros
* @param int $edit Determina si se esta editando un foro existente
*/
function bx_show_form($edit = 0){
    global $xoopsModule, $xoopsConfig;
    
    if ($edit){
        $id = rmc_server_var($_REQUEST, 'id', 0);
        if ($id<=0){
            redirectMsg('forums.php', __('Provided ID is not valid!','bxpress'), 1);
            die();
        }
        
        $forum = new bXForum($id);
        if ($forum->isNew()){
            redirectMsg('forums.php', __('Specified forum does not exists!','bxpress'), 1);
            die();
        }
    }
    
    bXFunctions::menu_bar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Edit Forum','bxpress') : __('New Forum','bxpress')));
    xoops_cp_header();
    
    $bcHand = new bXCategoryHandler();
    $bfHand = new bXForumHandler();
    
    $form = new RMForm($edit ? __('Edit Forum','bxpress') : __('New Forum','bxpress'), 'frmForum', 'forums.php');
    // Categorias
    $ele = new RMFormSelect(__('Category','bxpress'), 'cat', 0, $edit ? array($forum->category()) : null);
    $ele->addOption(0, __('Select category...','bxpress'), $edit ? 0 : 1);
    $ele->addOptionsArray($bcHand->getForSelect());
    $form->addElement($ele, true, 'noselect:0');
    // NOmbre
    $form->addElement(new RMFormText(__('Forum name','bxpress'), 'name', 50, 150, $edit ? $forum->name() : ''), true);
    // Descripcion
    $form->addElement(new RMFormEditor(__('Forum description','bxpress'), 'desc', '90%', '300px', $edit ? $forum->description() : '',$xoopsConfig['editor_type']));

    // Activo
    $form->addElement(new RMFormYesNo(_AS_BB_FACTIVATE, 'active', $edit ? $forum->active() : 1));
    // Firmas
    $form->addElement(new RMFormYesNo(_AS_BB_FALLOWSIG, 'sig', $edit ? $forum->signature() : 1));
    // Prefijos
    $form->addElement(new RMFormYesNo(_AS_BB_FALLOWPREFIX, 'prefix', $edit ? $forum->prefix() : 1));
    // Temas Populares
    $form->addElement(new RMFormText(_AS_BB_FTHRESHOLD, 'hot_threshold', 10, 5, $edit ? $forum->hotThreshold() : 10), true, 'bigger:1');
    // Orden en la lista
    $form->addElement(new RMFormText(_AS_BB_FORDER, 'order', 10, 5, $edit ? $forum->order() : 0), false, 'bigger:-1');
    // Adjuntos
    $form->addElement(new RMFormYesNo(_AS_BB_FATTACH, 'attachments', $edit ? $forum->attachments() : 1));
    $ele = new RMFormText(_AS_BB_FATTACHSIZE, 'attach_maxkb', 10, 20, $edit ? $forum->maxSize() : 50);
    $ele->setDescription(_AS_BB_FATTACHSIZE_DESC);
    $form->addElement($ele, false, 'bigger:0');
    $ele = new RMFormText(_AS_BB_FATTACHEXT, 'attach_ext', 50, 0, $edit ? implode("|", $forum->extensions()) : 'zip|tar|jpg|gif|png|gz');
    $ele->setDescription(_AS_BB_FATTACHEXT_DESC);
    $form->addElement($ele);
    // Grupos con permiso
    if ($edit){
        $grupos = $forum->permissions();
    }
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSVIEW, 'perm_view', 1, 1, 5, $edit ? $grupos['view'] : array(0)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSTOPIC, 'perm_topic', 1, 1, 5, $edit ? $grupos['topic'] : array(1,2)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSREPLY, 'perm_reply', 1, 1, 5, $edit ? $grupos['reply'] : array(1,2)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSEDIT, 'perm_edit', 1, 1, 5, $edit ? $grupos['edit'] : array(1,2)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSDELETE, 'perm_delete', 1, 1, 5, $edit ? $grupos['delete'] : array(1)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSVOTE, 'perm_vote', 1, 1, 5, $edit ? $grupos['vote'] : array(1,2)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSATTACH, 'perm_attach', 1, 1, 5, $edit ? $grupos['attach'] : array(1,2)));
    $form->addElement(new RMFormGroups(_AS_BB_GROUPSAPPROVE, 'perm_approve', 1, 1, 5, $edit ? $grupos['approve'] : array(1,2)));
    
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? _AS_BB_FEDIT : _AS_BB_FCREATE, 'submit', '', 1);
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'forums.php\';"');
    $form->addElement($ele);
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $forum->id()));
    $form->display();
    
    xoops_cp_footer();
}

/**
* @desc Almacena los datos de un foro
*/
function bx_save_forum($edit = 0){
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
function bx_save_changes(){
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
function bx_activate_forums($status=1){
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
function bx_delete_forums(){
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
function bx_moderators(){
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
function bx_save_moderators(){
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


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'new':
        bx_show_form();
        break;
    case 'edit':
        bx_show_form(1);
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
        bx_show_forums();
        break;
}
