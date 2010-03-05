<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','sets');
include 'header.php';

/**
* @desc Visualiza todos los albums
**/
function showAlbums(){
	global $tpl, $xoopsModule, $mc, $xoopsSecurity;
	
	define('RMSUBLOCATION','sets');
	$db = Database::getInstance();

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	
	$query = "search=$search&page=$page&limit=$limit&sort=$sort&mode=$mode";
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_sets');
	$sql1 = '';
	$words = array();
	if ($search){
		
		//Separamos en palabras
		$words = explode(" ",$search);
		foreach ($words as $k){
			$k = trim($k);
			
			if (strlen($k)<=2){
				continue;
			}
			
			$sql1.= $sql1=='' ? " WHERE (title LIKE '%$k%' OR uname LIKE '%$k%')" : " OR (title LIKE '%$k%' OR uname LIKE '%$k%')";			

		}
	}


	list($num)=$db->fetchRow($db->query($sql.$sql1));
	
	if ($page > 0){ $page -= 1; }
    $start = $page * $limit;
    $tpages = (int)($num / $limit);
    if($num % $limit > 0) $tpages++;
    $pactual = $page + 1;
    if ($pactual>$tpages){
    	$rest = $pactual - $tpages;
    	$pactual = $pactual - $rest + 1;
    	$start = ($pactual - 1) * $limit;
    }
	
	$nav = new RMPageNav($num, $limit, $page, 5);

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	//Fin de barra de navegación

	
	$sql = str_replace('COUNT(*)','*',$sql);
	
	$sql2 = $sort ? " ORDER BY $sort ".($mode ? "DESC" : "ASC ") : '';
	$sql2.= " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);
	
	$sets = array();
	
	while($rows = $db->fetchArray($result)){
		
		foreach ($words as $k){
			$rows['title'] = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['title']);
			$rows['uname'] = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['uname']);
		}
		
		$set = new GSSet();
		$set->assignVars($rows);

		$sets[] = array(
			'id'=>$set->id(),
			'title'=>$set->title(),
			'owner'=>$set->uname(),
			'public'=>$set->isPublic(),
			'date'=>formatTimeStamp($set->date(),'c'),
			'pics'=>$set->pics(),
			'url'=>$set->url()
		);

	}

	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Albums managemenet','admin_galleries'));
	RMTemplate::get()->assign('xoops_pagetitle','Albums management');
	
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_head("<script type='text/javascript'>\nvar delete_warning='".__('Do you really wish to delete selected albums?','admin_galleries')."';\n</script>");
	RMTemplate::get()->add_script('../include/js/gsscripts.php?file=sets');
	
	$cHead = '<link href="'.XOOPS_URL.'/modules/galleries/styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	include RMTemplate::get()->get_template('admin/gs_albums.php','module','galleries');
	xoops_cp_footer();
	
}


/**
* @desc Formulario de creación/edición de albums
**/
function formAlbums($edit = 0){

	global $xoopsModule, $xoopsUser;
	
	define('RMSUBLOCATION','newalbum');
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	

	if($edit){
		//Verificamos que el album sea válido
		if ($id<=0){
			redirectMsg('./sets.php?'.$ruta,__('Please provide a valid ID!','admin_galleries'),1);
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirectMsg('./sets.php?'.$ruta,__('Specified album does not exists!','admin_galleries'),1);
			die();
		}
	}


	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./sets.php'>".__('Albums management','admin_galleries')."</a> &raquo; ".($edit ? __('Edit album','admin_galleries') : __('New album','admin_galleries')));
	xoops_cp_header();
	

	$form = new RMForm($edit ? __('Edit album','admin_galleries') : __('New Album','admin_galleries'), 'frmsets','sets.php');
	$form->addElement(new RMFormText(__('Album title','admin_galleries'),'title',50,100,$edit ? $set->title() : ''),true);

	$ele = new RMFormSelect(__('Privacy level','admin_galleries'),'public');
	$ele->addOption(0,__('Private','admin_galleries'),$edit ? ($set->isPublic()==0 ? 1 : 0) : 0);
	$ele->addOption(1,__('Public for friends','admin_galleries'),$edit ? ($set->isPublic()==1 ? 1 : 0) : 0);
	$ele->addOption(2,__('Public','admin_galleries'),$edit ? ($set->isPublic()==2 ? 1 : 0) : 0);

	$form->addElement($ele,true);

	$form->addElement(new RMFormUser(__('Owner','admin_galleries'),'owner',0,$edit ? array($set->owner()) : array($xoopsUser->uid()),30));
	
	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));	
	$form->addElement(new RMFormHidden('page',$page));	
	$form->addElement(new RMFormHidden('limit',$limit));	
	$form->addElement(new RMFormHidden('sort',$sort));	
	$form->addElement(new RMFormHidden('mode',$mode));	
	$form->addElement(new RMFormHidden('search',$search));	

	$buttons = new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Save Changes!','admin_galleries') : __('Create Album!','admin_galleries'),'submit');
	$buttons->addButton('cancel',__('Cancel','admin_galleries'),'button','onclick="window.location=\'sets.php?'.$ruta.'\'"');

	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}

