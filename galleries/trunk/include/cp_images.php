<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Visualiza todas las imágenes pertenecientes del usuario
**/
function showImages(){
	global $xoopsOption, $tpl, $db, $xoopsUser, $xoopsModuleConfig,$set, $xoopsConfig, $cp;
	
	$mc =& $xoopsModuleConfig;

	$xoopsOption['template_main'] = 'gs_panel.html';
	include 'header.php';

	GSFunctions::makeHeader();

	if($set){
		//Verificamos si el album es válido
		if($set<=0){
			redirect_header(GSFunctions::get_url().($mc['urlmode'] ? 'cpanel/sets' : 'cpanel.php?s=cpanel/sets'),1, __('Specified album is not valid!','galleries'));
			die();
		}
		//Verificamos que el album exista
		$album = new GSSet($set);
		if($album->isNew()){
			redirect_header(GSFunctions::get_url().($mc['urlmode'] ? 'cpanel/sets' : 'cpanel.php?s=cpanel/sets'),1, __('Specified album does not exists!','galleries'));
			die();
		}

	}
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_images')." a ";
	$sql.= $set ? " INNER JOIN ".$db->prefix('gs_setsimages')." b ON (a.id_image=b.id_image AND b.id_set='".$set."' AND a.owner='".$xoopsUser->uid()."') " : " WHERE a.owner=".$xoopsUser->uid();
    
    global $page;
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
	$limit = $limit<=0 ? 10 : $limit;
	$user = new GSUser($xoopsUser->uid(),1);

	list($num)=$db->fetchRow($db->query($sql));
		
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
            $nav = new RMPageNav($num, $limit, $pactual, 5);
            $nav->target_url(GSFunctions::get_url().($mc['urlmode'] ? 'cp/'.$cp.'/pag/{PAGE_NUM}/' : '?cp='.$cp.'&amp;pag={PAGE_NUM}'));
            $tpl->assign('picsNavPage', $nav->render(false));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(__('Showing images %u to %u of %u'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación
	
	$sql = "SELECT a.* FROM ".$db->prefix('gs_images')." a ";
	$sql.= $set ? " INNER JOIN ".$db->prefix('gs_setsimages')." b ON (a.id_image=b.id_image AND b.id_set='".$set."' AND a.owner='".$xoopsUser->uid()."') GROUP BY a.id_image" : " WHERE a.owner=".$xoopsUser->uid();
	$sql.= " ORDER BY created DESC LIMIT $start,$limit";
	$result = $db->query($sql);
	
    $tpl->assign('images', GSFunctions::process_image_data($result));

	$tpl->assign('lang_exists',$set ? sprintf(__('Existing images for user "%s" in album "%s"', 'galleries'),$xoopsUser->uname(),$album->title()) : sprintf(__('Existing images for user "%s"','galleries'),$xoopsUser->uname()));
	$tpl->assign('lang_id', __('ID','galleries'));
	$tpl->assign('lang_title',__('Title','galleries'));
	$tpl->assign('lang_image',__('Image','galleries'));
	$tpl->assign('lang_public', __('Public','galleries'));
	$tpl->assign('lang_created',__('Created','galleries'));
	$tpl->assign('lang_edit', __('Edit','galleries'));
	$tpl->assign('lang_del',__('Delete','galleries'));
	$tpl->assign('lang_options',__('Options','galleries'));	
	$tpl->assign('lang_set', __('Add to Album...','galleries'));
	$tpl->assign('lang_confirm',_MS_GS_CONFIRM);
	$tpl->assign('lang_confirms',_MS_GS_CONFIRMS);
	$tpl->assign('lang_save', __('Save Changes','galleries'));
	$tpl->assign('lang_changename',__('Change name','galleries'));
	$tpl->assign('lang_changedesc',__('Change description','galleries'));
	$tpl->assign('lang_adddesc',__('Add Description','galleries'));

	//Links de menu
	$tpl->assign('link_sets',GS_URL.($mc['urlmode'] ? "/cpanel/sets" : ""));
    
    RMTemplate::get()->add_local_script('panel.js', 'galleries');
    RMTemplate::get()->add_local_script('jquery.checkboxes.js', 'rmcommon', 'include');
	RMTemplate::get()->add_style('panel.css', 'galleries');
	
	createLinks();

	include 'footer.php';

}


/**
* @desc Formulario de imágenes
**/
function formImages($edit = 0){

	global $xoopsOption, $tpl, $db, $xoopsUser, $xoopsModuleConfig, $xoopsUser, $xoopsConfig;
	
	$mc =& $xoopsModuleConfig;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
	$referer = isset($_REQUEST['referer']) ? $_REQUEST['referer'] : '';
	
	if(!$referer){
		$referer = XOOPS_URL.'/modules/galleries/cpanel.php?pag='.$page;
	}

	if($edit){
		//Verificamos si la imagen es válida
		if($id<=0){
			redirect_header($referer,1, __('Sepecified images is not valid!','galleries'));
			die();
		}	

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if($img->isNew()){
			redirect_header($referer,1, __('Sepecified image does not exists!','galleries'));
			die();
		}

		//Verificamos que el usuario se el dueño de la imagen
		if($img->owner()!=$xoopsUser->uid()){
			redirect_header($referer,1, __('You are not authorized!','galleries'));
			die();
		}	

	}
		

	$xoopsOption['template_main'] = 'gs_formpics.html';
	include 'header.php';

	GSFunctions::makeHeader();

	$form = new RMForm($edit ? __('Edit Image','galleries') : __('New Image','galleries'), 'frmimg','cpanel.php');
	$form->setExtra("enctype='multipart/form-data'");

	$form->addElement(new RMFormText(__('Image title','galleries'),'title',50,100,$edit ? $img->title(false) : ''));

	if ($edit){
		$user = new GSUser($img->owner(),1);
		$url = $user->filesURL();

		$form->addElement(new RMFormLabel(__('Current picture','galleries'),"<img src='".$url."/ths/".$img->image()."' />"));
	}else{
		$form->addElement(new RMFormFile(__('Image file','galleries'),'image',45, $mc['size_image']*1024));
	}


	$form->addElement(new RMFormTextArea(__('Description','galleries'),'desc',4,50,$edit ? $img->desc() : ''));
	$ele = new RMFormSelect(__('Access level','galleries'),'public');
	$ele->addOption(0,__('Private','galleries'),$edit ? ($img->isPublic()==0 ? 1 : 0) : 0);
	$ele->addOption(1,__('Public for friends','galleries'),$edit ? ($img->isPublic()==1 ? 1 : 0) : 0);
	$ele->addOption(2,__('Public for all','galleries'),$edit ? ($img->isPublic()==2 ? 1 : 0) : 0);

	$form->addElement($ele,true);

	//Albumes
	if ($edit){
		$albums = $img->sets(false);
		foreach ($albums as $k=>$v){
			$sets[] = $v['id_set'];
		}
	}
	$ele = new RMFormSelect(__('Albums','galleries'),'albums[]',1,$edit ? $sets : '');
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".$xoopsUser->uid()."'";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$ele->addOption($rows['id_set'],$rows['title']);
	}
	$form->addElement($ele);
	
	$ele = new RMFormText(__('Tags','galleries'),'tags',50,255,$edit ?  implode(", ", $img->tags(false, 'tag')) : '');
	$ele->setDescription(__('Separe each tag with commas.','galleries'));

	$form->addElement($ele, true);

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));	
	$form->addElement(new RMFormHidden('page',$page));	
	$form->addElement(new RMFormHidden('referer',$referer));
	
	$buttons = new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Save Changes','galleries') : __('Create Image','galleries'),'submit');
	$buttons->addButton('cancel',__('Cancel','galleries'),'button','onclick="window.location=\''.$referer.'\'"');
	$form->addElement($buttons);
	

	$tpl->assign('form_pics',$form->render());
	createLinks();
	
	RMTemplate::get()->add_style('panel.css', 'galleries');
	
	include 'footer.php';
}


