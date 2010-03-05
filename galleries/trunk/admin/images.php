<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','images');
include 'header.php';

/**
* @desc Visualiza todas las imágenes existentes
**/
function showImages(){

	global $xoopsModule, $tpl, $xoopsConfig, $xoopsSecurity;
	
	$db = Database::getInstance();

	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
	$limit = $limit<=0 ? 10 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? $_REQUEST['owner'] : 0;
	$mindate = isset($_REQUEST['mindate']) ? $_REQUEST['mindate'] : '';
	$maxdate = isset($_REQUEST['maxdate']) ? $_REQUEST['maxdate'] : '';
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 1;
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_images');
	$sql1 = '';
	$words = array();
	if ($search!=''){
		
		//Separamos en palabras
		$words = explode(" ",$search);
		foreach ($words as $k){
			$k = trim($k);
			
			if (strlen($k)<=2){
				continue;
			}
			
			$sql1.= $sql1=='' ? " WHERE (title LIKE '%$k%')" : " OR (title LIKE '%$k%')";			

		}
	}
	
	if ($owner>0){
		$sql1 .= $search!='' ? " AND owner='$owner'" : " WHERE owner='$owner'";
	}
	
	if ($mindate!=''){
		$maxdate = $maxdate!='' ? $maxdate : time();
		$sql1 .= $search!='' || $owner>0 ? " AND " : " WHERE ";
		$sql1 .= "(created>='$mindate' AND created<='$maxdate')";
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
	
    	
    	if ($tpages > 1) {
    	    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&search='.$search.'&owner='.$owner.'&mindate='.$mindate.'&maxdate='.$maxdate.'&sort='.$sort.'&mode='.$mode, 0);
    	    $tpl->assign('imgsNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	
	//Fin de barra de navegación

	$sql = "SELECT * FROM ".$db->prefix('gs_images');
	$sql2 = " ORDER BY $sort ".($mode ? "DESC" : "ASC");
	$sql2.= " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);

	$users = array();
	while($rows = $db->fetchArray($result)){
	
		$title ='';
		foreach ($words as $k){
			$title = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['title']);
		}

		$img = new GSImage();
		$img->assignVars($rows);
		if (!isset($users[$img->owner()])){
			$users[$img->owner()] = new GSUser($img->owner(), 1);
		}

		$link = $users[$img->owner()]->userURL()."img/".$img->id();

		$xu = $users[$img->owner()];
		$tpl->append('images',array('id'=>$img->id(),'title'=>$title ? $title : $img->title(),'desc'=>substr($img->desc(),0,150),
		'image'=>$users[$img->owner()]->filesURL().'/ths/'.$img->image(),'created'=>formatTimeStamp($img->created(),'string'),
		'owner'=>$xu->uname(),'public'=>$img->isPublic(),'link'=>$link));

	}
	
	$form = new RMForm('','frmNav', '');
	$ele = new RMFormUser('','owner', false, $owner>0 ? array($owner) : array(0), 50, null, null, 1);
	$ele->setForm('frmNav');
	$tpl->assign('user_field', $ele->render());
	$ele = new RMFormDate('', 'mindate', $mindate==null ? null : $mindate, 1);
	$tpl->assign('mindate_field', $ele->render());
	$ele = new RMFormDate('', 'maxdate', $maxdate==null ? null : $maxdate, 1);
	$tpl->assign('maxdate_field', $ele->render());

	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_GS_IMGSLOC);
	
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/gs_images.php",'module','galleries');
	xoops_cp_footer();

}


/**
* @desc Formulario de creación/edición de imágenes
**/
function formImages($edit = 0){

	global $xoopsModule, $mc,$xoopsUser, $db;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? $_REQUEST['owner'] : 0;
	$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "pag=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";

	if($edit){
		//Verificamos si la imagen es válida
		if($id<=0){
			redirectMsg('./images.php?'.$ruta,_AS_GS_ERRIMGVALID, 1);
			die();
		}

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if ($img->isNew()){
			redirectMsg('./images.php?'.$ruta,_AS_GS_ERRIMGEXIST, 1);
			die();
		}

	}

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./images.php'>"._AS_GS_IMGSLOC."</a> &raquo; ".($edit ? _AS_GS_EDITIMG : _AS_GS_NEWIMG));
	xoops_cp_header();

	$form = new RMForm($edit ? _AS_GS_EDITIMG : _AS_GS_NEWIMG, 'frmimg','images.php');
	$form->setExtra("enctype='multipart/form-data'");
	
		if (!$edit){
		$ele = new RMFormUserEXM(_AS_GS_USER,'uid',0,($uid ? array($uid) : ($edit ? array($img->owner()) : array($xoopsUser->uid()))),50);
		$ele->onChange("$('op').value='".($edit ? 'edit' : 'new')."'; $('frmimg').submit();");
		$form->addElement($ele);
	}else{
		$xu = new XoopsUser($img->owner());
		$form->addElement(new RMLabel(_AS_GS_USER,$xu->uname()));
		$form->addElement(new RMHidden('uid',$img->owner()));
	}

	$form->addElement(new RMText(_AS_GS_TITLE,'title',50,100,$edit ? $img->title() : ''));
	$form->addElement(new RMFile(_AS_GS_IMAGE,'image',45, $mc['size_image']*1024));
	if ($edit){
		$user = new GSUser($img->owner(),1);
		$url = $user->filesURL();

		$form->addElement(new RMLabel(_AS_GS_IMGACT,"<img src='".$url."/ths/".$img->image()."' />"));
		
	}

	$form->addElement(new RMTextArea(_AS_GS_DESC,'desc',4,50,$edit ? $img->desc() : ''));
	
	$ele = new RMSelect(_AS_GS_PUBLIC,'public');
	$ele->addOption(0,_AS_GS_PRIVATE,$edit ? ($img->isPublic()==0 ? 1 : 0) : 0);
	$ele->addOption(1,_AS_GS_PRIVFRIEND,$edit ? ($img->isPublic()==1 ? 1 : 0) : 0);
	$ele->addOption(2,_AS_GS_PUBLIC,$edit ? ($img->isPublic()==2 ? 1 : 0) : 0);

	$form->addElement($ele,true);



	//Albumes
	if ($edit){
		$albums = $img->sets(false);
		foreach ($albums as $k=>$v){
			$sets[] = $v['id_set'];
		}
	}
	$ele = new RMSelect(_AS_GS_ALBUMS,'albums[]',1,$edit ? $sets : '');
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".($uid ? $uid : ($edit ? $img->owner() : $xoopsUser->uid()))."'";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$ele->addOption($rows['id_set'],$rows['title']);
	}
	$form->addElement($ele);

	//Etiquetas
	$tgs = '';
	if($edit){
		$tags = $img->tags(false, 'tag');
		foreach ($tags as $k => $tag){
			$tgs .= $tgs=='' ? $tag : " ".$tag;
		}
	}
	
	$ele = new RMText(_AS_GS_TAGS,'tags',50,100,$edit ?  $tgs : '');
	$ele->setDescription(_AS_GS_DESCTAGS);

	$form->addElement($ele, true);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	if ($edit)
	$form->addElement(new RMHidden('id',$id));	

	$form->addElement(new RMHidden('page',$page));	
	$form->addElement(new RMHidden('limit',$limit));	
	$form->addElement(new RMHidden('search',$search));
	$form->addElement(new RMHidden('owner',$owner));
	$form->addElement(new RMHidden('sort',$sort));
	$form->addElement(new RMHidden('mode',$mode));

	$buttons = new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'images.php?'.$ruta.'\'"');
	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}


