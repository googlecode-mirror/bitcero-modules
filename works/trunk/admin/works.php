<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('PW_LOCATION','works');
include 'header.php';

/**
* @desc Barra de Menus
*/
function optionsBar(){
	global $tpl;
	
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

	$tpl->append('xoopsOptions', array('link' => './works.php', 'title' => _AS_PW_WORKS, 'icon' => '../images/works16.png'));
	$tpl->append('xoopsOptions', array('link' => './works.php?op=new&pag='.$page.'&limit='.$limit, 'title' => _AS_PW_NEWWORK, 'icon' => '../images/add.png'));
}


/**
* @desc Visualiza todos los trabajos existentes
**/ 
function showWorks(){
	global $tpl, $adminTemplate, $xoopsModule, $db, $util;


	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works');
	
	list($num)=$db->fetchRow($db->query($sql));
	
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;

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
	
    	
    	if ($tpages > 1) {
    	    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit, 0);
    	    $tpl->assign('worksNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_PW_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación



	$sql = "SELECT * FROM ".$db->prefix('pw_works');
	$sql.= " ORDER BY id_work DESC LIMIT $start, $limit"; 
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$work = new PWWork();
		$work->assignVars($row);

		//Obtenemos la categoría
		$cat = new PWCategory($work->category());

		//Obtenemos el cliente
		$user = new PWClient($work->client());

		$tpl->append('works',array('id'=>$work->id(),'title'=>$work->title(),'catego'=>$cat->name(),
		'client'=>$user->name(),'start'=>formatTimeStamp($work->start(),'s'),'mark'=>$work->mark(),'public'=>$work->isPublic()));

	}

	$tpl->assign('lang_exist',_AS_PW_EXIST);
	$tpl->assign('lang_id',_AS_PW_ID);
	$tpl->assign('lang_title',_AS_PW_TITLE);
	$tpl->assign('lang_catego',_AS_PW_CATEGO);
	$tpl->assign('lang_client',_AS_PW_CLIENT);
	$tpl->assign('lang_start',_AS_PW_START);
	$tpl->assign('lang_mark',_AS_PW_MARK);
	$tpl->assign('lang_public',_AS_PW_PUBLIC);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_delete',_DELETE);
	$tpl->assign('lang_pub',_AS_PW_PUBLISH);
	$tpl->assign('lang_nopub',_AS_PW_NOPUBLIC);
	$tpl->assign('token',$util->getTokenHTML());
	$tpl->assign('lang_mk',_AS_PW_MRK);
	$tpl->assign('lang_nomark',_AS_PW_NOMARK);
	$tpl->assign('lang_images',_AS_PW_IMAGE);
	$tpl->assign('lang_submit',_SUBMIT);

	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; "._AS_PW_WORKLOC);
	$adminTemplate = "admin/pw_works.html";
	xoops_cp_header();
	xoops_cp_footer();
}


