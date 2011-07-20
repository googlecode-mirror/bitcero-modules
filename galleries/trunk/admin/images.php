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

function send_error($message){
    $data['error'] = 1;
    $data['message'] = $message;
    echo json_encode($data);
    die();
}

/**
* @desc Visualiza todas las imágenes existentes
**/
function showImages(){

	global $xoopsModule, $tpl, $xoopsConfig, $xoopsSecurity;
	
	$db = Database::getInstance();

	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $page = $page<=0 ? 1 : $page;
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
	$limit = $limit<=0 ? 10 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? $_REQUEST['owner'] : '';
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
   	$start = $num<=0 ? 0 : ($page-1) * $limit;
    $tpages = ceil($num / $limit);
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url("images.php?page={PAGE_NUM}&amp;limit=$limit&amp;search=$search&amp;owner=$owner&amp;mindate=$mindate&amp;maxdate=$maxdate&amp;sort=$sort&amp;mode=$mode");

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	
	//Fin de barra de navegación

	$sql = "SELECT * FROM ".$db->prefix('gs_images');
	$sql2 = " ORDER BY $sort ".($mode ? "DESC" : "ASC");
	$sql2.= " LIMIT $start,$limit";

	$result = $db->query($sql.$sql1.$sql2);

	$users = array();
    $images = array();
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
		$images[] = array(
            'id'=>$img->id(),
            'title'=>$title ? $title : $img->title(),
            'desc'=>substr($img->desc(),0,150),
		    'image'=>$users[$img->owner()]->filesURL().'/ths/'.$img->image(),
            'created'=>formatTimeStamp($img->created(),'c'),
		    'owner'=>$xu->uname(),
            'public'=>$img->isPublic(),
            'link'=>$link
        );

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
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Images Management','galleries'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Images','galleries'));
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('../include/js/gsscripts.php?file=sets&form=frm-images');
	RMTemplate::get()->add_head("<script type='text/javascript'>\nvar delete_warning='".__('Do you really wish to delete selected images?','galleries')."';\n</script>");
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
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? $_REQUEST['owner'] : '';
	$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "page=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";

	if($edit){
		//Verificamos si la imagen es válida
		if($id<=0){
			redirectMsg('./images.php?'.$ruta,__('Image ID not valid!','galleries'), 1);
			die();
		}

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if ($img->isNew()){
			redirectMsg('./images.php?'.$ruta,__('Specified image does not exists!','galleries'), 1);
			die();
		}

	}

	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./images.php'>".__('Images','galleries')."</a> &raquo; ".($edit ? __('Edit image','galleries') : __('Add image','galleries')));
	xoops_cp_header();

	$form = new RMForm($edit ? __('Editing image','galleries') : __('Add image','galleries'), 'frmimg','images.php');
	$form->setExtra("enctype='multipart/form-data'");
	
	if (!$edit){
		$ele = new RMFormUser(__('User','galleries'),'uid',0,($uid ? array($uid) : ($edit ? array($img->owner()) : array($xoopsUser->uid()))),50);
		$ele->onChange("$('op').value='".($edit ? 'edit' : 'new')."'; $('frmimg').submit();");
		$form->addElement($ele);
	}else{
		$xu = new XoopsUser($img->owner());
		$form->addElement(new RMFormLabel(__('User','galleries'),$xu->uname()));
		$form->addElement(new RMFormHidden('uid',$img->owner()));
	}

	$form->addElement(new RMFormText(__('Title','galleries'),'title',50,100,$edit ? $img->title() : ''));
	$form->addElement(new RMFormFile(__('Image file','galleries'),'image',45, $mc['size_image']*1024));
	if ($edit){
		$user = new GSUser($img->owner(),1);
		$url = $user->filesURL();

		$form->addElement(new RMFormLabel(__('Current image','galleries'),"<img src='".$url."/ths/".$img->image()."' />"));
		
	}

	$form->addElement(new RMFormTextArea(__('Description','galleries'),'desc',4,50,$edit ? $img->desc() : ''));
	
	$ele = new RMFormSelect(__('Privacy','galleries'),'public');
	$ele->addOption(0,__('Private','galleries'),$edit ? ($img->isPublic()==0 ? 1 : 0) : 0);
	$ele->addOption(1,__('Visible for friends','galleries'),$edit ? ($img->isPublic()==1 ? 1 : 0) : 0);
	$ele->addOption(2,__('Visible for all','galleries'),$edit ? ($img->isPublic()==2 ? 1 : 0) : 1);

	$form->addElement($ele,true);


    $db = Database::getInstance();
	//Albumes
	if ($edit){
		$albums = $img->sets(false);
		foreach ($albums as $k=>$v){
			$sets[] = $v['id_set'];
		}
	}
	$ele = new RMFormSelect(__('Albums','galleries'),'albums[]',1,$edit ? $sets : '');
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".($uid ? $uid : ($edit ? $img->owner() : $xoopsUser->uid()))."'";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$ele->addOption($rows['id_set'],$rows['title']);
	}
	$form->addElement($ele);
	
	$ele = new RMFormText(__('Tags','galleries'),'tags',50,100,$edit ?  implode(", ", $img->tags(false, 'tag')) : '');
	$ele->setDescription(__('Separate each tag with a comma (,)','galleries'));

	$form->addElement($ele, true);

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	if ($edit)
	$form->addElement(new RMFormHidden('id',$id));	

	$form->addElement(new RMFormHidden('page',$page));	
	$form->addElement(new RMFormHidden('limit',$limit));	
	$form->addElement(new RMFormHidden('search',$search));
	$form->addElement(new RMFormHidden('owner',$owner));
	$form->addElement(new RMFormHidden('sort',$sort));
	$form->addElement(new RMFormHidden('mode',$mode));

	$buttons = new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Save changes','galleries') : __('Add image','galleries'),'submit');
	$buttons->addButton('cancel',__('Cancel','galleries'),'button','onclick="window.location=\'images.php?'.$ruta.'\'"');
	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}


