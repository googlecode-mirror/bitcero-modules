<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','pages');
require 'header.php';

/**
 * Muestra los envíos existentes
 */
function showPages($acceso = -1){
	global $mc, $xoopsModule;
	
	$keyw = '';
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$db = Database::getInstance();
	
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("qpages_pages");
	if ($acceso>=0){
		$sql .= " WHERE acceso=$acceso";
	}
	if (trim($keyw)!=''){
		$sql .= ($acceso>=0 ? " AND " : " WHERE ") . "titulo LIKE '%$keyw%'";
		$tpl->assign('keyw', $keyw);
	}
	if (isset($cat) && $cat>0){
		$sql .= ($acceso>=0 || $keyw!='' ? " AND " : " WHERE ") . "cat='$cat'";
		$tpl->assign('catego', $cat);
	}
	
	/**
	 * Paginacion de Resultados
	 */
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	$limit = isset($limit) && $limit>0 ? $limit : 15;
	list($num) = $db->fetchRow($db->query($sql));
	$page = isset($page) ? $page : 0;
	
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
	
	$sql .= " ORDER BY porder, cat LIMIT $start,$limit";
	$sql = str_replace("SELECT COUNT(*)", "SELECT *", $sql);
	
	$result = $db->query($sql);
	$pages = array();
	while ($row = $db->fetchArray($result)){
		$page = new QPPage();
		$page->assignVars($row);
		# Enlaces para las categorías
		$catego = new QPCategory($page->getCategory());
		$estado = $page->getAccess() ? _AS_QP_PUBLISHED : _AS_QP_PRIVATED;
		$pages[] = array('id'=>$page->getID(), 'titulo'=>$page->getTitle(),'catego'=>$catego->getName(), 'fecha'=>formatTimeStamp($page->getDate(),'s'),
				'link'=>$page->getPermaLink(),'menu'=>$page->getInMenu() ? _YES : _NO, 'estado'=>$estado,
				'modificada'=>$page->getModDate()==0 ? '--' : formatTimestamp($page->getModDate(),'s'), 
				'lecturas'=>$page->getReads(),'order'=>$page->order(),'type'=>$page->type());
	}
	
	/**
	 * Cargamos las categorias
	 */
	$categos = array();
	qpArrayCategos($categos);
	$categories = array();
	foreach ($categos as $k){
		$categories[] = array('id'=>$k['id_cat'],'nombre'=>$k['nombre']);
	}
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($acceso<0 ? _AS_QP_PAGELIST : ($acceso==0 ? _AS_QP_PRIVATELIST : _AS_QP_PUBLICLIST)));
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/qp_pages.html", 'module', 'qpages');
	
	xoops_cp_footer();
}
/**
 * Muestra el formulario para la creación de un nuevo artículo
 */
