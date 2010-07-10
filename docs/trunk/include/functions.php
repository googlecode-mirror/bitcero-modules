<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

/**
* @desc Genera el arbol de categorías en un array
* @param array Referencia del Array que se rellenará
* @param int Id de la Sección padre
* @param int Contador de sangría
*/
function getSectionTree(&$array, $parent = 0, $saltos = 0, $resource = 0, $fields='*', $exclude=0){
	global $db;
	$sql = "SELECT $fields FROM ".$db->prefix("pa_sections")." WHERE ".($resource>0 ? "id_res='$resource' AND" : '')."
			parent='$parent' ".($exclude>0 ? "AND id_sec<>'$exclude'" : '')." ORDER BY `order`";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$ret = array();
		$ret = $row;
		$ret['saltos'] = $saltos;
		$array[] = $ret;
		getSectionTree($array, $row['id_sec'], $saltos + 1, $resource, $fields, $exclude);
	}
	
	return true;
	
}

/**
* Assign vars to Smarty var, then this var can be used as index of the resource
* @param int Id of the section parent
* @param int Jumps (level)
* @param object Resource (owner)
* @param string Smarty var to append
* @param string Index number to add (eg. 1.1)
* @param bool Indicates if the array will be assigned to Smarty var or not
* @param array Reference to an array for fill.
* @return empty
*/
function assignSectionTree($parent = 0, $jumps = 0, AHResource $res, $var = 'index', $number='', $assign = true, &$array = null){
	global $tpl;
	$db = Database::getInstance();
	
	if (get_class($res)!='AHResource') return;
	
	$sql = "SELECT * FROM ".$db->prefix("pa_sections")." WHERE ".($res->id()>0 ? "id_res='".$res->id()."' AND" : '')."
			parent='$parent' ORDER BY `order`";
	$result = $db->query($sql);
	$sec = new AHSection();
	$i = 1; // Counter
	$num = 1;
	while ($row = $db->fetchArray($result)){
		$sec->assignVars($row);
		$link = ah_make_link($res->nameId().'/'.$sec->nameId());
		if ($assign){
			$tpl->append($var, array('title'=>$sec->title(),'nameid'=>$sec->nameId(), 'jump'=>$jumps,'link'=>$link, 'number'=>$jumps==0 ? $num : ($number !='' ? $number.'.' : '').$i));
		} else {
			$array[] = array('title'=>$sec->title(), 'nameid'=>$sec->nameId(), 'jump'=>$jumps,'link'=>$link, 'number'=>$jumps==0 ? $num : ($number !='' ? $number.'.' : '').$i);
		}
		assignSectionTree($sec->id(), $jumps+1, $res, $var, ($number !='' ? $number.'.' : '').$i, $assign, $array);
		$i++;
		if ($jumps==0) $num++;
	}
	
	return true;
}

/**
* @desc Obtiene el primer parent de la sección especificada
* @param int Id de la sección
*/
function getSuperParent($id){
	global $db;
	
	if ($id<=0) return;
	
	$sql = "SELECT id_sec, parent, nameid FROM ".$db->prefix("pa_sections")." WHERE id_sec='$id'";
	$result = $db->query($sql);
	if ($db->getRowsNum($result)<=0) return;
	list($id_sec, $parent, $nameid) = $db->fetchRow($result);
	if ($parent>0){
		$section = getSuperParent($parent);
	} else {
		$section['id'] = $id_sec;
		$section['parent'] = $parent;
		$section['nameid'] = $nameid;
	}
	
	return $section;
	
}

function ahBuildReference($id){
	global $xoopsModuleConfig, $tpl;
	
	$ref = new AHReference($id);
	if ($ref->isNew()) return;

	$ret = "<a name='top$id'></a><a href='javascript:;' ".(!$xoopsModuleConfig['refs_method'] ? "title='".$ref->title()."' " : " ");
	if ($xoopsModuleConfig['refs_method']){
		$ret .= "onclick=\"doReference(event,'$id');\"";
	} else {
		$ret .= "onclick=\"showReference($id,'$xoopsModuleConfig[refs_color]');\"";
		$tpl->append('references', array('id'=>$ref->id(),'text'=>$ref->reference()));
		$tpl->assign('have_refs', 1);
	}
	$ret .= "><img src='".XOOPS_URL."/modules/ahelp/images/reflink.png' align='textop' ".(!$xoopsModuleConfig['refs_method'] ? "alt='".$ref->title()."'" : "")." /></a>";
	
	return $ret;
}

function ahBuildFigure($id){
    
    $fig = new AHFigure($id);
    if ($fig->isNew()) return;
    
    $ret = "<div ";
    if ($fig->_class()!='') $ret .= "class='".$fig->_class()."' ";
    if ($fig->style()!='') $ret .= "style='".$fig->style()."' ";
    
    $ret .= $fig->figure();
    
    $ret .= "<div class='ahFigureFoot'>".$fig->desc()."</div></div>";
    
    return $ret;
    
}

/**
* @desc Función para crear las referencias del documento
*/
function ahParseReferences($text){
	
    // Parseamos las referencias
	$pattern = "/\[ref:(.*)]/esU";
	$replacement = "ahBuildReference(\\1)";
	$text = preg_replace($pattern, $replacement, $text);
    
    // Parseamos las figuras
    $pattern = "/\[fig:(.*)]/esU";
    $replacement = "ahBuildFigure(\\1)";
    $text = preg_replace($pattern, $replacement, $text);
    
	return $text;
	
}

/**
* @desc Incrementa las lecturas en un recurso si es posible
* @param object Objeto AHResource
* @return bool
*/
function addRead(AHResource &$res){
	
	if (!isset($_SESSION['ahResources'])){
		$_SESSION['ahResources'] = array($res->id());
		$res->addRead();
		return true;
	} else {
		
		if (in_array($res->id(), $_SESSION['ahResources'])) return false;
		
		$_SESSION['ahResources'][] = $res->id();
		$res->addRead();
		return true;
	}
		
}



function ah_make_link($link=''){
    global $xoopsModuleConfig;
    
    $mc =& $xoopsModuleConfig;
    $url = $mc['access'] ? XOOPS_URL.$mc['htpath'].'/' : XOOPS_URL.'/modules/ahelp/index.php?page=';
    
    return $url.$link;
    
}
