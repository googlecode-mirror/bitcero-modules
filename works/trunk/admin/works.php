<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','works');
include 'header.php';

/**
* @desc Visualiza todos los trabajos existentes
**/ 
function showWorks(){
	global $xoopsModule, $xoopsSecurity;
    
    $db = Database::getInstance();
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $limit = rmc_server_var($_REQUEST,'limit', 15);
    $show = rmc_server_var($_REQUEST,'show', '');
    
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works');
	if ($show=='public'){
        $sql .= " WHERE public=1";
    } elseif($show=='hidden'){
        $sql .= " WHERE public=0";
    }
    
	list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('works.php?page={PAGE_NUM}');

	$sql = "SELECT * FROM ".$db->prefix('pw_works');
    if ($show=='public'){
        $sql .= " WHERE public=1";
    } elseif($show=='hidden'){
        $sql .= " WHERE public=0";
    }
	$sql.= " ORDER BY id_work DESC LIMIT $start, $limit"; 
	$result = $db->query($sql);
    $works = array(); //Container
    
	while ($row = $db->fetchArray($result)){
		$work = new PWWork();
		$work->assignVars($row);

		//Obtenemos la categoría
		$cat = new PWCategory($work->category());

		//Obtenemos el cliente
		$user = new PWClient($work->client());

		$works[] = array(
            'id'=>$work->id(),
            'title'=>$work->title(),
            'catego'=>$cat->name(),
		    'client'=>$user->name(),
            'start'=>formatTimeStamp($work->start(),'s'),
            'mark'=>$work->mark(),
            'public'=>$work->isPublic(),
            'description'=>$work->descShort()
        );

	}

	PWFunctions::toolbar();
    RMTemplate::get()->add_style('admin.css', 'works');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('../include/js/admin_works.js');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='".__('Do you really want to delete selected works?','admin_works')."';\n
        var pw_select_message = '".__('You must select some work before to execute this action!','admin_works')."';</script>");
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Works','admin_works'));
	xoops_cp_header();
    
    include RMTemplate::get()->get_template("admin/pw_works.php", 'module', 'works');
    
	xoops_cp_footer();
}


