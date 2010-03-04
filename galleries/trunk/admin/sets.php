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

define('GS_LOCATION','sets');
include 'header.php';

function optionsBar(){
	global $tpl;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "&pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";

	$tpl->append('xoopsOptions', array('link' => './sets.php', 'title' => _AS_GS_SETS, 'icon' => '../images/album16.png'));
	$tpl->append('xoopsOptions', array('link' => './sets.php?op=new'.$ruta, 'title' => _AS_GS_NEW, 'icon' => '../images/add.png'));
	
}

/**
* @desc Visualiza todos los albums
**/
function showAlbums(){
	global $tpl, $xoopsModule, $mc, $adminTemplate, $db, $util;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_sets');
	$sql1 = '';
	$words = array();
	if ($search){
		
		//Separamos en palabras
		$words = explode(" ",$search);
		foreach ($words as $k){
			$k = trim($k);
			
			if (strlen($k)<=2){
				continue;
			}
			
			$sql1.= $sql1=='' ? " WHERE (title LIKE '%$k%' OR uname LIKE '%$k%')" : " OR (title LIKE '%$k%' OR uname LIKE '%$k%')";			

		}
	}


	list($num)=$db->fetchRow($db->query($sql.$sql1));
	
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
    	    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&sort='.$sort.'&mode='.$mode.'&search='.$search, 0);
    	    $tpl->assign('setsNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	$tpl->assign('sort',$sort);
	$tpl->assign('mode',$mode);
	$tpl->assign('search',$search);
	//Fin de barra de navegación

	
	$sql = str_replace('COUNT(*)','*',$sql);
	
	$sql2 = $sort ? " ORDER BY $sort ".($mode ? "DESC" : "ASC ") : '';
	$sql2.= " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);
	while($rows = $db->fetchArray($result)){
		
		foreach ($words as $k){
			$rows['title'] = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['title']);
			$rows['uname'] = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['uname']);
		}
		
		$set = new GSSet();
		$set->assignVars($rows);

		$tpl->append('sets',array('id'=>$set->id(),'title'=>$set->title(),'owner'=>$set->uname(),
		'public'=>$set->isPublic(),'date'=>formatTimeStamp($set->date(),'string'),'pics'=>$set->pics(),
		'url'=>$set->url()));

	}


	$tpl->assign('lang_existing', _AS_GS_EXISTING);
	$tpl->assign('lang_id', _AS_GS_ID);
	$tpl->assign('lang_title', _AS_GS_TITLE);
	$tpl->assign('lang_owner', _AS_GS_OWNER);
	$tpl->assign('lang_public', _AS_GS_PUBLIC);
	$tpl->assign('lang_privatef',_AS_GS_PRIVFRIEND);
	$tpl->assign('lang_date', _AS_GS_CREATED);
	$tpl->assign('lang_pics', _AS_GS_PICS);
	$tpl->assign('lang_options', _OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('lang_publics',_AS_GS_PUBLICS);
	$tpl->assign('lang_nopublics',_AS_GS_PRIVATE);
	$tpl->assign('token',$util->getTokenHTML()); 
	$tpl->assign('lang_search',_AS_GS_SEARCH);

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_GS_SETSLOC);
	$adminTemplate = "admin/gs_albums.html";
	$cHead = '<link href="'.XOOPS_URL.'/modules/galleries/styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	xoops_cp_footer();
	
}


/**
* @desc Formulario de creación/edición de albums
**/
function formAlbums($edit = 0){

	global $xoopsModule, $xoopsUser;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	

	if($edit){
		//Verificamos que el album sea válido
		if ($id<=0){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRSETVALID,1);
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRSETEXIST,1);
			die();
		}
	}


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./sets.php'>"._AS_GS_SETSLOC."</a> &raquo; ".($edit ? _AS_GS_EDITSET : _AS_GS_NEWSET));
	xoops_cp_header();
	

	$form = new RMForm($edit ? _AS_GS_EDITSET : _AS_GS_NEWSET, 'frmsets','sets.php');
	$form->addElement(new RMText(_AS_GS_TITLE,'title',50,100,$edit ? $set->title() : ''),true);

	$ele = new RMSelect(_AS_GS_PRIVACY,'public');
	$ele->addOption(0,_AS_GS_PRIVATE,$edit ? ($set->isPublic()==0 ? 1 : 0) : 0);
	$ele->addOption(1,_AS_GS_PRIVFRIEND,$edit ? ($set->isPublic()==1 ? 1 : 0) : 0);
	$ele->addOption(2,_AS_GS_PUBLIC,$edit ? ($set->isPublic()==2 ? 1 : 0) : 0);

	$form->addElement($ele,true);

	$form->addElement(new RMFormUserEXM(_AS_GS_OWNER,'owner',0,$edit ? array($set->owner()) : array($xoopsUser->uid()),30));
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));	
	$form->addElement(new RMHidden('page',$page));	
	$form->addElement(new RMHidden('limit',$limit));	
	$form->addElement(new RMHidden('sort',$sort));	
	$form->addElement(new RMHidden('mode',$mode));	
	$form->addElement(new RMHidden('search',$search));	

	$buttons = new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'sets.php?'.$ruta.'\'"');

	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}

