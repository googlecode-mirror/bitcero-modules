<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION','content');
include '../../mainfile.php';
include_once 'include/functions.php';

if ($print && !$xoopsModuleConfig['print']){
	redirect_header(ah_make_link(''), 2, _MS_AH_NOPRINT);
	die();
}

if (trim($id_sec)==''){
	redirect_header(ah_make_link(''), 2, _MS_AH_NOID);
	die();
}

if (trim($id_res)==''){
	redirect_header(ah_make_link(''), 2, _MS_AH_NOID);
	die();
}

$browser = $_SERVER['HTTP_USER_AGENT'];
$pos = stripos($browser, 'Mozilla/5');
$xoopsModuleConfig['refs_method'] = $pos!==false ? $xoopsModuleConfig['refs_method'] : 0;

/**
* @desc Muestra el contenido completo de una sección
*/
function showSection(AHResource &$res, AHSection &$section){
	global $xoopsUser, $xoopsModuleConfig, $db, $tpl, $xoopsOption, $print;
	
	// Comprobamos si la sección especificada es una sección ráiz en el
	// recurso especificado
	if ($section->parent()>0){
		// Obtenemos el id del parent correcto
		$parent = getSuperParent($section->parent());
		$link = ah_make_link($res->nameId().'/'.$parent['nameid']);
		header('location: '.$link.'#'.$section->nameId());
	}
	
	$xoopsOption['template_main'] = 'ahelp_section.html';
	$xoopsOption['module_subpage'] = 'content';
	include 'header.php';
	makeHeader();
	makeFooter();
	$sql = "SELECT * FROM ".$db->prefix("pa_sections")." WHERE parent='".$section->id()."' AND id_res='".$res->id()."' ORDER BY `order`";
	$result = $db->query($sql);
	// Almacenamos los datos de la sección raíz
	$tpl->append('sections', array('id'=>$section->id(),'title'=>$section->title(),'content'=>ahParseReferences(trim($section->content())),
			'edited'=>ahFormatDate($section->modified()),'jump'=>0,'nameid'=>$section->nameId(), 'link'=>$res->nameId().'/'.$section->nameId()));
	$tpl->append('pre_index', array('nameid'=>$section->nameId(), 'id'=>$section->id(), 'title'=>$section->title(),'jump'=>0));
	$edited = $section->modified();
	$editdata = array('modified'=>$section->modified(),'uid'=>$section->uid(),'uname'=>$section->uname());
	$i = 1;
	
	while ($row = $db->fetchArray($result)){
		$tree = array();
		$sec = new AHSection();
		$sec->assignVars($row);
		getSectionTree($tree, $sec->id(), 2);
		$tpl->append('sections', array('id'=>$sec->id(),'title'=>$sec->title(),'content'=>ahParseReferences(trim($sec->content())),
			'edited'=>ahFormatDate($sec->modified()),'jump'=>1,'nameid'=>$sec->nameId(),'havemore'=>count($tree)>0,
			'link'=>$res->nameId().'/'.$sec->nameId()));
		if ($sec->modified()>$edited){
			$edited = $sec->modified();
			$editdata = array('modified'=>$edited, 'uid'=>$sec->uid(),'uname'=>$sec->uname());
		}
		
		if ($i<=7){
            $tpl->append('pre_index', array('nameid'=>$sec->nameId(), 'id'=>$sec->id(), 'title'=>$sec->title(),'jump'=>1));
        }
		
		foreach ($tree as $subsec){
			$sub = new AHSection();
			$sub->assignVars($subsec);
			$tpl->append('sections', array('id'=>$sub->id(),'title'=>$sub->title(),'content'=>ahParseReferences(trim($sub->content())),
			'edited'=>ahFormatDate($sub->modified()),'jump'=>$subsec['saltos'],'nameid'=>$sub->nameId(), 'link'=>$res->nameId().'/'.$sub->nameId()));
			if ($sub->modified()>$edited){
				$edited = $sub->modified();
				$editdata = array('modified'=>$edited, 'uid'=>$sub->uid(),'uname'=>$sub->uname());
			}
			if ($i<=7){
	            $tpl->append('pre_index', array('nameid'=>$sub->nameId(), 'id'=>$sub->id(), 'title'=>$sub->title(),'jump'=>$subsec['saltos']));
	        }
	        $i++;
			
		}
		
		$i++;
		
	}
	
	$tpl->assign('index_rest', $i-7);
    $tpl->assign('lang_indexmore', sprintf(_MS_AH_INDEXMORE, $i-7));
	
   	$tpl->assign('show_navigation', 1);
	$tpl->assign('index_link', ah_make_link($res->nameId()));
    $tpl->assign('lang_more', _MS_AH_MORE);
    $tpl->assign('lang_less', _MS_AH_LESS);
	
	$tpl->assign('page_title', $res->title());
    $tpl->assign('xoops_pagetitle', $section->title()." &laquo; ".$res->title());
	$tpl->assign('lang_find',_MS_AH_FIND);
	$tpl->assign('lang_findlabel',_MS_AH_FINDLABEL);
	$tpl->assign('lang_contents', _MS_AH_CONTENTSSEC);
	if ($mc['print']) $tpl->assign('lang_print', _MS_AH_PRINT);
	$tpl->assign('lang_edit', _MS_AH_EDIT);
	$tpl->assign('last_edited', sprintf(_MS_AH_LASTEDITED, ahFormatDate(xoops_getUserTimestamp($edited)), $editdata['uname']));
	$tpl->assign('lang_rating', _MS_AH_RATING);
	$tpl->assign('lang_rate', _MS_AH_RATE);
	$tpl->assign('lang_reads', sprintf(_MS_AH_READS, $res->reads()));
	$tpl->assign('lang_votes', sprintf(_MS_AH_VOTES, $res->votes()));
	$rating = @floor($res->rating()/$res->votes());
	$rating = $rating > 5 ? 5 : $rating;
	$rating = $rating <=0 ? 0 : $rating;
	$tpl->assign('res_rating', $rating);
	$tpl->assign('article_desc', $res->desc());
	$tpl->assign('show_tip', $mc['refs_method']);
	$tpl->assign('lang_loading', _MS_AH_LOADING);
	$tpl->assign('lang_close', _MS_AH_CLOSE);
	$tpl->assign('lang_references', _MS_AH_REFS);
	$tpl->assign('res_id', $res->id());
	$tpl->assign('print_link', ah_make_link('print/'.$res->nameId().'/'.$section->nameId()));
	$tpl->assign('edit_link', ah_make_link('list/'.$res->nameId().'/'));
	$tpl->assign('lang_top', _MS_AH_TOP);
    $tpl->assign('lang_nocontent', _MS_AH_NOCONTENT_LEGEND);
	
	//Determinamos si usuario tiene permisos de editar el recurso
	//Determinamos si usuario tiene permisos de editar el recurso
	if (!$xoopsUser){
		$edit = false;
	} else {
		if ($xoopsUser->uid()==$res->owner() || $xoopsUser->isAdmin() || 
			$res->isEditor($xoopsUser->uid())){
			$edit=true;
		}else{
			$edit = false;
		}
	}
	
	// Navegación de Secciones
	$sql = "SELECT * FROM ".$db->prefix("pa_sections")." WHERE id_res='".$res->id()."' AND parent = '0' ORDER BY `order`";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$sec = new AHSection();
		$sec->assignVars($row);
		
		if ($sec->id()==$section->id() && isset($sprev)){
			$link = ah_make_link($res->nameId().'/'.$sprev->nameId());
			$tpl->assign('previous', array('id'=>$sprev->id(), 'title'=>$sprev->title(),'link'=>$link));
		}
		
		if ($sec->order()>$section->order()){
			$link = ah_make_link($res->nameId().'/'.$sec->nameId());
			$tpl->assign('next', array('id'=>$sec->id(), 'title'=>$sec->title(),'link'=>$link));
			break;
		}
		
		$sprev = $sec;
	}
	
	$tpl->assign('show_navigation', 1);
	$tpl->assign('lang_index', _MS_AH_INDEX);
	$tpl->assign('edit',$edit);
	//Formato de la información
    $tpl->assign('index_width', $mc['index_width']);
	
	$location = "<a href='".ah_make_link()."'>"._MS_AH_HOME."</a> &raquo; ";
	$location .= "<a href='".ah_make_link($res->nameId())."'>".$res->title()."</a>";
	$location .= " &raquo; ".$section->title();
	$tpl->assign('location_bar', $location);
	
	
	if ($print){
		
		$tpl->assign('show_print', 1);
		$url = ah_make_link($res->nameId().'/'.$section->nameId().'/');
		$tpl->assign('lang_print_from', sprintf(_MS_AD_PRINTINFO, $xoopsConfig['sitename'], $url));
		$tpl->assign('index_width', '10');
		$tpl->assign('file', 'ahelp_section.html');
		echo $tpl->fetch("db:ahelp_printpage.html");
		
	} else {	
		include 'footer.php';
	}
	
}

// Recurso
$res = new AHResource($id_res);
if ($res->isNew()){
	redirect_header(ah_make_link(''), 2, _MS_AH_NOID);
	die();
}

// Sección
$section = new AHSection($id_sec, $res->id());

if ($section->isNew()){
	redirect_header(ah_make_link(''), 2, _MS_AH_NOID);
	die();
}

//Verificamos si es una publicación aprobada
if (!$res->approved()){
	redirect_header(ah_make_link(),2,_MS_AH_NOTAPPROVED);
	die();
}

// Comprobamos permisos
if (!$res->isAllowed($xoopsUser ? $xoopsUser->groups() : XOOPS_GROUP_ANONYMOUS)){
	redirect_header(ah_make_link(), 2, _MS_AH_NOALLOWED);
	die();
}

addRead($res);

showSection($res, $section);
