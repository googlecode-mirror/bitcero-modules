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
* Este archivo permite controlar el bloque o los bloques
* Bloques Existentes:
* 
* 1. Publicaciones Recientes
* 2. Publicaciones Populares (Mas Leídas)
* 3. Publicaciones Mejor Votadas
*/

function ahelp_block_resources($options){
	global $xoopsModule;
	
	include_once XOOPS_ROOT_PATH.'/modules/ahelp/class/ahresource.class.php';
	$db =& Database::getInstance();
	$util =& RMUtils::getInstance();
	
	if (isset($xoopsModule) && $xoopsModule->dirname()=='ahelp'){
		global $xoopsModuleConfig;
		$mc =& $xoopsModuleConfig;
	} else {
		$mc = $util->moduleConfig('ahelp');
	}
	
	$sql = "SELECT * FROM ".$db->prefix("pa_resources");
	if ($options[0]==0){
		$sql .= " WHERE type='0'";
	} elseif($options[0]==1){
		$sql .= " WHERE type='1'";
	}
	
	switch ($options[1]){
		case 'recents':
			$sql .= " ORDER BY created DESC";
			break;
		case 'popular':
			$sql .= " ORDER BY `reads` DESC";
			break;
		case 'rated':
			$sql .= " ORDER BY rating/votes DESC";
			break;
	}
	
	$sql .= " LIMIT 0, ".($options[2]>0 ? $options[2] : 5);
	$link = XOOPS_URL.'/modules/ahelp/';
	
	$result = $db->query($sql);
	$block = array();
	while ($row = $db->fetchArray($result)){
		$res = new AHResource();
		$res->assignVars($row);
		$ret = array();
		
		$ret['id'] = $res->id();
		$ret['title'] = $res->title();
		if ($options[5]){
			$ret['desc'] = $options[6]==0 ? $res->desc() : substr($util->filterTags($res->desc()), 0, $options[6]);
		}
		if ($options[3]){
			$ret['img'] = $res->image();
		}
		// Enlace al recurso
		if ($res->type()){
			$ret['link'] = $link . ($mc['access'] ? 'article/'.$res->id().'/'.$res->nameId() : 'content.php?t=a&amp;id='.$res->id());
		}else{
			$ret['link'] = $link . ($mc['access'] ? 'resource/'.$res->id().'/'.$res->nameId() : 'content.php?id='.$res->id());
		}
		
		$ret['rating'] = @intval($res->rating()/$res->votes());
		$ret['votes'] = sprintf(_BS_AH_VOTES, $res->votes());
		$ret['author'] = sprintf(_BS_AH_RESBY, $res->owname());
		$ret['reads'] = sprintf(_BS_AH_READS, $res->reads());
		
		$block['resources'][] = $ret;
		
	}
	$block['cols'] = $options[4];
	$block['image'] = $options[3];
	$block['format'] = $options[7];
	
	return $block;
	
}

function ahelp_block_resources_edit($options){
	
	$rtn = "<table cellspacing='1' cellpadding='2' border='0'>
				<tr class='even'>
					<td>"._BS_AH_RESTYPE."</td>
					<td>
						<select name='options[0]'>
							<option value='2'".($options[0]==2 ? " selected='selected'" : "").">"._BS_AH_RESALL."</option>
							<option value='0'".($options[0]==0 ? " selected='selected'" : "").">"._BS_AH_RESBOOKS."</option>
							<option value='1'".($options[0]==1 ? " selected='selected'" : "").">"._BS_AH_RESARTICLE."</option>
						</select>
					</td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_BLOCKTYPE."</td>
					<td>
					<select name='options[1]'>
						<option value='recents'".($options[1]=='recents' ? " selected='selected'" : "").">"._BS_AH_RECENT."</option>
						<option value='popular'".($options[1]=='popular' ? " selected='selected'" : "").">"._BS_AH_POPULARS."</option>
						<option value='rated'".($options[1]=='rated' ? " selected='selected'" : "").">"._BS_AH_BESTRATED."</option>
					</select>
					</td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_RESNUM."</td>
					<td><input type='text' name='options[2]' value='$options[2]' size='5' /></td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_SHOWIMG."</td>
					<td>
						<input type='radio' value='1' name='options[3]'".($options[3] ? " checked='checked'" : "")." /> "._YES."
						<input type='radio' value='0' name='options[3]'".(!$options[3] ? " checked='checked'" : "")." /> "._NO."
					</td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_COLS."</td>
					<td><input type='text' name='options[4]' value='$options[4]' size='5' /></td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_DESC."</td>
					<td>
						<input type='radio' value='1' name='options[5]'".($options[5] ? " checked='checked'" : "")." /> "._YES."
						<input type='radio' value='0' name='options[5]'".(!$options[5] ? " checked='checked'" : "")." /> "._NO."
					</td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_DESCLEN."<br /><br /><small><em>"._BS_AH_DESCLEN_DESC."</em></small></td>
					<td><input type='text' name='options[6]' value='$options[6]' size='5' /></td>
				</tr>
				<tr class='even'>
					<td>"._BS_AH_FORMAT."</td>
					<td>
					<select name='options[7]'>
						<option value='1'".($options[7] ? " selected='selected'" : "").">"._BS_AH_VERTICAL."</option>
						<option value='0'".(!$options[7] ? " selected='selected'" : "").">"._BS_AH_HORIZONTAL."</option>
					</select>
					</td>
				</tr>
	        </table>";
	
	return $rtn;
	
}
