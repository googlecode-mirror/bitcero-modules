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

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $sql = "SELECT * FROM ".$db->prefix("bxpress_forums");
    if ($catid>0){
        $sql .= " WHERE cat='$catid'";
    }
    $sql .= " ORDER BY cat,`order`";
    
    $result = $db->query($sql);
    $categos = array();
    $forums = array();

    while ($row = $db->fetchArray($result)){
        $forum = new bXForum();
        $forum->assignVars($row);
        // Cargamos la categoría
        if (isset($categos[$forum->category()])){
            $catego = $categos[$forum->category()];   
        } else {
            $categos[$forum->category()] = new bXCategory($forum->category());
            $catego = $categos[$forum->category()];
        }
        // Asignamos los valores
        $forums[] = array(
            'id'=>$forum->id(),
            'title'=>$forum->name(),
            'topics'=>$forum->topics(),
            'posts'=>$forum->posts(),
            'catego'=>$catego->title(),
            'active'=>$forum->active(),
            'attach'=>$forum->attachments(),
            'order'=>$forum->order()
        );
    }

    bXFunctions::menu_bar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Forums Management','bxpress'));
    xoops_cp_header();
    
    RMTemplate::get()->set_help('http://www.redmexico.com.mx/docs/bxpress-forums/foros/standalone/1/');
    RMTemplate::get()->add_local_script('jquery.checkboxes.js','rmcommon','include');
    RMTemplate::get()->add_local_script('admin.js','bxpress');
    RMTemplate::get()->add_head('<script type="text/javascript">
        var bx_select_message = "'.__('You must select one forum at least in order to run this action!','bxpress').'";
        var bx_message = "'.__('Do you really want to delete selected forums?\n\nAll posts sent in this forum will be deleted also!','bxpress').'";
    </script>');
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
    RMTemplate::get()->set_help('http://www.redmexico.com.mx/docs/bxpress-forums/foros/standalone/1/#crear-foro');
    RMTemplate::get()->add_style('admin.css','bxpress');
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
    $form->addElement(new RMFormEditor(__('Forum description','bxpress'), 'desc', '90%', '300px', $edit ? $forum->description() : ''));

    // Activo
    $form->addElement(new RMFormYesNo(__('Activate forum','bxpress'), 'active', $edit ? $forum->active() : 1));
    // Firmas
    $form->addElement(new RMFormYesNo(__('Allow signatures in the posts','bxpress'), 'sig', $edit ? $forum->signature() : 1));
    // Temas Populares
    $form->addElement(new RMFormText(__('Answers to match a topic as popular','bxpress'), 'hot_threshold', 10, 5, $edit ? $forum->hotThreshold() : 10), true, 'bigger:1');
    // Orden en la lista
    $form->addElement(new RMFormText(__('Order in the list','bxpress'), 'order', 10, 5, $edit ? $forum->order() : 0), false, 'bigger:-1');
    // Adjuntos
    $form->addElement(new RMFormYesNo(__('Allow attachments','bxpress'), 'attachments', $edit ? $forum->attachments() : 1));
    $ele = new RMFormText(__('Maximum attachments file size','bxpress'), 'attach_maxkb', 10, 20, $edit ? $forum->maxSize() : 50);
    $ele->setDescription(__('Specify this value in Kilobytes','bxpress'));
    $form->addElement($ele, false, 'bigger:0');
    $ele = new RMFormText(__('Allowed file types','bxpress'), 'attach_ext', 50, 0, $edit ? implode("|", $forum->extensions()) : 'zip|tar|jpg|gif|png|gz');
    $ele->setDescription(__('Specified the extensions of allowed file types separating each one with "|" and without the dot.','bxpress'));
    $form->addElement($ele);
    // Grupos con permiso
    if ($edit){
        $grupos = $forum->permissions();
    }
    $form->addElement(new RMFormGroups(__('Can view the forum','bxpress'), 'perm_view', 1, 1, 5, $edit ? $grupos['view'] : array(0)));
    $form->addElement(new RMFormGroups(__('Can start new topics','bxpress'), 'perm_topic', 1, 1, 5, $edit ? $grupos['topic'] : array(1,2)));
    $form->addElement(new RMFormGroups(__('Can answer','bxpress'), 'perm_reply', 1, 1, 5, $edit ? $grupos['reply'] : array(1,2)));
    $form->addElement(new RMFormGroups(__('Can edit their posts','bxpress'), 'perm_edit', 1, 1, 5, $edit ? $grupos['edit'] : array(1,2)));
    $form->addElement(new RMFormGroups(__('Can delete','bxpress'), 'perm_delete', 1, 1, 5, $edit ? $grupos['delete'] : array(1)));
    $form->addElement(new RMFormGroups(__('Can vote','bxpress'), 'perm_vote', 1, 1, 5, $edit ? $grupos['vote'] : array(1,2)));
    $form->addElement(new RMFormGroups(__('Can attach','bxpress'), 'perm_attach', 1, 1, 5, $edit ? $grupos['attach'] : array(1,2)));
    $form->addElement(new RMFormGroups(__('Can send without approval','bxpress'), 'perm_approve', 1, 1, 5, $edit ? $grupos['approve'] : array(1,2)));
    
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes','bxpress') : __('Create Forum','bxpress'), 'submit', '', 1);
    $ele->addButton('cancel', __('Cancel','bxpress'), 'button', 'onclick="window.location=\'forums.php\';"');
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
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsConfig;

    $q = $edit ? 'action=edit' : 'action=new';
    
    $prefix = 1;
    foreach ($_POST as $k => $v){
        if (substr($k, 0, 5)=='perm_'){
            $permissions[substr($k, 5)] = $v;
        } else {
            $$k = $v;
        }

        if($k=='XOOPS_TOKEN_REQUEST' || $k=='action') continue;
        $q = '&'.$k.'='.$v;
    }

    if (!$xoopsSecurity->check()){
    	redirectMsg('forums.php?'.$q, __('Session token expired','bxpress'), 1);
    	die();
    }
    
    if ($edit){
        $id = rmc_server_var($_REQUEST, 'id', 0);
        if ($id<=0){
            redirectMsg('forums.php', __('Specified id is not valid!','bxpress'), 1);
            die();
        }
        
        $forum = new bXForum($id);
        if ($forum->isNew()){
            redirectMsg('forums.php', __('Specified forum does not exists!','bxpress'), 1);
            die();
        }
    } else {
        $forum = new bXForum();
    }
    
    $forum->setName($name);
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
    $forum->setFriendName(TextCleaner::getInstance()->sweetstring($name));
    $forum->setPermissions($permissions);
    
    if ($forum->save()){
        if ($parent>0){
            $pf = new bXForum($parent);
            if (!$pf->isNew()){
                $pf->setSubforums($pf->subforums() + 1);
                $pf->save();
            }
        }
        if (!$edit){
            //Redireccionamos a ventana de selección de moderadores
                redirectMsg('forums.php?action=moderators&id='.$forum->id(), __('Forum saved successfully! Redirecting to moderators assignment...','bxpress'), 0);
        }else{
            redirectMsg('forums.php', __('Changes saved successfully!','bxpress'), 0);
        }
    } else {
        redirectMsg('forums.php?'.$q, __('Forum could not be saved!','bxpress') . $forum->errors() , 1);
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
		$sql = "UPDATE ".$db->prefix("bxpress_forums")." SET `order`='".$v."' WHERE id_forum='$k'";
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
	
	$sql = "UPDATE ".$db->prefix("bxpress_forums")." SET active='$status' WHERE ";
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
	global $tpl, $xoopsModule, $xoopsConfig, $xoopsSecurity;
	
	$ids = rmc_server_var($_REQUEST, 'ids', 0);

    if (!$xoopsSecurity->check()){
        redirectMsg('forums.php', __('Session token expired!','bxpress'), 1);
    	die();
	}

    $errors = '';
    foreach($ids as $id){

        $forum = new bXForum($id);
        if ($forum->isNew()){
            $errors .= sprintf(__('Forum with id "%u" does not exists!','bxpress'), $id);
            die();
        }


        if (!$forum->delete()){
            $errors = sprintf(__('Forum "%s" could not be deleted!','bxpress'), $forum->name()).'<br />'.$forum->errors();
        }

    }

    if($errors!=''){
        redirectMsg('forums.php', __('Errors ocurred while trying to delete forums:','bxpress').'<br />'.$errors, 1);
    } else {
        redirectMsg('forums.php', __('Forums deleted without errors','bxpress'), 0);
    }
	
}


/**
* @desc Visualiza lista de usuarios para determinar moderadores
**/
function bx_moderators(){
	global $xoopsModule;

	$id= rmc_server_var($_REQUEST, 'id', 0);
	
	if ($id<=0){
		redirectMsg('forums.php', __('No forum ID has been provided!','bxpress'), 1);
		break;
	}
	
	$forum = new bXForum($id);
	if ($forum->isNew()){
		redirectMsg('forums.php', __('Specified forum does not exists!','bxpress'), 1);
		break;
	}

    RMTemplate::get()->set_help('http://www.redmexico.com.mx/docs/bxpress-forums/foros/standalone/1/#moderadores');
	bXFunctions::menu_bar();
	xoops_cp_header();


	//Lista de usuarios
	$form = new RMForm(sprintf(__('Forum "%s" Moderators','bxpress'), $forum->name()), 'formmdt','forums.php');

	$form->addElement(new RMFormUser(__('Moderators','bxpress'),'users',1, $forum->moderators(),30),true,'checked');
    $form->element('users')->setDescription(__('Choose from the list the moderators users','bxpress'));

	
	$buttons= new RMFormButtonGroup();
	$buttons->addButton('sbt', __('Save Moderators','bxpress'), 'submit');
	$buttons->addButton('cancel', __('Cancel','bxpress'), 'button', 'onclick="window.location.href=\'forums.php\';"');
	
	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action','savemoderat'));
	$form->addElement(new RMFormHidden('id',$id));

	$form->display();


	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('forum Moderators','bxpress'));
	xoops_cp_footer();


}

/**
* @desc Almacena los usuarios moderadores
**/
function bx_save_moderators(){
    global $xoopsSecurity;
	
	if (!$xoopsSecurity->check()){
    	redirectMsg('forums.php', __('Session token expired!','bxpress'), 1);
    	die();
    }
	
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	//Verificamos si el foro es válido	
	if ($id<=0){
		redirectMsg('forums.php', __('A forum ID has not been provided!','bxpress'), 1);
		die();
	}

	//Comprobamos que el foro exista
	$forum = new bXForum($id);
	if ($forum->isNew()){
		redirectMsg('forums.php', __('Sepecified forum does not exists!','bxpress'), 1);
		die();
	}

	$forum->setModerators($users);
	if ($forum->save()){
		redirectMsg('forums.php',__('Moderator saved successfully!','bxpress'),0);
	}
	else{
		redirectMsg('forums.php',__('Moderators could not be saved!','bxpress').'<br />'.$forum->errors(),1);
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
        bx_save_forum();
        break;
    case 'saveedit':
        bx_save_forum(1);
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
    	bx_delete_forums();
    	break;
    case 'moderators':
        bx_moderators();
        break;
    case 'savemoderat':
        bx_save_moderators();
        break;
    default:
        bx_show_forums();
        break;
}