/**
* @desc Almacena la información de la imagen
**/
function saveImages($edit = 0){
	
	global $xoopsUser,$xoopsModuleConfig, $xoopsConfig, $db;

	$mc =& $xoopsModuleConfig;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	if ($edit){
		if($id<=0){
            redirect_header($referer,1, __('Sepecified images is not valid!','galleries'));
            die();
        }    

        //Verificamos si la imagen existe
        $img = new GSImage($id);
        if($img->isNew()){
            redirect_header($referer,1, __('Sepecified image does not exists!','galleries'));
            die();
        }

        //Verificamos que el usuario se el dueño de la imagen
        if($img->owner()!=$xoopsUser->uid()){
            redirect_header($referer,1, __('You are not authorized!','galleries'));
            die();
        }    	

	}else{
		$img = new GSImage();
	}

	$img->setTitle($title);
	$img->setDesc($desc);
	$img->isNew() ? $img->setCreated(time()) : $img->setModified(time());
	if (!$edit) $img->setOwner($xoopsUser->uid());
	$img->setPublic($public);

	
	//Insertamos las etiquetas
	$tgs = array();
	$tags = explode(",",$tags);
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
		//Verificamos si el usuario se encuentra registrado
		$user = new GSUser($xoopUser->uname());
		if($user->isNew()){
			//Insertamos información del usuario
			$user->setUid($xoopsUser->uid());
			$user->setUname($xoopsUser->uname());
			$user->setQuota($mc['quota']*1024*1024);
			$user->setDate(time());

			if(!$user->save()){
				redirect_header('./cpanel.php?op='.($edit ? 'edit&id='.$id : 'new').'&pag='.$page,1,_MS_GS_ERRUSER);
				die();
			}else{
				mkdir($mc['storedir']."/".$user->uname());
				mkdir($mc['storedir']."/".$user->uname()."/ths");
				mkdir($mc['storedir']."/".$user->uname()."/formats");
			}
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
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['size_image']*1024);

	if ($up->fetchMedia('image')){
	
		if (!$up->upload()){
			redirect_header('./cpanel.php?op='.($edit ? 'edit&id='.$id : 'new'),1,$up->getErrors());
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
	}
	
	$new = $img->isNew();
	if(!$img->save()){
		redirect_header($referer,1, __('Errores ocurred while trying to update database!','galleries').$img->errors());
		die();
	}else{
		$new ? $user->addPic() : '';
		$img->setTags($tgs);

		$sets = '';
        $db = Database::getInstance();
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

		redirect_header($referer,1, __('Database updated successfully!','galleries'));
		die();
	}

}


/**
* @desc Elimina las imágenes especificadas
**/
function deleteImages(){
	global $util, $xoopsModule, $db;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
	$referer = isset($_REQUEST['referer']) ? base64_decode($_REQUEST['referer']) : '';

	if(!$referer){
		$referer = XOOPS_URL.'/modules/galleries/cpanel.php?pag='.$page;
	}

	
	//Verificamos si nos proporcionaron al menos un imagen para eliminar
	if (!is_array($ids) && $ids<=0){
		redirect_header($referer,2,_MS_GS_ERRIMGDELETE);
		die();
	}

	if (!is_array($ids)){
		$ids = array($ids);
	}
	
	$errors = '';
	foreach ($ids as $k){

		//Verificamos si la imagen es válida
		if($k<=0){
			$errors .= sprintf(_MS_GS_ERRNOTVALID, $k);
			continue;			
		}

		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
			$errors .= sprintf(_MS_GS_ERRNOTEXIST, $k);
			continue;
		}	

		$sets = $img->sets();
		if(!$img->delete()){
			$errors .= sprintf(_MS_GS_ERRDELETE, $k);
		}else{

			//Decrementamos el número de imágenes de los albumes
			foreach ($sets as $k => $set){
				$set->quitPic($img->id());
			}
		}
	}

	if($errors!=''){
		redirect_header($referer,2,_MS_GS_DBERRORS.$errors);
		die();
	}else{
		redirect_header($referer,1,_MS_GS_DBOK);
		die();
	}
		
}

