<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include 'header.php';

/**
* @desc Muestra todas las referencias existentes
**/
function rd_show_notes(){
	global $xoopsModule, $xoopsSecurity;
    
    define('RMCSUBLOCATION','notes_list');
    
	$id_res = rmc_server_var($_GET, 'res', 0);
    
    if($id_res<=0){
        redirectMsg('resources.php', __('Select a resource to view notes from this','docs'), 0);
        die();
    }
    
    $res = new RDResource($id_res);
    if($res->isNew()){
        redirectMsg('resources.php', __('The specified resource does not exists!','docs'), 0);
        die();
    }
    
	$search = rmc_server_var($_GET, 'search', '');
	
	//Separamos frase en palabras
	$words=explode(" ",$search);
    
    $db = Database::getInstance();
    
	//Navegador de páginas
	$sql="SELECT COUNT(*) FROM ".$db->prefix('rd_references').($id_res ? " WHERE id_res='$id_res'" : '');
	$sql1='';
	if ($search){
		foreach ($words as $k){
			//verifica que palabra sea mayor a 2 letras	
			if (strlen($k)<=2) continue;
			$sql1.=($sql1=='' ? ($id_res ? " AND " : " WHERE ") : " OR ")." (title LIKE '%$k%' OR text LIKE '%$k%')";	

		}	
	}
	
	list($num) = $db->fetchRow($db->query($sql.$sql1));
	$page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('notes.php?res='.$id_res.'&amp;page={PAGE_NUM}');
    
	$sql = str_replace("COUNT(*)","*", $sql);
    $sql2=" LIMIT $start,$limit";
	$result=$db->queryF($sql.$sql1.$sql2);
    $notes = array();
	while ($rows=$db->fetchArray($result)){
		$ref= new RDReference();
		$ref->assignVars($rows);
	
		$notes[] = array(
            'id'=>$ref->id(),
            'title'=>$ref->getVar('title'),
            'text'=> substr(strip_tags($ref->getVar('text')), 0, 50).'...'
        );
	
	}
    
    // Event
    $notes = RMEvents::get()->run_event('docs.loading.notes', $notes, $res);
	
    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->add_script('../include/js/admin.js');
    RMTemplate::get()->assign('xoops_pagetitle', sprintf(__('Notes in %s', 'docs'), $res->getVar('title')));
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_head('<script type="text/javascript">
    var rd_select_message = "'.__('You have not selected any note!','docs').'";
    var rd_message = "'.__('Do you really wish to delete selected notes?','docs').'";
    </script>');
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('References','docs'));
	xoops_cp_header();
	
    include RMEvents::get()->run_event('docs.admin.template.notes', RMTemplate::get()->get_template('admin/rd_notes.php','module','docs'));
    
	xoops_cp_footer();

}

/**
* desc Edita Referencias
**/
function rd_edit_note(){
	global $xoopsModule;
    
    RMTemplate::get()->assign('xoops_pagetitle', __('Editing Note','docs'));
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Editing note','docs'));
	xoops_cp_header();

	$id = rmc_server_var($_GET, 'id', 0);
    $id_res = rmc_server_var($_GET, 'res', 0);
    
	if ($id_res<=0){
		redirectMsg('./notes.php', __('Resource not specified','docs'),1);
		die();
	}
    
    $res = new RDResource($id_res);
    if($res->isNew()){
        redirectMsg('./notes.php', __('Specified resource does not exists!','docs'),1);
        die();
    }
    
    if ($id<=0){
        redirectMsg('./notes.php?res='.$res, __('Note not specified','docs'),1);
        die();
    }
    
	//Verifica que referencia exista
	$ref= new RDReference($id);
	if ($ref->isNew()){
		redirectMsg('./notes.php?res='.$res, __('Specified note does not exists!','docs'),1);
		die();
	}

	//Formulario
	$form=new RMForm(__('Edit Note','docs'),'frmref','notes.php');
	
	$form->addElement(new RMFormText(__('Title','docs'),'title',50,150,$ref->getVar('title')),true);
	$form->addElement(new RMFormTextArea(__('Content','docs'),'reference',5,50,$ref->getVar('text','e')),true);

	$buttons=new RMFormButtonGroup();
	$buttons->addButton('sbt',__('Save Changes','docs'),'submit');
	$buttons->addButton('cancel',__('Cancel','docs'),'button','onclick="window.location=\'notes.php?res='.$res.'\';"');

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action','saveedit'));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('res',$id_res));
	
	$form->display();
	
	xoops_cp_footer();

}