function newForm($edit = 0, $redir = false){
	global $db, $mc, $xoopsModule, $myts, $util;
	
	foreach($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$pag = isset($page) ? $page : '';
	
	if ($edit){
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		if ($id<=0){
			redirectMsg("pages.php?cat=$cat&page=$pag&limit=$limit", _AS_QP_NOID, 1);
			die();
		}
		$page = new QPPage($id);
	}
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_NEWPAGE);
	optionsBar();
	$head = '<script type="text/javascript" src="'.QP_URL.'/include/jquery-min.js"></script>';
	$head .= '<script type="text/javascript" src="'.QP_URL.'/include/jquery-ui-min.js"></script>';
	$head .= '<script type="text/javascript" src="'.QP_URL.'/include/forms_pages.js"></script>';
	xoops_cp_header($head);
	
	$form = new RMForm($edit ? _AS_QP_EDITTITLE : _AS_QP_NEWTITLE, 'frmNew', 'pages.php');
	$form->styles('width: 25%;','odd');
	$form->addElement(new RMText(_AS_QP_TITLE, 'titulo', 50, 255, $edit ? $page->getTitle() : ''), true);
	$form->tinyCSS(QP_URL.'/styles/main.css');
	if ($edit){
		$ele = new RMText(_AS_QP_FRIENDTITLE, 'titulo_amigo', 50, 255, $page->getFriendTitle());
		$ele->setDescription(_AS_QP_FRIENDDESC);
		$form->addElement($ele);
		$form->addElement(new RMHidden('id', $page->getID()));
	}
	
	$ele = new RMSelect(_AS_QP_CATEGO, 'catego', 0);
	$categos = array();
	qpArrayCategos($categos);
	$ele->addOption('0',_SELECT, $edit ? 0 : 1);
	foreach ($categos as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . " " . $k['nombre'], $edit ? ($k['id_cat']==$page->getCategory() ? 1 : 0) : 0);
	}
	$form->addElement($ele, true, "Select:0");

	$form->addElement(new RMTextArea(_AS_QP_SHORTDESC, 'desc', 5, 60, $edit ? $page->getDescription() : ''));
	
	$ele = new RMEditor(_AS_QP_PAGETEXT, 'texto', '100%','450px',$edit ? $page->getText() : '',$mc['editor']);
	$form->addElement($ele, true);
	if ($edit){
		$html = $page->html();
		$xcode = $page->xcode();
		$br = $page->br();
		$image = $page->image();
		$smiley = $page->smiley();
	} else {
		$html = 1;
		$xcode = 0;
		$br = 0;
		$image = 0;
		$smiley = 1;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $html, $xcode, $image, $smiley, $br));
	
	// Grupos
	$ele = new RMGroups(_AS_QP_GROUPS, 'grupos', 1, 1, 3, $edit ? $page->getGroups() : array(0));
	$ele->setDescription(_AS_QP_GROUPS_DESC);
	$form->addElement($ele);
	
	$form->addElement(new RMYesNo(_AS_QP_INMENU, 'menu', $edit ? $page->getInMenu() : 0));
	if ($edit){
		$ele = new RMRadio(_AS_QP_PAGESTATUS, 'acceso', 0);
		$ele->addOption(_AS_QP_PUBLISHED, '1', $page->getAccess() ? 1 : 0);
		$ele->addOption(_AS_QP_PRIVATED, '0', $page->getAccess() ? 0 : 1);
		$form->addElement($ele);
	}
	
	$page_metas = $edit ? $page->get_meta() : array();
	$available_metas = qp_get_metas();
	include 'metas.php';
	$form->addElement(new RMLabel('Additional Fields', $meta_data));
	
	$ele = new RMButtonGroup();
	$ele->addButton('saveret', _AS_QP_SAVEANDRETURN, 'submit');
	$ele->addButton('onlysave', _AS_QP_SAVE, 'submit');
	if (!$edit) $ele->addButton('publish', _AS_QP_PUBLISH, 'submit');
	$ele->setExtra('saveret', 'onclick="document.forms[\'frmNew\'].op.value=\''.($edit ? "saveretedit" : "saveret").'\';"');
	$ele->setExtra('onlysave', 'onclick="document.forms[\'frmNew\'].op.value=\''.($edit ? 'saveedit' : 'save').'\';"');
	if (!$edit) $ele->setExtra('publish', 'onclick="document.forms[\'frmNew\'].op.value=\''.($edit ? 'publishedit' : 'publish').'\';"');
	
	$form->addElement($ele);
	$form->addElement(new RMHidden('op', $edit ? 'publishedit' : 'publish'));
	$form->addElement(new RMHidden('type', 0));
	$form->addElement(new RMHidden('cat', $cat));
	$form->addElement(new RMHidden('page', $pag));
	$form->addElement(new RMHidden('limit', $limit));
	$form->display();
	

	xoops_cp_footer();
}
/**
 * Muestra el formulario para la creación de una nueva página enlazada
 */
