<?php
// $Id: bookmarks.php 16 2009-09-13 01:38:59Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','bookmarks');
require('header.php');

include_once '../include/general.func.php';

/**
* @desc Muestra la lista de los elementos disponibles
*/
function show_bookmarks(){
    global $xoopsModule, $xoopsConfig, $xoopsSecurity;
    
    // Cargamos los sitios
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".$db->prefix("mw_bookmarks")." ORDER BY title";
    $result = $db->query($sql);
    
    while ($row = $db->fetchArray($result)){
        $bm = new MWBookmark();
        $bm->assignVars($row);
        $bookmarks[] = array(
        	'id'=>$bm->id(),
        	'name'=>$bm->getVar('title'),
        	'icon'=>$bm->getVar('icon'),
        	'desc'=>$bm->getVar('alt'),
        	'active'=>$bm->getVar('active'),
        	'url'=>str_replace(array('{','}'), array('<span>{','}</span>'), $bm->getVar('url'))
        );
    }
    
    $temp = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH.'/modules/mywords/images/icons');
    foreach ($temp as $icon){
        $icons[] = array('url'=>XOOPS_URL."/modules/mywords/images/icons/$icon", 'name'=>$icon);
    }
    
    MWFunctions::include_required_files();
    
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Bookmark Sites','admin_mywords'));
	xoops_cp_header();
	
    extract($_GET);
	include RMTemplate::get()->get_template('admin/mywords_bookmarks.php','module','mywords');
	RMTemplate::get()->assign('xoops_pagetitle', __('Bookmarks Management','admin_mywords'));
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/bookmarks.js');
	
	xoops_cp_footer();
    
}

/**
* @desc Muestra el formulario para agregar un nuevo sitio
*/
function newForm($edit = 0){
    global $xoopsModule, $xoopsConfig, $mc;
    
    
    if ($edit){
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id==0){
            redirectMsg('bookmarks.php', _AS_MW_ERRID, 1);
            die();
        }
        
        $book = new MWBookmark($id);
        
        if ($book->isNew()){
            redirectMsg('bookmarks.php', _AS_MW_ERRID, 1);
            die();
        }
        
    }
        
    menubar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_MW_EDITBM : _AS_MW_ADDBM));
    xoops_cp_header('<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />');
    
    $form = new RMForm($edit ? _AS_MW_ETITLE : _AS_MW_NTITLE, 'bookForm', 'bookmarks.php');

    $form->styles('width: 30%', 'odd');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new RMText(_AS_MW_NAME, 'name', 50, 150, $edit ? $book->name() : ''), true);
    $ele = new RMText(_AS_MW_URL, 'url', 50, '255', $edit ? $book->url() : '');
    $ele->setDescription(_AS_MW_URLD);
    $form->addElement($ele, true);
    // Icons
    $icons = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH.'/modules/mywords/images/icons');
    $ele = '';
    foreach ($icons as $icon){
        $ele .= "<label class='icons'><input type='radio' name='icon' value='$icon' ".($icon==$book->icon() ? 'checked="checked"' : '')." /> <img src='../images/icons/$icon' alt='$icon' title='$icon' /></label>";
    }
    $form->addElement(new RMLabel(_AS_MW_ICON, $ele));
    if ($edit && $book->icon()!=''){
        $form->addElement(new RMLabel(_AS_MW_CURRENTICON, '<img src="../images/icons/'.$book->icon().'" alt="" />'));
    }
    $form->addElement(new RMText(_AS_MW_ALT, 'alt', 50, '200', $edit ? $book->text() : ''));
    $form->addElement(new RMYesNo(_AS_MW_ACTIVE, 'active', $edit ? $book->active() : 1));
    unset($ele);
    $ele = new RMButtonGroup();
    $ele->addButton('sbt', _SUBMIT, 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', "onclick='history.go(-1);'");
    
    $form->addElement($ele);
    $form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMHidden('id',$book->id()));
    $form->display();
    
    xoops_cp_footer();
    
}