/**
* @desc Formulario de creación de muchas imágenes
**/
function formBulkImages(){
	
	global $mc,$xoopsUser, $tpl, $xoopsModule, $xoopsSecurity;

	$page = rmc_server_var($_REQUEST, 'page', 1);
	$owner = rmc_server_var($_REQUEST, 'owner', 0);
	$uid = rmc_server_var($_REQUEST, 'uid', 0);
	$sort = rmc_server_var($_REQUEST, 'sort', 'created');
	$mode = rmc_server_var($_REQUEST, 'mode', 1);
    $psets = rmc_server_var($_REQUEST, 'albums', array());
    $tags = rmc_server_var($_REQUEST, 'tags', '');

	$ruta = "page=$page&owner=$owner&sort=$sort&mode=$mode";

	$db = Database::getInstance();
	
	//Lista de albumes
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".($uid ? $uid : $xoopsUser->uid())."' ORDER BY id_set DESC";
	$result = $db->query($sql);
	$sets = array();
	while($rows = $db->fetchArray($result)){
		$sets[] = array('id'=>$rows['id_set'],'title'=>$rows['title'],'selected'=>in_array($rows['id_set'], $psets));
	}
    
    $show_controls = false;
    
    if($uid>0 && !empty($sets)){
        $show_controls = true;
        $uploader = new RMFlashUploader('files-container', '../include/upload.php');
        $uploader->add_setting('scriptData', array(
            'op'=>'savebulk',
            'sets'=>implode(',',$psets),
            'uid'=>$uid,
            'owner'=>$owner,
            'sort'=>$sort,
            'mode'=>$mode,
            'page'=>$page,
            'tags'=>$tags,
            'token'=>$xoopsSecurity->createToken(),
            'rmsecurity'=>TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.GS_URL.'/admin/images.php'.'|'.$xoopsSecurity->createToken(), true)
            // Need better code
        ));
        $uploader->add_setting('multi', true);
        $uploader->add_setting('fileExt', '*.jpg;*.png;*.gif');
        $uploader->add_setting('fileDesc', __('All Images (*.jpg, *.png, *.gif)','galleries'));
        $uploader->add_setting('sizeLimit', $mc['size_image']*1024);
        $uploader->add_setting('buttonText', __('Browse Images...','galleries'));
        $uploader->add_setting('queueSizeLimit', 100);
        $uploader->add_setting('onComplete',"function(event, id, file, resp, data){
                eval('ret = '+resp);
                if (ret.error){
                    \$('#upload-errors').append('<span class=\"failed\"><strong>'+file.name+'</strong>: '+ret.message+'</span>');
                } else {
                    total++;
                    ids[total-1] = ret.id;
                    \$('#upload-errors').append('<span class=\"done\"><strong>'+file.name+'</strong>: ".__('Uploaded successfully!','rmcommon')."</span>');
                }
                return true;
            }");
        $uploader->add_setting('onAllComplete', "function(event, data){
                
                if(total<=0){ return; }
                
                \$('.select_image_cat').hide('slow');
                \$('#upload-errors').hide('slow');
                \$('#upload-errors').html('');
                \$('#upload-controls').hide('slow');
                \$('#resizer-bar').show('slow');
                \$('#resizer-bar').effect('highlight',{},1000);
                \$('#gen-thumbnails').show();
                
                var increments = 1/total*100;
                url = '".GS_URL."/include/upload.php';
                params = '".TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.GS_URL.'/admin/images.php'.'|'.$xoopsSecurity->createToken(), true)."';
                resize_image(params);
                
            }");
        $uploader->add_setting('onError', 'function (event,ID,fileObj,errorObj) {
          alert(errorObj.type + \' Error: \' + errorObj.info);
        }');
            RMTemplate::get()->add_head($uploader->render());
    }


	$form = new RMForm('','frmImgs','images.php');
	$ele = new RMFormUser('','uid',0,($uid ? array($uid) : array($xoopsUser->uid())),0);
	
	$users_field = $ele->render();
	
	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Create batch images','galleries'));
	RMTemplate::get()->assign('xoops_pagetitle', __('Create batch images','galleries'));
	RMTemplate::get()->add_local_script('images.js', 'galleries');
    RMTemplate::get()->add_style('uploader.css', 'galleries');
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/gs_formimages.php",'module','galleries');
	
	xoops_cp_footer();

}