function newLinkForm($edit = 0){
	global $db, $mc, $xoopsModule, $myts, $util;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$pag = $page;
	
	if ($edit){
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		if ($id<=0){
			redirectMsg("pages.php&cat=$cat&limit=$limit&page=$pag", _AS_QP_NOID, 1);
			die();
		}
		$page = new QPPage($id);
	}
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_NEWPAGE);
	optionsBar();
	$head = '<script type="text/javascript" src="'.QP_URL.'/include/jquery-min.js"></script>';
	$head .= '<script type="text/javascript" src="'.QP_URL.'/include/jquery-ui-min.js"></script>';
	$head .= '<script type="text/javascript" src="'.QP_URL.'/include/forms_pages.js"></script>';
	xoops_cp_header($head);
	
	$form = new RMForm($edit ? _AS_QP_EDITLINKTITLE : _AS_QP_NEWLINKTITLE, 'frmNew', 'pages.php');
	$form->addElement(new RMText(_AS_QP_TITLE, 'titulo', 50, 255, $edit ? $page->getTitle() : ''), true);
	$form->tinyCSS(QP_URL.'/styles/main.css');
	if ($edit){
		$ele = new RMText(_AS_QP_FRIENDTITLE, 'titulo_amigo', 50, 255, $page->getFriendTitle());
		$ele->setDescription(_AS_QP_FRIENDDESC);
		$form->addElement($ele);
		$form->addElement(new RMHidden('id', $page->getID()));
	}
	
	$ele = new RMSelect(_AS_QP_CATEGO, 'catego', 0);
	$categos = array();
	qpArrayCategos($categos);
	$ele->addOption('0',_SELECT, $edit ? 0 : 1);
	foreach ($categos as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . " " . $k['nombre'], $edit ? ($k['id_cat']==$page->getCategory() ? 1 : 0) : 0);
	}
	$form->addElement($ele, true, "Select:0");

	$form->addElement(new RMTextArea(_AS_QP_SHORTDESC, 'desc', 5, 60, $edit ? $page->getDescription() : ''));
	// URL
	$form->addElement(new RMText(_AS_QP_URL, 'url', 50, 255, $edit ? $page->url() : ''), true);
	// Grupos
	$ele = new RMGroups(_AS_QP_GROUPS, 'grupos', 1, 1, 3, $edit ? $page->getGroups() : array(0));
	$ele->setDescription(_AS_QP_GROUPS_DESC);
	$form->addElement($ele);
	
	$form->addElement(new RMYesNo(_AS_QP_INMENU, 'menu', $edit ? $page->getInMenu() : 0));
	if ($edit){
		$ele = new RMRadio(_AS_QP_PAGESTATUS, 'acceso', 0);
		$ele->addOption(_AS_QP_PUBLISHED, '1', $page->getAccess() ? 1 : 0);
		$ele->addOption(_AS_QP_PRIVATED, '0', $page->getAccess() ? 0 : 1);
		$form->addElement($ele);
	}
	
	$page_metas = $edit ? $page->get_meta() : array();
	$available_metas = qp_get_metas();
	include 'metas.php';
	$form->addElement(new RMLabel('', $meta_data));
	
	$ele = new RMButtonGroup();
	$ele->addButton('saveret', _AS_QP_SAVEANDRETURN, 'submit');
	$ele->addButton('onlysave', _AS_QP_SAVE, 'submit');
	if (!$edit) $ele->addButton('publish', _AS_QP_PUBLISH, 'submit');
	$ele->setExtra('saveret', 'onclick="document.forms[\'frmNew\'].op.value=\''.($edit ? "saveretedit" : "saveret").'\';"');
	$ele->setExtra('onlysave', 'onclick="document.forms[\'frmNew\'].op.value=\''.($edit ? 'saveedit' : 'save').'\';"');
	if (!$edit) $ele->setExtra('publish', 'onclick="document.forms[\'frmNew\'].op.value=\''.($edit ? 'publishedit' : 'publish').'\';"');
	
	$form->addElement($ele);
	$form->addElement(new RMHidden('op', $edit ? 'publishedit' : 'publish'));
	$form->addElement(new RMHidden('type', 1));
	$form->addElement(new RMHidden('cat', $cat));
	$form->addElement(new RMHidden('limit', $limit));
	$form->addElement(new RMHidden('page', $pag));
	$form->display();
	

	xoops_cp_footer();
}
/**
 * Esta función permite guardar y publicar un envío
 */