/**
* @desc Formulario de creacion/edición de trabajos
**/
function formWorks($edit = 0){

	global $xoopsModule, $xoopsModuleConfig;
	$page = rmc_server_var($_REQUEST, 'page', 1);

	$ruta = "page=$page";

	
	PWFunctions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./works.php'>".__('Works Management','admin_works')."</a> &raquo; ".($edit ? __('Editing work','admin_works'): __('New work','admin_works')));
	xoops_cp_header();

	$id = rmc_server_var($_REQUEST, 'id', 0);

	if ($edit){

		//Verificamos que el trabajo sea válido
		if ($id<=0){
			redirectMsg('./works.php?'.$ruta,__('Provided Work ID is not valid!','admin_works'),1);
			die();
		}

		//Verificamos que el trabajo exista
		$work = new PWWork($id);
		if ($work->isNew()){
			redirectMsg('./works.php?'.$ruta,__('Specified work does not exists!','admin_works'),1);
			die();

		}	
	}


	$form = new RMForm($edit ? __('Edit Work','admin_works') : __('Create Work','admin_works'),'frmwork','works.php');
	$form->setExtra("enctype='multipart/form-data'");

	$form->addElement(new RMFormText(__('Title','admin_works'),'title',50,200,$edit ? $work->title() : ''),true);
	$form->addElement(new RMFormTextArea(__('Short description','admin_works'),'short',4,50,$edit ? $work->descShort() : ''),true);
	$form->addElement(new RMFormEditor(__('Description','admin_works'),'desc','90%','200px',$edit ? $work->desc('e') : ''),true);
	if ($edit){
		$dohtml = $work->getVar('dohtml');
		$doxcode = $work->getVar('doxcode');
		$dobr = $work->getVar('dobr');
		$dosmiley = $work->getVar('dosmiley');
		$doimage = $work->getVar('doimage');
	} else {
		$dohtml = 1;
		$doxcode = 0;
		$dobr = 0;
		$dosmiley = 0;
		$doimage = 0;
	}
	$form->addElement(new RMFormTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	$ele = new RMFormSelect(__('Category','admin_works'),'catego');
	$ele->addOption(0,__('Select...','admin_works'));
	//Categorias existentes
    $db = Database::getInstance();
	$result = $db->query("SELECT * FROM ".$db->prefix("pw_categos")." ORDER BY `order`");
	while ($rows = $db->fetchArray($result)){
		$ele->addOption($rows['id_cat'],$rows['name'],$edit ? ($work->category()==$rows['id_cat'] ? 1 : 0) : '');

	}
	$form->addElement($ele,true,'noselect:0');

	//Clientes Existentes
	$ele = new RMFormSelect(__('Customer','admin_works'),'client');
	$ele->addOption(0,__('Select...','admin_works'));
	$sql = "SELECT * FROM ".$db->prefix('pw_clients');
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$ele->addOption($row['id_client'],$row['name'],$edit ? ($work->client()==$row['id_client'] ? 1 : 0) : '');
	}

	$form->addElement($ele,true,'noselect:0');

	$form->addElement(new RMFormTextArea(__('Customer comment','admin_works'),'comment',4,50,$edit ? $work->comment() : ''));
	$form->addElement(new RMFormText(__('Web site','admin_works'),'site',50,150,$edit ? $work->nameSite() : ''));
	$form->addElement(new RMFormText(__('Site URL','admin_works'),'url',50,255,$edit ? $work->url() : ''));
	$form->addElement(new RMFormDate(__('Start date','admin_works'),'start',$edit ? $work->start() : time()));
	$form->addElement(new RMFormText(__('Long time','admin_works'),'period',50,255,$edit ? $work->period() : ''));
	$form->addElement(new RMFormText(__('Monetary cost','admin_works'),'cost',10,20,$edit ? $work->cost() : 0));
	$form->addElement(new RMFormYesno(__('Featured','admin_works'),'mark',$edit ? $work->mark() : 0));
	$form->addElement(new RMFormYesno(__('Visible','admin_works'),'public',$edit ? $work->isPublic() : 1));

	$form->addElement(new RMFormFile(__('Work image','admin_works'),'image',45, $xoopsModuleConfig['size_image']*1024));
	if ($edit){
		$form->addElement(new RMFormLabel(__('Current image','admin_works'),"<img src='".XOOPS_UPLOAD_URL."/works/ths/".$work->image()."' />"));
	}

	$ele = new RMFormSelect(__('Rating','admin_works'),'rating');
	for ($i=0; $i<=10; $i++){
		$ele->addOption($i,$i,$edit ? ($work->rating()==$i ? 1 : 0) : 0);
	}

	$form->addElement($ele,true);
	
	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('page',$page));

	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Save Changes','admin_works') : __('Create Work','admin_works'), 'submit');
	$ele->addButton('cancel', __('Cancel','admin_works'), 'button', 'onclick="window.location=\'works.php?'.$ruta.'\';"');
	$form->addElement($ele);

	$form->display();

	xoops_cp_footer();

}

