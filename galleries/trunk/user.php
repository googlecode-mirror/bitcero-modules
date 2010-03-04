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

$toget = 'id';
include 'include/parse.php';

define('GS_LOCATION','user');
include '../../mainfile.php';

function showUserPics(){
	global $usr, $db, $xoopsModule, $mc, $xoopsModuleConfig, $xoopsConfig, $exmUser, $xoopsOption, $tpl;
	global $pag, $set;
	
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRUSR);
		die();
	}
	
	$xoopsOption['template_main'] = 'gs_userpics.html';
	$xoopsOption['module_subpage'] = 'userpics';
	include 'header.php';
	
	GSFunctions::makeHeader();
	
	// Información del Usuario
	$tpl->assign('lang_picsof', sprintf(_MS_GS_PICSOF, $user->uname()));
	$tpl->assign('user', array('id'=>$user->uid(),'uname'=>$user->uname(),'avatar'=>$user->userVar('user_avatar'),'link'=>$user->userURL()));
	
	// Lenguaje
	$tpl->assign('lang_sets', _MS_GS_SETS);
	$tpl->assign('lang_tags', _MS_GS_TAGS);
	$tpl->assign('lang_bmark', _MS_GS_BMARK);
	$tpl->assign('lang_pics', _MS_GS_PICS);
	$tpl->assign('lang_quickview', _MS_GS_QUICK);
	$tpl->assign('sets_link', GS_URL.'/'.($mc['urlmode'] ? "explore/sets/usr/".$user->uname() : "explore.php?by=explore/sets/usr/".$user->uname()));
	$tpl->assign('tags_link', GS_URL.'/'.($mc['urlmode'] ? "explore/tags/usr/".$user->uname() : "explore.php?by=explore/tags/usr/".$user->uname()));
	$tpl->assign('bmark_link', GS_URL.'/'.($mc['urlmode'] ? "cpanel/booksmarks/" : "cpanel.php?s=cpanel/bookmarks"));
	$tpl->assign('xoops_pagetitle', sprintf(_MS_GS_PICSOF, $user->uname()).' &raquo; '.$mc['section_title']);

	//Verificamos si el usuario es dueño o amigo
	if ($exmUser && $exmUser->uid()==$user->uid()){
		$public = '';
	}else{
		if($exmUser && $user->isFriend($exmUser->uid())){
			$public = " AND public<>'0'";
		}else{
			$public = " AND public='2'";
		}
	}

	
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_images")." WHERE owner='".$user->uid()."' $public";
	
	$page = isset($pag) ? $pag : 0;
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
    	$urlnav .= isset($usr) ? 'usr/'.$user->uname() : 'user.php?id=usr/'.$user->uname();	    
    	$nav = new GSPageNav($num, $limit, $start, 'pag',$urlnav,0);
   	    $tpl->assign('upNavPage', $nav->renderNav(4, 1));
   	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación
	
	$sql = str_replace("COUNT(*)",'*',$sql);
	$sql .= " ORDER BY created DESC, modified DESC LIMIT $start, $limit";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$img = new GSImage();
		$img->assignVars($row);
		$imglink = $user->userURL().'img/'.$img->id().'/';
		$imgfile = $user->filesURL().'/'.($mc['user_format_mode'] ? 'formats/user_' : 'ths/').$img->image();
		
		// Conversion de los formatos
		if (!$img->userFormat() && $mc['user_format_mode']){
			GSFunctions::resizeImage($crop, $user->filesPath().'/'.$img->image(),$user->filesPath().'/formats/user_'.$img->image(), $width, $height);
			$img->setUserFormat(1, 1);
		}
		
		$tpl->append('images', array('id'=>$img->id(),'title'=>$img->title(),
			'image'=>$imgfile,'link'=>$imglink,
			'bigimage'=>$user->filesURL().'/'.$img->image(),
			'created'=>sprintf(_MS_GS_CREATED, formatTimestamp($img->created(),'string')),
			'comments'=>sprintf(_MS_GS_COMMENTS, $img->comments()),'desc'=>$showdesc ? $img->desc() : ''));
	}
	
	// Datos para el formato
	$tpl->assign('max_cols', $cols);
	
	$util =& RMUtils::getInstance();
	
	$xmh .= "\n<link href='".GS_URL."/include/css/lightbox.css' type='text/css' media='screen' rel='stylesheet' />\n
			<script type='text/javascript'>\nvar gs_url='".GS_URL."';\n</script>";
	$util->addScript('prototype');
	$util->addScript('scriptaeffects');
	global $xoTheme;
	$xoTheme->addScript(GS_URL."/include/js/lightbox.js");
	
	include 'footer.php';
	
}