/**
* @desc Almacena en la base de datos todos los datos del album
**/
function saveAlbums($edit = 0){

	global $xoopsSecurity, $xoopsUser;
	
	$db = Database::getInstance();

	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!isset($owner) || $owner<=0) $owner = $xoopsUser->uid();

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	$op = $op='save'?'new':'edit';

	if (!$xoopsSecurity->check()){
		redirectMsg('./sets.php?op='.$op.'&'.$ruta,__('Session token expired!','admin_galleries'),1);
		die();
	}


	if ($edit){
		//Verificamos que el album sea válido
		if ($id<=0){
			redirectMsg('./sets.php?'.$ruta,__('Please provide a valid ID!','admin_galleries'),1);
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirectMsg('./sets.php?'.$ruta,__('Specified album does not exists!','admin_galleries'),1);
			die();
		}

		$sql ="SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." WHERE title='$title' AND owner=$owner AND id_set<>".$set->id();
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./sets.php?op=edit&id='.$set->id().'&'.$ruta,__('You have another album with same name. Please specify a different name.','admin_galleries'),1);
			die();
		}
		
	}else{

		//Verificamos que el titulo del album no exista
		$sql ="SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." WHERE title='$title' AND owner=$owner";
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./sets.php?'.$op.'&'.$ruta,__('You have another album with same name. Please specify a different name.','admin_galleries'),1);
			die();
		}

		$set = new GSSet();
	}

	$set->setTitle($title);
	$set->setPublic($public);
	$set->setDate(time());
	$set->setOwner($owner);
	$xu = new XoopsUser($owner); 
	$set->setUname($xu->uname());

	$new = $set->isNew();

	if (!$set->save()){
		redirectMsg('./sets.php?'.$ruta,__('Database could not be updated!','admin_galleries').'<br />'.$set->errors(),1);
		die();
	}else{
		//Incrementamos el número de albums del usuario
		if ($new){
			$user = new GSUser($set->owner(), 1);
			$user->addSet();
		}

		redirectMsg('./sets.php?'.$ruta,__('Database updated successfully!','admin_galleries'),0);
		die();
	}

}

/**
* @desc Elimina de la base de datos el(s) album(s) especicado(s)
**/
function deleteAlbums(){

	global $xoopsSecurity, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	
	//Verificamos si nos proporcionaron al menos un album para eliminar
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./sets.php?'.$ruta,__('Select one album at least!','admin_galleries'),1);
		die();
	}

	if (!is_array($ids)){
		$album = new GSSet($ids);
		$ids = array($ids);
	}
	

	if (!$xoopsSecurity->check()){
		redirectMsg('./sets.php?'.$ruta,__('Session token expired!','admin_galleries'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
			
			//Verificamos si el album es válido
			if($k<=0){
				$errors .= sprintf(__('ID "%u" is not valid','admin_galleries'), $k);
				continue;			
			}

			//Verificamos si el album existe
			$set = new GSSet($k);
			if ($set->isNew()){
				$errors .= sprintf(__('Album "%u" does not exists','admin_galleries'), $k);
				continue;
			}	

			if(!$set->delete()){
				$errors .= sprintf(__('Album "%u" could not be deleted','admin_galleries'), $k);
			}else{
				//Decrementamos el número de albumes del usuario
				$user = new GSUser($set->owner(),1);
				$user->quitSet();
			}
		}

		if($erros!=''){
			redirectMsg('./sets.php?'.$ruta,__('Errors ocurred while trying to delete albums','admin_galleries').'<br />'.$errors,1);
			die();
		}else{
			redirectMsg('./sets.php?'.$ruta,__('Database updated successfully!','admin_galleries'),0);
			die();
		}
		
}


/**
* @desc Permite hacer publico o no un album
**/
function publicAlbums($pub = 0){

	global $xoopsSecurity;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	
	//Verificamos si nos proporcionaron al menos un album para publicar
	if (!is_array($ids)){
		redirectMsg('./sets.php?'.$ruta,__('Select one album at least!','admin_galleries'),1);
		die();
	}

	if (!$xoopsSecurity->check()){
		redirectMsg('./sets.php?'.$ruta,__('Session token expired!','admin_galleries'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si el album es válido
		if($k<=0){
			$errors .= sprintf(__('"%u" is not a valid ID','admin_galleries'), $k);
			continue;			
		}
		//Verificamos si el album existe
		$set = new GSSet($k);
		if ($set->isNew()){
			$errors .= sprintf(__('Album "%u" does not exists','admin_galleries'), $k);
			continue;
		}	

		$set->setPublic($pub);
		if(!$set->save()){
			$errors .= sprintf(__('Error ocurred while trying to update album "%u"!','admin_galleries'), $k);
		}
	}

	if($erros!=''){
		redirectMsg('./sets.php?'.$ruta,__('Errors ocurred while trying to update albums','admin_galleries').'<br />'.$errors,1);
		die();
	}else{
		redirectMsg('./sets.php?'.$ruta,__('Database updated successfully!','admin_galleries'),0);
		die();
	}
		

	

	


}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formAlbums();
	break;
	case 'edit':
		formAlbums(1);
	break;
	case 'save':
		saveAlbums();
	break;
	case 'saveedit':
		saveAlbums(1);
	break;
	case 'delete':
		deleteAlbums();
	break;
	case 'public':
		publicAlbums(2);
	break;
	case 'private':
		publicAlbums(0);
	break;
	case 'privatef':
		publicAlbums(1);
	break;
	default:
		showAlbums();
		break;
}