function savePage($state=0){
	global $db, $util, $xoopsUser, $myts, $mc;
	
	$cat = 0;
	$url = '';
	$texto = '';
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	$pag = isset($page) ? $page : '';
	
	if (!$util->validateToken()){
		redirectMsg("pages.php?op=new&cat=$cat&limit=$limit&page=$pag", _AS_QP_ERRTOKEN, 1);
		die();
	}
	
	if ($titulo==''){
		redirectMsg("pages.php?op=new&cat=$cat&limit=$limit&page=$pag", _AS_QP_ERRTITLE, 1);
		die();
	}
	
	if (isset($pretitulo)){
		
		if ($pretitulo != $titulo){
			$titulo_amigo = $util->sweetstring($titulo);
		} else {
			$titulo_amigo = $titulo_amigo;
		}
		
	} else {
		$titulo_amigo = $util->sweetstring($titulo);
	}
	
	$titulo = $myts->makeTboxData4Save($titulo);
	
	if ($texto=='' && $type==0){
		redirectMsg("pages.php?op=new&cat=$cat&limit=$limit&page=$pag", _AS_QP_ERRTEXT, 1);
		die();
	}
	
	if ($catego<=0){
		redirectMsg("pages.php?op=new&cat=$cat&limit=$limit&page=$pag", _AS_NP_ERRCAT, 1);
		die();
	}
	
	if (count($grupos)<=0){
		$grupos = array(0);
	}
	
	/**
	 * Comprobamos que no exista otra página con el mismo título
	 */
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE titulo_amigo='$titulo_amigo'";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>0){
		
		foreach ($_POST as $k => $v){
			if ($k=='titulo_amigo') continue;
			if ($k=='titulo') $k = 'pretitulo';
			$hiddens[$k] = $v;
		}
		$buttons['ok']['type'] = 'submit';
		$buttons['ok']['value'] = _SUBMIT;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		
		$texto = _AS_QP_ERREXISTS;
		$texto .= "<br /><br />"._AS_QP_TITLE."<br />
					<input type='text' name='titulo' value='$titulo' size='50' maxlength='255' /><br />
					"._AS_QP_FRIENDTITLE."<br />
					<input type='text' name='titulo_amigo' value='$titulo_amigo' size='50' maxlength='255' /><br />";
		
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_FRIENDTITLE);
		xoops_cp_header();
		$util->msgBox($hiddens, 'pages.php', $texto, '../images/warn.png', $buttons, true, '400px');
		xoops_cp_footer();
		die();
	}
	
	#Guardamos los datos del Post
	$page = new QPPage();
	$page->setTitle($titulo);
	$page->setFriendTitle($titulo_amigo);
	$page->setDate(time());
	$page->setModDate(time());
	$page->setText($myts->makeTareaData4Save($texto));
	$page->setCategory($catego);
	$page->setDescription(substr($util->filterTags($desc), 0, 255));
	$page->setGroups($grupos);
	$page->setInMenu($menu);
	$page->setHTML(isset($dohtml) ? 1 : 0);
	$page->setXCode(isset($doxcode) ? 1 : 0);
	$page->setImage(isset($doimage) ? 1 : 0);
	$page->setBR(isset($dobr) ? 1 : 0);
	$page->setSmiley(isset($dosmiley) ? 1 : 0);
	$page->setUid($xoopsUser->uid());
	$page->setType($type);
	$page->setURL(formatURL($url));
	if ($state==0 || $state==1){
		$page->setAccess(0);
	} else {
		$page->setAccess(1);
	}
	
	// Add Metas
	foreach($meta_name as $k => $v){
		$page->add_meta($v, $meta_value[$k]);
	}
	
	if ($page->save()){
		$xoopsUser->incrementPost();
		redirectMsg($state==0 ? "pages.php?op=edit&id=".$page->getID()."&cat=$cat&limit=$limit&page=$pag" : "pages.php?cat=$cat&limit=$limit&page=$pag", _AS_QP_DBOK, 0);
	} else {
		redirectMsg("pages.php?op=new&cat=$cat&limit=$limit&page=$pag", _AS_QP_DBERROR . "<br />" . $page->errors(), 1);
	}
	
	
}
/**
 * Almacena la información de un artículo editado
 */