/**
* @desc Almacena información perteneciente a referencia
**/
function rd_save_note($edit = 0){
	global $xoopsSecurity;

    $id = 0;
    
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$ruta="?res=$res";

	if (!$xoopsSecurity->validateToken()){
		redirectMsg('./notes.php'.$ruta, __('Session token expired!','docs'),1);
		die();
	}
    
    if ($edit){
	    //Verifica que referencia sea válida
	    if ($id<=0){
		    redirectMsg('./notes.php'.$ruta, __('Note id not specified!','docs'),1);
		    die();
	    }

	    //Verifica que referencia exista
	    $ref= new RDReference($id);
	    if ($ref->isNew()){
		    redirectMsg('./notes.php'.$ruta, __('Specified note does not exists!','docs'),1);
		    die();
	    }
    } else {
        
        $ref = new RDReference();
        
    }
	
    $db = Database::getInstance();
	//Comprobar si el título de la referencia en esa publicación existe
	$sql="SELECT COUNT(*) FROM ".$db->prefix('rd_references')." WHERE title='$title' AND id_res='$res' AND id_ref<>'$id'";
	list($num)=$db->fetchRow($db->queryF($sql));
	if ($num>0){
		redirectMsg('./notes.php'.$ruta, __('Already exists a note with same title','docs'),1);
		die();
	}

	$ref->setVar('title',$title);
	$ref->setVar('text',$reference);
    $ref->setVar('id_res', $res);

	if ($ref->save()){
		redirectMsg('./notes.php?action=locate&res='.$res.'&id='.$ref->id(), __('Note saved successfully!','docs'),0);
		die();
	}else{
		redirectMsg('./notes.php?res='.$res, __('Note could not be saved!','docs').'<br />'.$ref->errors(),1);
		die();
	}	

}

/**
* @desc Elimina referencias
**/
function rd_delete_notes(){
	global $xoopsModule, $xoopsSecurity;

	$ids = rmc_server_var($_POST, 'ids', array());
	$page = rmc_server_var($_POST, 'page', 1);
	$id_res = rmc_server_var($_POST, 'res', 0);
	$search = rmc_server_var($_POST, 'search', '');

	$ruta="?res=$id_res&search=$search&page=$page";

	//Comprueba si se proporciono una referencia 
	if (!is_array($ids)){
		redirectMsg('./notes.php'.$ruta, __('No note selected!','docs'),1);
		die();	
	}
	
		
	if (!$xoopsSecurity->check()){
	    redirectMsg('./notes.php'.$ruta, __('Session token expired','docs'), 1);
		die();
	}

	$errors='';
    
    $db = Database::getInstance();
    $sql = "DELETE FROM ".$db->prefix("rd_references")." WHERE id_ref IN(".implode(",",$ids).")";
    if(!$db->queryF($sql)){
        redirectMsg($ruta, __('Notes could not be deleted!','docs').'<br />'.$db->error(), 1);
    } else {
        redirectMsg($ruta, __('Notes deleted successfully!','docs'), 0);
    }

}

function rd_locate_note(){
    
    $res = rmc_server_var($_GET, 'res', 0);
    $id = rmc_server_var($_GET, 'id', 0);
    
    if ($res<=0 || $id<=0){
        header('location: notes.php');
        die();
    }
    
    $limit = 15;
    
    $db = Database::getInstance();
    $sql = "SELECT id_ref FROM ".$db->prefix("rd_references")." WHERE id_res='$res'";
    $result = $db->query($sql);
    if ($db->getRowsNum($result)<=$limit){
        header('location: notes.php?res='.$res);
        die();
    }
    
    $counter = 0;
    while(list($note) = $db->fetchRow($result)){
        $counter++;
        if ($note==$id){
            $page = ceil($counter/$limit);
            break;
        }
    }
    
    header('location: notes.php?res='.$res.'&page='.$page.'#note'.$id);
    die();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'edit':
		rd_edit_note();
	    break;
	case 'save':
		rd_save_note();
	    break;
    case 'saveedit':
        rd_save_note(1);
        break;
	case 'delete':
		rd_delete_notes();
	    break;
    case 'locate':
        rd_locate_note();
        break;
	default:
		rd_show_notes();
        break;
}
