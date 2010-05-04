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
	global $mc, $xoopsModule, $xoopsSecurity;
	
	$keyw = rmc_server_var($_REQUEST, 'keyw', '');
    $acceso = rmc_server_var($_REQUEST, 'acceso', -1);
    $cat = rmc_server_var($_REQUEST, 'cat', '');
	
	$db = Database::getInstance();
	
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("qpages_pages");
	if ($acceso>=0){
		$sql .= " WHERE acceso=$acceso";
	}
	if (trim($keyw)!=''){
		$sql .= ($acceso>=0 ? " AND " : " WHERE ") . "titulo LIKE '%$keyw%'";
	}
	if (isset($cat) && $cat>0){
		$sql .= ($acceso>=0 || $keyw!='' ? " AND " : " WHERE ") . "cat='$cat'";
	}
	
	/**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_REQUEST,'page', 1);
	$page = $page<=0 ? 1 :  $page;
    $limit = 15;
	list($num) = $db->fetchRow($db->query($sql));
	$tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 
    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('pages.php?cat='.$cat.'&page={PAGE_NUM}');
	
	$sql .= " ORDER BY id_page DESC LIMIT $start,$limit";
	$sql = str_replace("SELECT COUNT(*)", "SELECT *", $sql);
	
	$result = $db->query($sql);
	$pages = array();
	while ($row = $db->fetchArray($result)){
		$p = new QPPage();
		$p->assignVars($row);
		# Enlaces para las categorías
		$catego = new QPCategory($p->getCategory());
		$pages[] = array(
			'id'=>$p->getID(),
			'titulo'=>$p->getTitle(),
			'catego'=>$catego->getName(),
			'fecha'=>formatTimeStamp($p->getDate(),'s'),
			'link'=>$p->getPermaLink(),
			'estado'=>$p->getAccess(),
			'modificada'=>$p->getModDate()==0 ? '--' : formatTimestamp($p->getModDate(),'c'), 
			'lecturas'=>$p->getReads(),
			'order'=>$p->order(),
			'type'=>$p->type(),
            'desc'=>$p->getDescription()
		);
	}
	
	/**
	 * Cargamos las categorias
	 */
	$categos = array();
	qpArrayCategos($categos);
	$categories = array();
	foreach ($categos as $k){
		$categories[] = array('id'=>$k['id_cat'],'nombre'=>$k['nombre'], 'saltos'=>$k['saltos']);
	}
	
    RMTemplate::get()->add_style('admin.css', 'qpages');
    RMTemplate::get()->add_script('../include/js/qpages.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->assign('xoops_pagetitle', __('Pages Management','qpages'));
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($acceso<0 ? __('All Pages','qpages') : ($acceso==0 ? __('Draft pages','qpages') : __('Published pages','qpages'))));
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/qp_pages.php", 'module', 'qpages');
	
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
	
	$page = isset($page) ? $page : 1;
	
	if ($edit){
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		if ($id<=0){
			redirectMsg("pages.php?cat=$cat&page=$page", __('You must specify a page ID to edit!','qpages'), 1);
			die();
		}
		$page = new QPPage($id);
	}
	
    RMTemplate::get()->add_script('../include/js/forms_pages.js');
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($edit ? __('Edit page','qpages') : __('New page','qpages')));
	xoops_cp_header();
	
	$form = new RMForm($edit ? __('Edit Page','qpages') : __('New Page','qpages'), 'frmNew', 'pages.php');
	$form->styles('width: 25%;','odd');
	$form->addElement(new RMFormText(__('Page title','qpages'), 'titulo', 50, 255, $edit ? $page->getTitle() : ''), true);
	if ($edit){
		$ele = new RMFormText(__('Friendly title','qpages'), 'titulo_amigo', 50, 255, $page->getFriendTitle());
		$ele->setDescription(__('Specify a title to use in friendly urls. Remember, this title must not contain any special char, only numbers or letters.'));
		$form->addElement($ele);
		$form->addElement(new RMFormHidden('id', $page->getID()));
	}
	
	$ele = new RMFormSelect(__('Category','qpages'), 'catego', 0);
	$categos = array();
	qpArrayCategos($categos);
	$ele->addOption('0',__('Select category...','qpages'), $edit ? 0 : 1);
	foreach ($categos as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . " " . $k['nombre'], $edit ? ($k['id_cat']==$page->getCategory() ? 1 : 0) : 0);
	}
	$form->addElement($ele, true, "Select:0");

	$form->addElement(new RMFormTextArea(__('Introduction','qpages'), 'desc', 5, 60, $edit ? $page->getVar('desc','e') : ''));
	
	$ele = new RMFormEditor(__('Page content','qpages'), 'texto', '100%','450px',$edit ? $page->getText() : '');
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
	$form->addElement(new RMFormTextOptions(__('Text options','qpages'), $html, $xcode, $image, $smiley, $br));
	
	// Grupos
	$ele = new RMFormGroups(__('Allowed groups','qpages'), 'grupos', 1, 1, 3, $edit ? $page->getGroups() : array(0));
	$ele->setDescription(__('These groups can access and read page content.','qpages'));
	$form->addElement($ele);
	
	$ele = new RMFormRadio(__('Status','qpages'), 'acceso', 1);
	$ele->addOption(__('Public','qpages'), '1', $edit ? ($page->getAccess() ? 1 : 0) : 0);
	$ele->addOption(__('Private','qpages'), '0', $edit ? ($page->getAccess() ? 0 : 1) : 1);
	$form->addElement($ele);
	
	$page_metas = $edit ? $page->get_meta() : array();
	$available_metas = qp_get_metas();
	include 'metas.php';
	$form->addElement(new RMFormLabel(__('Additional Fields', 'qpages'), $meta_data));
	
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Update Page','qpages') : __('Save Page','qpages'), 'submit');
    $ele->addButton('cancel', __('Cancel','qpages'), 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('type', 0));
	$form->addElement(new RMFormHidden('cat', $cat));
	$form->addElement(new RMFormHidden('page', $page));
	$form->display();

	xoops_cp_footer();
}
/**
 * Muestra el formulario para la creación de una nueva página enlazada
 */