function saveEdited($status=0){
	global $db, $util, $xoopsUser, $myts, $mc;
	
	$cat = 0;
	$url='';
	$texto = '';
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	$pag = isset($page) ? $page : '';
	
	if ($id<=0){
		redirectMsg("pages.php?cat=$cat&limit=$limit&page=$pag", _AS_QP_NOID, 1);
		die();
	}
	if (!$util->validateToken()){
		redirectMsg("pages.php?op=edit&cat=$cat&id=$id&page=$pag&limit=$limit", _AS_QP_ERRTOKEN, 1);
		die();
	}
	$page = new QPPage($id);
	if ($page->isNew()){
		redirectMsg("pages.php?cat=$cat&limit=$limit&page=$pag", _AS_QP_NOID, 1);
		die();
	}
	$op = $page->type() ? 'editlink' : 'edit';
	
	if ($titulo==''){
		redirectMsg("pages.php?op=$op&cat=$cat&id=$id&limit=$limit&page=$pag", _AS_QP_ERRTITLE, 1);
		die();
	}
	
	if ($texto==''&& $type==0){
		redirectMsg("pages.php?op=$op&cat=$cat&id=$id&limit=$limit&page=$pag", _AS_QP_ERRTEXT, 1);
		die();
	}
	
	if ($catego<=0){
		redirectMsg("pages.php?op=$op&cat=$cat&id=$id&limit=$limit&page=$pag", _AS_NP_ERRCAT, 1);
		die();
	}
	
	if (isset($pretitulo)){
		
		if ($pretitulo != $titulo){
			$titulo_amigo = $util->sweetstring($titulo);
		} else {
			$titulo_amigo = $titulo_amigo;
		}
		
	} else {
		$titulo_amigo = $titulo_amigo!='' ? $titulo_amigo : $util->sweetstring($titulo);
	}
	
	$titulo = $myts->makeTboxData4Save($titulo);
	
	if (count($grupos)<=0){
		$grupos = array(0);
	}
	
	/**
	 * Comprobamos que no exista otra página con el mismo título
	 */
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE titulo_amigo='$titulo_amigo' AND id_page<>$id";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>0){
		
		foreach ($_POST as $k => $v){
			if ($k=='titulo_amigo') continue;
			if ($k=='titulo') $k = 'pretitulo';
			$hiddens[$k] = $v;
		}
		$buttons['ok']['type'] = 'submit';
		$buttons['ok']['value'] = _SUBMIT;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		
		$texto = _AS_QP_ERREXISTS;
		$texto .= "<br /><br />"._AS_QP_TITLE."<br />
					<input type='text' name='titulo' value='$titulo' size='50' maxlength='255' /><br />
					"._AS_QP_FRIENDTITLE."<br />
					<input type='text' name='titulo_amigo' value='$titulo_amigo' size='50' maxlength='255' /><br />";
		
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_FRIENDTITLE);
		xoops_cp_header();
		$util->msgBox($hiddens, 'pages.php', $texto, '../images/warn.png', $buttons, true, '400px');
		xoops_cp_footer();
		die();
	}


	$page->setTitle($titulo);
	$page->setFriendTitle($titulo_amigo);
	$page->setModDate(time());
	if ($texto!='') $page->setText($myts->makeTareaData4Save($texto));
	$page->setCategory($catego);
	$page->setAccess($acceso);
	$page->setDescription($desc);
	$page->setGroups($grupos);
	$page->setInMenu($menu);
	$page->setHTML(isset($dohtml) ? 1 : 0);
	$page->setXCode(isset($doxcode) ? 1 : 0);
	$page->setImage(isset($doimage) ? 1 : 0);
	$page->setBR(isset($dobr) ? 1 : 0);
	$page->setSmiley(isset($dosmiley) ? 1 : 0);
	$page->setUid($xoopsUser->uid());
	$page->setType($type);
	$page->setURL(formatUrl($url));
	
	// Add Metas
	foreach($meta_name as $k => $v){
		$page->add_meta($v, $meta_value[$k]);
	}
	
	if ($page->update()){
		redirectMsg($status==0 ? "pages.php?op=edit&cat=$cat&id=".$page->getID()."&limit=$limit&page=$pag" : 'pages.php?cat='.$cat, _AS_QP_DBOK, 0);
	} else {
		redirectMsg("pages.php?op=new&cat=$cat&limit=$limit&page=$pag", _AS_QP_DBERROR . "<br />" . $page->errors(), 1);
	}
}
/**
 * Elimina un artículo de la base de datos
 */