/**
* @desc Almacena los datos de un sitio
*/
function save_bookmark($edit){
    global $xoopsSecurity;
    
    if (!$xoopsSecurity->check()){
		redirectMsg('bookmarks.php', __('Operation not allowed!', 'admin_mywords'), 1);
		die();
    }
    
    if ($edit){
		
		$id = rmc_server_var($_POST, 'id', 0);
		if ($id<=0){
			redirectMsg('bookmarks.php', __('Site ID not provided!','admin_mywords'), 1);
			die();
		}
		
		$book = new MWBookmark($id);
		if($book->isNew()){
			redirectMsg('bookmarks.php', __('Social site not exists!','admin_mywords'), 1);
			die();
		}
		
    }
    
    die();
    
    $qs .= $edit ? "&op=edit" : "&op=new";
    
    if ($edit){
        
        if ($id==0){
            redirectMsg('bookmarks.php', _AS_MW_ERRID, 1);
            die();
        }
        
        $book = new MWBookmark($id);
        
        if ($book->isNew()){
            redirectMsg('bookmarks.php', _AS_MW_ERRID, 1);
            die();
        }
        
    } else {
        $book = new MWBookmark();
    }
    
    $book->setName($name);
    $book->setActive($active);
    $book->setText($alt);
    $book->setUrl($url);
    
    $book->setIcon($icon);
    
    if ($book->save()){
        redirectMsg('bookmarks.php', _AS_MW_DBOK, 0);
    } else {
        redirectMsg('bookmarks.php?'.$qs, _AS_MW_DBERROR . '<br />' . $book->errors(), 1);
    }
    
}

/**
* @desc Activa o desactiva los sitios
*/
function activate(){
    global $db;
    
    $bm = isset($_POST['bm']) ? $_POST['bm'] : array();
    
    if (count($bm)<=0){
        redirectMsg('bookmarks.php', _AS_MW_ERRSEL, 1);
        die();
    }
    
    $in = '';
    foreach ($bm as $id){
        $in .= $in=='' ? $id : ','.$id;
    }
    
    $sql = "SELECT * FROM ".$db->prefix("mw_bookmarks")." WHERE id_book IN ($in)";
    $result = $db->query($sql);
    
    $book = new MWBookmark();
    
    while ($row = $db->fetchArray($result)){
        $book->assignVars($row);
        $book->unsetNew();
        $book->setActive($book->active() ? 0 : 1);
        $book->save();
    }
    
    redirectMsg('bookmarks.php', _AS_MW_DBOK, 0);
    
}

/**
* @desc Elimina un sitio de la base de datos
*/
function deleteBookmark(){
    global $db;
    
    $bm = isset($_REQUEST['bm']) ? $_REQUEST['bm'] : array();
    if (!is_array($bm)) $bm = array($bm);
    
    if (count($bm)<=0){
        redirectMsg('bookmarks.php', _AS_MW_ERRSEL, 1);
        die();
    }
    
    $in = '';
    foreach ($bm as $id){
        $in .= $in=='' ? $id : ','.$id;
    }
    
    $sql = "SELECT * FROM ".$db->prefix("mw_bookmarks")." WHERE id_book IN ($in)";
    $result = $db->query($sql);
    
    $book = new MWBookmark();
    
    while ($row = $db->fetchArray($result)){
        $book->assignVars($row);
        $book->delete();
    }
    
    redirectMsg('bookmarks.php', _AS_MW_DBOK, 0);
       
}


$action = rmc_server_var($_POST, 'action', '');

switch($action){
    default:
        show_bookmarks();
        break;
    case 'new':
        save_bookmark();
        break;
    case 'edit':
        newForm(1);
        break;
    case 'save':
        saveBookmark();
        break;
    case 'saveedit':
        saveBookmark(1);
        break;
    case 'activate':
        activate();
        break;
    case 'delete':
        deleteBookmark();
        break;
}

?>