function newLinkForm($edit = 0){
	global $db, $mc, $xoopsModule, $myts;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$pag = isset($page) ? $page : 1;
	
	if ($edit){
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		if ($id<=0){
			redirectMsg("pages.php&cat=$cat&page=$pag", __('You must provide a page ID to edit','qpages'), 1);
			die();
		}
		$page = new QPPage($id);
	}
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($edit ? __('Edit linked page','qpages') : __('New linked page','qpages')));

	RMTemplate::get()->add_script('../include/js/forms_pages.js');
	xoops_cp_header();
	
	$form = new RMForm($edit ? __('Edit Linked Page','qpages') : __('New Linked Page','qpages'), 'frmNew', 'pages.php');
	$form->addElement(new RMFormText(__('Page title','qpages'), 'titulo', 50, 255, $edit ? $page->getTitle() : ''), true);
	if ($edit){
		$ele = new RMFormText(__('Friendly title','qpages'), 'titulo_amigo', 50, 255, $page->getFriendTitle());
		$ele->setDescription(__('Thsi title will be used in friendly url.','qpages'));
		$form->addElement($ele);
		$form->addElement(new RMFormHidden('id', $page->getID()));
	}
	
	$ele = new RMFormSelect(__('Category','qpages'), 'catego', 0);
	$categos = array();
	qpArrayCategos($categos);
	$ele->addOption('0',_SELECT, $edit ? 0 : 1);
	foreach ($categos as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . " " . $k['nombre'], $edit ? ($k['id_cat']==$page->getCategory() ? 1 : 0) : 0);
	}
	$form->addElement($ele, true, "Select:0");

	$form->addElement(new RMFormTextArea(__('Introduction','qpages'), 'desc', 5, 60, $edit ? $page->getDescription() : ''));
	// URL
	$form->addElement(new RMFormText(__('Target URL','qpages'), 'url', 50, 255, $edit ? $page->url() : ''), true);
	// Grupos
	$ele = new RMFormGroups(__('Allowed groups','qpages'), 'grupos', 1, 1, 3, $edit ? $page->getGroups() : array(0));
	$ele->setDescription(__('Only selected groups can access to this document.','qpages'));
	$form->addElement($ele);
	
	$ele = new RMFormRadio(__('Status','qpages'), 'acceso', 1);
    $ele->addOption(__('Public','qpages'), '1', $edit ? ($page->getAccess() ? 1 : 0) : 0);
    $ele->addOption(__('Private','qpages'), '0', $edit ? ($page->getAccess() ? 0 : 1) : 1);
    $form->addElement($ele);
	
	$page_metas = $edit ? $page->get_meta() : array();
	$available_metas = qp_get_metas();
	include 'metas.php';
	$form->addElement(new RMFormLabel('', $meta_data));
	
	$ele = new RMFormButtonGroup();
	$ele->addButton('save', $edit ? __('Update Page','qpages') : __('Save Page','qpages'), 'submit');
	$ele->addButton('cancel', __('Cancel','qpages'), 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('type', 1));
	$form->addElement(new RMFormHidden('cat', $cat));
	$form->addElement(new RMFormHidden('page', $pag));
	$form->display();
	

	xoops_cp_footer();
}
/**
 * Esta función permite guardar y publicar un envío
 */