/**
* @desc Almacena la información del trabajo en la base de datos
**/
function saveWorks($edit = 0){

	global $xoopsSecurity, $xoopsModuleConfig;
	
	$query = '';
	foreach ($_POST as $k => $v){
		$$k = $v;
		if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='op' || $k=='sbt') continue;
		$query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
	}

	if (!$xoopsSecurity->check()){
		redirectMsg('./works.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','admin_works'), 1);
		die();
	}

	if ($edit){
		//Verificamos que el trabajo sea válido
		if ($id<=0){
			redirectMsg('./works.php?'.$query,__('Work ID not valid!','admin_works'),1);
			die();
		}

		//Verificamos que el trabajo exista
		$work = new PWWork($id);
		if ($work->isNew()){
			redirectMsg('./works.php?'.$query,__('Specified work does not exists!','admin_works'),1);
			die();

		}
	}else{
		$work = new PWWork();
	}
	
	$db = Database::getInstance();
	// Check if work exists already
	if ($edit){
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("pw_works")." WHERE title='$title' and id_work<>'$id'";
	} else {
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("pw_works")." WHERE title='$title'";
	}
	list($num)=$db->fetchRow($db->query($sql));
	if ($num>0){
		redirectMsg("works.php?".$query, __('A work with same name already exists!','admin_works'), 1);
		die();
	}

	$work->setTitle($title);
	$work->set_title_id(TextCleaner::sweetstring($title));
	$work->setDescShort(substr(stripcslashes($short),0,255));
	$work->setDesc($desc);
	$work->setCategory($catego);
	$work->setClient($client);
	$work->setComment($comment);
	$work->setNameSite($site);
	$work->setUrl($url);
	$work->setStart($start);
	$work->setPeriod($period);
	$work->setCost($cost);
	$work->setMark($mark);
	$work->setPublic($public);
	$work->setRating($rating);
	$work->isNew() ? $work->setCreated(time()) : '';
	$work->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$work->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$work->setVar('dobr', isset($dobr) ? 1 : 0);
	$work->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$work->setVar('doimage', isset($doimage) ? 1 : 0);
	
	
	//Imagen
    include_once RMCPATH.'/class/uploader.php';
	$folder = XOOPS_UPLOAD_PATH.'/works';
	$folderths = XOOPS_UPLOAD_PATH.'/works/ths';
	if ($edit){
		$image = $work->image();
		$filename=$work->image();
	}
	else{
		$filename = '';
	}

	//Obtenemos el tamaño de la imagen
	$thSize = $xoopsModuleConfig['image_main_ths'];
	$imgSize = $xoopsModuleConfig['image_main'];

	$up = new RMFileUploader($folder, $xoopsModuleConfig['size_image']*1024, array('jpg','png','gif'));

	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			redirectMsg('./works.php?id='.$id.'&op='.($edit ? 'edit' : 'new'),$up->getErrors(), 1);
			die();
		}
					
		if ($edit && $work->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/works/'.$work->image());
			@unlink(XOOPS_UPLOAD_PATH.'/works/ths/'.$work->image());
			
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageResizer($fullpath, $fullpath);
		switch ($xoopsModuleConfig['redim_image']){
			
			case 0:
				//Recortar miniatura
				$redim->resizeWidth($imgSize[0]);
				$redim->setTargetFile($folderths."/$filename");				
				$redim->resizeAndCrop($thSize[0],$thSize[1]);
				break;	
			case 1: 
				//Recortar imagen grande
				$redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($thSize[0]);			
				break;
			case 2:
				//Recortar ambas
				$redim->resizeAndCrop($imgSize[0],$imgSize[1]);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeAndCrop($thSize[0],$thSize[1]);
				break;
			case 3:
				//Redimensionar
				$redim->resizeWidth($imgSize[0]);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($thSize[0]);
				break;				
		}


	}

	
	$work->setImage($filename);
	
	if (!$work->save()){
		redirectMsg('./works.php?'.$query,__('Errors ocurred while trying to update database!','admin_works').$work->errors(),1);
		die();
	}else{	
		redirectMsg('./works.php?op=edit&id='.$work->id(),__('Database updated successfully!','admin_works'),0);
		die();

	}
}

/**
* @desc Elimina de la base de datos la información del trabajo
**/
function deleteWorks(){

	global $xoopsSecurity, $xoopsModule;

	$ids = rmc_server_var($_POST, 'ids', 0);
	$page = rmc_server_var($_POST, 'page', 1);
    $show = rmc_server_var($_POST, 'show', 1);
	
	$ruta = "pag=$page&show=$show";

	//Verificamos que nos hayan proporcionado un trabajo para eliminar
	if (!is_array($ids)){
		redirectMsg('./works.php?'.$ruta, __('You must select a work at least!','admin_works'),1);
		die();
	}

     if (!$xoopsSecurity->check()){
	    redirectMsg('./works.php?'.$ruta, __('Session token expired!','admin_works'), 1);
		die();
	 }

	 $errors = '';
	 foreach ($ids as $k){
	    //Verificamos si el trabajo es válido
		if ($k<=0){
		    $errors.=sprintf(__('Work ID "%s" is not valid!','admin_works'), $k);
			continue;
		}

		//Verificamos si el trabajo existe
		$work = new PWWork($k);
		if ($work->isNew()){
		    $errors.=sprintf(__('Work with ID "%s" does not exists!','admin_works'), $k);
			continue;
		}
		
		if (!$work->delete()){
		    $errors.=sprintf(__('Work "%s" could not be deleted!','admin_works'),$work->title());
		}
	 }
	
	if ($errors!=''){
	    redirectMsg('./works.php?'.$ruta,__('Errors ocurred while trying to delete works','admin_works').'<br />'.$errors,1);
		die();
	}else{
	    redirectMsg('./works.php?'.$ruta,__('Works deleted successfully!','admin_works'),0);
		die();
	}

}

