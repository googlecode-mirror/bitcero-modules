<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$toget = 'id';
include 'include/parse.php';

define('GS_LOCATION','user');
include '../../mainfile.php';

function showUserPics(){
	global $usr, $db, $xoopsModule, $mc, $xoopsModuleConfig, $xoopsConfig, $xoopsUser, $xoopsOption, $tpl;
	global $page, $set;
	
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, __('Specified user does not exists','galleries'));
		die();
	}
	
	$xoopsOption['template_main'] = 'gs_userpics.html';
	$xoopsOption['module_subpage'] = 'userpics';
	include 'header.php';
	
	GSFunctions::makeHeader();
	
	// Información del Usuario
	$tpl->assign('lang_picsof', sprintf(__('Pictures of %s'), $user->uname()));
	$tpl->assign('user', array('id'=>$user->uid(),'uname'=>$user->uname(),'avatar'=>$user->userVar('user_avatar'),'link'=>$user->userURL()));
	
	// Lenguaje
	$tpl->assign('lang_bmark', __('Favorites','galleries'));
	$tpl->assign('lang_pics', __('Pictures','galleries'));
	$tpl->assign('sets_link', GSFunctions::get_url() ? "explore/sets/usr/".$user->uname().'/' : "?explore=sets&amp;usr=".$user->uname());
	$tpl->assign('tags_link', GSFunctions::get_url() ? "explore/tags/usr/".$user->uname().'/' : "?explore=tags&amp;usr=".$user->uname());
	$tpl->assign('bmark_link', GSFunctions::get_url() ? "cp/booksmarks/" : "?cpanel=bookmarks");
	$tpl->assign('xoops_pagetitle', sprintf(__('Pictures of %s','galleries'), $user->uname()).' &raquo; '.$mc['section_title']);

	//Verificamos si el usuario es dueño o amigo
	if ($xoopsUser && $xoopsUser->uid()==$user->uid()){
		$public = '';
	}else{
		if($xoopsUser && $user->isFriend($xoopsUser->uid())){
			$public = " AND public<>'0'";
		}else{
			$public = " AND public='2'";
		}
	}

	
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_images")." WHERE owner='".$user->uid()."' $public";

	/**
	* @desc Formato para el manejo de las imágenes
	*/
	if ($mc['user_format_mode']){
		$format = $mc['user_format_values'];
		$crop = $format[0]; // 0 = Redimensionar, 1 = Cortar
		$width = $format[1];
		$height = $format[2];
		$limit = $format[3];
		$cols = $format[4];
		$showdesc = $format[5];
	} else {
		$limit = $mc['limit_pics'];
		$cols = $mc['cols_pics'];
		$showdesc=0;
	}
	
	list($num) = $db->fetchRow($db->query($sql));
	
	if ($page > 0){ $page -= 1; }
   	$start = $page * $limit;
   	$tpages = (int)($num / $limit);
   	if($num % $limit > 0) $tpages++;
   	$pactual = $page + 1;
	if ($pactual>$tpages){
   	    $pactual = $tpages;
   	    $start = $tpages ? ($tpages - 1) * $limit : -1;
   	}
	
   	$urlnav = '';
   	if ($tpages > 1) {
    	$urlnav .= isset($usr) ? 'usr/'.$user->uname().'/' : '?usr='.$user->uname();	    
    	$nav = new RMPageNav($num, $limit, $pactual, 5);
        $nav->target_url(GSFunctions::get_url().$urlnav . ($mc['urlmode'] ? 'pag/{PAGE_NUM}/' : '&amp;pag={PAGE_NUM}'));
   	    $tpl->assign('upNavPage', $nav->render(false));
   	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(__('Showing pictures %u to %u from %u','galleries'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
    $tpl->assign('show_desc', $showdesc);
	//Fin de barra de navegación
	
	$sql = str_replace("COUNT(*)",'*',$sql);
	$sql .= " ORDER BY created DESC, modified DESC LIMIT $start, $limit";
	$result = $db->query($sql);
    
	$tpl->assign('images', GSFunctions::process_image_data($result));
	
	// Datos para el formato
	$tpl->assign('gs_user_format', 1);
	
	include 'footer.php';
	
}

/**
* @desc Mostramos los detalles de una imágen
*/
function showImageDetails(){
	global $usr, $set, $img, $db, $xoopsModule, $mc, $xoopsModuleConfig, $xoopsConfig, $xoopsUser, $xoopsOption, $tpl;
	
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(GSFunctions::get_url(), 0, __('Specified user does not exists!','galleries'));
		die();
	}
	
	$image = new GSImage($img);
	if ($image->isNew()){
		redirect_header(GSFunctions::get_url(), 0, __('Specified image does not exists!','galleries'));
		die();
	}
	$user = new GSUser($image->owner(),1);

	//Verificamos la privacidad de la imagen
	if (!$image->isPublic()){
		//Privada, Verificamos si el usuario es el dueño de la imagen
		if(!$xoopsUser || $xoopsUser->uid()!=$image->owner()){
			redirect_header(GSFunctions::get_url(),1, __('You can not view this image!','galleries'));
			die();		
		}
	}else{
		if($image->isPublic()==1){//Privada y amigos
			if (!$xoopsUser || $xoopsUser->uid()!=$image->owner()){
				//Verificamos si es un amigo
				if (!$xoopsUser || !$user->isFriend($xoopsUser->uid())){
					redirect_header(GSFunctions::get_url(),1, __('You are not authorized to view this image!','galleries'));
					die();	
				}
			}			
		}
	}

	//Incrementamos las vistas de la imagen
	$image->addViews();
	
	if (isset($set)){
		$set = new GSSet($set);
		if ($set->isNew()){
			redirect_header(GSFunctions::get_url(), 0, __('Specified album does not exists!','galleries'));
			die();
		}
	}
	
	$xoopsOption['template_main'] = 'gs_imgdetails.html';
	$xoopsOption['module_subpage'] = 'picsdetails';
	include 'header.php';
	
	GSFunctions::makeHeader();
	
	$tpl->assign('user', array('id'=>$user->id(),'uname'=>$user->uname(),'link'=>$user->userURL(),
			'avatar'=>$user->userVar('user_avatar')!='' ? XOOPS_URL.'/uploads/avatars/'.$user->userVar('user_avatar') : GS_URL.'/images/avatar.png'));
	
	$tpl->assign('user_link', $user->userURL());
	$tpl->assign('lang_alsobelong', __('Also belongs to:','galleries'));
	$tpl->assign('lang_postcards', __('Send postcard','galleries'));
	$tpl->assign('lang_bookmark', __('+ Bookmark','galleries'));
	$tpl->assign('lang_toset', __('+ to Ablum','galleries'));
	$tpl->assign('lang_lastpic', __('This is the last picture','galleries'));
	$tpl->assign('lang_firstpic', __('This is the first picture','galleries'));
	$tpl->assign('toset_link', GSFunctions::get_url().($mc['urlmode'] ? 'cp/toset/ids/'.$image->id().'/referer/'.base64_encode(RMFunctions::current_url()).'/' : '?cp=toset&amp;ids='.$image->id().'&amp;referer='.base64_encode(RMFunctions::current_url())));
	$tpl->assign('edit_link', GSFunctions::get_url().($mc['urlmode'] ? 'cp/edit/id/'.$image->id().'/referer/'.base64_encode(RMFunctions::current_url()).'/' : '?cp=edit&amp;id='.$image->id().'&amp;referer='.base64_encode(RMFunctions::current_url())));
	$tpl->assign('bookmark_link', GSFunctions::get_url().($mc['urlmode'] ? 'cp/bookmarks/add/'.$image->id().'/referer/'.base64_encode($_SERVER['REQUEST_URI']) : '?cpanel=bookmarks&amp;add='.$image->id().'&amp;referer='.base64_encode($_SERVER['REQUEST_URI'])));
	$tpl->assign('postcard_link', GSFunctions::get_url().($mc['urlmode'] ? 'postcard/new/img/'.$image->id().'/' : '?postcard=new&amp;img='.$image->id()));
	$tpl->assign('delete_link', GSFunctions::get_url().($mc['urlmode'] ? 'cp/delete/referer/'.base64_encode(RMFunctions::current_url()).'/ids/' : '?cpanel=delete&amp;referer='.base64_encode(RMFunctions::current_url()).'&amp;ids='));
	$tpl->assign('lang_confirmdel', sprintf(__('Dow you really want to delete this picture?\n(%s)','galleries'), $image->title()));
	
	if ($xoopsUser && $xoopsUser->uid()==$image->owner()){
		$tpl->assign('lang_edit', __('Edit','galleries'));
		$tpl->assign('lang_delete', __('Delete','galleries'));
		$tpl->assign('isowner', 1);
	}
	
	$tpl->assign('postcards', $mc['postcards']);
	
	$tpl->assign('image',array('title'=>$image->title(),'id'=>$image->id(),'file'=>$user->filesURL().'/'.$image->image(),
		'desc'=>$image->desc()));
		
	$tpl->assign('xoops_pagetitle', $image->title().' &raquo; '.$xoopsModule->name());
	
	//Verificamos si el usuario es dueño o amigo
	if ($xoopsUser && $xoopsUser->uid()==$user->uid()){
		$public = '';
	}else{
		if($xoopsUser && $user->isFriend($xoopsUser->uid())){
			$public = " AND public<>'0'";
		}else{
			$public = " AND public='2'";
		}
	}
	// Imágenes anterior y siguiente
	if (!isset($set)){
		// Imágen Siguiente
        $sql = "SELECT * FROM ".$db->prefix("gs_images")." WHERE id_image>'".$image->id()."' AND owner='".$user->uid()."' $public ORDER BY id_image ASC LIMIT 0,1";
        $result = $db->query($sql);
        if ($db->getRowsNum($result)>0){
            $row = $db->fetchArray($result);
            $pn = new GSImage();
            $pn->assignVars($row);
            $tpl->assign('next', array('link'=>$user->userURL().'img/'.$pn->id().'/','id'=>$pn->id(),'title'=>$pn->title(),'file'=>$user->filesURL().'/ths/'.$pn->image()));;
        } else {
            $tpl->assign('is_last', 1);
            $tpl->assign('img_size', array('width'=>$mc['image_ths'][0], 'height'=>$mc['image_ths'][1]));
        }
        
        // Imágen Anterior
        $sql = "SELECT * FROM ".$db->prefix("gs_images")." WHERE id_image<'".$image->id()."' AND owner='".$user->uid()."' $public ORDER BY id_image DESC LIMIT 0,1";
        $result = $db->query($sql);
        if ($db->getRowsNum($result)>0){
            $row = $db->fetchArray($result);
            $pn = new GSImage();
            $pn->assignVars($row);
            $tpl->assign('prev', array('link'=>$user->userURL().'img/'.$pn->id().'/','id'=>$pn->id(),'title'=>$pn->title(),'file'=>$user->filesURL().'/ths/'.$pn->image()));;
        } else {
            $tpl->assign('is_first', 1);
            $tpl->assign('img_size', array('width'=>$mc['image_ths'][0], 'height'=>$mc['image_ths'][1]));
        }
		
		$tpl->assign('prevnext_title', sprintf(__('Pictures of %s'), $user->uname()));
		$tpl->assign('title_link', $user->userURL());
		
		$result = $db->query("SELECT COUNT(*) FROM ".$db->prefix("gs_images")." WHERE owner='".$user->uid()."' $public");
		list($num) = $db->fetchRow($result);
		$tpl->assign('pics_count', $num);
		
		// Tambien pertenece
		$tbl1 = $db->prefix("gs_sets");
		$tbl2 = $db->prefix("gs_setsimages");
		$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_image='".$image->id()."' AND a.id_set=b.id_set";
		$result = $db->query($sql);
		while ($row = $db->fetchArray($result)){
			$oset = new GSSet();
			$oset->assignVars($row);

			//Verificamos la privacidad del album
			if (!$oset->ispublic()){
				if(!$xoopsUser) continue;
				
				if($xoopsUser->uid()!=$oset->owner()) continue;
			}else{
				if (!$xoopsUser && $oset->isPublic()==1 && !$user->isFriend($xoopsUser->uid())) continue;
			}

			$tpl->append('sets', array('id'=>$oset->id(),'title'=>$oset->title(),'link'=>$user->userURL().'set/'.$oset->id().'/'));
		}
		
	} else {
		
		// Imágen Siguiente
		$tbl1 = $db->prefix("gs_images");
		$tbl2 = $db->prefix("gs_setsimages");
		
		$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image AND a.id_image>'".$image->id()."' AND a.owner='".$user->uid()."' $public ORDER BY a.id_image ASC LIMIT 0,1";
		$result = $db->query($sql);
		if ($db->getRowsNum($result)>0){
			$row = $db->fetchArray($result);
			$pn = new GSImage();
			$pn->assignVars($row);
			$tpl->assign('next', array('link'=>$user->userURL().'img/'.$pn->id().'/set/'.$set->id().'/','id'=>$pn->id(),'title'=>$pn->title(),'file'=>$user->filesURL().'/ths/'.$pn->image()));;
		} else {
			$tpl->assign('is_last', 1);
			$tpl->assign('img_size', array('width'=>$mc['image_ths'][0], 'height'=>$mc['image_ths'][1]));
		}
		
		// Imágen Anterior
		
		$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image AND a.id_image<'".$image->id()."' AND a.owner='".$user->uid()."' $public ORDER BY a.id_image DESC LIMIT 0,1";
		$result = $db->query($sql);
		if ($db->getRowsNum($result)>0){
			$row = $db->fetchArray($result);
			$pn = new GSImage();
			$pn->assignVars($row);
			$tpl->assign('prev', array('link'=>$user->userURL().'img/'.$pn->id().'/set/'.$set->id().'/','id'=>$pn->id(),'title'=>$pn->title(),'file'=>$user->filesURL().'/ths/'.$pn->image()));;
		} else {
			$tpl->assign('is_first', 1);
			$tpl->assign('img_size', array('width'=>$mc['image_ths'][0], 'height'=>$mc['image_ths'][1]));
		}
		
		$tpl->assign('prevnext_title', sprintf(__('Pictures of %s','galleries'), $set->title()));
		$tpl->assign('title_link', $user->userURL().'set/'.$set->id().'/');
		
		$result = $db->query("SELECT COUNT(*) FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image AND a.owner='".$user->uid()."' $public");
		list($num) = $db->fetchRow($result);
		$tpl->assign('pics_count', $num);
		
		// Tambien pertenece
		$tbl1 = $db->prefix("gs_sets");
		$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_set<>'".$set->id()."' AND b.id_image='".$image->id()."' AND a.id_set=b.id_set";
		$result = $db->query($sql);
		$tpl->append('sets', array('id'=>0, 'title'=>sprintf(__('Galleries of %s','galleries'), $user->uname()),'link'=>$user->userURL()));
		while ($row = $db->fetchArray($result)){
			$oset = new GSSet();
			$oset->assignVars($row);

			//Verificamos la privacidad del album
			if (!$oset->ispublic()){
				if(!$xoopsUser) continue;
				if($xoopsUser->uid()!=$oset->owner()) continue;
			}else{
				if (!$xoopsUser && $oset->isPublic()==1 && !$user->isFriend($xoopsUser->uid())) continue;
			}
			
			$tpl->append('sets', array('id'=>$oset->id(),'title'=>$oset->title(),'link'=>$user->userURL().'set/'.$oset->id(),'/'));
		}
		
	}
	
	// Etiquetas
	$tags = $image->tags(true, '*');
	$link = GSFunctions::get_url().($mc['urlmode'] ? 'explore/tags/tag/' : "?explore=tags&amp;tag=");
	foreach ($tags as $tag){
		$tpl->append('tags', array('id'=>$tag->id(),'tag'=>$tag->tag(),'link'=>$link.$tag->tag()));
	}
	
	// Comentarios
	$tpl->assign('users_link', GSFunctions::get_url().($mc['urlmode'] ? 'usr/' : '?usr='));
	
	RMFunctions::get_comments('galleries','image='.$image->id());
	// Comments form
	RMFunctions::comments_form('galleries', 'image='.$image->id(), 'module', GS_PATH.'/class/galleriescontroller.php');
	
	include 'footer.php';
	
}

/**
* @desc Mostramos el contenido de un Álbum
*/
function showSetContent(){
	global $usr, $db, $xoopsModule, $mc, $xoopsModuleConfig, $xoopsConfig, $xoopsUser, $xoopsOption, $tpl;
	global $pag, $set;
    
	$mc =& $xoopsModuleConfig;
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(GSFunctions::get_url(), 0, __('Specified users does not exists!','galleries'));
		die();
	}
	
	$set = new GSSet($set);
	if ($set->isNew()){
		redirect_header(GSFunctions::get_url(), 0, __('Specified album does not exists!','galleries'));
		die();
	}

	//Verificamos la privacidad del album
	if (!$set->ispublic()){
		if(!$xoopsUser || $xoopsUser->uid()!=$set->owner()){
			redirect_header(GSFunctions::get_url(),1, __('You can not view this private album!','galleries'));
			die();
		}
	}else{
		if (!$xoopsUser && $set->isPublic()==1 && !$user->isFriend($xoopsUser->uid())){
			redirect_header(GSFunctions::get_url(),1, sprintf(__('You must be a friend of %s in order to see this album!','galleries'), $user->uname()));
			die();
		}
	}

	//Incrementamos el número de hits del album
	if(!isset($_SESSION['vsets'])){
		$set->addHit();
		$_SESSION['vsets']=array($set->id());
	}elseif(!in_array($set->id(),$_SESSION['vsets'])){
		$set->addHit();
		$_SESSION['vsets'][]=$set->id();	
	}


	
	$xoopsOption['template_main'] = $mc['set_format_mode'] ? 'gs_setpics.html' : 'gs_userpics.html';
	$xoopsOption['module_subpage'] = 'userset';
	include 'header.php';
	
	GSFunctions::makeHeader();
	
	// Información del Usuario
	$tpl->assign('lang_picsof', sprintf(__('Images in %s'), $set->title()));
	$tpl->assign('user', array('id'=>$user->uid(),'uname'=>$user->uname(),'avatar'=>$user->userVar('user_avatar'),'link'=>$user->userURL()));
	
	// Lenguaje
	$tpl->assign('lang_bmark', __('Favorites','galleries'));
	$tpl->assign('lang_pics', __('My Images','galleries'));
	$tpl->assign('sets_link', GSFunctions::get_url().($mc['urlmode'] ? "explore/sets/usr/".$user->uname().'/' : "?explore=sets&amp;usr=".$user->uname()));
	$tpl->assign('tags_link', GSFunctions::get_url().($mc['urlmode'] ? "explore/tags/usr/".$user->uname().'/' : "?explore=tags&amp;usr=".$user->uname()));
	$tpl->assign('bmark_link', GSFunctions::get_url().($mc['urlmode'] ? "cp/bookmarks/" : "?cp=bookmarks"));
	$tpl->assign('xoops_pagetitle', sprintf(__('Images in %s'), $set->title()).' &raquo; '.$mc['section_title']);
	$tpl->assign('lang_inset', _MS_GS_INSET);
	$tpl->assign('lang_numpics', sprintf(__('Pictures: %s','galleries'), $set->pics()));
	$tpl->assign('lang_numviews', sprintf(__('Hits: %s'), $set->hits()));


	//Verificamos la privacidad de las imágenes
	if ($xoopsUser && $xoopsUser->uid()==$user->uid()){
		$public = '';
	}else{
		if ($xoopsUser && $user->isFriend($xoopsUser->uid())){
			$public = " AND public<>0";
		}else{
			$public = "AND public='2'";
		}	
	}
	
	$tbl1 = $db->prefix("gs_images");
	$tbl2 = $db->prefix("gs_setsimages");
	$sql = "SELECT COUNT(*) FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image $public AND owner='".$user->uid()."'";
	
	$page = isset($pag) ? $pag : 1;
	/**
	* @desc Formato para el manejo de las imágenes
	*/
	if ($mc['set_format_mode']){
		$format = $mc['set_format_values'];
		$crop = $format[0]; // 0 = Redimensionar, 1 = Cortar
		$width = $format[1];
		$height = $format[2];
		$limit = $format[3];
		$cols = $format[4];
		@$showdesc = $format[5];
		// Imágenes Grandes
		$format = $mc['setbig_format_values'];
		$bcrop = $format[0];
		$bwidth = $format[1];
		$bheight = $format[2];
		$blimit = $format[3];
		$bcols = $format[4];
		@$bshowdesc = $format[5];
		
		// Medidas
		$tpl->assign('big_width', $bwidth + 15);
		
	} else {
		$limit = $mc['limit_pics'];
		$cols = $mc['cols_pics'];
		$showdesc=0;
	}

	list($num) = $db->fetchRow($db->query($sql));
	if ($page > 0){ $page -= 1; }
	$start = $page * $limit;
   	$tpages = (int)($num / $limit);
   	if($num % $limit > 0) $tpages++;
   	$pactual = $page + 1;
   	if ($pactual>$tpages){
   	    $pactual = $tpages;
   	    $start = ($tpages-1) * $limit;
   	}

   	$urlnav = '';
   	if ($tpages > 1) {
        $urlnav .= $mc['urlmode'] ? 'usr/'.$user->uname().'/set/'.$set->id() : 'user.php?id=usr/'.$user->uname().'/set/'.$set->id();
        $nav = new RMPageNav($num, $limit, $pactual, 5);
        $nav->target_url(GS_URL.'/'.$urlnav.'/pag/{PAGE_NUM}');
        $tpl->assign('upNavPage', $nav->render(false));
    }

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación
	
	$sql = str_replace("COUNT(*)",'*',$sql);
	$sql .= " ORDER BY a.id_image ASC, a.modified DESC LIMIT $start, $limit";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$img = new GSImage();
		$img->assignVars($row);
		$imglink = $user->userURL().'img/'.$img->id().'/set/'.$set->id().'/';
		$imgfile = $user->filesURL().'/'.($mc['set_format_mode'] ? 'formats/set_' : 'ths/').$img->image();
		
		// Conversion de los formatos
		if (!$img->setFormat() && $mc['set_format_mode']){
			GSFunctions::resizeImage($crop, $user->filesPath().'/'.$img->image(),$user->filesPath().'/formats/set_'.$img->image(), $width, $height);
			$img->setSetFormat(1, 1);
		}
		
		$tpl->append('images', array('id'=>$img->id(),'title'=>$img->title(),
			'thumbnail'=>$imgfile,'link'=>$imglink,
			'bigimage'=>$user->filesURL().'/'.$img->image(),
			'created'=>sprintf(_MS_GS_CREATED, formatTimestamp($img->created(),'string')),
			'comments'=>sprintf(_MS_GS_COMMENTS, $img->comments()),'desc'=>$showdesc ? $img->desc() : ''));
		
	}
	
	// Imagen grande del album
	$sql = "SELECT * FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image $public AND owner='".$user->uid()."' 
			ORDER BY a.id_image DESC LIMIT 0,$blimit";
	$result = $db->query($sql);
	$bi = 0;
	// cremos la imagen grande para los albumes
	while ($row = $db->fetchArray($result)){
		$img = new GSImage();
		$img->assignVars($row);
		if ($mc['set_format_mode'] && !$img->bigSetFormat()){
			GSFunctions::resizeImage($bcrop, $user->filesPath().'/'.$img->image(),$user->filesPath().'/formats/bigset_'.$img->image(), $bwidth, $bheight);
			$img->setBigSetFormat(1, 1);
		}
		
		if ($mc['set_format_mode']){
			list($ancho, $altura, $tipo, $atr) = getimagesize($user->filesPath().'/formats/bigset_'.$img->image());
			$tpl->assign('big_width', $ancho);
		}
		
		$imglink = $user->userURL().'img/'.$img->id().'/set/'.$set->id().'/';
		// ASignamos las imagenes grandes para los albumes
		$imgfile = $user->filesURL().'/'.($mc['set_format_mode'] ? 'formats/bigset_' : 'ths/').$img->image();
		if ($bi==0){
			$tpl->assign('lang_updated', sprintf(_MS_GS_UPDATED, formatTimestamp($img->created(),'c')));
		}
		$tpl->append('bigs', array('id'=>$img->id(),'title'=>$img->title(),
		'image'=>$imgfile,'link'=>$imglink,
		'created'=>sprintf(_MS_GS_CREATED, formatTimestamp($img->created(),'string')),
		'comments'=>sprintf(_MS_GS_COMMENTS, $img->comments()),'desc'=>$bshowdesc ? $img->desc() : ''));
	}
	
	RMFunctions::get_comments('galleries','set='.$set->id());
	// Comments form
	RMFunctions::comments_form('galleries', 'set='.$set->id(), 'module', MW_PATH.'/class/galleriescontroller.php');
	
	// Datos para el formato
	$tpl->assign('max_cols', $cols);
	
	include 'footer.php';
}

/**
* @desc Busca la posición exacta de una etiqueta
*/
function browsePic(){
	global $usr, $mc, $xoopsModuleConfig, $xoopsConfig, $xoopsUser, $pag, $set, $browse;
	
	$mc =& $xoopsModuleConfig;
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRUSR);
		die();
	}
	
	$image = new GSImage($browse);
	if ($image->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRIMG);
		die();
	}
	
	if (isset($set)){
		$set = new GSSet($set);
		if ($set->isNew()){
			redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRSET);
			die();
		}
	}
	
	$page = GSFunctions::pageFromPic($image, $user, $set>0 ? $set : 0);
	header('location: '.$user->userURL().'pag/'.$page);
	
}

if (isset($set) && !isset($img)){
	showSetContent();
} elseif(isset($img)){
	showImageDetails();
} elseif (isset($browse)){
	browsePic();
} else {
	showUserPics();
}