/**
* @desc Almacena el título y descripción de las imágenes
**/
function saveAll(){

	$names = isset($_REQUEST['names']) ? $_REQUEST['names'] : null;
	$desc = isset($_REQUEST['descs']) ? $_REQUEST['descs'] : null;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';

	$errors = '';
	foreach ($names as $k => $v){
		
		//Verificamos si la imagen es válida
		if($k<=0){
			$errors .= sprintf(_MS_GS_ERRNOTVALID, $k);
			continue;			
		}

		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
			$errors .= sprintf(_MS_GS_ERRNOTEXIST, $k);
			continue;
		}	

		$band = false;		
		if ($v!=$img->title()){
			$img->setTitle($v);
			$band = true;
		}
		if ($desc[$k]!=$img->desc()){
			$img->setDesc($desc[$k]);
			$band = true;
		}
		
		if ($band){

			$img->setModified(time());
			
			if(!$img->save()){
				$errors .= sprintf(_MS_GS_ERRSAVE, $k);
			}

		}
	
	}

	if($errors!=''){
		redirect_header('./cpanel.php?pag='.$page,2,_MS_GS_DBERRORS.$errors);
		die();
	}else{
		redirect_header('./cpanel.php?pag='.$page,2,_MS_GS_DBOK);
		die();
	}



}