/**
* @desc Publica o no los trabajos
**/
function publicWorks($pub = 0){
	global $xoopsSecurity;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$show = rmc_server_var($_POST, 'show', 1);

	$ruta = "page=$page&show=$show";

	//Verificamos que nos hayan proporcionado un trabajo para publicar
	if (!is_array($ids)){
		redirectMsg('./works.php?'.$ruta, __('You must specify a work ID','admin_works'),1);
		die();
	}
	
	if (!$xoopsSecurity->check()){
		redirectMsg('./works.php?'.$ruta, __('Session token expired!','admin_works'), 1);
		die();
	}
	$errors = '';
	foreach ($ids as $k){
		//Verificamos si el trabajo es válido
		if ($k<=0){
			$errors.=sprintf(__('Work ID "%s" is not valid!', 'admin_works'), $k);
			continue;
		}

		//Verificamos si el trabajo existe
		$work = new PWWork($k);
		if ($work->isNew()){
			$errors.=sprintf(__('Work with ID "%s" does not exists!','admin_works'), $k);
			continue;
		}

		$work->setPublic($pub);
		
		if (!$work->save()){
			$errors.=sprintf(__('Work "%s" could not be saved!','admin_works'),$k);
		}
	}
	
	if ($errors!=''){
		redirectMsg('./works.php?'.$ruta,__('Errors ocurred while trying to update works').'<br />'.$errors,1);
		die();
	}else{
		redirectMsg('./works.php?'.$ruta,__('Works updated successfully!','admin_works'),0);
		die();
	}

	
}

/**
* @desc Destaca o no los trabajos
**/
function markWorks($mark = 0){
	global $xoopsSecurity;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$show = rmc_server_var($_POST, 'show', 1);

	$ruta = "page=$page&show=$show";

	//Verificamos que nos hayan proporcionado un trabajo para destacar
	if (!is_array($ids)){
        redirectMsg('./works.php?'.$ruta, __('You must specify a work ID','admin_works'),1);
        die();
    }
    
    if (!$xoopsSecurity->check()){
        redirectMsg('./works.php?'.$ruta, __('Session token expired!','admin_works'), 1);
        die();
    }
	$errors = '';
	foreach ($ids as $k){
		//Verificamos si el trabajo es válido
		if ($k<=0){
            $errors.=sprintf(__('Work ID "%s" is not valid!', 'admin_works'), $k);
            continue;
        }

        //Verificamos si el trabajo existe
        $work = new PWWork($k);
        if ($work->isNew()){
            $errors.=sprintf(__('Work with ID "%s" does not exists!','admin_works'), $k);
            continue;
        }

		$work->setMark($mark);
		
		if (!$work->save()){
            $errors.=sprintf(__('Work "%s" could not be saved!','admin_works'),$k);
        }
	}
	
	if ($errors!=''){
		redirectMsg('./works.php?'.$ruta,__('Errors ocurred while trying to update works').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./works.php?'.$ruta,__('Works updated successfully!','admin_works'),0);
        die();
	}

	
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch($op){
	case 'new':
		formWorks();
		break;
	case 'edit':
		formWorks(1);
		break;
	case 'save':
		saveWorks();
		break;
	case 'saveedit':
		saveWorks(1);
		break;
	case 'delete':
		deleteWorks();
		break;
	case 'public':
		publicWorks(1);
		break;
	case 'nopublic':
		publicWorks();
		break;
	case 'mark':
		markWorks(1);
		break;
	case 'nomark';
		markWorks(0);
		break;
	default:
		showWorks();
}
?>
