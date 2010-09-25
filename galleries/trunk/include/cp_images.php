<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

/**
* @desc Visualiza todas las imágenes pertenecientes del usuario
**/
function showImages(){
	global $xoopsOption, $tpl, $db, $exmUser, $xoopsModuleConfig,$set;
	
	$mc =& $xoopsModuleConfig;

	$xoopsOption['template_main'] = 'gs_panel.html';
	include 'header.php';

	GSFunctions::makeHeader();

	if($set){
		//Verificamos si el album es válido
		if($set<=0){
			redirect_header(GS_URL.($mc['urlmode'] ? '/cpanel/sets' : '/cpanel.php?s=cpanel/sets'),1,_MS_GS_ERRSETVALID);
			die();
		}
		//Verificamos que el album exista
		$album = new GSSet($set);
		if($album->isNew()){
			redirect_header(GS_URL.($mc['urlmode'] ? '/cpanel/sets' : '/cpanel.php?s=cpanel/sets'),1,_MS_GS_ERRSETEXIST);
			die();
		}


	}
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_images')." a ";
	$sql.= $set ? " INNER JOIN ".$db->prefix('gs_setsimages')." b ON (a.id_image=b.id_image AND b.id_set='".$set."' AND a.owner='".$exmUser->uid()."') " : " WHERE a.owner=".$exmUser->uid();

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
	$limit = $limit<=0 ? 10 : $limit;
	$user = new GSUser($exmUser->uid(),1);

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
	      $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit, 0);
    	      $tpl->assign('picsNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación
	
	$sql = "SELECT a.* FROM ".$db->prefix('gs_images')." a ";
	$sql.= $set ? " INNER JOIN ".$db->prefix('gs_setsimages')." b ON (a.id_image=b.id_image AND b.id_set='".$set."' AND a.owner='".$exmUser->uid()."') GROUP BY a.id_image" : " WHERE a.owner=".$exmUser->uid();
	$sql.= " ORDER BY created DESC LIMIT $start,$limit";
	$result = $db->query($sql);
	$ulink = $user->userURL();
	while ($rows = $db->fetchArray($result)){
		
		$img = new GSImage();
		$img->assignVars($rows);
		
		//Obtenemos la imagen
		$urlimg = $user->filesURL().'/ths/'.$img->image();
		$link = $ulink.'img/'.$img->id().'/';

		$tpl->append('images',array('id'=>$img->id(),'title'=>$img->title(false),'desc'=>$img->desc(),
		'image'=>$urlimg,'owner'=>$exmUser->uid(),
		'uname'=>$exmUser->uname(),'public'=>$img->isPublic(),'created'=>formatTimeStamp($img->created(),'s'),
		'link'=>$link));

	}

	$tpl->assign('lang_exists',$set ? sprintf(_MS_GS_EXISTSSET,$exmUser->uname(),$album->title()) : sprintf(_MS_GS_EXISTS,$exmUser->uname()));
	$tpl->assign('lang_id',_MS_GS_ID);
	$tpl->assign('lang_title',_MS_GS_TITLE);
	$tpl->assign('lang_image',_MS_GS_IMAGE);
	$tpl->assign('lang_public',_MS_GS_PUBLIC);
	$tpl->assign('lang_created',_MS_GS_CREATED);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_options',_OPTIONS);	
	$tpl->assign('lang_set',_MS_GS_ADDSET);
	$tpl->assign('lang_confirm',_MS_GS_CONFIRM);
	$tpl->assign('lang_confirms',_MS_GS_CONFIRMS);
	$tpl->assign('lang_save',_MS_GS_SAVE);
	$tpl->assign('lang_changename',_MS_GS_CHANGENAME);
	$tpl->assign('lang_changedesc',_MS_GS_CHANGEDESC);
	$tpl->assign('lang_adddesc',_MS_GS_ADDDESC);

	//Links de menu
	$tpl->assign('link_sets',GS_URL.($mc['urlmode'] ? "/cpanel/sets" : ""));

	$xmh.= "
	<script type='text/javascript'>
	function hideName(id){
	    var strong = $('strongs['+id+']');
	    var nombre = $('names['+id+']');
	    var imagen = $('imgnames['+id+']');
	    strong.style.visibility = 'hidden';
	    strong.style.display = 'none';
	    imagen.style.display = 'none';
	    imagen.style.display = 'hidden';
	    nombre.style.display = 'block';
	    nombre.focus();
	}
	function hideDesc(id){
	    var span = $('spans['+id+']');
	    var desc = $('descs['+id+']');
	    span.style.visibility = 'hidden';
	    span.style.display = 'none';
	    desc.style.display = 'block';
	    desc.focus();
	}
	</script>";

	$xmh.= '<link rel="stylesheet" type="text/css" media="screen" href="'.GS_URL.'/styles/panel.css" />';
	
	createLinks();

	include 'footer.php';

}