/**
* @desc Formulario de creacion/edición de trabajos
**/
function formWorks($edit = 0){

	global $xoopsModule, $db, $xoopsModuleConfig;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

	$ruta = "pag=$page&limit=$limit";

	
	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./works.php'>"._AS_PW_WORKLOC."</a> &raquo; ".($edit ? _AS_PW_WORKEDIT : _AS_PW_NEWWORK));
	xoops_cp_header();

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	if ($edit){

		//Verificamos que el trabajo sea válido
		if ($id<=0){
			redirectMsg('./works.php?'.$ruta,_AS_PW_ERRWORKVALID,1);
			die();
		}

		//Verificamos que el trabajo exista
		$work = new PWWork($id);
		if ($work->isNew()){
			redirectMsg('./works.php?'.$ruta,_AS_PW_ERRWORKEXIST,1);
			die();

		}	
	}


	$form = new RMForm($edit ? _AS_PW_WORKEDIT : _AS_PW_NEWWORK,'frmwork','works.php');
	$form->setExtra("enctype='multipart/form-data'");

	$form->addElement(new RMText(_AS_PW_FTITLE,'title',50,200,$edit ? $work->title() : ''),true);
	$form->addElement(new RMTextArea(_AS_PW_FSHORT,'short',4,50,$edit ? $work->descShort() : ''),true);
	$form->addElement(new RMEditor(_AS_PW_FDESC,'desc','90%','350px',$edit ? $work->desc('e') : ''),true);
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
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	$ele = new RMSelect(_AS_PW_FCATEGO,'catego');
	$ele->addOption(0,_SELECT);
	//Categorias existentes
	$result = $db->query("SELECT * FROM ".$db->prefix("pw_categos")." ORDER BY `order`");
	while ($rows = $db->fetchArray($result)){
		$ele->addOption($rows['id_cat'],$rows['name'],$edit ? ($work->category()==$rows['id_cat'] ? 1 : 0) : '');

	}
	$form->addElement($ele,true,'noselect:0');

	//Clientes Existentes
	$ele = new RMSelect(_AS_PW_FCLIENT,'client');
	$ele->addOption(0,_SELECT);
	$sql = "SELECT * FROM ".$db->prefix('pw_clients');
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$ele->addOption($row['id_client'],$row['name'],$edit ? ($work->client()==$row['id_client'] ? 1 : 0) : '');
	}

	$form->addElement($ele,true,'noselect:0');

	$form->addElement(new RMTextArea(_AS_PW_FCOMMENT,'comment',4,50,$edit ? $work->comment() : ''));
	$form->addElement(new RMText(_AS_PW_NAMESITE,'site',50,150,$edit ? $work->nameSite() : ''));
	$form->addElement(new RMText(_AS_PW_FURL,'url',50,255,$edit ? $work->url() : ''));
	$form->addElement(new RMDate(_AS_PW_FSTART,'start',$edit ? $work->start() : time()));
	$form->addElement(new RMText(_AS_PW_FPERIOD,'period',50,255,$edit ? $work->period() : ''));
	$form->addElement(new RMText(_AS_PW_FCOST,'cost',10,20,$edit ? $work->cost() : 0));
	$form->addElement(new RMYesno(_AS_PW_FMARK,'mark',$edit ? $work->mark() : 0));
	$form->addElement(new RMYesno(_AS_PW_FPUBLIC,'public',$edit ? $work->isPublic() : 1));

	$form->addElement(new RMFile(_AS_PW_FIMAGE,'image',45, $xoopsModuleConfig['size_image']*1024));
	if ($edit){
		$form->addElement(new RMLabel(_AS_PW_FIMGACT,"<img src='".XOOPS_UPLOAD_URL."/works/ths/".$work->image()."' />"));
	}

	$ele = new RMSelect(_AS_PW_RATING,'rating');
	for ($i=0; $i<=10; $i++){
		$ele->addOption($i,$i,$edit ? ($work->rating()==$i ? 1 : 0) : 0);
	}

	$form->addElement($ele,true);
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('page',$page));
	$form->addElement(new RMHidden('limit',$limit));

	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'works.php?'.$ruta.'\';"');
	$form->addElement($ele);


	$form->display();

	xoops_cp_footer();

}

/**
* @desc Almacena la información del trabajo en la base de datos
**/
function saveWorks($edit = 0){

	global $util, $xoopsModuleConfig;
	
	$query = '';
	foreach ($_POST as $k => $v){
		$$k = $v;
		if ($k == 'EXM_TOKEN_REQUEST' || $k=='op' || $k=='sbt') continue;
		$query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
	}

	if (!$util->validateToken()){
		redirectMsg('./works.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$query, _AS_PW_ERRSESSID, 1);
		die();
	}

	if ($edit){
		//Verificamos que el trabajo sea válido
		if ($id<=0){
			redirectMsg('./works.php?'.$query,_AS_PW_ERRWORKVALID,1);
			die();
		}

		//Verificamos que el trabajo exista
		$work = new PWWork($id);
		if ($work->isNew()){
			redirectMsg('./works.php?'.$query,_AS_PW_ERRWORKEXIST,1);
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
		redirectMsg("works.php?".$query, _AS_PW_EXISTS, 1);
		die();
	}

	$work->setTitle($title);
	$work->set_title_id(RMUtils::sweetstring($title));
	$work->setDescShort(substr(stripcslashes($short),0,255));
	$work->setDesc($desc);
	$work->setCategory($catego);
	$work->setClient($client);
	$work->setComment($comment);
	$work->setNameSite($site);
	$work->setUrl($url);
	$date=rmsoft_read_date('start');
	$work->setStart($date);
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
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
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

	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $xoopsModuleConfig['size_image']*1024);//tamaño

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
		$redim = new RMImageControl($fullpath, $fullpath);
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
				$redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
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
		redirectMsg('./works.php?'.$ruta,_AS_PW_DBERROR.$work->errors(),1);
		die();
	}else{	
		redirectMsg('./works.php?'.$ruta,_AS_PW_DBOK,0);
		die();

	}
}