function savePage($edit=0){
	global $xoopsSecurity, $xoopsUser, $myts, $mc, $xoopsModule;
	
	$cat = 0;
	$url = '';
	$texto = '';
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
    
    $q = "op=".($edit?'edit&id='.$id:'new')."&cat=$cat&page=$page";
	
    if ($edit){
        if ($id<=0){
            redirectMsg("pages.php?cat=$cat&page=$page", __('Page ID has not been provided','qpages'), 1);
            die();
        }
        
        $page = new QPPage($id);
        if ($page->isNew()){
            redirectMsg("pages.php?cat=$cat&page=$page", __('Specified page does not exists!','qpages'), 1);
            die();
        }
    } else {
        $page = new QPPage();
    }
    
	if (!$xoopsSecurity->check()){
		redirectMsg("pages.php?$q", __('Session token expired!','qpages'), 1);
		die();
	}
	
	if ($titulo==''){
		redirectMsg("pages.php?$q", __('Title is missing','qpages'), 1);
		die();
	}
	
	if (isset($pretitulo)){
		
		if ($pretitulo != $titulo){
			$titulo_amigo = TextCleaner::getInstance()->sweetstring($titulo);
		} else {
			$titulo_amigo = $titulo_amigo;
		}
		
	} else {
		$titulo_amigo = $edit ? ($titulo_amigo!='' ? $titulo_amigo : TextCleaner::getInstance()->sweetstring($titulo)) : TextCleaner::getInstance()->sweetstring($titulo);
	}
	
	if ($texto=='' && $type==0){
		redirectMsg("pages.php?op=new&cat=$cat&page=$page", __('Content is missing','qpages'), 1);
		die();
	}
	
	if ($catego<=0){
		redirectMsg("pages.php?op=new&cat=$cat&page=$page", __('You must select a category for this page','qpages'), 1);
		die();
	}
	
	if (count($grupos)<=0){
		$grupos = array(0);
	}
	
	/**
	 * Comprobamos que no exista otra página con el mismo título
	 */
	$db = Database::getInstance();
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE titulo_amigo='$titulo_amigo'";
    
    $sql .= $edit ? " AND id_page<>".$page->getID() : '';
    
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>0){
		
		$form = new RMForm(__('Review Page','qpages'), 'frm-review', 'pages.php');
		
		$form->addElement(new RMFormLabel('', __('A page with same friendly name already exists. Please change the freindly title to prevent errors.','qpages')));
		foreach ($_POST as $k => $v){
			if ($k=='titulo_amigo') continue;
			if ($k=='text') continue;
			if ($k=='grupos') continue;
			if ($k=='XOOPS_TOKEN_REQUEST') continue;
			if ($k=='titulo') $k = 'pretitulo';
			$hiddens[$k] = $v;
		}
		
		$form->addElement(new RMFormText(__('Title','qpages'), 'titulo', 50, 255, $titulo), true);
		$form->addElement(new RMFormText(__('Friendly title','qpages'), 'titulo_amigo', 50, 255, $titulo_amigo), true);
		$form->addElement(new RMFormHidden('texto', TextCleaner::getInstance()->specialchars($texto)));
		
		foreach($hiddens as $k => $v){
			$form->addElement(new RMFormHidden($k, $v));
		}
		
		foreach ($grupos as $group){
			$form->addElement(new RMFormHidden('grupos[]', $group));
		}
		
		$ele = new RMFormButtonGroup();
		$ele->addButton('sbt', __('Save Page', 'qpages'), 'submit');
		$ele->addButton('cancel', __('Cancel','qpages'), 'button', 'onclick="history.go(-1);"');
		$form->addElement($ele);
		
		qpages_toolbar();
		xoops_cp_header();
		$form->display();
		xoops_cp_footer();
		die();

	}
	
	#Guardamos los datos del Post
	$page->setTitle($titulo);
	$page->setFriendTitle($titulo_amigo);
	$page->setDate($edit ? $page->getVar('fecha') : time());
	$page->setModDate(time());
	$page->setText($texto);
	$page->setCategory($catego);
	$page->setVar('desc', TextCleaner::getInstance()->clean_disabled_tags($desc));
	$page->setGroups($grupos);
	$page->setHTML(isset($dohtml) ? 1 : 0);
	$page->setXCode(isset($doxcode) ? 1 : 0);
	$page->setImage(isset($doimage) ? 1 : 0);
	$page->setBR(isset($dobr) ? 1 : 0);
	$page->setSmiley(isset($dosmiley) ? 1 : 0);
	$page->setUid($xoopsUser->uid());
	$page->setType($type);
	$page->setURL(formatURL($url));
	$page->setAccess($acceso);
	
	// Add Metas
	foreach($meta_name as $k => $v){
		$page->add_meta($v, $meta_value[$k]);
	}
	
    $ret = $edit ? $page->update() : $page->save();
    
	if ($ret){
		$xoopsUser->incrementPost();
		redirectMsg("pages.php?op=".($page->type() ? 'editlink' : 'edit')."&id=".$page->getID()."&cat=$cat&page=$page", __('Database updated successfully!','qpages'), 0);
	} else {
		redirectMsg("pages.php?$q", __('Errors ocurred while trying to update database','qpages') . "<br />" . $page->errors(), 1);
	}
	
	
}