/**
* @desc almacena la información de la imagen
**/
function saveImages($edit = 0){

	global $mc, $xoopsSecurity, $db, $xoopsUser, $db;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "page=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";

	if (!$xoopsSecurity->check()){
		redirectMsg('./images.php?'.$ruta,__('Session token expired!','galleries'),1);
		die();
	}


	if ($edit){
		//Verificamos si la imagen es válida
		if($id<=0){
			redirectMsg('./images.php?op=edit&id='.$id.'&'.$ruta,__('Image ID not valid!','galleries'), 1);
			die();
		}

		//Verificamos si la imagen existe
		$img = new GSImage($id);
		if ($img->isNew()){
			redirectMsg('./images.php?op=edit&id='.$id.'&'.$ruta,__('Specified image does not exists!','galleries'), 1);
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
				redirectMsg('./images.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$ruta,__('New user owner could not be saved!','galleries'), 1);
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
	include_once RMCPATH.'/class/uploader.php';
	$folder = $mc['storedir']."/".$user->uname();
	$folderths = $mc['storedir']."/".$user->uname()."/ths";

	$up = new RMFileUploader($folder, $mc['size_image']*1024, array('jpg','png','gif'));
    
	if ($edit){
		$filename=$img->image();
	}
	else{
		$filename = '';
	}

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
		$redim = new RMImageResizer($fullpath, $fullpath);
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


	} else {
		echo $up->getErrors();
		
	}
	//Fin de Imagen
	
	$img->setImage($filename);
    $db = Database::getInstance();
	
	$new = $img->isNew();
	if(!$img->save()){
		redirectMsg('./images.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$ruta,__('Errors ocurred while trying to save image.','galleries').$img->errors(), 1);
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

		redirectMsg('./images.php?'.$ruta,__('Database updated successfully!','galleries'), 0);
		die();
	}
}

/**
* @desc Almacena la información del grupo de imágenes
**/
function saveBulkImages(){
	global $util, $mc, $xoopsUser;
	
    XoopsLogger::getInstance()->activated = false;
    XoopsLogger::getInstance()->renderingEnabled = false;
    
	set_time_limit(0);
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	$ruta = "page=$page&search=$search&owner=$uid&sort=$sort&mode=$mode";
	
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
			send_error(__('User owner could not be created!','galleries')."<br />".$user->errors());
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
	$tgs = explode(",",$tags);
	/**
	* @desc Almacena los ids de las etiquetas que se asignarán a la imágen
	*/
	$ret = array(); 
	foreach ($tgs as $k){
		$k = trim($k);
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
	include_once RMCPATH.'/class/uploader.php';
    $updir = $mc['storedir']."/".$xu->uname();
    $upths = $mc['storedir']."/".$xu->uname()."/ths";
    
    // Cargamos la imágen
    if (!file_exists($updir)){
        mkdir($updir,511);
    }

    if (!file_exists($upths)){
        mkdir($upths, 511);
    }

    $uploader = new RMFileUploader($updir, $mc['size_image']*1024, array('gif', 'jpg', 'jpeg', 'png'));

    $err = array();
    if (!$uploader->fetchMedia('Filedata')){
        send_error($uploader->getErrors());
    }

    if (!$uploader->upload()){
        send_error($uploader->getErrors());
    }

    // Insertamos el archivo en la base de datos
    $img = new GSImage();
    $img->setTitle($uploader->savedFileName);
    $img->setOwner($uid);
    $img->setPublic(2);
    $img->setCreated(time());
    $img->setImage($uploader->getSavedFileName());

    if (!$image->save()){
        unlink($uploader->savedDestination);
        send_error(__('File could not be inserted to database!','galleries'));
    }

    $ret['message'] = '1';
    $ret['id'] = $image->id();
    echo json_encode($ret);
    die();
	
	/*
    foreach ($_FILES['image']['name'] as $k => $v){
		if ($v=='') continue;
		
		
		//Imagen
		$filename = '';
		
		if ($up->fetchMedia('image',$k)){

			if (!$up->upload()){
				$errors .= sprintf(__('Image could not be uploaded due to next reason: %s','galleries'), $up->getErrors());
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
			$redim = new RMImageResizer($fullpath, $fullpath);
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
				$errors .= sprintf(__('Image could not be inserted in database!','galleries'), $v)." (".$img->errors().")";
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
		redirectMsg('./images.php?'.$ruta,__('Errors ocurred while trying to upload images.','galleries').$errors,1);
		die();
	}else{
		redirectMsg('./images.php?'.$ruta,__('Images uploaded successfully!','galleries'),0);
		die();
	}*/

}

/**
* @desc Elimina la(s) imagen(es) especificada(s)
**/
function deleteImages(){

	global $xoopsSecurity, $xoopsModule, $db;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? intval($_REQUEST['owner']) : '';
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "page=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";

	
	//Verificamos si nos proporcionaron al menos un imagen para eliminar
	if (!is_array($ids)){
		redirectMsg('./images.php?'.$ruta,__('No images has been selected!','galleries'),1);
		die();
	}
	
    if (!$xoopsSecurity->check()){
	    redirectMsg('./images.php?'.$ruta,__('Session token expired!','galleries'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){

	    //Verificamos si la imagen es válida
		if($k<=0){
		    $errors .= sprintf(__('Image ID "%s" not valid','galleries'), $k);
			continue;			
		}

		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
		    $errors .= sprintf(__('Image "%s" does not exists!','galleries'), $k);
			continue;
		}	

		$sets = $img->sets();
		if(!$img->delete()){
		    $errors .= sprintf(__('Image "%s" could not be deleted!', 'galleries'), $k);
		}else{
	        //Decrementamos el número de imágenes de los albumes
			foreach ($sets as $k => $set){
			    $set->quitPic($img->id());
			}
		}
	}

	if($erros!=''){
	    redirectMsg('./images.php?'.$ruta,__('Errors ocurred while trying to delete images','galleries').$errors,1);
		die();
	}else{
	    redirectMsg('./images.php?'.$ruta,__('Images deleted successfully!','galleries'),0);
		die();
	}
		


}

/**
* @desc Publica o no publica las imágenes especificadas
**/
function publicImages($pub = 0){

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$owner = isset($_REQUEST['owner']) ? intval($_REQUEST['owner']) : '';	
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'created';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

	$ruta = "page=$page&limit=$limit&search=$search&owner=$owner&sort=$sort&mode=$mode";


	//Verificamos si nos proporcionaron al menos una imagen
	if (!is_array($ids)){
		redirectMsg('./images.php?'.$ruta,__('No images has been selected!','galleries'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
	
		//Verificamos si la imagen es válida
		if($k<=0){
			$errors .= sprintf(__('Image ID "%s" is not valid','galleries'), $k);
			continue;			
		}
		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
			$errors .= sprintf(__('Image "%s" does not exists!','galleries'), $k);
			continue;
		}	
		
		$img->setPublic($pub);
		if(!$img->save()){
			$errors .= sprintf(__('Image could not be updated!','galleries'), $k);
		}
	}

	if($erros!=''){
		redirectMsg('./images.php?'.$ruta,__('Errors ocurred while trying to update images!').$errors,1);
		die();
	}else{
		redirectMsg('./images.php?'.$ruta,__('Images updated successfully!','galleries'),0);
		die();
	}

}

$op = rmc_server_var($_REQUEST, 'op', '');

switch($op){
	case 'new':
		formImages();
	break;
	case 'newbulk':
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
        XoopsLogger::getInstance()->activated = false;
        XoopsLogger::getInstance()->renderingEnabled = false;
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
