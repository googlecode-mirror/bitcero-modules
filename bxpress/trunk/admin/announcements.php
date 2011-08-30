<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','announcements');
include 'header.php';

$db = Database::getInstance();
$db->queryF("DELETE FROM ".$db->prefix("bxpress_announcements")." WHERE expire<='".time()."'");
/**
* @desc Muestra la lista de los anuncios existentes
*/
function showAnnounces(){
	global $db, $xoopsModule;
	
	$result = $db->query("SELECT * FROM ".$db->prefix("bxpress_announcements")." ORDER BY date");
	$announcements = array();

	while ($row = $db->fetchArray($result)){
		$an = new bXAnnouncement();
		$an->assignVars($row);
		$announcements = array(
            'id'=>$an->id(),
            'text'=>substr($util->filterTags($an->text()), 0, 100),
			'date'=>formatTimestamp($an->date()),
            'expire'=>formatTimeStamp($an->expire()),
			'where'=>constant('_AS_BXPRESS_FWHERE'.$an->where()),
			'wherelink'=>$an->where()==1 ? '../forum.php?id='.$an->forum() : '../',
			'by'=>$an->byName()
        );
	}
	
    bXFunctions::menu_bar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Announcements Management','bxpress'));
	xoops_cp_header();

    include RMTemplate::get()->get_template("admin/forums_announcements.php", 'module', 'bxpress');

	xoops_cp_footer();
	
}

/**
* @desc Presenta el formulario para creación o edición de un anuncio
*/
function showForm($edit = 0){
	global $tpl, $xoopsModule, $mc, $db;
	
	if ($edit){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if ($id<=0){
			redirectMsg('announcements.php', _AS_BXPRESS_ERRID, 1);
			die();
		}
		
		$an = new BBAnnouncement($id);
		if ($an->isNew()){
			redirectMsg('announcements.php', _AS_BXPRESS_ERREXISTS, 1);
			die();
		}
	}
	
	showOptions();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_BB_EDITLOC : _AS_BB_NEWLOC));
	$cHead = '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	$form = new RMForm($edit ? _AS_BB_EDITLOC : _AS_BB_NEWLOC, 'frmAnnouncements', 'announcements.php');
	$form->oddClass('oddForm');
	$form->addElement(new RMEditor(_AS_BB_FANNOUNCEMENT, 'text', '100%','300px', $edit ? $an->text('e') : '', $mc['editor']), true);
	$ele = new RMCheck('');
	$ele->asTable(5);
	$ele->addOption(_AS_BXPRESS_BBCODE, 'doxcode', 1, $edit ? $an->bbcode() : 1);
	$ele->addOption(_AS_BXPRESS_SMILES, 'dosmiley', 1, $edit ? $an->smiley() : 1);
	$ele->addOption(_AS_BXPRESS_HTML, 'dohtml', 1, $edit ? $an->html() : 0);
	$ele->addOption(_AS_BXPRESS_BR, 'dobr', 1, $edit ? $an->wrap() : 1);
	$ele->addOption(_AS_BXPRESS_IMG, 'doimg', 1, $edit ? $an->doImage() : 1);
	$form->addElement($ele);
	
	// Caducidad
	$ele = new RMDate(_AS_BXPRESS_FEXPIRE, 'expire', $edit ? $an->expire() : time());
	$form->addElement($ele);
	// Mostran en
	$ele = new RMRadio(_AS_BXPRESS_FWHERE, 'where', 1, 0);
	$ele->addOption(_AS_BXPRESS_FWHERE0, 0, $edit ? $an->where()==0 : 1);
	$ele->addOption(_AS_BXPRESS_FWHERE1, 1, $edit ? $an->where()==1 : 0);
	$ele->addOption(_AS_BXPRESS_FWHERE2, 2, $edit ? $an->where()==2 : 0);
	$form->addElement($ele);
	
	// Foros
	$ele = new RMSelect(_AS_BXPRESS_FFORUM, 'forum',0,$edit ? array($an->forum()) : array());
	$ele->setDescription(_AS_BXPRESS_FFORUM_DESC);
	$tbl1 = $db->prefix("bxpress_categories");
	$tbl2 = $db->prefix("bxpress_forums");
	$sql = "SELECT b.*, a.title FROM $tbl1 a, $tbl2 b WHERE b.cat=a.id_cat AND b.active='1' ORDER BY a.order, b.order";
	$result = $db->query($sql);
	$categories = array();
	while ($row = $db->fetchArray($result)){
		$cforum = array('id'=>$row['id_forum'], 'name'=>$row['name']);
		if (isset($categores[$row['cat']])){
			$categories[$row['cat']]['forums'][] = $cforum;
		} else {
			$categories[$row['cat']]['title'] = $row['title'];
			$categories[$row['cat']]['forums'][] = $cforum;
		}
	}
		
	foreach ($categories as $cat){
		$ele->addOption(0, $cat['title'], 0, true, 'color: #000; font-weight: bold; font-style: italic; border-bottom: 1px solid #c8c8c8;');
		foreach ($cat['forums'] as $cforum){
			$ele->addOption($cforum['id'], $cforum['name'],0,false,'padding-left: 10px;');
		}
			
	}
	$form->addElement($ele);
	
	$ele = new RMButtonGroup();
	$ele->addButton('sbt', $edit ? _AS_BXPRESS_FEDIT : _AS_BXPRESS_FCREATE, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'announcements.php\';"');
	$form->addElement($ele);
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMHidden('id',$id));
	
	$form->display();
	xoops_cp_footer();

}