/**
 * Elimina un artículo de la base de datos
 */
function deletePage(){
	global $xoopsSecurity, $xoopsModule;
	
	if (!$xoopsSecurity->check()){
		redirectMsg("pages.php?cat=$cat&page=$page", __('Session token expired!','qpages'), 1);
		die();
	}
	
	$ids = rmc_server_var($_POST, 'ids', array());
	
	if (!is_array($ids) || empty($ids)){
		redirectMsg("pages.php?cat=$cat&page=$page", __('Select at least a category to delete','qpages'), 1);
		die();
	}
	
	$errors = '';
	foreach($ids as $id){
		$page = new QPPage($id);
		if ($page->isNew()){
			$errors .= sprintf(__('Page with ID "%s" does not exists!','qpages'), $id).'<br />';
		}
		
		if (!$page->delete()){
			$errors .= sprintf(__('Page "%s" could not be deleted:','qpages'), $page->getTitle()).$page->errors();
		}
		
	}
		
	if ($errors!=''){
		redirectMsg("pages.php?cat=$cat&page=$page", __('Errors ocurred while trying to delete pages','qpages').'<br />'.$errors, 1);
	} else {
		redirectMsg("pages.php?cat=$cat&page=$page", __('Pages deleted successfully!','qpages'), 0);
	}
	
}

