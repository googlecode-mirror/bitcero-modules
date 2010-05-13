<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


define('AH_LOCATION', 'figures');
include ('../../mainfile.php');
include 'header.php';

// Mensajes de Error
if (isset($_SESSION['exmMsg'])){
	$tpl->assign('showExmInfoMsg', 1);
	$tpl->assign('exmInfoMessage', array('text'=>html_entity_decode($_SESSION['exmMsg']['text']),'level'=>$_SESSION['exmMsg']['level']));
	unset($_SESSION['exmMsg']);
}

$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$res=new AHresource($id);
//Verificamos si el usuario tiene permisos de edicion
if (!$xoopsUser){
	redirect_header(XOOPS_URL.'/modules/ahelp',2,_MS_AH_NOTPERMEDIT);
	die();
}else{
	if (!($xoopsUser->uid()==$res->owner()) && 
	!$res->isEditor($xoopsUser->uid()) && 
	!$xoopsUser->isAdmin()){
		redirect_header(XOOPS_URL.'/modules/ahelp',2,_MS_AH_NOTPERMEDIT);
		die();
	}
}

/**
* @desc Visualiza  todas las figuras existentes de una publicación
**/
function Figures(){
	global $db,$tpl,$util;

	$myts=&MyTextSanitizer::getInstance();
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$search=isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';

	//Navegador de páginas
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_figures')." WHERE id_res=$id";
	$sql1='';
	if ($search){
		
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
		
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;	
			$sql1.=($sql1=='' ? ' AND ' : " OR "). " `desc` LIKE '%$k%' ";
		}	
	
	}
	list($num)=$db->fetchRow($db->queryF($sql.$sql1));
	
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
    	$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&id='.$id.'&section='.$id_sec.'&search='.$search, 0);
    	$tpl->assign('figNavPage', $nav->renderNav(4, 1));
    }

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_AH_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin navegador de páginas

	$sql="SELECT * FROM ".$db->prefix('pa_figures')." WHERE id_res=$id";
	$sql1='';
	if ($search){
		
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
		
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;	
			$sql1.=($sql1=='' ? ' AND ' : " OR "). " `desc` LIKE '%$k%' ";
		}	
	
	}
	$sql2=" LIMIT $start,$limit ";
	
	$result=$db->queryF($sql.$sql1.$sql2);
	while ($rows=$db->fetchArray($result)){
		
		$fig=new AHFigure();
		$fig->assignVars($rows);
		$tpl->append('figures',array('id'=>$fig->id(),'desc'=>$fig->desc()));		
		
	}
	
	$tpl->assign('lang_id',_MS_AH_ID);
	$tpl->assign('lang_desc',_MS_AH_DESC);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_close',_CLOSE);
	$tpl->assign('lang_exist',_MS_AH_EXIST);
	$tpl->assign('lang_insert',_MS_AH_INSERT);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_new',_MS_AH_NEWF);
	$tpl->assign('id',$id);
	$tpl->assign('search',$search);	
	$tpl->assign('token', $util->getTokenHTML());
	$tpl->assign('lang_confirm',_MS_AH_CONFIRM);
	$tpl->assign('lang_search',_MS_AH_SEARCH);
	$tpl->assign('lang_submit',_SUBMIT);

	echo $tpl->fetch('db:ahelp_figures.html');

}

/**
* @desc Formulario de creación o edición de figuras
**/
function formFigures($edit=0){
	global $xoopsConfig,$tpl;	
	
	$myts=&MyTextSanitizer::getInstance();
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$id_fig=isset($_REQUEST['fig']) ? intval($_REQUEST['fig']) : 0;
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
	$search=isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';
	$ruta='id='.$id.'&limit='.$limit.'&pag='.$pag.'&search='.$search;

	if ($edit){
		//Comprueba que la figura sea válida
		if ($id_fig<=0){
			redirectMsg('./figures.php?'.$ruta,_MS_AH_NOTFIG,1);
			die();
		}

		//Comprueba  si existe la figura		
		$fig=new AHFigure($id_fig);
		if ($fig->isNew()){
			redirectMsg('./figures.php?'.$ruta,_MS_AH_NOTFIGEXIST,1);
			die();	
		}

	}


	$form=new RMForm($edit ? _MS_AH_EDITF : _MS_AH_NEWF,'frmfig','figures.php');

	$form->addElement(new RMText(_MS_AH_DESC,'desc',50,255,$edit ? $fig->desc() : ''),true);
	$form->addElement(new RMEditor(_MS_AH_FIG,'figure','99%','200px',$edit ? $fig->figure() : '',$xoopsConfig['editor_type']),true);
	
	$form->addElement(new RMTextOptions(_OPTIONS,$edit ? $fig->HTML(): 1,$edit ? $fig->code() : 0,$edit ? $fig->image() : 0,$edit ? $fig->smiley() : 0,$edit ? $fig->br() : 0));

	$form->addElement(new RMText(_MS_AH_CLASS,'class',50,150,$edit ? $fig->_class() : ''));
	$form->addElement(new RMText(_MS_AH_STYLE,'style',50,255,$edit ? $fig->style() : ''));

	$buttons=new RMButtonGroup();

	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'figures.php?'.$ruta.'\';"');	

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('id_fig',$id_fig));
	$form->addElement(new RMHidden('limit',$limit));	
	$form->addElement(new RMHidden('pag',$pag));
	$form->addElement(new RMHidden('search',$search));
	$form->addElement($buttons);

	$tpl->assign('content_form',$form->render());
	
	echo $tpl->fetch('db:ahelp_figures.html');

}