function deletePage(){
	global $util, $xoopsModule;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	
	if ($ok){
		
		$page = new QPPage($id);
		if ($id<=0){
			redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_NOID, 1);
			die();
		}
		if ($page->isNew()){
			redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_NOID, 1);
			die();
		}
		
		if ($page->delete()){
			redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_DBOK, 0);
		} else {
			redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_DBERROR . "<br />" . $page->errors(), 1);
		}
		
	} else {
		
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_DELPAGE);
		xoops_cp_header();
		$hiddens['op'] = 'delete';
		$hiddens['id'] = $id;
		$hiddens['page'] = $page;
		$hiddens['ok'] = 1;
		$hiddens['cat'] = $cat;
		$hiddens['limit'] = $limit;
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		
		$util->msgBox($hiddens, 'pages.php', _AS_QP_CONFIRMDEL, XOOPS_ALERT_ICON, $buttons, true, 400);
		xoops_cp_footer();
	}
	
}

function approveBulk($acceso){
	global $db;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	if (count($pages)<=0){
		redirectMsg($aprovado ? "pages.php?op=private&cat=$cat&limit=$limit&page=$page" : "pages.php?op=public&cat=$cat&limit=$limit&page=$page", _AS_QP_SELECTONE, 1);
		die();
	}
	
	$sql = "UPDATE ".$db->prefix("qpages_pages")." SET acceso='$acceso' WHERE ";
	$cond = '';
	foreach ($pages as $k){
		if ($cond==''){
			$cond.="id_page='$k'";
		} else {
			$cond.=" OR id_page='$k'";
		}
	}
	
	$sql .= $cond;
	if ($db->queryF($sql)){
		redirectMsg($acceso ? "pages.php?op=private&cat=$cat&limit=$limit&page=$page" : "pages.php?op=public&cat=$cat&limit=$limit&page=$page", _AS_QP_DBOK, 0);
	} else {
		redirectMsg($acceso ? "pages.php?op=private&cat=$cat&limit=$limit&page=$page" : "pages.php?op=public&cat=$cat&limit=$limit&page=$page", _AS_QP_DBERROR . '<br />' . $db->error(), 1);
	}
	
}

function linkedPages(){
	global $db;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	if (count($pages)<=0){
		redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_SELECTONE, 1);
		die();
	}
	
	$sql = "SELECT * FROM ".$db->prefix("qpages_pages")." WHERE id_page IN (";
	$sql1 = '';
	foreach ($pages as $k){
		$sql1 .= $sql1=='' ? "'$k'" : ",'$k'";
	}
	$sql .= $sql1 . ")";
	
	$result = $db->query($sql);

	while ($row = $db->fetchArray($result)){
		$page = new QPPage();
		$page->assignVars($row);
		$page->setType(!$page->type());
		$page->update();
	}

	redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_DBOK, 0);
	
}

function saveChanges(){
	global $db;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	if (count($porder)<=0){
		header("location: pages.php?cat=$cat&page=$page&limit=$limit", '', 0);
		die();
	}
	
	foreach($porder as $k => $v){
		$pag = new QPPage($k);
		if ($pag->isNew()) continue;
		$pag->setOrder($v);
		$pag->update();
	}
	redirectMsg("pages.php?cat=$cat&page=$page&limit=$limit", _AS_QP_DBOK, 0);
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		newForm();
		break;
	case 'newlink':
		newLinkForm();
		break;
	case 'saveret':
		savePage(0);
		break;
	case 'save':
		savePage(1);
		break;
	case 'publish':
		savePage(2);
		break;
	case 'edit':
		newForm(1);
		break;
	case 'editlink':
		newLinkForm(1);
		break;
	case 'saveretedit':
		saveEdited(0);
		break;
	case 'saveedit':
	case 'publishedit':
		saveEdited(1);
		break;
	case 'delete':
		deletePage();
		break;
	case 'private':
		showPages(0);
		break;
	case 'public':
		showPages(1);
		break;
	case 'publicate':
		approveBulk(1);
		break;
	case 'privatize':
		approveBulk(0);
		break;
	case 'savechanges':
		saveChanges();
		break;
	case 'linked':
		linkedPages();
		break;
	default:
		showPages();
		break;
}
?>