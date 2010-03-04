<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

function gs_photos_show($options){
	
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
	
	$options[0]<=0 ? 4 : $options[0];
	$db =& Database::getInstance();
	$util =& RMUtils::getInstance();
	
	$sql = "SELECT * FROM ".$db->prefix("gs_images");
	$users = array();

	if ($options[3]>0){
		if (!isset($users[$options[3]])) $users[$options[3]] = new GSUser($options[3], 1);
		$user = $users[$options[3]];
		$sql .= " WHERE owner='".$user->uid()."'";
	}
	$order = $options[2]=='0' ? "created DESC" : ($options[2]=='1' ? 'RAND()' : 'views DESC');
	$sql .= " ORDER BY $order LIMIT 0,$options[0]";
	$result = $db->query($sql);
	$mc =& $util->moduleConfig('galleries');
	$block = array();
	
	while ($row = $db->fetchArray($result)){
		$pic = new GSImage();
		$pic->assignVars($row);
		
		if (!isset($users[$pic->owner()])) $users[$pic->owner()] = new GSUser($pic->owner(), 1);
		$user =& $users[$pic->owner()];
		
		$rtn = array();
		$rtn['title'] = $pic->title();
		if ($options[4]) $rtn['desc'] = $pic->desc();
		$rtn['created'] = formatTimestamp($pic->created(), 'string');
		$rtn['views'] = $pic->views();
		$rtn['by'] = sprintf(_BK_GS_BY, $user->userURL(), $user->uname());
		$rtn['link'] = $user->userURL().'img/'.$pic->id().'/';
		$rtn['file'] = $user->filesURL().'/ths/'.$pic->image();
		$block['pics'][] = $rtn;
	}
	
	$block['cols'] = $options[1];
	$block['width'] = floor(100/$options[1]);
	$block['showdesc'] = $options[4];
	return $block;
	
}

function gs_photos_edit($options, &$form){
	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS, 1, 'head'));
	$form->addElement(new RMText(_BK_GS_PICNUM, 'options[0]', 3, 3, $options[0]), true, 'num');
	$form->addElement(new RMText(_BK_GS_COLSNUM, 'options[1]', 3, 2, $options[1]), true, 'num');
	$ele = new RMSelect(_BK_GS_PICTYPE, 'options[2]');
	$ele->addOption(0,_BK_GS_PICRECENT, $options[2]==0 ? 1 : 0);
	$ele->addOption(1,_BK_GS_PICRANDOM, $options[2]==1 ? 1 : 0);
	$ele->addOption(2,_BK_GS_PICVIEWED, $options[2]==2 ? 1 : 0);
	$form->addElement($ele);
	
	include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsusers.class.php';
	
	$util =& RMUtils::getInstance();
	$form->addElement(new RMFormUserGS(_BK_GS_PICUSER, 'options[3]', false, array($options[3]), 40, 500, 300, 1));
	$form->addElement(new RMYesNo(_BK_GS_PICDESC, 'options[4]', $options[4]));
	
	return $form;
}
?>