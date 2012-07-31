<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function dt_block_items($options){
	global $db, $xoopsModule;
	
	if ($xoopsModule && $xoopsModule->dirname()=='dtransport'){
		global $xoopsModuleConfig;
		$mc =& $xoopsModuleConfig;
	} else {
		$util =& RMUtils::getInstance();
		$mc =& $util->moduleConfig('dtransport');
	}
	
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' ";
	
	if ($options[10]>0){
		$sql .= "AND uid='$options[10]' ";
	}
    
    if ($options[11]>0) $sql .= "AND id_cat='$options[11]'";
	
	$sql .= " ORDER BY";
	switch ($options[0]){
		case 0:
			$sql .= " modified DESC, created DESC";
			break;
		case 1:
			$sql .= " hits DESC";
			break;
		case 2:
			$sql .= " `rating`/`votes` DESC";
			break;
	}
	
	$sql .= " LIMIT 0, $options[1]";
	
	$result = $db->query($sql);
	$block = array();
	while($row = $db->fetchArray($result)){
		$item = new DTSoftware();
		$item->assignVars($row);
		$rtn = array();
		$rtn['title'] = $item->name();
		if ($options[2]) $rtn['img'] = $item->image()!='' ? XOOPS_URL.'/uploads/dtransport/ths/'.$item->image() : '';
		if ($options[3]) $rtn['desc'] = $item->shortdesc();
		if ($options[4]) $rtn['hits'] = $item->hits();
		if ($options[5]) $rtn['urate'] = @number_format($item->rating()/$item->votes(), 1);
		if ($options[6]){
			if ($item->votes()<=0 || $item->rating()<=0){
				$rate = 0;
			} else {
				$rate = (($item->rating()/$item->votes())*6);
			}
			$rtn['srate'] = '<div style="width: 60px; background: url('.XOOPS_URL.'/modules/dtransport/images/stargray.gif) repeat-x left; height: 12px; padding: 0; text-align: left;">
					<div style="width: '.$rate.'px; background: url('.XOOPS_URL.'/modules/dtransport/images/star.gif) repeat-x left; max-width: 60px; font-size: 2px; height: 12px;">
					&nbsp;
					</div>
				</div>';
		}
        $link = XOOPS_URL.'/modules/dtransport';
		if ($options[7]){
			$rtn['dlink'] = $mc['urlmode'] ? $link .'/item/'.$item->nameId().'/download/' :  $link .'/down.php?id='.$item->id();
		}
		if($options[9]) $rtn['user'] = $item->uname();
		$rtn['link'] = $mc['urlmode'] ? $link .'/item/'.$item->nameId().'/' :  $link .'/item.php?id='.$item->id();
		$block['downs'][] = $rtn;
	}
	
	$block['showimg'] = $options[2];
	$block['showdesc'] = $options[3];
	$block['showhits'] = $options[4];
	$block['showurate'] = $options[5];
	$block['showsrate'] = $options[6];
	$block['showdlink'] = $options[7];
	$block['showuser'] = $options[9];
	$block['downlang'] = _BK_DT_DOWNTEXT;
	$block['showtitles'] = $options[8];
	$block['langtitle'] = _BK_DT_TITLETEXT;
	$block['langhits'] = _BK_DT_HITSTEXT;
	$block['langurate'] = _BK_DT_URATETEXT;
	$block['langsrate'] = _BK_DT_SRATETEXT;
	$block['languser'] = _BK_DT_USERBY;
	
	return $block;

}

function dt_block_items_edit($options, &$form){
	
	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS,1));
	
	$ele = new RMSelect(_BK_DT_TYPE, 'options[0]', 0, array($options[0]));
	$ele->addOption(0, _BK_DT_TYPE0);
	$ele->addOption(1, _BK_DT_TYPE1);
	$ele->addOption(2, _BK_DT_TYPE2);
	$form->addElement($ele);
    // Categoría
    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
    $categos = array();
    DTFunctions::getCategos(&$categos, 0, 0, array(), false, 1);
    $ele = new RMSelect(_BK_DT_CAT, 'options[11]', false, $options[11]);
    $ele->addOption(0, _BK_DT_CATALL);
    foreach ($categos as $cat){
        $ele->addOption($cat['id_cat'], $cat['name']);
    }
    $form->addElement($ele);
    
	// Numero de Descargas
	$form->addElement(new RMText(_BK_DT_NUM, 'options[1]', 5, 2, $options[1]), true, 'num');
	// Mostrar imágen
	$form->addElement(new RMYesNo(_BK_DT_IMG, 'options[2]', $options[2]));
	// Mostrar Descripción
	$form->addElement(new RMYesNo(_BK_DT_DESC, 'options[3]', $options[3]));
	// Mostrar Hits
	$form->addElement(new RMYesNo(_BK_DT_HITS, 'options[4]', $options[4]));
	// Mostrar Ratig de Usuarios
	$form->addElement(new RMYesNo(_BK_DT_URATE, 'options[5]', $options[5]));
	// Mostrar Rating del Sitio
	$form->addElement(new RMYesNo(_BK_DT_SRATE, 'options[6]', $options[6]));
	// Mostrar Enlace de descarga
	$form->addElement(new RMYesNo(_BK_DT_DOWN, 'options[7]', $options[7]));
	// MOstrar títulos de la tabla
	$form->addElement(new RMYesNo(_BK_DT_TITLES, 'options[8]',$options[8]));
	// Mostrar Nombre de Usuario
	$form->addElement(new RMYesNo(_BK_DT_SHOWUSER, 'options[9]', $options[9]));
	$form->addElement(new RMFormUserEXM(_BK_DT_ONLYUSER, 'options[10]', false, array($options[10]), 50, 600, 300, 1));
	
	return $form;
	
}