/**
* @desc Almacena en la base de datos todos los datos del album
**/
function saveAlbums($edit = 0){

	global $util, $db, $xoopsUser;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!isset($owner) || $owner<=0) $owner = $xoopsUser->uid();

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";

	if (!$util->validateToken()){
		redirectMsg('./sets.php?'.$ruta,_AS_GS_SESSINVALID,1);
		die();
	}


	if ($edit){
		//Verificamos que el album sea válido
		if ($id<=0){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRSETVALID,1);
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRSETEXIST,1);
			die();
		}

		$sql ="SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." WHERE title='$title' AND owner=$owner AND id_set<>".$set->id();
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./sets.php?op=edit&id='.$set->id().'&'.$ruta,_AS_GS_ERRTITLE,1);
			die();
		}
	}else{

		//Verificamos que el titulo del album no exista
		$sql ="SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." WHERE title='$title' AND owner=$owner";
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRTITLE,1);
			die();
		}

		$set = new GSSet();
	}

	$set->setTitle($title);
	$set->setPublic($public);
	$set->setDate(time());
	$set->setOwner($owner);
	$xu = new XoopsUser($owner); 
	$set->setUname($xu->uname());

	$new = $set->isNew();

	if (!$set->save()){
		redirectMsg('./sets.php?'.$ruta,_AS_GS_DBERROR.'<br />'.$set->errors(),1);
		die();
	}else{
		//Incrementamos el número de albums del usuario
		if ($new){
			$user = new GSUser($set->owner(), 1);
			$user->addSet();
		}

		redirectMsg('./sets.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}

}

/**
* @desc Elimina de la base de datos el(s) album(s) especicado(s)
**/
function deleteAlbums(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	
	//Verificamos si nos proporcionaron al menos un album para eliminar
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRSET,1);
		die();
	}

	if (!is_array($ids)){
		$album = new GSSet($ids);
		$ids = array($ids);
	}
	

	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_SESSINVALID,1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			
			//Verificamos si el album es válido
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si el album existe
			$set = new GSSet($k);
			if ($set->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}	

			if(!$set->delete()){
				$errors .= sprintf(_AS_GS_ERRDELETE, $k);
			}else{
				//Decrementamos el número de albumes del usuario
				$user = new GSUser($set->owner(),1);
				$user->quitSet();
			}
		}

		if($erros!=''){
			redirectMsg('./sets.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./sets.php?'.$ruta,_AS_GS_DBOK,0);
			die();
		}
		


	}else{

		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./sets.php'>"._AS_GS_SETSLOC."</a> &raquo; "._AS_GS_LOCDELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $page;
		$hiddens['sort'] = $sort;		
		$hiddens['mode'] = $mode;		
		$hiddens['search'] = $search;			
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'sets.php?'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'sets.php',(isset($album) ? sprintf(_AS_GS_DELETECONF, $album->title()) : _AS_GS_DELETECONFS). '<br /><br />' ._AS_GS_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
}


/**
* @desc Permite hacer publico o no un album
**/
function publicAlbums($pub = 0){

	global $util;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_set';
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&sort=$sort&mode=$mode&search=$search";
	
	//Verificamos si nos proporcionaron al menos un album para publicar
	if (!is_array($ids)){
		redirectMsg('./sets.php?'.$ruta,_AS_GS_ERRSETPUBLIC,1);
		die();
	}

	if (!$util->validateToken()){
		redirectMsg('./sets.php?'.$ruta,_AS_GS_SESSINVALID,1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si el album es válido
		if($k<=0){
			$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
			continue;			
		}
		//Verificamos si el album existe
		$set = new GSSet($k);
		if ($set->isNew()){
			$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
			continue;
		}	

		$set->setPublic($pub);
		if(!$set->save()){
			$errors .= sprintf(_AS_GS_ERRDELETE, $k);
		}
	}

	if($erros!=''){
		redirectMsg('./sets.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./sets.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}
		

	

	


}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formAlbums();
	break;
	case 'edit':
		formAlbums(1);
	break;
	case 'save':
		saveAlbums();
	break;
	case 'saveedit':
		saveAlbums(1);
	break;
	case 'delete':
		deleteAlbums();
	break;
	case 'public':
		publicAlbums(2);
	break;
	case 'private':
		publicAlbums(0);
	break;
	case 'privatef':
		publicAlbums(1);
	break;
	default:
		showAlbums();
		break;
}
?>
