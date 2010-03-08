<?php
// $Id$
// --------------------------------------------------------------
// MyFolder
// Advanced Portfolio System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include 'header.php';
/**
 * Mostramos las categor?as
 */
function rmmfShow(){
	global $db;
	define('RMCLOCATION','categories');
	
	MFFunctions::toolbar();
	RMTemplate::get()->add_head("<script type='text/javascript'>
			<!--
				function decision(message, url){
					if(confirm(message)) location.href = url;
				}
			-->
		   </script>");
	
	$result = array();
	MFFunctions::get_categories($result);
	
	include_once '../class/table.class.php';
	$table = new MFTable(true);
	$table->setCellStyle("padding: 0px; padding-left: 10px; border-bottom: 1px solid #0066CC; border-right: 1px solid #0066CC; background: url(../images/bgth.jpg) repeat-x; height: 20px; color: #FFFFFF;");
	$table->openTbl('100%','',1);
	$table->openRow('left');
	$table->addCell(_MA_RMMF_CATEGOS, 1,3);
	$table->closeRow();
	$table->setRowClass('head');
	$table->setCellStyle("padding: 0px; padding-left: 3px; padding-right: 3px; border-bottom: 1px solid #DBE691; border-right: 1px solid #DBE691; background: url(../images/bghead.jpg) repeat-x; height: 20px; color: #000000;");
	$table->openRow('center');
	$table->addCell(_MA_RMMF_NAME, 0, '','center');
	$table->addCell(_MA_RMMF_ORDER, 0, '','center');
	$table->addCell(_MA_RMMF_OPTIONS,0,'','center');
	$table->closeRow();
	
	$table->setRowClass('odd,even', true);
	$table->setCellStyle('');
	foreach ($result as $k=>$v){
		$table->openRow();
		$table->addCell((($v['saltos']<=0) ? "<img src='../images/plus.gif' border='0' align='absmiddle' />" : str_repeat("&nbsp;", $v['saltos']) .  "<img src='../images/root.gif' border='0' align='absmiddle' />")
						." <strong>$v[nombre]</strong>", 0, '', 'left');
		$table->addCell($v['orden'], 0, '', 'center');
		$table->addCell("<a href='?op=edit&amp;id=$v[id_cat]'>"._MA_RMMF_EDIT."</a> &nbsp;| &nbsp;
					<a href=\"javascript:decision('".sprintf(_MA_RMMF_CONFIRM, $v['nombre'])."','?op=del&id=$v[id_cat]');\">"._MA_RMMF_DELETE."</a>", 0, '', 'center');
		$table->closeRow();
	}
	
	$table->closeTbl();
	xoops_cp_header();

	xoops_cp_footer();
}

/**
 * Creamos una nueva categor?a
 */
function rmmfNew(){
	global $db, $mc;
	define('RMCLOCATION','newcategory');
	
	MFFunctions::toolbar();
	
	$form = new RMForm(__('New Category','admin_myfolder'), 'frmNew', 'categos.php');
	$form->addElement(new RMFormText(__('Name','admin_mywords'), 'nombre', 50, 150));
	
	$ele = new RMFormSelect(__('Parent category','admin_myfolder'), 'parent');
	$ele->addOption('', __('Select category...','admin_myfolder'), 1);
	$result = array();
	MFFunctions::get_categories($result);
	foreach ($result as $k => $v){
		$ele->addOption($v['id_cat'], $v['nombre']);
	}
	$form->addElement($ele);
	$form->addElement(new RMFormText(__('Display order','admin_myfolder'), 'orden', 5, 5, 0));
	$ele = new RMFormEditor(__('Description', 'admin_myfolder'), 'desc', '96%', '300px');
	$form->addElement($ele);
	
	$ele = new RMFormButtonGroup('');
	$ele->addButton('sbt', __('Create Category','admin_myfolder'), 'submit');
	$ele->addButton('cancel', __('Cancel','admin_myfolder'), 'button', 'onclick="history.go(-1);"');
	$form->addElement($ele, false);
	$form->addElement(new RMFormHidden('op', 'save'));
	
	xoops_cp_header();
	$form->display();
	xoops_cp_footer();
}

function rmmfSave(){
	global $db, $myts;
	
	$nombre = rmc_server_var($_POST, 'nombre', '');
	$parent = rmc_server_var($_POST, 'parent', 0);
	$desc = rmc_server_var($_POST, 'desc', '');
	$orden = rmc_server_var($_POST, 'orden', 0);
	
	if ($nombre==''){
		redirectMsg('categos.php?op=new', __('You must provide a name for this category','admin_myfolder'), 1);
		die();
	}
	
	$tbl = $db->prefix("rmmf_categos");
	list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM $tbl WHERE nombre='$nombre' AND parent='$parent'"));
	if ($num>0){
		redirectMsg('categos.php?op=new', __('A category with same name already exists!', 'admin_myfolder'), 1);
		die();
	}
	
	$desc = $myts->makeTareaData4Save($desc);
	$sql = "INSERT INTO $tbl (`nombre`,`orden`,`desc`,`parent`) VALUES
			('$nombre','$orden','$desc','$parent')";
	$db->query($sql);
	if ($db->error()!=''){
		redirectMsg('categos.php?op=new', __('There was some errors while trying to update database!', 'admin_myfolder').'<br />'.$db->error());
		die();
	} else {
		redirectMsg('categos.php', __('Database updated successfully!', 'admin_myfolder'));
		die();
	}
	
}

/**
 * Editamos una categor?a
 */
function rmmfEdit(){
	global $db, $mc, $myts;
	
	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	
	if ($id<=0){ header('location: categos.php'); die(); }
	
	define('_RMMF_LOCATION','NEWCATEGO');
	xoops_cp_header();
	rmmf_make_adminnav();
	
	include_once '../class/catego.class.php';
	include_once '../common/form.class.php';
	
	$catego = new MFCategory($id);
	
	$form = new RMForm(_MA_RMMF_MODCATEGO, 'frmmod', 'categos.php?op=saveedit');
	$form->addElement(new RMText(_MA_RMMF_NAME, 'nombre', 50, 150, $catego->getVar('nombre')));
	$result = array();
	$select = "<select name='parent'>
				<option value='0'>"._MA_RMMF_SELECT."</option>";
	rmmf_get_categos($result);
	foreach ($result as $k => $v){
		$select .= "<option value='$v[id_cat]'".(($v['id_cat']==$catego->getVar('parent')) ? " selected='selected'" : '').">$v[nombre]</option>";
	}
	$select .= "</select>";
	$form->addElement(new RMLabel(_MA_RMMF_PARENT, $select));
	$form->addElement(new RMText(_MA_RMMF_ORDER, 'orden', 5, 5, $catego->getVar('orden')));
	$form->addElement(new RMLabel(_MA_RMMF_DESC, rmmf_select_editor('desc',$mc['editor'],$myts->makeTareaData4Edit($catego->getVar('desc')),'100%','250px')));
	$form->addElement(new RMButton('sbt',_MA_RMMF_SEND));
	$form->addElement(new RMHidden('id',$id));
	$form->display();
	rmmf_make_footer();
	xoops_cp_footer();
	
	
}

/**
 * Guardamos los valores editados
 */
function rmmfSaveEdit(){
	global $db, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($id<=0){ header('location: categos.php'); die(); }
	
	if ($nombre==''){
		redirect_header('?op=edit&amp;id='.$id, 1, _MA_RMMF_ERRNAME);
		die();
	}
	
	$tbl = $db->prefix("rmmf_categos");
	list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM $tbl WHERE id_cat<>'$id' AND nombre='$nombre' AND parent='$parent'"));
	if ($num>0){
		redirect_header('?op=edit&amp;id='.$id, 1, _MA_RMMF_ERREXISTS);
		die();
	}
	
	$desc = $myts->makeTareaData4Save($desc);
	$sql = "UPDATE $tbl SET `nombre`='$nombre',`orden`='$orden',`desc`='$desc',
			`parent`='$parent' WHERE id_cat='$id'";
	$db->query($sql);
	if ($db->error()!=''){
		redirect_header('?op=edit&amp;id='.$id, 2, sprintf(_MA_RMMF_ERRDB, $db->error()));
		die();
	} else {
		header('location: categos.php'); die();
	}
}

/** 
 * Eliminamos una categoria
 */
function rmmfDelete(){
	global $db;
	$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0);
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$catego = isset($_POST['catego']) ? $_POST['catego'] : 0;
	
	if ($id<=0){ header('location: categos.php'); die(); }
	
	include_once '../class/catego.class.php';
	$catego = new MFCategory($id);
	$pass = false;
	if ($catego->getWorksNumber()<=0){ $ok = 1; $pass = true;}
	
	if ($ok){
		if ($catego<=0 && !$pass){
			redirect_header('?op=del&amp;id='.$id, 2, _MA_RMMF_SELECTCAT);
			die();
		}
		
		if (!$pass){
			$db->queryF("UPDATE ".$db->prefix("rmmf_works")." SET catego='$catego' WHERE catego='$id'");
		}
		
		$db->queryF("UPDATE ".$db->prefix("rmmf_categos")." SET parent='0' WHERE parent='$id'");
		
		$db->queryF("DELETE FROM ".$db->prefix("rmmf_categos")." WHERE id_cat='$id'");
		if ($db->error()!=''){
			redirect_header('categos.php', 2, sprintf(_MA_RMMF_ERRDB, $db->error()));
			die();
		} else {
			header('location: categos.php'); die();
		}
	} else {
		xoops_cp_header();
		rmmf_make_adminnav();
		$result = array();
		$select = "<select name='catego'>
				<option value='0'>"._MA_RMMF_SELECT."</option>";
		rmmf_get_categos($result);
		foreach ($result as $k => $v){
			$select .= "<option value='$v[id_cat]'>$v[nombre]</option>";
		}
	$select .= "</select>";
		echo "<div class='confirmMsg'><form name='frmDel' method='post' action='categos.php?op=del'>
				"._MA_RMMF_SELECTCAT."<br /><br />$select
				</form></div>";
		rmmf_make_footer();
		xoops_cp_footer();
	}
}

$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');

switch ($op){
	case 'new':
		rmmfNew();
		break;
	case 'save':
		rmmfSave();
		break;
	case 'edit':
		rmmfEdit();
		break;
	case 'saveedit':
		rmmfSaveEdit();
		break;
	case 'del':
		rmmfDelete();
		break;
	default:
		rmmfShow();
		break;
}
