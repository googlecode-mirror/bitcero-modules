<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2



define('AH_LOCATION', 'figs');
include 'header.php';

/**
* @desc Muestra todas las figuras existentes
**/
function showFigures(){
	global $tpl,$db,$adminTemplate,$xoopsModule,$util;

	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	//Separamos frase en palabras
	$words=explode(" ",$search);

	//Navegador de páginas
	$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_figures').($id_res ? " WHERE id_res='$id_res'" : '');
	$sql1='';
	if ($search){
		foreach ($words as $k){
			//verifica que palabra sea mayor a 2 letras	
			if (strlen($k)<=2) continue;
			$sql1.=($sql1=='' ? ($id_res ? " AND " : " WHERE ") : " OR ")." (`desc` LIKE '%$k%' )";	

		}	
	}
	list($num) = $db->fetchRow($db->query($sql.$sql1));
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
		$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&search='.$search.'&res='.$id_res, 0);
		$tpl->assign('figNavPage', $nav->renderNav(4, 1));
	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_AH_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);

	//Fin de navegador de páginas

	$sql = str_replace("COUNT(*)","*", $sql);
    $sql2=" LIMIT $start,$limit";
	$result=$db->queryF($sql.$sql1.$sql2);
	while ($rows=$db->fetchArray($result)){
		$fig=new AHFigure();
		$fig->assignVars($rows);
		
		$res=new AHResource($fig->resource());

		$tpl->append('figures',array('id'=>$fig->id(),'desc'=>$fig->desc(),'resource'=>$res->title()));		

	}


	//Lista de publicaciones
	$sql="SELECT id_res,title FROM ".$db->prefix('pa_resources');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$res=new AHResource();
		$res->assignVars($rows);

		$tpl->append('resources',array('id'=>$res->id(),'title'=>$res->title()));

	}

	$tpl->assign('lang_id',_AS_AH_ID);
	$tpl->assign('lang_desc',_AS_AH_DESC);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_delete',_DELETE);
	$tpl->assign('lang_exist',_AS_AH_EXIST);
	$tpl->assign('token',$util->getTokenHTML());
	$tpl->assign('lang_res',_AS_AH_RESOURCE);
	$tpl->assign('lang_search',_AS_AH_SEARCH);
	$tpl->assign('lang_select',_SELECT);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('lang_result',_AS_AH_RESULT);
	$tpl->assign('res',$id_res);
	$tpl->assign('search',$search);


	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_FIGS);
	$adminTemplate = 'admin/ahelp_figs.html';
	xoops_cp_header();
	 
	xoops_cp_footer();

}

/**
* @desc Permite la edición de figuras
**/
function editFigures(){
	global $xoopsModule,$xoopsConfig;

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_FIGS);
	$adminTemplate = 'admin/ahelp_figs.html';
	xoops_cp_header();
	 

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta="?res=$id_res&search=$search&limit=$limit&pag=$page";

	//Verifica que figura sea válida
	if ($id<=0){
		redirectMsg('./figs.php'.$ruta,_AS_AH_FIGNOTVALID,1);
		die();
	}

	//Verifica que figura exista
	$fig=new AHFigure($id);
	if ($fig->isNew()){
		redirectMsg('./figs.php'.$ruta,_AS_AH_FIGNOTEXIST,1);
		die();
	}

	
	$form=new RMForm(_AS_AH_EDIT,'frmfig','figs.php');
	
	$form->addElement(new RMText(_AS_AH_DESC,'desc',50,255,$fig->desc()),true);
	$form->addElement(new RMEditor(_AS_AH_FIG,'figure','90%','200px',$fig->figure(),$xoopsConfig['editor_type']),true);
	
	$form->addElement(new RMTextOptions(_OPTIONS, $fig->HTML(),$fig->code(),$fig->image(),$fig->smiley(),$fig->br()));

	$form->addElement(new RMText(_AS_AH_CLASS,'class',50,150,$fig->_class()));
	$form->addElement(new RMText(_AS_AH_STYLE,'style',50,255,$fig->style()));

	$buttons=new RMButtonGroup();

	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'figs.php'.$ruta.'\';"');	

	$form->addElement($buttons);

	$form->addElement(new RMHidden('op','save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('res',$id_res));
	$form->addElement(new RMHidden('search',$search));
	$form->addElement(new RMHidden('limit',$limit));
	$form->addElement(new RMHidden('pag',$page));
	

	$form->display();

		xoops_cp_footer();
}

/**
* @desc Almacena información perteneciente a la figura
**/
function saveFigures(){

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$ruta="?res=$id_res&search=$search&limit=$limit&pag=$pag";

	//Verifica que figura sea válida
	if ($id<=0){
		redirectMsg('./figs.php',_AS_AH_FIGNOTVALID,1);
		die();
	}

	//Verifica que figura exista
	$fig=new AHFigure($id);
	if ($fig->isNew()){
		redirectMsg('./figs.php',_AS_AH_FIGNOTEXIST,1);
		die();
	}

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
		redirectMsg('./figs.php'.$ruta,_AS_AH_DBERROR,1);
		die();
	}else{
		redirectMsg('./figs.php'.$ruta,_AS_AH_DBOK,0);
	}
    
}

/**
* @desc Elimina figuras
**/
function deleteFigures(){
	global $xoopsModule,$util;

	$figures=isset($_REQUEST['figs']) ? $_REQUEST['figs'] : null;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta="?res=$id_res&search=$search&limit=$limit&pag=$page";

	//Comprueba si se proporciono una figura
	if (!is_array($figures) && $figures<=0){
		redirectMsg('./figs.php'.$ruta,_AS_AH_NOTFIG,1);
		die();	
	}

	if (!is_array($figures)) $figures=array($figures);
	
	if ($ok){	
		
		if (!$util->validateToken()){
			redirectMsg('./figs.php'.$ruta,_AS_AH_SESSINVALID,1);
			die();
		}

		$errors='';

		foreach ($figures as $k){
			//Determina si la figura es válida
			if ($k<=0){
				$errors.=sprintf(_AS_AH_NOTVALID,$k);
				continue;
			}

			//Determina si la figura existe
			$fig=new AHFigure($k);
			if ($fig->isNew()){
				$errors.=sprintf(_AS_AH_NOTEXIST,$k);
				continue;
			}

			//Elimina Figura
			if (!$fig->delete()){
				$errors.=sprintf(_AS_AH_NOTDELETE,$k);
			}


		}

		if ($errors!=''){
			redirectMsg('./figs.php'.$ruta,_AS_AH_ERRORS.$errors,1);
			die();

		}else{
			redirectMsg('./figs.php'.$ruta,_AS_AH_DBOK,0);
			die();

		}
		
	
	
	}else{
		
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_FIGS);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['figs[]'] = $figures;
		$hiddens['op'] = 'delete';
		$hiddens['res'] = $id_res;
		$hiddens['search'] = $search;
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $page;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'figs.php'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'figs.php', _AS_AH_DELETECONF. '<br /><br />' ._AS_AH_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}


}

$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'edit':
		editFigures();
	break;
	case 'save':
		saveFigures();
	break;
	case 'delete':
		deleteFigures();
	break;
	default:
		showFigures();

}
?>