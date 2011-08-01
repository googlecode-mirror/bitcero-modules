<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','explore');
include '../../mainfile.php';

$toget = 'by';
include("include/parse.php");


/**
* @desc Visualiza todos los albumes existentes
**/
function sets(){
	global $tpl, $xoopsOption, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $db, $pag, $usr;
	
	$mc =& $xoopsModuleConfig;
	
	$xoopsOption['template_main'] = 'gs_sets.html';
	$xoopsOption['module_subpage'] = 'exploresets';
	include 'header.php';

	//Verificamos si el usuario proporcionado existe
	if ($usr){
		$owner = new GSUser($usr);
		if ($owner->isNew()){
			redirect_header(XOOPS_URL."/modules/galleries",1,_MS_GS_ERRUSER);
			die();
		}

	}

	GSFunctions::makeHeader();

	//Verificamos si el usuario es dueño o amigo
	if($usr && $exmUser){
		if($owner->uid()==$exmUser->uid()){
			$public = '';
		}else{
			if ($owner->isFriend($exmUser->uid())){
				$public = " WHERE public<>'0'";
			}else{
				$public = " WHERE public='2'";
			}
		}
	}else{
		$public = " WHERE public='2'";
	}


	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." $public ";
	$sql .= isset($usr) ? ($public ? " AND " : " WHERE ")." uname='".$usr."'" : '';

	$page = isset($pag) ? $pag : '';
	$limit = $mc['limit_sets'];


	/**
	* @desc Formato para el manejo de las imágenes
	*/
	if ($mc['set_format_mode']){
		$format = $mc['set_format_values'];
		$crop = $format[0]; // 0 = Redimensionar, 1 = Cortar
		$width = $format[1];
		$height = $format[2];
		$limit = $mc['limit_sets'];
		$cols = $format[4];
		$showdesc = @$format[5];
	} else {
		$limit = $mc['limit_sets'];
		$cols = $mc['cols_sets'];
		$showdesc=0;
		$width = $mc['image_ths'][0];
	}


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
		if($mc['urlmode']){
			$urlnav = 'explore/sets/';
		}else{
			$urlnav = 'explore.php?by=explore/sets/';
		}
		$urlnav.= isset($usr) ? 'usr/'.$usr.'/' : '';		    

    	    $nav = new RMPageNav($num, $limit, $pactual, 5);
            $nav->target_url(GS_URL.'/'.$urlnav.'pag/{PAGE_NUM}/');
    	    $tpl->assign('setsNavPage', $nav->render(false));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(__('Albums %u to %u from %u', 'galleries'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);	
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." $public";
	$sql.= isset($usr) ? ($public ? " AND " : " WHERE ")."uname='".$usr."'" : '';
	$sql.=  " ORDER BY date desc";
	$sql.= " LIMIT $start,$limit";
	$result = $db->query($sql);
	$users = array();
    
    $tf = new RMTimeFormatter(0, __("%M% %d%, %Y%", 'galleries'));
    
	while ($rows = $db->fetchArray($result)){
		$set = new GSSet();
		$set->assignVars($rows);

		//Obtenemos una imagen del album
		$sql = "SELECT b.* FROM ".$db->prefix('gs_setsimages')." a, ".$db->prefix('gs_images')." b WHERE";
		$sql.= " a.id_set='".$set->id()."' AND b.id_image=a.id_image AND b.public=2 AND b.owner='".$set->owner()."' ORDER BY b.created DESC LIMIT 0,1" ;

		$resimg = $db->query($sql);
		if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
		if ($db->getRowsNum($resimg)>0){
			$rowimg = $db->fetchArray($resimg);
			$img = new GSImage();
			$img->assignVars($rowimg);
			$urlimg = $users[$set->owner()]->filesURL().'/'.($mc['set_format_mode'] ? 'formats/set_' : 'ths/').$img->image();
			
			// Conversion de los formatos
			if (!$img->setFormat() && $mc['set_format_mode']){
				GSFunctions::resizeImage($crop, $users[$set->owner()]->filesPath().'/'.$img->image(),$users[$set->owner()]->filesPath().'/formats/set_'.$img->image(), $width, $height);
				$img->setSetFormat(1, 1);
			}
		} else {
			$urlimg = '';
		}
	
		$tpl->append('sets',array('id'=>$set->id(),'title'=>$set->title(),'date'=>$tf->format($set->date()),
		'owner'=>$set->owner(),'uname'=>$set->uname(),'pics'=>$set->pics(),'img'=>$urlimg,
		'link'=>$users[$set->owner()]->userURL().'set/'.$set->id().'/',
		'linkuser'=>$users[$set->owner()]->userURL()));

	}
	
	$tpl->assign('lang_date',__('Created on:','galleries'));
	$tpl->assign('lang_by',__('Owned by:','galleries'));
	$tpl->assign('max_cols',$cols);
	$tpl->assign('margin',$width+20);
	$tpl->assign('lang_imgs', __('Images:','galleries'));
	$tpl->assign('usr',isset($owner) ? 1 : 0);
	$tpl->assign('lang_setsexist',__('Existing Albums','galleries'));

	if (isset($owner)){
		$tpl->assign('user', array('id'=>$owner->uid(),'uname'=>$owner->uname(),'avatar'=>$owner->userVar('user_avatar')));
	
	$tpl->assign('lang_setsof',sprintf(__('Sets of %s','galleries'),$owner->uname()));
	$tpl->assign('pics_link', GS_URL.'/'.($mc['urlmode'] ? "usr/".$owner->uname() : "user.php?id=usr/".$owner->uname()."/"));
	$tpl->assign('tags_link', GS_URL.'/'.($mc['urlmode'] ? "explore/tags/usr/".$owner->uname() : "explore.php?by=explore/tags/usr/".$owner->uname()."/"));
	$tpl->assign('bmark_link', GS_URL.'/'.($mc['urlmode'] ? "cpanel/booksmarks/" : "cpanel.php?s=cpanel/bookmarks"));
	}
	

	include 'footer.php';
}


/**
* desc Visualiza todas las imágenes existentes
**/
function pics(){

	global $tpl, $xoopsOption, $exmUser, $xoopsModuleConfig, $db;
	
	$mc =& $xoopsModuleConfig;
	
	$xoopsOption['template_main'] = 'gs_pics.html';
	$xoopsOption['module_subpage'] = 'explorepics';
	include 'header.php';

	GSFunctions::makeHeader();

	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_images')." WHERE public=2";
    
    global $page;
	$limit = $mc['limit_pics'];

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
            $nav->target_url(GSFunctions::get_url().($mc['urlmode'] ? 'explore/photos/pag/{PAGE_NUM}/' : '?explore=photos&amp;pag={PAGE_NUM}'));
            $tpl->assign('picsNavPage', $nav->render(false));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(__('Showing <strong>%u</strong> to <strong>%u</strong> from <strong>%u</strong> photos.','galleries'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_images')." WHERE public=2";
	$sql .= " ORDER BY created DESC LIMIT $start,$limit";
	$result = $db->query($sql);
	
    $tpl->assign('images', GSFunctions::process_image_data($result));

	$tpl->assign('lang_exist',__('Existing photos','galleries'));

	include ('footer.php');
}

/**
* @desc Visualiza las etiquetas existentes
**/
function tags(){

	global $tpl, $xoopsOption, $xoopsUser, $xoopsModuleConfig, $db, $pag, $usr, $xoopsConfig, $tag;
	
	$xoopsOption['template_main'] = 'gs_tags.html';
	$xoopsOption['module_subpage'] = 'tags';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();

	//Verificamos si el usuario existe
	if (isset($usr)){
		$user = new GSUser($usr);
		if($user->isNew()){
			redirect_header(XOOPS_URL.'/modules/galleries/',1,__('Specified user does not exists!','galleries'));
			die();
		}
	}


	//Obtenemos la etiqueta de mayor hit
	if (!$usr){
		$sql="SELECT MAX(hits) FROM ".$db->prefix('gs_tags');
	}else{
		$sql = "SELECT  MAX(hits) FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages');
		$sql.=" b INNER JOIN ".$db->prefix('gs_images')." c ON (a.id_tag=b.id_tag AND  b.id_image=c.id_image ";
		$sql.= " AND c.owner=".$user->uid().")";
	}

	list($maxhit)=$db->fetchRow($db->query($sql));

	//Obtenemos la lista de etiquetas
	if (!$usr){
		$sql = "SELECT * FROM ".$db->prefix('gs_tags')." ORDER BY tag";
	}else{
		$sql = "SELECT a.* FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages');
		$sql.=" b INNER JOIN ".$db->prefix('gs_images')." c ON (a.id_tag=b.id_tag AND  b.id_image=c.id_image ";
		$sql.= " AND c.owner=".$user->uid().")";
		$sql.= "  GROUP BY a.id_tag ORDER BY tag LIMIT 0,".$mc['num_tags'];
	}

	$result = $db->query($sql);
    
	$sz = $maxhit>0 ? $mc['font_tags']/$maxhit : 11;
	while($rows = $db->fetchArray($result)){
		
		$tag = new GSTag();
		$tag->assignVars($rows);

		$size=intval($tag->hits()*$sz);
		if ($size<10){
			$size=10;
		}	

		if ($mc['urlmode']){
			$link = GSFunctions::get_url()."explore/tags/tag/".$tag->getVar('nameid').'/';
			$link.= ($usr ? "usr/".$user->uname()."/" : '');
		}else{
			$link = GSFunctions::get_url()."?explore=tags&amp;tag=".$tag->getVar('nameid');
			$link.= $usr ? "&amp;usr=".$user->uname() : '';
		}

		$tpl->append('tags',array('id'=>$tag->id(),'tag'=>$tag->tag(),'hits'=>$tag->hits(),'size'=>$size,
		'link'=>$link));
	}

	$tpl->assign('font',$mc['font_tags']);
	$tpl->assign('lang_hits', __('Hits:','galleries'));
	$tpl->assign('lang_tagsof',$usr ? sprintf(__('Tags of %s','galleries'),$user->uname()) : __('Existing tags','galleries'));
	$tpl->assign('lang_pics',__('Pictures','galleries'));
	$tpl->assign('lang_bmark',__('Favorites','galleries'));
	$tpl->assign('usr',$usr);

	if ($usr){
		$tpl->assign('pics_link', GS_URL.'/'.($mc['urlmode'] ? "usr/".$user->uname() : "user.php?id=usr/".$user->uname()."/"));
		$tpl->assign('sets_link', GS_URL.'/'.($mc['urlmode'] ? "explore/sets/usr/".$user->uname()."/" : "explore.php?by=explore/sets/usr/".$user->uname()."/"));
		$tpl->assign('bmark_link', GS_URL.'/'.($mc['urlmode'] ? "cpanel/booksmarks/" : "cpanel.php?s=cpanel/bookmarks"));
	}

	
	include('footer.php');
}


/**
* @desc Visualiza todas las imágenes de la etiqueta especificada
**/
function imgsTag(){

	global $tpl, $xoopsOption, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $db, $page, $tag, $usr, $hits;
	
	$xoopsOption['template_main'] = 'gs_imagestag.html';
	$xoopsOption['module_subpage'] = 'exploretags';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();

	//Verificamos si la etiqueta existe
	$tag = new GSTag($tag);
	if($tag->isNew()){
		redirect_header(GSFunctions::get_url(),1, __('Specified tag does not exists!','galleries'));
		die();
	}

	//Incrementamos el número de hits de la etiqueta
	if(!isset($_SESSION['vtags'])){
		$tag->addHit();
		$_SESSION['vtags']=array($tag->id());
	}elseif(!in_array($tag->id(),$_SESSION['vtags'])){
		$tag->addHit();
		$_SESSION['vtags'][]=$tag->id();	
	}
	

	//Verificamos si el usuario existe
	if (isset($usr)){
		$user = new GSUser($usr);
		if($user->isNew()){
			redirect_header(GSFunctions::get_url(),1, __('Specified user does not exists!','galleries'));
			die();
		}
		$users[$user->uid()] = $user;
	}


	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tagsimages')." a INNER JOIN ".$db->prefix('gs_images');
	$sql.= " b ON (a.id_tag=".$tag->id()." AND a.id_image=b.id_image  AND b.public=2";
	$sql.= $usr ? " AND b.owner=".$user->uid().") " : ")";

	$limit = $xoopsModuleConfig['num_imgstags'];

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
	        if($mc['urlmode']){
		        $urlnav = 'explore/tags/tag/'.$tag->getVar('nameid').'/';
                $urlnav.= $usr ? 'usr/'.$user->uname().'/' : '';
	        }else{
		        $urlnav = '?xplore=tags&amp;tag='.$tag->getVar('nameid');
                $urlnav.= $usr ? '&amp;usr='.$user->uname() : '';
	        }

    	    $nav = new RMPageNav($num, $limit, $pactual, 5);
    	    $nav->target_url(GSFunctions::get_url().$urlnav.($mc['urlmode'] ? 'pag/{PAGE_NUM}/' : '&amp;pag={PAGE_NUM}'));
    	    $tpl->assign('tagsNavPage', $nav->render(false));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(__('Showing photos %u to %u from %u.','galleries'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	//Obtenemos las imágenes pertenecientes a la etiqueta
	$sql = "SELECT b.* FROM ".$db->prefix('gs_tagsimages')." a INNER JOIN ".$db->prefix('gs_images');
	$sql.= " b ON (a.id_tag=".$tag->id()." AND a.id_image=b.id_image  AND b.public=2";
	$sql.= $usr ? " AND b.owner=".$user->uid().") " : ")";
	$sql.=" GROUP BY b.id_image";
	$sql.= " ORDER BY `created` DESC LIMIT $start, $limit";
	$result = $db->query($sql);
	
    $tpl->assign('images', GSFunctions::process_image_data($result));
	
	$tpl->assign('lang_picstag',$usr ? sprintf(__('%s: tagged as %s','galleries'),$user->uname(),$tag->tag()) : sprintf(__('Images tagged as "%s"','galleries'),$tag->tag()));
	$tpl->assign('tagname',$tag->tag());
	$tpl->assign('usr',$usr);
	
	if ($usr){
		$tpl->assign('pics_link', GS_URL.'/'.($mc['urlmode'] ? "usr/".$user->uname() : "user.php?id=usr/".$user->uname()."/"));
		$tpl->assign('tags_link', GS_URL.'/'.($mc['urlmode'] ? "explore/tags/usr/".$user->uname()."/" : "explore.php?by=explore/tags/usr/".$user->uname()."/"));
		$tpl->assign('sets_link', GS_URL.'/'.($mc['urlmode'] ? "explore/sets/usr/".$user->uname()."/" : "explore.php?by=explore/sets/usr/".$user->uname()."/"));
		$tpl->assign('bmark_link', GS_URL.'/'.($mc['urlmode'] ? "cpanel/booksmarks/" : "cpanel.php?s=cpanel/bookmarks"));
	}
	
	include('footer.php');
}

switch ($explore){
	case 'sets':
		sets();
		break;
	case 'photos':
		pics();
		break;
	case 'tags':
		if (isset($tag)){
			imgsTag();
		} else {
			tags();
		}
		break;
}