/**
* @desc Formulario de creación de muchas imágenes
**/
function formBulkImages(){
	
	global $mc,$xoopsUser, $tpl, $adminTemplate, $xoopsModule, $db;

	$num = isset($_REQUEST['num']) ? intval($_REQUEST['num']) : 10;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? $_REQUEST['owner'] : 0;
	$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;
	$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : 0;
	$file = isset($_REQUEST['image']) ? $_REQUEST['image'] : 0;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "pag=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";


	for ($i=1; $i<=$num; $i++){
		$tpl->append('fields',array('i'=>$i,'title'=>isset($title[$i]) ? $title[$i] : '','file'=>isset($file[$i]) ? urlencode($file[$i]) : ''));
	}


	//Lista de albumes
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".($uid ? $uid : $xoopsUser->uid())."'";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$tpl->append('sets',array('id'=>$rows['id_set'],'title'=>$rows['title']));
	}


	$form = new RMForm('','frmImgs','images.php');

	$ele = new RMFormUserEXM(_AS_GS_USER,'uid',0,($uid ? array($uid) : array($xoopsUser->uid())),0);
	$ele->setForm('frmImgs');
	$ele->onChange("sendData();");
	
	$tpl->assign('users',$ele->render());
	$tpl->assign('lang_newimg',_AS_GS_NEWIMGS);
	$tpl->assign('lang_title',_AS_GS_TITLE);
	$tpl->assign('lang_img',_AS_GS_IMAGE);
	$tpl->assign('lang_save',_AS_GS_SAVE);
	$tpl->assign('lang_user',_AS_GS_USERASSIGN);
	$tpl->assign('size',$mc['size_image']*1024);
	$tpl->assign('ruta',$ruta);
	$tpl->assign('owner',$owner);
	$tpl->assign('lang_albums',_AS_GS_ALBUMS);
	$tpl->assign('lang_cancel',_CANCEL);
	$tpl->assign('lang_tags',_AS_GS_TAGS);
	$tpl->assign('lang_msg',_AS_GS_MESSAGE);
	$tpl->assign('num',$num);
	$tpl->assign('pag',$page);
	$tpl->assign('limit',$limit);	
	$tpl->assign('search',$search);
	$tpl->assign('sort',$sort);	
	$tpl->assign('mode',$mode);
	
	
	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_GS_IMGSLOC);
	$adminTemplate = "admin/gs_formimages.html";
	xoops_cp_header($form->javascript());
	
	xoops_cp_footer();

}