/**
* @desc Mostramos los detalles de una imágen
*/
function showImageDetails(){
	global $usr, $set, $img, $db, $xoopsModule, $mc, $xoopsModuleConfig, $xoopsConfig, $exmUser, $xoopsOption, $tpl;
	
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRUSR);
		die();
	}
	
	$image = new GSImage($img);
	if ($image->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRIMG);
		die();
	}
	$user = new GSUser($image->owner(),1);

	//Verificamos la privacidad de la imagen
	if (!$image->isPublic()){
		//Privada, Verificamos si el usuario es el dueño de la imagen
		if(!$exmUser || $exmUser->uid()!=$image->owner()){
			redirect_header(XOOPS_URL.'/modules/galleries',1,_MS_GS_ERRPRIVACY);
			die();		
		}
	}else{
		if($image->isPublic()==1){//Privada y amigos
			if (!$exmUser || $exmUser->uid()!=$image->owner()){
				//Verificamos si es un amigo
				if (!$exmUser || !$user->isFriend($exmUser->uid())){
					redirect_header(XOOPS_URL.'/modules/galleries',1,_MS_GS_ERRNOTFRIEND);
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
			redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRSET);
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
	$tpl->assign('lang_photos', _MS_GS_PHOTOS);
	$tpl->assign('lang_browse', _MS_GS_BROWSE);
	$tpl->assign('lang_alsobelong', _MS_GS_BELONG);
	$tpl->assign('lang_tags', _MS_GS_TAGS);
	$tpl->assign('lang_postcards', _MS_GS_POSTCARDS);
	$tpl->assign('lang_bookmark', _MS_GS_BOOKMARK);
	$tpl->assign('lang_toset', _MS_GS_TOSET);
	$tpl->assign('lang_lastpic', _MS_GS_LASTPIC);
	$tpl->assign('lang_firstpic', _MS_GS_FIRSTPIC);
	$tpl->assign('toset_link', GS_URL.'/'.($mc['urlmode'] ? 'cpanel/toset/' : 'cpanel.php?s=cpanel/toset/'));
	$tpl->assign('edit_link', GS_URL.'/'.($mc['urlmode'] ? 'cpanel/edit/&amp;id=' : 'cpanel.php?op=edit&amp;id='));
	$tpl->assign('bookmark_link', GS_URL.'/'.($mc['urlmode'] ? 'cpanel/bookmarks/add/'.$image->id().'/referer/'.base64_encode($_SERVER['REQUEST_URI']) : 'cpanel.php?s=cpanel/bookmarks/add/'.$image->id().'/referer/'.base64_encode($_SERVER['REQUEST_URI'])));
	$tpl->assign('postcard_link', GS_URL.'/'.($mc['urlmode'] ? 'postcard/new/img/'.$image->id().'/' : 'postcard.php?id=postcard/new/img/'.$image->id()));
	$where = $user->userURL().(isset($set) ? 'set/'.$set->id().'/' : '');
	$tpl->assign('del_return', base64_encode($where));
	$tpl->assign('delete_link', GS_URL.'/'.($mc['urlmode'] ? 'cpanel/delete/&amp;ids=' : 'cpanel.php?op=delete&amp;ids='));
	$tpl->assign('lang_confirmdel', sprintf(_MS_GS_CONFDEL, $image->title()));
	
	if ($exmUser && $exmUser->uid()==$image->owner()){
		$tpl->assign('lang_edit', _EDIT);
		$tpl->assign('lang_delete', _DELETE);
		$tpl->assign('isowner', 1);
	}
	
	$tpl->assign('postcards', $mc['postcards']);
	
	$tpl->assign('image',array('title'=>$image->title(),'id'=>$image->id(),'file'=>$user->filesURL().'/'.$image->image(),
		'desc'=>$image->desc()));
		
	$tpl->assign('xoops_pagetitle', $image->title().' &raquo; '.$xoopsModule->name());
	
	//Verificamos si el usuario es dueño o amigo
	if ($exmUser && $exmUser->uid()==$user->uid()){
		$public = '';
	}else{
		if($exmUser && $user->isFriend($exmUser->uid())){
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
		
		$tpl->assign('prevnext_title', sprintf(_MS_GS_PNTITLE, $user->uname()));
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
				if(!$exmUser) continue;
				
				if($exmUser->uid()!=$oset->owner()) continue;
			}else{
				if (!$exmUser && $oset->isPublic()==1 && !$user->isFriend($exmUser->uid())) continue;
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
		
		$tpl->assign('prevnext_title', sprintf(_MS_GS_PNTITLE, $set->title()));
		$tpl->assign('title_link', $user->userURL().'set/'.$set->id().'/');
		
		$result = $db->query("SELECT COUNT(*) FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image AND a.owner='".$user->uid()."' $public");
		list($num) = $db->fetchRow($result);
		$tpl->assign('pics_count', $num);
		
		// Tambien pertenece
		$tbl1 = $db->prefix("gs_sets");
		$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_set<>'".$set->id()."' AND b.id_image='".$image->id()."' AND a.id_set=b.id_set";
		$result = $db->query($sql);
		$tpl->append('sets', array('id'=>0, 'title'=>sprintf(_MS_GS_GALFROM, $user->uname()),'link'=>$user->userURL()));
		while ($row = $db->fetchArray($result)){
			$oset = new GSSet();
			$oset->assignVars($row);

			//Verificamos la privacidad del album
			if (!$oset->ispublic()){
				if(!$exmUser) continue;
				if($exmUser->uid()!=$oset->owner()) continue;
			}else{
				if (!$exmUser && $oset->isPublic()==1 && !$user->isFriend($exmUser->uid())) continue;
			}
			
			$tpl->append('sets', array('id'=>$oset->id(),'title'=>$oset->title(),'link'=>$user->userURL().'set/'.$oset->id(),'/'));
		}
		
	}
	
	// Etiquetas
	$tags = $image->tags(true, '*');
	$link = GS_URL.'/'.($mc['urlmode'] ? 'explore/tags/tag/' : "explore.php?by=explore/tags/tag/");
	foreach ($tags as $tag){
		$tpl->append('tags', array('id'=>$tag->id(),'tag'=>$tag->tag(),'link'=>$link.$tag->tag()));
	}
	
	// Comentarios
	include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';
	$tpl->assign('users_link', GS_URL.'/'.($mc['urlmode'] ? 'usr/' : 'user.php?id=usr/'));
	$tpl->assign('lang_usays', _MS_GS_USAYS);
	$tpl->assign('lang_comments', _MS_GS_TCOMMENTS);
	$tpl->assign('lang_send', _MS_GS_SEND);

	XoopsCommentHandler::renderNavbar($image->id(), $image->title(), $_SERVER['REQUEST_URI'], $_SERVER['REQUEST_URI'], 'post');
	$comments = XoopsCommentHandler::getComments($image->id(), $xoopsModule->mid(), false, $_SERVER['REQUEST_URI']);
	foreach ($comments as $k => $v){
		$comments[$k]['created'] = sprintf(_MS_GS_POSTED, formatTimestamp($v['createdtime'], 'string'));
	}
	$tpl->assign('comments', $comments);
	
	include 'footer.php';
	
}

/**
* @desc Mostramos el contenido de un Álbum
*/
function showSetContent(){
	global $usr, $db, $xoopsModule, $mc, $xoopsModuleConfig, $xoopsConfig, $exmUser, $xoopsOption, $tpl;
	global $pag, $set;
	
	$mc =& $xoopsModuleConfig;
	$user = new GSUser($usr);
	if ($user->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRUSR);
		die();
	}
	
	$set = new GSSet($set);
	if ($set->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries', 0, _MS_GS_ERRSET);
		die();
	}

	//Verificamos la privacidad del album
	if (!$set->ispublic()){
		if(!$exmUser || $exmUser->uid()!=$set->owner()){
			redirect_header(XOOPS_URL.'/modules/galleries/',1,_MS_GS_ERRSETPRIVACY);
			die();
		}
	}else{
		if (!$exmUser && $set->isPublic()==1 && !$user->isFriend($exmUser->uid())){
			redirect_header(XOOPS_URL.'/modules/galleries/',1,_MS_GS_ERRNOTFRIENDSET);
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
	$tpl->assign('lang_picsof', sprintf(_MS_GS_PICSOFIN, $set->title()));
	$tpl->assign('user', array('id'=>$user->uid(),'uname'=>$user->uname(),'avatar'=>$user->userVar('user_avatar'),'link'=>$user->userURL()));
	
	// Lenguaje
	$tpl->assign('lang_sets', _MS_GS_SETS);
	$tpl->assign('lang_tags', _MS_GS_TAGS);
	$tpl->assign('lang_bmark', _MS_GS_BMARK);
	$tpl->assign('lang_pics', _MS_GS_PICS);
	$tpl->assign('lang_quickview', _MS_GS_QUICK);
	$tpl->assign('sets_link', GS_URL.'/'.($mc['urlmode'] ? "explore/sets/usr/".$user->uname() : "explore.php?by=explore/sets/usr/".$user->uname()));
	$tpl->assign('tags_link', GS_URL.'/'.($mc['urlmode'] ? "explore/tags/usr/".$user->uname() : "explore.php?by=explore/tags/usr/".$user->uname()));
	$tpl->assign('bmark_link', GS_URL.'/'.($mc['urlmode'] ? "cpanel/booksmarks/" : "cpanel.php?s=cpanel/bookmarks"));
	$tpl->assign('xoops_pagetitle', sprintf(_MS_GS_PICSOFIN, $set->title()).' &raquo; '.$mc['section_title']);
	$tpl->assign('lang_inset', _MS_GS_INSET);
	$tpl->assign('lang_numpics', sprintf(_MS_GS_NUMPICS, $set->pics()));
	$tpl->assign('lang_numviews', sprintf(_MS_GS_NUMVIEWS, $set->hits()));


	//Verificamos la privacidad de las imágenes
	if ($exmUser && $exmUser->uid()==$user->uid()){
		$public = '';
	}else{
		if ($exmUser && $user->isFriend($exmUser->uid())){
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
    	$nav = new GSPageNav($num, $limit, $start, 'pag',$urlnav,0);
   	    $tpl->assign('upNavPage', $nav->renderNav(4, 1));
   	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación
	
	$sql = str_replace("COUNT(*)",'*',$sql);
	$sql .= " ORDER BY a.created DESC, a.modified DESC LIMIT $start, $limit";
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
			'image'=>$imgfile,'link'=>$imglink,
			'bigimage'=>$user->filesURL().'/'.$img->image(),
			'created'=>sprintf(_MS_GS_CREATED, formatTimestamp($img->created(),'string')),
			'comments'=>sprintf(_MS_GS_COMMENTS, $img->comments()),'desc'=>$showdesc ? $img->desc() : ''));
		
	}
	
	// Imagen grande del album
	$sql = "SELECT * FROM $tbl1 a, $tbl2 b WHERE b.id_set='".$set->id()."' AND a.id_image=b.id_image $public AND owner='".$user->uid()."' 
			ORDER BY a.created DESC LIMIT 0,$blimit";
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
			$tpl->assign('lang_updated', sprintf(_MS_GS_UPDATED, formatTimestamp($img->created(),'string')));
		}
		$tpl->append('bigs', array('id'=>$img->id(),'title'=>$img->title(),
		'image'=>$imgfile,'link'=>$imglink,
		'created'=>sprintf(_MS_GS_CREATED, formatTimestamp($img->created(),'string')),
		'comments'=>sprintf(_MS_GS_COMMENTS, $img->comments()),'desc'=>$bshowdesc ? $img->desc() : ''));
	}
	
	
	// Datos para el formato
	$tpl->assign('max_cols', $cols);
	
	$util =& RMUtils::getInstance();
	
	$xmh .= "\n<link href='".GS_URL."/include/css/lightbox.css' type='text/css' media='screen' rel='stylesheet' />\n
			<script type='text/javascript'>\nvar gs_url='".GS_URL."';\n</script>";
	$util->addScript('prototype');
	$util->addScript('scriptaeffects');
	global $xoTheme;
	$xoTheme->addScript(GS_URL."/include/js/lightbox.js");
	
	include 'footer.php';
}

/**
* @desc Busca la posición exacta de una etiqueta
*/
function browsePic(){
	global $usr, $mc, $xoopsModuleConfig, $xoopsConfig, $exmUser, $pag, $set, $browse;
	
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

?>