function approveBulk($acceso){
	
	$cat = rmc_server_var($_POST, 'cat', '');
    $page = rmc_server_var($_POST, 'page', 1);
    $ids = rmc_server_var($_POST, 'ids', array());
	
	if (count($ids)<=0){
		redirectMsg($aprovado ? "pages.php?op=private&cat=$cat&page=$page" : "pages.php?op=public&cat=$cat&page=$page", __('You must select at least one category!','qpages'), 1);
		die();
	}
	
    $db = Database::getInstance();
	$sql = "UPDATE ".$db->prefix("qpages_pages")." SET acceso='$acceso' WHERE ";
	$cond = implode(",", $ids);
	
	$sql .= ' id_page IN ('.$cond.')';
    
	if ($db->queryF($sql)){
		redirectMsg($acceso ? "pages.php?op=private&cat=$cat&page=$page" : "pages.php?op=public&cat=$cat&page=$page", __('Database updated successfully!','qpages'), 0);
	} else {
		redirectMsg($acceso ? "pages.php?op=private&cat=$cat&page=$page" : "pages.php?op=public&cat=$cat&page=$page", __('Database update failed!','qpages') . '<br />' . $db->error(), 1);
	}
	
}

function linkedPages(){
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	if (count($ids)<=0){
		redirectMsg("pages.php?cat=$cat&page=$page", __('Select at least a cetegory to edit','qpages'), 1);
		die();
	}
    
    $db = Database::getInstance();
	
	$sql = "SELECT * FROM ".$db->prefix("qpages_pages")." WHERE id_page IN (";
	$sql .= implode(',', $ids).')';
	
	$result = $db->query($sql);

	while ($row = $db->fetchArray($result)){
		$page = new QPPage();
		$page->assignVars($row);
		$page->setType(!$page->type());
		$page->update();
	}
    
	redirectMsg("pages.php?cat=$cat&page=$page", __('Database updated successfully!','qpages'), 0);
	
}

function saveChanges(){
	global $db;
	
	$cat = rmc_server_var($_POST, 'cat', '');
    $page = rmc_server_var($_POST, 'page', 1);
    $porder = rmc_server_var($_POST, 'porder', array());
	
	if (count($porder)<=0){
		header("location: pages.php?cat=$cat&page=$page", '', 0);
		die();
	}
	
	foreach($porder as $k => $v){
		$pag = new QPPage($k);
		if ($pag->isNew()) continue;
		$pag->setOrder($v);
		$pag->update();
	}
	redirectMsg("pages.php?cat=$cat&page=$page", __('Chenges saved successfully!','qpages'), 0);
	
}

function clonePage(){
    
    $id = rmc_server_var($_GET,'id',0);
    
    $page = new QPPage($id);
    if ($page->isNew()){
        redirectMsg('pages.php', __('Specified page does not exists!','qpages'), 1);
        die();
    }
    
    $page->setNew();
    $page->setTitle($page->getTitle().' [cloned]');
    $page->setFriendTitle(TextCleaner::sweetstring($page->getTitle()));
    if (!$page->save()){
        redirectMsg('pages.php', __('Page could not be cloned!','qpages'), 1);
        die();
    }
    
    redirectMsg('pages.php?op=edit&id='.$page->getID(), __('Page cloned successfully!','qpages'), 0);
    
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		newForm();
		break;
	case 'newlink':
		newLinkForm();
		break;
	case 'save':
		savePage();
		break;
	case 'edit':
		newForm(1);
		break;
	case 'editlink':
		newLinkForm(1);
		break;
	case 'saveedit':
		savePage(1);
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
    case 'clone':
        clonePage();
        break;
	default:
		showPages();
		break;
}
?>