/**
* @desc Almacena los datos de un anuncio
*/
function saveAnnouncement($edit = 0){
	global $xoopsUser, $util, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$util->validateToken()){
		redirectMsg('announcements.php?op='.($edit ? 'edit&id='.$id : 'new'), _AS_BB_ERRTOKEN, 1);
		die();
	}
	
	if ($edit){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if ($id<=0){
			redirectMsg('announcements.php', _AS_BXPRESS_ERRID, 1);
			die();
		}
		
		$an = new BBAnnouncement($id);
		if ($an->isNew()){
			redirectMsg('announcements.php', _AS_BXPRESS_ERREXISTS, 1);
			die();
		}
	} else {
		$an = new BBAnnouncement();
	}
	
	$expire = rmsoft_read_date('expire');
	if ($expire<=time()){
		redirectMsg('announcements.php?op='.($edit ? 'edit&id='.$id : 'new'), _AS_BXPRESS_ERRCADUC, 1);
		die();
	}
	
	$an->setBBCode(isset($doxcode) ? 1 : 0);
	$an->setBy($xoopsUser->uid());
	$an->setByName($xoopsUser->uname());
	if (!$edit) $an->setDate(time());
	$an->setDoImage(isset($doimg) ? 1 : 0);
	$an->setExpire($expire);
	$an->setForum($forum);
	$an->setHtml(isset($dohtml) ? 1 : 0);
	$an->setSmiley(isset($dosmiley) ? 1 : 0);
	$an->setText($text);
	$an->setWhere($where);
	$an->setWrap(isset($dobr) ? 1 : 0);
	
	if ($an->save()){
		redirectMsg('announcements.php', _AS_BB_DBOK, 0);
	} else {
		redirectMsg('announcements.php', _AS_BB_ERRACTION . '<br />' . $an->errors(), 1);
	}
		
}

/**
* @desc Elimina anuncios de la base de datos
*/
function deleteAnnouncements(){
	global $util, $db;
	
	if (!$util->validateToken()){
		redirectMsg('announcements.php', _AS_BB_ERRTOKEN, 1);
		die();
	}
	
	$an = isset($_REQUEST['announcements']) ? $_REQUEST['announcements'] : null;
	
	if (!isset($an)){
		redirectMsg('announcements.php', _AS_BXPRESS_ERRSEL, 1);
		die();
	}
	
	if (!is_array($an) && $an<=0){
		redirectMsg('announcements.php', _AS_BXPRESS_ERRSEL, 1);
		die();
	}
	
	if (!is_array($an)) $an = array($an);
	
	if (count($an)<=0){
		redirectMsg('announcements.php', _AS_BXPRESS_ERRSEL, 1);
		die();
	}
	
	$sql = "DELETE FROM ".$db->prefix("bxpress_announcements")." WHERE ";
	$sql1 = '';
	foreach ($an as $k){
		$sql1 .= $sql1 == '' ? "id_an='$k'" : " OR id_an='$k'";
	}
	
	if ($db->queryF($sql.$sql1)){
		redirectMsg('announcements.php', _AS_BB_DBOK, 0);
	} else {
		redirectMsg('announcements.php', _AS_BB_ERRACTION . '<br />' . $db->error(), 0);
	}
	
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		showForm();
		break;
	case 'edit':
		showForm(1);
		break;
	case 'save':
		saveAnnouncement();
		break;
	case 'saveedit':
		saveAnnouncement(1);
		break;
	case 'delete':
		deleteAnnouncements();
		break;
	default:
		showAnnounces();
		break;
}
?>