/**
* @desc almacena la información de la imagen
**/
function saveImages($edit = 0){


	global $mc, $util, $db, $xoopsUser, $db;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "pag=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";

	if (!$util->validateToken()){
		redirectMsg('./images.php?'.$ruta,_AS_GS_SESSINVALID,1);
		die();
	}


	if ($edit){
		//Verificamos si la imagen es válida
		if($id<=0){
			redirectMsg('./images.php?op=edit&id='.$id.'&'.$ruta,_AS_GS_ERRIMGVALID, 1);
			die();
		}

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if ($img->isNew()){
			redirectMsg('./images.php?op=edit&id='.$id.'&'.$ruta,_AS_GS_ERRIMGEXIST, 1);
			die();
		}

	}else{
		$img = new GSImage();
	}
	
	$img->setTitle($title);
	$img->setDesc($desc);
	$img->isNew() ? $img->setCreated(time()) : $img->setModified(time());
	if (!$edit) $img->setOwner($uid);
	$img->setPublic($public);


	//Insertamos las etiquetas
	$tgs = array();
	$tags = explode(" ",$tags);
	foreach ($tags as $k){
		$k = trim($k);

		if (!$k) continue;

		if(strlen($k)<$mc['min_tag'] || strlen($k)>$mc['max_tag']){
			continue;
		}
		
		$tag = new GSTag($k);
		if (!$tag->isNew()){
			$tgs[] = $tag->id();
			continue;
		}

		$tag->setTag(strtolower($k));

		if ($tag->save()){
			$tgs[] = $tag->id();
		}
		
	}
	
	if (!$edit){
		if ($xoopsUser->uid()==$uid){
			$xu =& $xoopsUser;
		} else {
			$xu = new XoopsUser($uid);
		}

		//Verificamos si el usuario se encuentra registrado
		$user = new GSUser($xu->uname());
		if($user->isNew()){
			//Insertamos información del usuario
			$user->setUid($uid);
			$user->setUname($xu->uname());
			$user->setQuota($mc['quota']*1024*1024);
			$user->setDate(time());

			if(!$user->save()){
				redirectMsg('./images.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$ruta,_AS_GS_ERRUSER, 1);
				die();
			}else{
				mkdir($mc['storedir']."/".$user->uname());
				mkdir($mc['storedir']."/".$user->uname()."/ths");
				mkdir($mc['storedir']."/".$user->uname()."/formats");
			}
		}
	} else {
		$user = new GSUser($img->owner(), 1);
	}



	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = $mc['storedir']."/".$user->uname();
	$folderths = $mc['storedir']."/".$user->uname()."/ths";
	if ($edit){
		$filename=$img->image();
	}
	else{
		$filename = '';
	}
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['size_image']*1024);//tamaño

	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){

			redirectMsg('./images.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$ruta,$up->getErrors(), 1);
			die();
		}
					
		if ($edit && $img->image()!=''){
			@unlink($mc['storedir']."/".$user->uname()."/".$img->image());
			@unlink($mc['storedir']."/".$user->uname()."/ths/".$img->image());
			@unlink($mc['storedir']."/originals/".$img->image());
			
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		
		$thSize = $mc['image_ths'];
		$imgSize = $mc['image'];
			
		if ($thSize[0]<=0) $thSize[0] = 100;
		if (!isset($thSize[1]) || $thSize[1]<=0) $thSize[1] = $thSize[0];
		
		if ($imgSize[0]<=0) $imgSize[0] = 500;
		if (!isset($imgSize[1]) || $imgSize[1]<=0) $imgSize[1] = $imgSize[0];
			
		// Almacenamos la imágen original
		if ($mc['saveoriginal']){
			copy($fullpath, $mc['storedir'].'/originals/'.$filename);
		}
		
		// Redimensionamos la imagen
		$redim = new RMImageControl($fullpath, $fullpath);
		switch ($mc['redim_image']){
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
	//Fin de Imagen
	
	$img->setImage($filename);
	
	$new = $img->isNew();
	if(!$img->save()){
		redirectMsg('./images.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$ruta,_AS_GS_DBERROR.$img->errors(), 1);
		die();
	}else{
		$user->addPic();
		$img->setTags($tgs);

		$sets = '';
		$tbl1 = $db->prefix("gs_sets");
		$tbl2 = $db->prefix("gs_setsimages");
		foreach ($albums as $k => $v){
			// Si el album existe no incrementamos el numero de imagenes
			$sets .= $sets=='' ? "$tbl2.id_set<>'$v'" : " AND $tbl2.id_set<>'$v'";
			if ($img->inSet($v)) continue;
			
			$album = new GSSet($v);
			$album->addPic($img->id());
		}
		// Actualizamos los valores de los ignorados
		$sql = "UPDATE $tbl1, $tbl2 SET $tbl1.pics=$tbl1.pics-1 WHERE ($tbl2.id_image='".$img->id()."'".($sets!='' ? ' AND '.$sets : '').") AND $tbl1.id_set=$tbl2.id_set";
		$db->queryF($sql);
		$sets = str_replace($tbl2.'.', '', $sets);
		$sql = "DELETE FROM ".$db->prefix("gs_setsimages")." WHERE id_image='".$img->id()."' ".($sets!='' ? " AND ($sets)" : '');
		$db->queryF($sql);
		
		redirectMsg('./images.php?'.$ruta,_AS_GS_DBOK, 0);
		die();
	}
}

/**
* @desc Almacena la información del grupo de imágenes
**/
function saveBulkImages(){
	global $util, $mc, $xoopsUser;
	
	set_time_limit(0);
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	$ruta = "pag=$page&limit=$limit&search=$search&owner=$uid&sort=$sort&mode=$mode";
	
	if ($xoopsUser->uid()==$uid){
		$xu = $xoopsUser;
	} else {
		$xu = new XoopsUser($uid);
	}
	

	//Verificamos si el usuario se encuentra registrado	
	$user = new GSUser($xu->uname());
	if($user->isNew()){
		//Insertamos información del usuario
		$user->setUid($uid);
		$user->setUname($xu->uname());
		$user->setQuota($mc['quota']*1024*1024);
		$user->setDate(time());

		if(!$user->save()){
			redirectMsg('./images.php?'.$ruta,_AS_GS_ERRUSER."<br />".$user->errors(), 1);
			die();
		}else{
			mkdir($mc['storedir']."/".$user->uname());
			mkdir($mc['storedir']."/".$user->uname()."/ths");
			mkdir($mc['storedir']."/".$user->uname()."/formats");
		}
	} else {
		@mkdir($mc['storedir']."/".$user->uname());
		@mkdir($mc['storedir']."/".$user->uname()."/ths");
		@mkdir($mc['storedir']."/".$user->uname()."/formats");
	}
	
	// Insertamos las etiquetas
	$tgs = explode(" ",$tags);
	/**
	* @desc Almacena los ids de las etiquetas que se asignarán a la imágen
	*/
	$ret = array(); 
	foreach ($tgs as $k){
		$k = trim($k);
		$k = $util->sweetstring($k);
		if ($k=='') continue;
		// Comprobamos que la palabra tenga la longitud permitida
		if(strlen($k)<$mc['min_tag'] || strlen($k)>$mc['max_tag']){
			continue;
		}
		// Creamos la etiqueta
		$tag = new GSTag($k);
		if (!$tag->isNew()){
			// Si ya existe nos saltamos
			$ret[] = $tag->id(); 
			continue;
		}

		$tag->setTag($k);
		if ($tag->save()){
			$ret[] = $tag->id();
		}
	}	

	$errors = '';
	$k = 1;
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = $mc['storedir']."/".$xu->uname();
	$folderths = $mc['storedir']."/".$xu->uname()."/ths";
	
	foreach ($_FILES['image']['name'] as $k => $v){
		if ($v=='') continue;
		$img = new GSImage();
		$img->setTitle($title[$k]);
		$img->setOwner($uid);
		$img->setPublic(2);
		$img->setCreated(time());
		
		//Imagen
		$filename = '';
			
		$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['size_image']*1024);//tamaño
		
		if ($up->fetchMedia('image',$k)){

			if (!$up->upload()){
				$errors .= sprintf(_AS_GS_ERRIMG, $title[$k], $up->getErrors());
				continue;
			}
					
			$filename = $up->getSavedFileName();
			$fullpath = $up->getSavedDestination();
			
			$thSize = $mc['image_ths'];
			$imgSize = $mc['image'];
			
			if ($thSize[0]<=0) $thSize[0] = 100;
			if (!isset($thSize[1]) || $thSize[1]<=0) $thSize[1] = $thSize[0];
			
			if ($imgSize[0]<=0) $imgSize[0] = 500;
			if (!isset($imgSize[1]) || $imgSize[1]<=0) $imgSize[1] = $imgSize[0];
			
			// Almacenamos la imágen original
			if ($mc['saveoriginal']){
				copy($fullpath, $mc['storedir'].'/originals/'.$filename);
			}
			
			// Redimensionamos la imagen
			$redim = new RMImageControl($fullpath, $fullpath);
			switch ($mc['redim_image']){
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
		
		//Fin de Imagen
		$img->setImage($filename);
		
		if ($up->getErrors()==''){
			if (!$img->save()){
				$errors .= sprintf(_AS_GS_ERRSAVEIMG, $v)." (".$img->errors().")";
			} else {
				$user->addPic();
				$img->setTags($ret);
			
				//Albumes
				if (!empty($albums)){
					foreach ($albums as $k => $v){
						$album = new GSSet($v);
						$album->addPic($img->id());
					}
				}
			}
		}else{
			$errors .= $up->getErrors();
		}

		
		++$k;
	}

	if($errors!=''){
		redirectMsg('./images.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./images.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}

}

/**
* @desc Elimina la(s) imagen(es) especificada(s)
**/
function deleteImages(){

	global $util, $xoopsModule, $db;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? intval($_REQUEST['owner']) : 0;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "pag=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";

	
	//Verificamos si nos proporcionaron al menos un imagen para eliminar
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./images.php?'.$ruta,_AS_GS_ERRIMGDELETE,1);
		die();
	}

	if (!is_array($ids)){
		$im = new GSImage($ids);
		$ids = array($ids);
	}
	

	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./images.php?'.$ruta,_AS_GS_SESSINVALID,1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){

			//Verificamos si la imagen es válida
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si la imagen existe
			$img = new GSImage($k);
			if ($img->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}	

			$sets = $img->sets();
			if(!$img->delete()){
				$errors .= sprintf(_AS_GS_ERRDELETE, $k);
			}else{
	
				//Decrementamos el número de imágenes de los albumes
				foreach ($sets as $k => $set){
					$set->quitPic($img->id());
				}
			}
		}

		if($erros!=''){
			redirectMsg('./images.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./images.php?'.$ruta,_AS_GS_DBOK,0);
			die();
		}
		


	}else{

		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./images.php'>"._AS_GS_IMGSLOC."</a> &raquo; "._AS_GS_LOCDELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $page;
		$hiddens['search'] = $search;
		$hiddens['owner'] = $owner;
		$hiddens['sort'] = $sort;
		$hiddens['mode'] = $mode;			

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'images.php?'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'images.php',(isset($im) ? sprintf(_AS_GS_DELETECONF, $im->title()) : _AS_GS_DELETECONFS). '<br /><br />' ._AS_GS_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}


}

/**
* @desc Publica o no publica las imágenes especificadas
**/
function publicImages($pub = 0){

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? intval($_REQUEST['owner']) : 0;	
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "pag=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";


	//Verificamos si nos proporcionaron al menos una imagen
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./images.php?'.$ruta,_AS_GS_ERRIMGPUB,1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
	
		//Verificamos si la imagen es válida
		if($k<=0){
			$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
			continue;			
		}
		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
			$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
			continue;
		}	
		
		$img->setPublic($pub);
		if(!$img->save()){
			$errors .= sprintf(_AS_GS_ERRSAVE, $k);
		}
	}

	if($erros!=''){
		redirectMsg('./images.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./images.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}

}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formImages();
	break;
	case 'news':
		formBulkImages();
	break;
	case 'edit':
		formImages(1);
	break;
	case 'save':
		saveImages();
	break;
	case 'saveedit':
		saveImages(1);
	break;
	case 'savebulk':
		saveBulkImages();
	break;
	case 'delete':
		deleteImages();
	break;
	case 'public':
		publicImages(2);
	break;
	case 'private':
		publicImages(0);
	break;
	case 'privatef':
		publicImages(1);
	break;
	default:
		showImages();
		break;
}