/**
* @desc Formulario de albumes
**/
function formSets(){

	global $xoopsUser, $db, $xoopsConfig, $xoopsOption, $tpl, $ids;
	
	$page = rmc_server_var($_REQUEST, 'pag', 1);
  	$referer = rmc_server_var($_REQUEST, 'referer', '');
    
	if(!$referer){
		$referer = XOOPS_URL.'/modules/galleries/cpanel.php?pag='.$page;
	}

	$xoopsOption['template_main'] = 'gs_formaddsets.html';
	include 'header.php';

	GSFunctions::makeHeader();
	
	//Verificamos si nos proporcionaron al menos un imagen para actualizar
	if (!is_array($ids) && $ids<=0){
		redirect_header('./cpanel.php',2,__('You must select one image at least!','galleries'));
		die();
	}

	if (!is_array($ids)){
		$ids = array($ids);
	}
	

	$form = new RMForm(__('Add images to an album','galleries'),'frmset','cpanel.php');
	
	//Obtenemos los albumes del usuario
	$ele = new RMFormCheck(__('Albums','galleries'));
    $ele->setDescription(__('Select the albums where you want to assign the selected images.','galleries'));
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".$xoopsUser->uid()."'";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$ele->addOption($rows['title'],'albums[]',$rows['id_set']);
	}
	
	$form->addElement($ele,true);

	$form->addElement(new RMFormHidden('op','savesets'));
	foreach ($ids as $k=>$v){
		$form->addElement(new RMFormHidden('ids['.$k.']',$v));	
	}
	$form->addElement(new RMFormHidden('page',$page));	
	$form->addElement(new RMFormHidden('referer',$referer));
	
	$buttons = new RMFormButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\''.$referer.'\'"');
	$form->addElement($buttons);
	
	$tpl->assign('form_sets',$form->render());
	
	include 'footer.php';
}


/**
* @desc Almacena la asignación de albumes a las imágenes
**/
function saveSets(){
	global $db, $xoopsUser, $xoopsConfig;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	
	$errors = '';
	foreach ($ids as $k){

		//Verificamos si la imagen es válida
		if($k<=0){
			$errors .= sprintf(__('Image with id %u is not valid!','galleries'), $k);
			continue;			
		}

		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
			$errors .= sprintf(__('Image with id %u does not exists!','galleries'), $k);
			continue;
		}	
	
		if ($img->owner()!=$xoopsUser->uid()){
			$errors = sprintf(__('You don\'t have authorization!','galleries'), $k);
			continue;
		}

		$db = Database::getInstance();
		$sets = '';
		$tbl1 = $db->prefix("gs_sets");
		$tbl2 = $db->prefix("gs_setsimages");
		foreach ($albums as $k => $v){
			// Si el album existe no incrementamos el numero de imagenes
			if ($img->inSet($v)) continue;
			
			$album = new GSSet($v);
			$album->addPic($img->id());
		}
		// Actualizamos los valores de los ignorados
		$sql = "UPDATE $tbl1, $tbl2 SET $tbl1.pics=$tbl1.pics-1 WHERE ($tbl2.id_image='".$img->id()."'".($sets!='' ? ' AND '.$sets : '').") AND $tbl1.id_set=$tbl2.id_set";
		$db->queryF($sql);
		
	}

	if(!$referer){
		$referer = GSFunctions::get_url();
	}

	if($errors!=''){
		redirect_header($referer,2,__('Errors ocurred while trying to update images!','galleries').$errors);
		die();
	}else{
		redirect_header($referer,2, __('Images updated successfully!','galleries'));
		die();
	}
}

?>
