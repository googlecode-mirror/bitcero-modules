<?php
/*******************************************************************
* $Id$          *
* -------------------------------------------------------          *
* RMSOFT MyFolder 1.0                                              *
* Módulo para el manejo de un portafolio profesional               *
* CopyRight © 2006. Red México Soft                                *
* Autor: BitC3R0                                                   *
* http://www.redmexico.com.mx                                      *
* http://www.xoops-mexico.net                                      *
* --------------------------------------------                     *
* This program is free software; you can redistribute it and/or    *
* modify it under the terms of the GNU General Public License as   *
* published by the Free Software Foundation; either version 2 of   *
* the License, or (at your option) any later version.              *
*                                                                  *
* This program is distributed in the hope that it will be useful,  *
* but WITHOUT ANY WARRANTY; without even the implied warranty of   *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the     *
* GNU General Public License for more details.                     *
*                                                                  *
* You should have received a copy of the GNU General Public        *
* License along with this program; if not, write to the Free       *
* Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,   *
* MA 02111-1307 USA                                                *
*                                                                  *
* -------------------------------------------------------          *
* categos.php:                                                     *
* Manejo de Categorías                                             *
* -------------------------------------------------------          *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:35:09 a.m.                            *
*******************************************************************/

include 'header.php';
/**
 * Mostramos las categorías
 */
function rmmfShow(){
	global $db;
	define('_RMMF_LOCATION','CATEGOS');
	xoops_cp_header();
	echo "<script type='text/javascript'>
			<!--
				function decision(message, url){
					if(confirm(message)) location.href = url;
				}
			-->
		   </script>";
	rmmf_make_adminnav();
	
	$result = array();
	rmmf_get_categos($result);
	
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
	rmmf_make_footer();
	xoops_cp_footer();
}

/**
 * Creamos una nueva categoría
 */
function rmmfNew(){
	global $db, $mc;
	define('_RMMF_LOCATION','NEWCATEGO');
	xoops_cp_header();
	rmmf_make_adminnav();
	
	include_once '../common/form.class.php';
	$form = new RMForm(_MA_RMMF_NEWCATEGO, 'frmNew', 'categos.php?op=save');
	$form->addElement(new RMText(_MA_RMMF_NAME, 'nombre', 50, 150));
	$result = array();
	$select = "<select name='parent'>
				<option value='0'>"._MA_RMMF_SELECT."</option>";
	rmmf_get_categos($result);
	foreach ($result as $k => $v){
		$select .= "<option value='$v[id_cat]'>$v[nombre]</option>";
	}
	$select .= "</select>";
	$form->addElement(new RMLabel(_MA_RMMF_PARENT, $select));
	$form->addElement(new RMText(_MA_RMMF_ORDER, 'orden', 5, 5, 0));
	$form->addElement(new RMLabel(_MA_RMMF_DESC, rmmf_select_editor('desc',$mc['editor'],'','100%','250px')));
	$form->addElement(new RMButton('sbt',_MA_RMMF_SEND));
	$form->display();
	rmmf_make_footer();
	xoops_cp_footer();
}

function rmmfSave(){
	global $db, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($nombre==''){
		redirect_header('?op=new', 1, _MA_RMMF_ERRNAME);
		die();
	}
	
	$tbl = $db->prefix("rmmf_categos");
	list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM $tbl WHERE nombre='$nombre' AND parent='$parent'"));
	if ($num>0){
		redirect_header('?op=new', 1, _MA_RMMF_ERREXISTS);
		die();
	}
	
	$desc = $myts->makeTareaData4Save($desc);
	$sql = "INSERT INTO $tbl (`nombre`,`orden`,`desc`,`parent`) VALUES
			('$nombre','$orden','$desc','$parent')";
	$db->query($sql);
	if ($db->error()!=''){
		redirect_header('?op=new', 2, sprintf(_MA_RMMF_ERRDB, $db->error()));
		die();
	} else {
		header('location: categos.php'); die();
	}
	
}

/**
 * Editamos una categoría
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
?>