/**
* @desc Almacena toda la información referente a la figura
**/
function saveFigures($edit=0){
	global $util;
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$ruta='id='.$id.'&limit='.$limit.'&pag='.$pag.'&search='.$search;

	if (!$util->validateToken()){
		redirectMsg('./figures.php?'.$ruta,_MS_AH_SESSINVALID, 1);
		die();
	}

	//Comprobar publicacion valida
	if ($id<=0){
		redirectMsg('./figures.php?'.$ruta,_MS_AH_RES,1);
		die();
	}
	
	//Comprobar publicación existente existente
	$res=new AHResource($id);
	if ($res->isNew()){
		redirectMsg('./figures.php?'.$ruta,_MS_AH_RESNOEXIST,1);
		die();

	}

	if ($edit){
		//Comprueba que la figura sea válida
		if ($id_fig<=0){
			redirectMsg('./figures.php?'.$ruta,_MS_AH_NOTFIG,1);
			die();
		}

		//Comprueba  si existe la figura		
		$fig=new AHFigure($id_fig);
		if ($fig->isNew()){
			redirectMsg('./figures.php?'.$ruta,_MS_AH_NOTFIGEXIST,1);
			die();	
		}

	}else{

		$fig=new AHFigure();
	}

	$fig->setResource($id);
	$fig->setClass($class);
	$fig->setStyle($style);
	$fig->setDesc($desc);
	$fig->setFigure($figure);
	$fig->setHTML($dohtml);
	$fig->setCode($doxcode);
	$fig->setBr($dobr);
	$fig->setImage($doimage);
	$fig->setSmiley($dosmiley);
	
	if (!$fig->save()){
		redirectMsg('./figures.php?op=new'.$ruta,_MS_AH_DBERROR,1);
		die();
	}else{
		redirectMsg('./figures.php?'.$ruta,_MS_AH_DBOK,0);
	}
	
}


/**
* @desc Elimina figuras
**/
function deleteFigures(){
	global $util;
	$myts=&MyTextSanitizer::getInstance();
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$id_fig=isset($_REQUEST['fig']) ? intval($_REQUEST['fig']) : 0;
	$figs=isset($_REQUEST['figs']) ? $_REQUEST['figs'] : array();
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
	$search=isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';
	$ruta='id='.$id.'&section='.$id_sec.'&limit='.$limit.'&pag='.$pag.'&search='.$search;
	
	if (!$util->validateToken()){
		redirectMsg('./figures.php?'.$ruta,_MS_AH_SESSINVALID, 1);
		die();
	}
	
	if (!is_array($figs) || empty($figs)){
		redirectMsg('./figures.php?'.$ruta,_MS_AH_FIGS,1);
		die();
	}
	$errors='';
	foreach ($figs as $k){

		//Verifica si la figura es válida
		if ($k<=0){
			$errors.=sprintf(_MS_AH_NOTVALID,$k);
			continue;
		}
		//Verifica si existe la figura
		$fig=new AHFigure($k);
		if ($fig->isNew()){
			$errors.=sprintf(_MS_AH_NOTEXIST,$k);
			continue;
		}

		$fig->delete();

	}

	if ($errors!=''){
		redirectMsg('./figures.php?'.$ruta,_MS_AH_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./figures.php?'.$ruta,_MS_AH_DBOK,0);

	}

}

$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'new':
		formFigures();
	    break;
	case 'edit':
		formFigures(1);
	    break;
	case 'save':
		saveFigures();
	    break;
	case 'saveedit':
		saveFigures(1);
	    break;
	case 'delete':
		deleteFigures();
	    break;
	default: 
		figures();
        break;
}
