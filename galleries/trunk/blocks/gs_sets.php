<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function gs_sets_show($options){
    global $xoopsUser, $xoopsModuleConfig, $db;
    
    $wo = '';
    $tsets = $db->prefix("gs_sets");
    $tfriends = $db->prefix("gs_friends");
    $util = RMUtils::getInstance();
    $mc = $util->moduleConfig('galleries');
    
    $format = $mc['set_format_values'];
    $crop = $format[0]; // 0 = Redimensionar, 1 = Cortar
    $width = $format[1];
    $height = $format[2];
    
    if ($xoopsUser) $wo = "$tsets.owner='".$xoopsUser->uid()."' OR";
    
    $sql = "SELECT * FROM $tsets WHERE owner='1' OR public='2' ORDER BY `date` DESC LIMIT 0,$options[0]";
    $result = $db->query($sql);
    
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
    
    $block = array();
    $users = array();
    
    while ($row = $db->fetchArray($result)){
        $rtn = array();
        $set = new GSSet();
        $set->assignVars($row);   
        
        if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
        
        // Si se ha seleccionado la opción para mostrar imágenes entonces...
        if ($options[2]){
            //Obtenemos una imagen del album
            $sql = "SELECT b.* FROM ".$db->prefix('gs_setsimages')." a, ".$db->prefix('gs_images')." b WHERE";
            $sql.= " a.id_set='".$set->id()."' AND b.id_image=a.id_image AND b.public=2 AND b.owner='".$set->owner()."' ORDER BY b.created DESC LIMIT 0,1" ;

            $resimg = $db->query($sql);
            if ($db->getRowsNum($resimg)>0){
                $rowimg = $db->fetchArray($resimg);
                $img = new GSImage();
                $img->assignVars($rowimg);
                $urlimg = $users[$set->owner()]->filesURL().'/'.($mc['set_format_mode'] ? 'formats/set_' : 'ths/').$img->image();
                
                // Conversion de los formatos
                if (!$img->setFormat() && $mc['set_format_mode']){
                    GSFunctions::resizeImage($crop, $users[$set->owner()]->filesPath().'/'.$img->image(),$users[$set->owner()]->filesPath().'/formats/set_'.$img->image(), $width, $height);
                    $img->setSetFormat(1, 1);
                }
            } else {
                $urlimg = '';
            }
            
            $rtn['img'] = $urlimg;
            
            
        }
        
        $rtn['id'] = $set->id();
        $rtn['title'] = $set->title();
        $rtn['owner'] = $set->owner();
        $rtn['link'] = $users[$set->owner()]->userURL().'set/'.$set->id().'/';
        if ($options[3]){
            $rtn['date'] = formatTimeStamp($set->date(),'string');
            $rtn['pics'] = $set->pics();
            $rtn['uname'] = $set->uname();
            $rtn['linkuser'] = $users[$set->owner()]->userURL();
        }
        
        $block['sets'][] = $rtn;
        
    }
    
    $block['showimg'] = $options[2];
    $block['numcols'] = $options[1];
    $block['format'] = $options[4];
    $block['showinfo'] = $options[3];
    
    return $block;
    
}

function gs_sets_edit($options, &$form){
       $form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS, 1, 'head'));
       $form->addElement(new RMText(_BK_GS_SETSNUM, 'options[0]', 5, 2, $options[0]), true, 'num');
       $form->addElement(new RMText(_BK_GS_COLSNUM, 'options[1]', 5, 2, $options[1]), true, 'num');
       $form->addElement(new RMYesNo(_BK_GS_SHOWIMG, 'options[2]', $options[2]));
       $form->addElement(new RMYesNo(_BK_GS_SHOWINFO, 'options[3]', $options[3]));
       
       $ele = new RMSelect(_BK_GS_BKFORMAT, 'options[4]', 0, array($options[4]));
       $ele->addOption(1, _BK_GS_HORIZONTAL);
       $ele->addOption(0, _BK_GS_VERTICAL);
       $ele->addOption(2, _BK_GS_LIST);
       $form->addElement($ele);
       
       $form->addElement(new RMLabel(_BK_GS_IMPORTANT,_BK_GS_USETIP));
       return $form;
}
