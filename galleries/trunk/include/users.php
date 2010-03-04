<?php
// $Id$
// --------------------------------------------------------------
// RMSOFT Common Utilities
// Utilidades comunes para m?dulos de Red México
// CopyRight © 2005 - 2006. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoops-mexico.net
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
// --------------------------------------------------------------
// @copyright: 2005 - 2006. BitC3R0. Red México
// @author: BitC3R0

require '../../../mainfile.php';

if (file_exists(XOOPS_ROOT_PATH.'/rmcommon/language/'.$xoopsConfig['language'].'_users.php')){
	include_once XOOPS_ROOT_PATH.'/rmcommon/language/'.$xoopsConfig['language'].'_users.php';
} else {
	XOOPS_ROOT_PATH.'/rmcommon/language/spanish.php';
}

$tpl = new XoopsTpl();
$db =& Database::getInstance();

$type = 0;
$s = '';
$kw = '';
$ord = 2;

foreach($_REQUEST as $k => $v){
	$$k = $v;
}

if (!isset($field) || $field==''){
	echo "<script type='text/javascript'>\nwindow.close();\n</script>";
	die();
}

$field = addslashes($field);
$kw = addSlashes($kw);

if (is_string($s) && $s!=''){
	$users = explode(',',$s);
} elseif (is_array($s)) {
	$users = $s;
} else {
	$users = array();
}
$tpl->assign('selstring', implode(',',$users));

$sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_users");
if ($kw!=''){
	$sql .= " AND (uname LIKE '%$kw%' OR name LIKE '%$kw%')";
}

list($num) = $db->fetchRow($db->query($sql));

$page = isset($pag) ? $pag : 0;
$limit = isset($limit) && $limit>0 ? $limit : 100;
	
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

/**
* Paginación de resultados
*/
$tpl->assign('pactual', $pactual);
$tpl->assign('tpages', $tpages);
$tpl->assign('tusers', $num);
$tpl->assign('limit', $limit);
	
if ($pactual > 1){
	if ($pactual>2 && $tpages > 8){
		$tpl->append('pages', array('id'=>'primera'));
	}
	$tpl->append('pages', array('id'=>'anterior'));
}
	
$pstart = $pactual-3>0 ? $pactual-3 : 1;
$pend = ($pstart + 6)<=$tpages ? ($pstart + 6) : $tpages;
	
if ($pstart > 5){
	$tpl->append('pages', array('id'=>3,'salto'=>1));
}
	
if ($tpages > 0){
	for ($i=$pstart;$i<=$pend;$i++){
		$tpl->append('pages', array('id'=>$i));
	}
}
	
if ($pend < $tpages-5){
	$tpl->append('pages', array('id'=>$tpages-3,'salto'=>2));
}
	
if ($pactual < $tpages && $tpages > 1){
	$tpl->append('pages', array('id'=>'siguiente'));
	if ($pactual < $tpages-1 && $tpages > 8){
		$tpl->append('pages', array('id'=>'ultima'));
	}
}

$sql = str_replace('COUNT(*)','uid, uname', $sql);
switch($ord){
	case '0':
		$sql .= " ORDER BY date";
		break;
	case '1':
		$sql .= " ORDER BY uname";
		break;
	default:
		$sql .= " ORDER BY uid";
		break;
}
$sql .= " LIMIT $start,$limit";
//$sql = "SELECT uid, uname FROM ".$db->prefix("users")." WHERE level>0 LIMIT $start,$limit";
$result = $db->query($sql);

if ($all){
	$tpl->append('users', array('id'=>0,'name'=>_RMS_CF_ALL,'check'=>in_array(0, $users)));
}

while ($row = $db->fetchArray($result)){
	$tpl->append('users', array('id'=>$row['uid'],'name'=>$row['uname'],'check'=>in_array($row['uid'], $users)));
}

if (is_array($users) && count($users)>0){
	$sql = "SELECT uid,uname FROM ".$db->prefix("gs_users")." WHERE uid IN (".implode(',',$users).")";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$tpl->append('selected', array('id'=>$row['uid'],'name'=>$row['uname'],'check'=>in_array($row['uid'], $users)));
	}
	
}

$tpl->assign('field_type', $type ? 'checkbox' : 'radio');
$tpl->assign('lang_existing',_RMS_CFU_EXISTING);
$tpl->assign('lang_selected',_RMS_CFU_SELECTED);
$tpl->assign('user_count', count($users));
$tpl->assign('field_name', str_replace('[]','',$field));
$tpl->assign('lang_insertusers', _RMS_CFU_INSERT);
$tpl->assign('lang_search', _RMS_CFU_SEARCH);
$tpl->assign('lang_order', _RMS_CFU_ORDER);
$tpl->assign('lang_register', _RMS_CFU_REGISTERED);
$tpl->assign('lang_uname', _RMS_CFU_UNAME);
$tpl->assign('lang_id', _RMS_CFU_ID);
$tpl->assign('kw',$kw);
$tpl->assign('ord',$ord);
$tpl->assign('toeval', base64_decode($eval));
$tpl->assign('toeval_encode', $eval);

$tpl->display(XOOPS_ROOT_PATH.'/rmcommon/templates/rmcommon_users.html');

?>