/**
* @desc Formulario de imágenes
**/
function formImages($edit = 0){

	global $xoopsOption, $tpl, $db, $exmUser, $xoopsModuleConfig, $exmUser, $xmh;
	
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
			redirect_header($referer,1,_MS_GS_ERRIMAGE);
			die();
		}	

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if($img->isNew()){
			redirect_header($referer,1,_MS_GS_ERRIMAGEEXIST);
			die();
		}

		//Verificamos que el usuario se el dueño de la imagen
		if($img->owner()!=$exmUser->uid()){
			redirect_header($referer,1,_MS_GS_ERROWNER);
			die();
		}	

	}
		

	$xoopsOption['template_main'] = 'gs_formpics.html';
	include 'header.php';

	GSFunctions::makeHeader();

	$form = new RMForm($edit ? _MS_GS_EDIT : _MS_GS_NEW, 'frmimg','cpanel.php');
	$form->setExtra("enctype='multipart/form-data'");

	$form->addElement(new RMText(_MS_GS_TITLE,'title',50,100,$edit ? $img->title(false) : ''));

	if ($edit){
		$user = new GSUser($img->owner(),1);
		$url = $user->filesURL();

		$form->addElement(new RMLabel(_MS_GS_IMGACT,"<img src='".$url."/ths/".$img->image()."' />"));
	}else{
		$form->addElement(new RMFile(_MS_GS_IMAGE,'image',45, $mc['size_image']*1024));
	}


	$form->addElement(new RMTextArea(_MS_GS_DESC,'desc',4,50,$edit ? $img->desc() : ''));
	$ele = new RMSelect(_MS_GS_PUBLIC,'public');
	$ele->addOption(0,_MS_GS_PRIVATE,$edit ? ($img->isPublic()==0 ? 1 : 0) : 0);
	$ele->addOption(1,_MS_GS_PRIVFRIEND,$edit ? ($img->isPublic()==1 ? 1 : 0) : 0);
	$ele->addOption(2,_MS_GS_PUBLIC,$edit ? ($img->isPublic()==2 ? 1 : 0) : 0);

	$form->addElement($ele,true);

	//Albumes
	if ($edit){
		$albums = $img->sets(false);
		foreach ($albums as $k=>$v){
			$sets[] = $v['id_set'];
		}
	}
	$ele = new RMSelect(_MS_GS_FSETS,'albums[]',1,$edit ? $sets : '');
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".$exmUser->uid()."'";
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
	
	$ele = new RMText(_MS_GS_TAGS,'tags',50,100,$edit ?  $tgs : '');
	$ele->setDescription(_MS_GS_DESCTAGS);

	$form->addElement($ele, true);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));	
	$form->addElement(new RMHidden('page',$page));	
	$form->addElement(new RMHidden('referer',$referer));
	
	$buttons = new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\''.$referer.'\'"');
	$form->addElement($buttons);
	

	$tpl->assign('form_pics',$form->render());
	createLinks();
	
	$xmh.= '<link rel="stylesheet" type="text/css" media="screen" href="'.GS_URL.'/styles/panel.css" />';
	
	include 'footer.php';
}


/**
* @desc Almacena la información de la imagen
**/
function saveImages($edit = 0){
	
	global $exmUser,$xoopsModuleConfig, $db;

	$mc =& $xoopsModuleConfig;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	if ($edit){
		//Verificamos si la imagen es válida
		if($id<=0){
			redirect_header($referer,1,_MS_GS_ERRIMAGE);
			die();
		}	

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if($img->isNew()){
			redirect_header($referer,1,_MS_GS_ERRIMAGEEXIST);
			die();
		}	

	}else{
		$img = new GSImage();
	}

	$img->setTitle($title);
	$img->setDesc($desc);
	$img->isNew() ? $img->setCreated(time()) : $img->setModified(time());
	if (!$edit) $img->setOwner($exmUser->uid());
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
		//Verificamos si el usuario se encuentra registrado
		$user = new GSUser($xoopUser->uname());
		if($user->isNew()){
			//Insertamos información del usuario
			$user->setUid($exmUser->uid());
			$user->setUname($exmUser->uname());
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
		redirect_header($referer,1,_MS_GS_DBERROR.$img->errors());
		die();
	}else{
		$new ? $user->addPic() : '';
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

		redirect_header($referer,1,_MS_GS_DBOK);
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

	global $exmUser, $db, $xoopsOption, $tpl;
	
	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$referer = isset($_REQUEST['referer']) ? $_REQUEST['referer'] : '';
	if(!$referer){
		$referer = XOOPS_URL.'/modules/galleries/cpanel.php?pag='.$page;
	}

	$xoopsOption['template_main'] = 'gs_formaddsets.html';
	include 'header.php';

	GSFunctions::makeHeader();

	
	
	//Verificamos si nos proporcionaron al menos un imagen para actualizar
	if (!is_array($ids) && $ids<=0){
		redirect_header('./cpanel.php',2,_MS_GS_ERRIMGASSIGN);
		die();
	}

	if (!is_array($ids)){
		$ids = array($ids);
	}
	

	$form = new RMForm(_MS_GS_ADDALBUM,'frmset','cpanel.php');
	
	//Obtenemos los albumes del usuario
	$ele = new RMCheck(_MS_GS_SETS);
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".$exmUser->uid()."'";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$ele->addOption($rows['title'],'albums[]',$rows['id_set']);
	}
	
	$ele->asTable(3);
	$form->addElement($ele,true);

	$form->addElement(new RMHidden('op','savesets'));
	foreach ($ids as $k=>$v){
		$form->addElement(new RMHidden('ids['.$k.']',$v));	
	}
	$form->addElement(new RMHidden('page',$page));	
	$form->addElement(new RMHidden('referer',$referer));
	
	$buttons = new RMButtonGroup();
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
	global $db, $exmUser;

	foreach ($_POST as $k => $v){
		$$k = $v;
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
	
		if ($img->owner()!=$exmUser->uid()){
			$errors = sprintf(_MS_GS_ERRNOTOWNER, $k);
			continue;
		}

		
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
		$referer = XOOPS_URL.'/modules/galleries/cpanel.php?pag='.$page;
	}

	if($errors!=''){
		redirect_header($referer,2,_MS_GS_DBERRORS.$errors);
		die();
	}else{
		redirect_header($referer,2,_MS_GS_DBOK);
		die();
	}
}

?>
