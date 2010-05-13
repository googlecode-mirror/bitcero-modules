<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

if (isset($special) && ($special=='references' || $special=='figures')){
	define('AH_LOCATION','content');
} else {
	define('AH_LOCATION','resources');
}
include ('../../mainfile.php');

if (isset($special) && ($special=='references' || $special=='figures')){
	$xoopsOption['template_main'] = 'ahelp_figures_resume.html';
	$xoopsOption['module_subpage'] = 'content';
} else {
	$xoopsOption['template_main']='ahelp_resources.html';
	$xoopsOption['module_subpage'] = 'resource';
}

//Verifica que se haya proporcionado una publicación
if (trim($id)==''){
	redirect_header(AHURL,2,_MS_AH_NOTRESOURCE);
	die();
}

//Verifica que la publicación exista
$res= new AHResource($id);
if ($res->isNew()){
	header("Status: 404");
	redirect_header(AHURL,2,_MS_AH_NOTEXIST);
	die();
}

include_once 'include/functions.php';
include ('header.php');

$myts=&MyTextSanitizer::getInstance();
$id = $myts->addSlashes($id);

//Verificamos si la publicacion esta aprobada
if (!$res->approved()){
	redirect_header(AHURL,1,_MS_AH_NOTAPPROVED);
	die();
}

//Verifica si el usuario cuenta con permisos para ver la publicación
$allowed = $res->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS);
if (!$allowed && !$res->showIndex()){
	redirect_header(AHURL,2,_MS_AH_NOTPERM);
	die();
}

// Show Figures
if (isset($special) && $special=='figures'){
	
	$figs = $res->get_figures();
	
	foreach ($figs as $fig){
		$ret = "<div ";
	    if ($fig['class']!='') $ret .= "class='".$fig['class']."' ";
	    if ($fig['style']!='') $ret .= "style='".$fig['style']."' ";
	    
	    $ret .= ">".$fig['text'];
	    
	    $ret .= "</div>";
	    $tpl->append('figures', array(
	    	'title'=>$fig['desc'],
	    	'content'=>$ret
	    ));
	}
	
	$tpl->assign('what', 'figures');
	$tpl->assign('page_title', sprintf(_MS_AH_FIGSIN, $res->title()));
	makeHeader();
	
	makeFooter();
	include 'footer.php';
	exit();
}

if (isset($special) && $special=='references'){
	
	$refs = $res->get_references();
	foreach ($refs as $ref){
	    $tpl->append('references', array(
	    	'title'=>$ref['title'],
	    	'content'=>$myts->displayTarea($ref['text'], $ref['dohtml'], $ref['dosmiley'], $ref['doxcode'], $ref['doimage'], $ref['dobr'])
	    ));
	}
	
	$tpl->assign('page_title', sprintf(_MS_AH_REFSIN, $res->title()));
	$tpl->assign('what', 'references');
	makeHeader();
	
	makeFooter();
	include 'footer.php';
	exit();
}

if (!$allowed && !$res->quick()){
	redirect_header(AHURL,2,_MS_AH_NOTPERM);
	die();
}

if ($res->quick()){
	$content=false;
	//Obtiene índice
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='".$res->id()."' AND parent=0 ORDER BY `order`";
	$result=$db->queryF($sql);

	while ($rows=$db->fetchArray($result)){
		$sec=new AHSection();
		$sec->assignVars($rows);
		//$slink = $link;
		$link = ah_make_link($res->nameId().'/'.$sec->nameId());
		$tpl->append('sections',array('id'=>$id,'title'=>$sec->title(),
		'desc'=>substr($util->filterTags($sec->content()), 0, 255).(strlen($sec->content())>255 ? '...' : ''),
		'link'=>$link));
	}
	
	$tpl->assign('template', 'ahelp_quickindex.html');
	$tpl->assign('lang_index',_MS_AH_QINDEX);
	
} else {
	
	if (!$allowed){
		redirect_header(AHURL,2,_MS_AH_NOTPERM);
		die();
	}
	
	$tpl->assign('template', 'ahelp_resindex.html');
	$tpl->assign('lang_index', _MS_AH_INDEX);
	
	assignSectionTree(0, 0, $res, 'index');
	
	// Get the references and figures for this resource
	$refs =& $res->get_references();
	$figs =& $res->get_figures();
	
	if (!empty($refs) || !empty($figs)){
		$tpl->append('index', array('title'=>_MS_AH_SPECIALS, 'nameid'=>'', 'link'=>'', 'jump'=>0, 'number'=>''));
	}
	
	if (!empty($figs)){
		$tpl->append('index', array(
			'title'=>_MS_AH_FIGS,
			'nameid'=>'',
			'link'=>AHURL.'/figures/'.$res->nameId(),
			'jump'=>1,
			'number'=>'a'
		));
	}
	
	if (!empty($refs)){
		$tpl->append('index', array(
			'title'=>_MS_AH_REFS,
			'nameid'=>'',
			'link'=>AHURL.'/references/'.$res->nameId(),
			'jump'=>1,
			'number'=>'b'
		));
	}
	
}

$tpl->assign('resource',$res->title());
$tpl->assign('resource_desc',$res->desc());
$tpl->assign('xoops_pagetitle', $res->title() . " &raquo; " . $xoopsModule->name());
$tpl->assign('lang_home',_MS_AH_HOME);
$tpl->assign('lang_indexpublic',_MS_AH_INDEXPUBLIC);
$tpl->assign('id',$id);
$tpl->assign('access',$xoopsModuleConfig['access']);
$tpl->assign('url',AHURL);
$tpl->assign('id_public',$res->nameId());

makeHeader();

$tpl->assign('lang_titleheader', $res->title());

makeFooter();

include ('footer.php');
