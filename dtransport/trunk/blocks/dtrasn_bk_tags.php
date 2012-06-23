<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.com
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
// @copyright: 2007 - 2008 Red México


function dt_block_tags($options){
	global $db, $xoopsModule;
	
	if ($xoopsModule && $xoopsModule->dirname()=='dtransport'){
		global $xoopsModuleConfig;
		$mc =& $xoopsModuleConfig;
	} else {
		$util =& RMUtils::getInstance();
		$mc =& $util->moduleConfig('dtransport');
	}
	
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dttag.class.php';
	
	$sql="SELECT MAX(hits) FROM ".$db->prefix('dtrans_tags');
	list($maxhit)=$db->fetchRow($db->query($sql));
	$sql="SELECT * FROM ".$db->prefix('dtrans_tags');
	
	if ($options[2]<1){
		$sql .= " WHERE hits>0";
	}
	
	$sql .= " LIMIT 0,$options[0]";
	$result=$db->query($sql);
	$sz=$options[1]/$maxhit;
	
	$block = array();
	
	while ($row = $db->fetchArray($result)){
		$tag=new DTTag();
		$tag->assignVars($row);
		$link=XOOPS_URL."/modules/dtransport/".($mc['urlmode'] ? "tag/".$tag->tag() : "tags.php?id=".$tag->tag());
		
		$size=intval($tag->hit()*$sz);
		if ($size<$options[3]){
			$size=$options[3];
		}
		
		$rtn = array();
		$rtn['id'] = $tag->id();
		$rtn['tag'] = $tag->tag();
		$rtn['hits'] = $tag->hit();
		$rtn['link'] = $link;
		$rtn['size'] = $size;
		
		$block['tags'][] = $rtn;
		
	}
	
	$block['font'] = $options[4];
	
	return $block;
	
}

function dt_block_tags_edit($options, &$form){
	global $myts;
	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS,1));
	$form->addElement(new RMText(_BK_DT_TAGSNUM, 'options[0]', 5, 3, $options[0]), true, 'num');
	$form->addElement(new RMText(_BK_DT_TAGSSIZE, 'options[1]', 5, 2, $options[1]), true, 'num');
	$form->addElement(new RMYesNo(_BK_DT_TAGSCERO, 'options[2]', $options[2]));
	$form->addElement(new RMText(_BK_DT_TAGSMIN, 'options[3]', 5, 2, $options[3]), true, 'num');
	$form->addElement(new RMText(_BK_DT_TAGSFONT, 'options[4]', 50, 150, $myts->htmlSpecialChars($options[4])), true);
	
	return $form;
}

?>