/**
* @desc Elimina de la base de datos la información del trabajo
**/
function deleteWorks(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	
	$ruta = "pag=$page&limit=$limit";

	//Verificamos que nos hayan proporcionado un trabajo para eliminar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./works.php?'.$ruta,_AS_PW_ERRNOTWORKDEL,1);
		die();
	}
	
	if (!is_array($ids)){
		$wk = new PWWork($ids);
		$ids = array($ids);
	}


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./works.php?'.$ruta,_AS_PW_ERRSESSID, 1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			//Verificamos si el trabajo es válido
			if ($k<=0){
				$errors.=sprintf(_AS_PW_NOTVALID, $k);
				continue;
			}

			//Verificamos si el trabajo existe
			$work = new PWWork($k);
			if ($work->isNew()){
				$errors.=sprintf(_AS_PW_NOTEXIST, $k);
				continue;
			}
		
			if (!$work->delete()){
				$errors.=sprintf(_AS_PW_NOTDELETE,$k);
			}
		}
	
		if ($errors!=''){
			redirectMsg('./works.php?'.$ruta,_AS_PW_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./works.php?'.$ruta,_AS_PW_DBOK,0);
			die();
		}


	}else{
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
		<a href='./clients.php'>"._AS_PW_WORKLOC."</a> &raquo;"._AS_PW_DELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['pag'] = $page;
		$hiddens['limit'] = $limit;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'works.php?'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'works.php',($wk ? sprintf(_AS_PW_DELETECONF, $wk->title()) : _AS_PW_DELETECONFS). '<br /><br />' ._AS_PW_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
}

/**
* @desc Publica o no los trabajos
**/
function publicWorks($pub = 0){
	global $util;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

	$ruta = "pag=$page&limit=$limit";

	//Verificamos que nos hayan proporcionado un trabajo para publicar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./works.php?'.$ruta,_AS_PW_ERRNOTWORKPUB,1);
		die();
	}
	
	if (!$util->validateToken()){
		redirectMsg('./works.php?'.$ruta,_AS_PW_ERRSESSID, 1);
		die();
	}
	$errors = '';
	foreach ($ids as $k){
		//Verificamos si el trabajo es válido
		if ($k<=0){
			$errors.=sprintf(_AS_PW_NOTVALID, $k);
			continue;
		}

		//Verificamos si el trabajo existe
		$work = new PWWork($k);
		if ($work->isNew()){
			$errors.=sprintf(_AS_PW_NOTEXIST, $k);
			continue;
		}

		$work->setPublic($pub);
		
		if (!$work->save()){
			$errors.=sprintf(_AS_PW_NOTUPDATE,$k);
		}
	}
	
	if ($errors!=''){
		redirectMsg('./works.php?'.$ruta,_AS_PW_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./works.php?'.$ruta,_AS_PW_DBOK,0);
		die();
	}

	
}

/**
* @desc Destaca o no los trabajos
**/
function markWorks($mark = 0){
	global $util;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

	$ruta = "pag=$page&limit=$limit";

	//Verificamos que nos hayan proporcionado un trabajo para destacar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./works.php?'.$ruta,_AS_PW_ERRNOTWORKUP,1);
		die();
	}
	
	if (!$util->validateToken()){
		redirectMsg('./works.php?'.$ruta,_AS_PW_ERRSESSID, 1);
		die();
	}
	$errors = '';
	foreach ($ids as $k){
		//Verificamos si el trabajo es válido
		if ($k<=0){
			$errors.=sprintf(_AS_PW_NOTVALID, $k);
			continue;
		}

		//Verificamos si el trabajo existe
		$work = new PWWork($k);
		if ($work->isNew()){
			$errors.=sprintf(_AS_PW_NOTEXIST, $k);
			continue;
		}

		$work->setMark($mark);
		
		if (!$work->save()){
			$errors.=sprintf(_AS_PW_NOTUPDATE,$k);
		}
	}
	
	if ($errors!=''){
		redirectMsg('./works.php?'.$ruta,_AS_PW_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./works.php?'.$ruta,_AS_PW_DBOK,0);
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
